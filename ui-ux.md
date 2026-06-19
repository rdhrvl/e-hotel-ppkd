# HMS UI/UX Improvement Guide (v1)

---

## 1. Overview

Dokumen ini mendefinisikan standar dan perbaikan UI/UX untuk Hotel Management System (HMS) agar:

* Lebih cepat digunakan (operational efficiency)
* Minim error user
* Konsisten di semua module
* Siap diimplementasikan dengan Livewire + TailwindCSS

---

## 2. Design Principles

### 2.1 Clarity over Complexity

* Hindari terlalu banyak informasi dalam satu screen
* Gunakan grouping (card/section)

### 2.2 Speed is Priority

* Semua aksi utama maksimal 2–3 klik
* Gunakan shortcut (quick action)

### 2.3 Consistency

* Button, form, warna status harus konsisten

---

## 3. Layout System

### 3.1 Main Layout

* Sidebar (fixed)
* Topbar (branch selector + user menu)
* Content area (responsive)

### 3.2 Sidebar Menu

Urutan:

1. Dashboard
2. Rooms
3. Bookings
4. Guests
5. Housekeeping
6. Payments
7. Reports
8. Settings

---

## 4. Design System (Tailwind)

### 4.1 Color System

| Purpose | Class         |
| ------- | ------------- |
| Primary | bg-blue-600   |
| Success | bg-green-500  |
| Warning | bg-yellow-500 |
| Danger  | bg-red-500    |
| Info    | bg-gray-500   |

---

### 4.2 Status Badge Standard

#### Room Status

* available → green
* occupied → red
* reserved → yellow
* cleaning → blue
* maintenance → gray

#### Booking Status

* pending → gray
* confirmed → blue
* checked_in → green
* checked_out → black
* cancelled → red

---

### 4.3 Button Hierarchy

* Primary Action → solid (blue)
* Secondary → outline
* Danger → red

---

## 5. Core Screens UX

---

### 5.1 Dashboard

#### Components:

* KPI cards (Revenue, Occupancy, Rooms)
* Chart (occupancy trend)
* Recent bookings table
* Room status summary (grid)

#### Improvement:

* Gunakan card grid (max 4 per row)
* Gunakan warna status jelas
* Skeleton loading saat fetch data

---

### 5.2 Room Management

#### Table Features:

* Search (room number)
* Filter (status, type)
* Bulk action (maintenance, cleaning)

#### UX Improvement:

* Status pakai badge warna
* Inline edit untuk status
* Hover action (edit/delete)

---

### 5.3 Booking Page

#### Form UX:

* Step-based form:

  1. Guest info
  2. Room selection
  3. Date
  4. Confirmation

#### Improvement:

* Gunakan date picker
* Auto calculate total price
* Show room availability realtime

---

### 5.4 Check-in Flow

#### UX:

* Scan / input booking code
* Show booking detail
* 1-click check-in button

#### Improvement:

* Highlight warning jika room belum ready
* Disable button jika invalid

---

### 5.5 Check-out Flow

#### UX:

* Summary billing
* Payment input
* Confirm checkout

#### Improvement:

* Auto calculate charges
* Show unpaid balance
* Print invoice button

---

### 5.6 Housekeeping

#### UI:

* Calendar view
* Task list

#### Improvement:

* Drag & drop scheduling
* Color per status
* Filter by staff

---

## 6. Form UX Standard

### Rules:

* Label selalu di atas input
* Required field pakai (*)
* Error message di bawah input
* Gunakan helper text

### Validation:

* Real-time validation (Livewire)
* Disable submit jika invalid

---

## 7. Interaction Pattern

### 7.1 Loading State

* Gunakan skeleton loader
* Disable button saat loading

### 7.2 Empty State

* Tampilkan ilustrasi + CTA
* Contoh: "Belum ada booking"

### 7.3 Error Handling

* Toast notification
* Inline error

---

## 8. Component Standard

### 8.1 Table

* Sticky header
* Pagination
* Search + filter

### 8.2 Modal

* Max width: md / lg
* Close on overlay click

### 8.3 Toast

* Top-right position
* Auto hide (3s)

---

## 9. Accessibility (Basic)

* Contrast cukup (WCAG)
* Button minimal height 40px
* Keyboard navigation support

---

## 10. Performance UX

* Lazy load data
* Debounce search input
* Pagination (hindari load all data)

---

## 11. Mobile Responsiveness

* Sidebar collapse → hamburger
* Table → card view
* Button full width

---

## 12. Anti-Pattern (HARUS DIHINDARI)

* ❌ Terlalu banyak modal nested
* ❌ Tidak ada loading state
* ❌ Form panjang tanpa grouping
* ❌ Status tanpa warna

---

## 13. Implementation Notes (Livewire)

* Gunakan wire:model untuk form binding
* Gunakan wire:loading untuk state
* Gunakan component reusable:

  * <x-button>
  * <x-badge>
  * <x-modal>
  * <x-table>

---

## 14. Final Goal

UI/UX harus:

* Cepat dipahami (≤ 5 menit training)
* Minim error input
* Efisien untuk operasional harian hotel

---
