---
name: brutal-skill-ui
description: Neo-Brutalist & Raw Brutalist UI rules. Monospace and thick grotesque typography, raw borders, hard offset shadows, high contrast light/dark mode support.
---

# Protocol: Neo-Brutalist & Raw Brutalist UI Architect

## 1. Protocol Overview
Name: Neo-Brutalist & Raw Brutalist UI System
Description: An advanced frontend design framework enforcing a high-contrast raw aesthetic. It rejects soft gradients, diffuse shadows, and rounded container defaults in favor of thick solid borders, hard offset shadows, stark monochrome backgrounds with optional loud accent fills, monospace typography overlays, and distinct tactile micro-interactions.

## 2. Absolute Negative Constraints (Banned Elements)
- DO NOT use soft blur shadows (e.g., standard Tailwind `shadow-sm`, `shadow-md`, etc.). All shadows must be solid offsets (`box-shadow: Xpx Ypx 0px #000000` or custom variables).
- DO NOT use smooth gradients, mesh radial backgrounds, or glassmorphism.
- DO NOT use default border widths (`border` = 1px thin). Use `border-2` (2px) or `border-[3px]` for structural boundaries.
- DO NOT use subtle rounded shapes for layouts. Use `rounded-none` or highly restrained crisp borders (`rounded-md` up to 4px-8px for cards, never `rounded-2xl` or `rounded-full` for cards/panels).
- DO NOT use subtle low-contrast text colors. All body text and labels must pass AAA contrast standards.

## 3. Typographic Architecture
- Monospace Integration: Monospace must be used for all meta-data, status indicators, labels, and numbers. Target: `'Geist Mono', 'SF Mono', monospace`.
- Grotesque Sans: Use heavy-weight system sans or Outfit for headings (`font-weight: 700` or `800`) with absolute black text.
- Bordered Typography: Large headlines can use black outline typography with `webkit-text-stroke`.

## 4. Color Palette & Invert Mode
- Light Mode: Background `#ffffff` or raw bone `#f3f3f0`. Borders `#000000`. Cards `#ffffff`. Shadows `#000000`. Accents `#f1c40f` (bright yellow), `#e67e22` (orange), `#3498db` (blue).
- Dark Mode (Invert Mode): Background `#0a0a0a`. Borders `#ffffff`. Cards `#121212`. Shadows `#ffffff`. Accents `#f1c40f`, `#e67e22`, `#3498db`.
- High Contrast Rule: The UI must fully swap background/border/shadow colors when entering dark mode, preserving the thick flat lines and raw aesthetic.

## 5. Component Specifications
- Brutalist Cards:
  - White or slate background with `border-2 border-black` (or white in dark mode).
  - Hard shadow: `shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]` or `dark:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]`.
  - Hover states: `hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] dark:hover:shadow-[6px_6px_0px_0px_rgba(255,255,255,1)]` with smooth CSS transition.
- Primary Buttons:
  - Colored/White bg with `border-2 border-black`, hard offset shadow `shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]`.
  - Active/Click state: `active:translate-x-[2px] active:translate-y-[2px] active:shadow-none`.
- Inputs:
  - `border-2 border-black` with monospace text. Focus states add a high contrast background color.
