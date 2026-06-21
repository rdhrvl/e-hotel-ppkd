# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Hotel Management System (HMS): a modular-monolith web app for end-to-end hotel operations (reservations, room/guest management, check-in/out, billing, housekeeping, F&B, reporting). Built as a server-rendered Livewire app — **no SPA / React**. UI copy and product docs are bilingual (English + Indonesian).

Stack: PHP 8.3, Laravel 13, Livewire 4, TailwindCSS 4 (Vite 8), SQLite (default; PRD targets MySQL in prod).

## Commands

```bash
composer dev          # run everything at once: php artisan serve + queue:listen + pail logs + vite (concurrently)
composer test         # clears config then runs artisan test
php artisan test                                   # full suite
php artisan test --filter=AuthenticationFlowTest   # single test class
php artisan test tests/Feature/AuthenticationFlowTest.php
./vendor/bin/pint     # format / lint (Laravel Pint)

php artisan migrate:fresh --seed   # rebuild DB and load demo data (see DatabaseSeeder)
npm run dev / npm run build        # vite only
```

Tests run on an in-memory SQLite DB (`phpunit.xml`), isolated from the dev database.

## Seeded login accounts

All use password `password`. Roles: `superadmin@example.com`, `admin@example.com`, `frontdesk@example.com`, `housekeeping@example.com`, `fnb@example.com`.

## Architecture

**Livewire-as-pages.** Routes (`routes/web.php`) map directly to full-page Livewire components in `app/Livewire/Dashboard/*`, each rendering `#[Layout('layouts.app')]`. There are almost no traditional controllers. `/dashboard` resolves to `Reports::class`. Each component's `render()` does its own querying and passes data to its Blade view in `resources/views/livewire/dashboard/*`.

**Business logic lives in services, not components.** `app/Services/BookingService.php` owns the booking lifecycle (`createBooking`, `checkIn`, `addServiceCharge`, `checkOut`) — all wrapped in DB transactions, all writing audit logs, all enforcing the state machine (e.g. only `checked_in` bookings can check out; room must be `ready` before check-in). Livewire components inject the service and call it; they should not reimplement these transitions inline.

**State machines (enforced in BookingService + checked in views):**
- Booking: `pending → confirmed → checked_in → checked_out` (or `→ cancelled`)
- Room: `available → reserved → occupied → cleaning → available`, plus `maintenance`. Note an extra **`ready`** state exists beyond the PRD: after housekeeping cleans, the room becomes `ready` ("Ready for Check-In"), and `checkIn()` *requires* room status `ready`.

**Billing model.** Each booking has one `GuestBill` (room charges + extra charges + deposit + paid_amount). Extra/service charges (F&B, extra bed, damage fines) are `BookingItem` rows that increment `total_extra_charges`. Checkout computes due = total − deposit − paid, records a `Payment`, frees the room to `cleaning`, and auto-creates a `HousekeepingTask`. `Service` rows have a `type` (`extra_bed`, `f_and_b`, `laundry`, `general`); F&B ordering creates `FoodOrder`/`FoodOrderItem` *and* mirrors charges onto the bill via `addServiceCharge`.

**Notifications are event-driven and role-routed.** `Room::booted()` hooks `updated` — any change to `status` fires `NotificationService::handleRoomStatusChange`, which routes a `Notification` to target roles based on *who* made the change (front desk ↔ housekeeping), sets priority/urgency, and builds an `action_url`. `superadmin` receives everything. The `AlertsPanel` Livewire component (in the top nav) and `NotificationReadStatus` track per-user read state. When changing room status in code, expect a notification side effect.

**RBAC.** `Role` (slug-based: `superadmin`, `admin`, `front_desk`, `housekeeping`, `fnb`) belongs to `User`. Authorization uses `User` helper methods (`isAdmin()`, `isFrontDesk()`, `isHousekeeping()`, `isFnb()` — each also returns true for superadmin) plus a single `isAdmin` Gate (`AppServiceProvider`). Admin-only routes are grouped under `middleware('can:isAdmin')`. Sidebar nav links in `layouts/app.blade.php` are gated by these same helpers.

**Branch scoping.** Multi-branch data is scoped by `session('selected_branch_id', 1)`, set via the `BranchSelector` component in the nav. Components filter queries by branch and listen for the `branchChanged` event (`$refresh`). When adding branch-aware lists, replicate this filter + listener.

**Audit logging.** `BookingService::logActivity()` writes `AuditLog` rows (user, action, entity type/id, old/new JSON snapshots) inside the same transaction as the mutation. Viewable via the admin-only `AuditLogs` page.

## Design system (impeccable)

Visual work must follow `PRODUCT.md` and `DESIGN.md` (the "Refined Operational Canvas"). Key hard constraints:
- Font: **Outfit** everywhere. Primary accent: deep blue `#2563eb`.
- Semantic status colors are fixed: green = available/success, red = occupied/danger, yellow = reserved/warning, blue = cleaning/info. Keep this mapping constant across tables/badges/filters.
- **Prohibited:** colored side-stripe borders (e.g. `border-left: 4px`), gradient text, glassmorphism, card radius > 12px, uppercase tracked "eyebrow" labels as section titles, muted gray text on colored surfaces.
- Border-or-shadow rule: flat with 1px border at rest, promote to shadow on hover — never both at once.
- Dark mode is supported via a `dark` class on `<html>` and CSS variables (`--bg-primary`, `--text-primary`, etc.) defined in `resources/css/app.css`. Style with these variables, not hardcoded Tailwind grays.
- Target WCAG 2.1 AA; interactive targets ≥ 40px.
