---
name: Hotel Management System
description: Premium and efficient administrative design system for high-end hospitality operations.
colors:
  primary: "#2563eb"
  neutral-bg: "#f8fafc"
  neutral-surface: "#ffffff"
  neutral-border: "#e2e8f0"
  text-primary: "#0f172a"
  text-secondary: "#334155"
  text-muted: "#64748b"
  success: "#10b981"
  warning: "#f59e0b"
  danger: "#ef4444"
  info: "#3b82f6"
typography:
  display:
    fontFamily: "Outfit, sans-serif"
    fontSize: "2.5rem"
    fontWeight: 800
    lineHeight: 1.2
    letterSpacing: "-1px"
  body:
    fontFamily: "Outfit, sans-serif"
    fontSize: "0.95rem"
    fontWeight: 400
    lineHeight: 1.5
rounded:
  sm: "6px"
  md: "8px"
  lg: "12px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#ffffff"
    rounded: "{rounded.sm}"
    padding: "12px 24px"
  button-primary-hover:
    backgroundColor: "#1d4ed8"
  card:
    backgroundColor: "{colors.neutral-surface}"
    rounded: "{rounded.md}"
    padding: "24px"
  input:
    backgroundColor: "{colors.neutral-surface}"
    rounded: "{rounded.sm}"
    padding: "12px 16px"
---

# Design System: Hotel Management System

## 1. Overview

**Creative North Star: "Refined Operational Canvas"**

This design system serves as a clean, highly structured administrative workspace optimized for fast-paced hospitality operations. It prioritizes information density and typographic hierarchy, ensuring front-desk clerks and managers can consume metrics, manage room statuses, and finalize check-out guest bookings with zero friction.

The system rejects generic, low-contrast SaaS layouts (like light gray text on white backgrounds) and over-rounded, bubble-like elements. Instead, it relies on crisp, high-contrast states, defined semantic status indicators, and subtle transitions to establish expert confidence.

**Key Characteristics:**
* High legibility through contrast and dark-neutral colors.
* Functional status colors mapping directly to room and booking states.
* Snappy interactions guided by micro-transitions and clear hover feedback.

## 2. Colors

A premium, cool-tinted administrative palette designed to remain highly readable under varying ambient light conditions at the front desk.

### Primary
* **Pacific Cobalt** (#2563eb / oklch(50.48% 0.224 256.85)): A cool-tinted, premium cobalt blue for administrative dashboard clarity.

### Neutral
* **Background Primary** (#f8fafc / oklch(98.48% 0.005 238.45)): The default application background.
* **Background Card** (#ffffff / oklch(100% 0 0)): Used for white structured container surfaces and cards.
* **Text Primary** (#0f172a / oklch(12.94% 0.015 253.94)): The primary color for headings and high-priority labels.
* **Text Secondary** (#334155 / oklch(31.81% 0.021 251.46)): The standard body copy color.
* **Border Color** (#e2e8f0 / oklch(93.18% 0.009 238.44)): The default grid line and container border color.

### Named Rules
**The Color Contrast Rule.** Primary body text must always preserve a >= 4.5:1 contrast ratio against the background. Muted gray text is strictly forbidden on colored surfaces.
**The Semantic Status Rule.** Status colors (Green for Available/Success, Red for Occupied/Danger, Yellow for Reserved/Warning, Blue for Cleaning/Info) must maintain a constant semantic mapping across all tables, badges, and filters.

## 3. Typography

**Display Font:** Outfit, sans-serif
**Body Font:** Outfit, sans-serif

The system uses a single geometric sans-serif typeface, **Outfit**, utilizing variations in font weight, letter spacing, and sizing to create a crisp and easily scannable layout.

### Hierarchy
* **Display** (800, 2.5rem, 1.2): Reserved for primary page titles and guest brand headings.
* **Headline** (700, 1.5rem, 1.3): Used for section headers and sub-dashboard titles.
* **Title** (600, 1.1rem, 1.4): Used for card titles and table headers.
* **Body** (400, 0.95rem, 1.5): The standard readable size for copy and details. Max line length is restricted to 70ch.
* **Label** (500, 0.85rem, 0.05em letter-spacing): Used for labels, badges, inputs, and helpers.

### Named Rules
**The Uppercase Eyebrow Restriction Rule.** Small, uppercase uppercase tracked eyebrows are prohibited as generic section titles. Instead, structure headers using clean visual alignment and weight shifts.

## 4. Elevation

The system is flat and tactile. Surfaces are flat at rest, with subtle elevations or shadows appearing on hover/interaction to suggest tactile clickability.

### Shadow Vocabulary
* **Standard Shadow** (`box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05)`): Applied to cards at rest.
* **Active Shadow** (`box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03)`): Applied to hovered cards, active dropdowns, and modals to elevate them from the base canvas.

### Named Rules
**The Border-or-Shadow Rule.** Never combine a strong 1px border with a soft drop shadow on the same card or container. Choose clean borders at rest, and promote to shadows on hover.

## 5. Components

### Buttons
* **Shape:** Slightly rounded corners (6px / `--radius-sm`).
* **Primary:** Blue background (`var(--accent-primary)`) with bold white text. Padding is 12px 24px.
* **Hover / Focus:** Scale slightly to `0.98` on click. Shift color to `var(--accent-primary-hover)` on hover.

### Cards / Containers
* **Corner Style:** Standard rounded corners (8px / `--radius`).
* **Background:** White (`var(--bg-card)`) with 1px border (`var(--border-color)`).
* **Hover Treatment:** Transition border to `var(--border-hover)` and elevate with `var(--shadow-lg)`.

### Inputs / Fields
* **Style:** White background (`var(--bg-input)`) with a clean 1px border.
* **Focus:** Transition border to `var(--accent-primary)` and apply a soft glow: `box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1)`.

## 6. Do's and Don'ts

### Do:
* **Do** use semantic badge colors to represent state (e.g. green for Available / checked-in, red for Occupied / danger).
* **Do** maintain a consistent 6px corner radius for inputs and buttons, and 8px/12px for cards.
* **Do** ensure all form labels are positioned directly above the input fields.

### Don't:
* **Don't** use colored side-stripe borders (e.g. `border-left-width: 4px`) to designate item status or highlight state. Use full borders or background fills instead.
* **Don't** use gradient text or decorative glassmorphism.
* **Don't** use card corners exceeding 12px (`border-radius: 16px+` is forbidden).
