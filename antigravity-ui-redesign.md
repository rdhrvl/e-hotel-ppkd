# Antigravity — Room List 10-Column Layout Prompt (Gemini Model)

## Objective

Update the **Room Availability page** (`/room-availability`) so that the room list grid displays exactly **10 room cards per row** on large screens, with graceful responsive degradation on smaller viewports.

Do not change any business logic, data fetching, routing, or booking modal behavior — this is a **layout-only change** scoped to the room list grid and room card component.

---

## Step 1 — Extend `tailwind.config.js`

Tailwind does not include `grid-cols-10` by default. Add it as a custom token:

```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      gridTemplateColumns: {
        '10': 'repeat(10, minmax(0, 1fr))',
      },
    },
  },
}
```

---

## Step 2 — Update the Room List Grid Container

Find the element that wraps all `RoomCard` components on the Room Availability page and replace its grid classes with:

```html
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-8 xl:grid-cols-10 gap-3">
  <!-- RoomCard x N -->
</div>
```

**Responsive breakdown:**

| Breakpoint | Columns | Viewport       |
|------------|---------|----------------|
| default    | 2       | < 640px        |
| `sm:`      | 3       | ≥ 640px        |
| `md:`      | 5       | ≥ 768px        |
| `lg:`      | 8       | ≥ 1024px       |
| `xl:`      | 10      | ≥ 1280px       |

---

## Step 3 — Redesign the RoomCard for Compact Width

At 10 columns, each card is narrow. Redesign the card to be **vertically stacked and compact**:

```
RoomCard
bg-white rounded-xl border border-gray-200 shadow-sm
relative p-3 flex flex-col items-center gap-2 text-center
hover:shadow-md hover:border-blue-300 transition-all duration-150 cursor-pointer
```

**Internal layout (top to bottom):**

```
├── Status badge — absolute top-right
│   └── absolute top-2 right-2
│       text-[10px] font-medium px-1.5 py-0.5 rounded-full
│       Available:          bg-green-100 text-green-700
│       Booked:             bg-red-100 text-red-700
│       Partially Available: bg-yellow-100 text-yellow-700
│
├── Room icon
│   └── w-10 h-10 rounded-lg bg-gray-100
│       flex items-center justify-center mx-auto mt-1
│       BuildingOffice2Icon (Heroicons) or DoorOpen (Lucide) — w-5 h-5 text-gray-500
│
├── Room name
│   └── text-xs font-semibold text-gray-900 leading-tight
│       truncate w-full  ← prevent overflow on narrow cards
│
├── Capacity
│   └── text-[10px] text-gray-400
│       e.g., "8 pax"
│
└── "Book" button
    └── w-full mt-1 py-1.5 rounded-lg
        text-[11px] font-medium
        bg-blue-600 hover:bg-blue-700 text-white
        transition-colors duration-150
        disabled:opacity-50 disabled:cursor-not-allowed  ← when room is Booked
```

**Rules for compact cards:**
- All text must be `text-xs` or smaller (`text-[10px]`) — never `text-sm` or above inside the card
- Room name must use `truncate` — do not let long names wrap to two lines
- Do not render amenity chips inside the card — show them in a tooltip on hover or inside the booking modal
- The "Book" button must be disabled and visually muted when the room status is **Booked**

---

## Step 4 — Tooltip for Truncated Names and Amenities (Optional but Recommended)

Since text is truncated, add a native HTML tooltip so users can see the full name and amenities on hover:

```html
<div title="Full Room Name — Projector, Whiteboard, AC">
  <!-- card content -->
</div>
```

Or use a Tailwind-compatible tooltip library already in the project if available.

---

## Constraints

- **DO NOT** change the booking modal, filter bar, page header, or any other part of the Room Availability page
- **DO NOT** change routing, data fetching, or state management
- **DO NOT** remove Tailwind CSS or introduce another CSS framework
- **DO NOT** hardcode the number of rooms — the grid must work for any number of room cards
- **YOU MUST** register `grid-cols-10` in `tailwind.config.js` — do not use arbitrary values like `grid-cols-[repeat(10,_minmax(0,_1fr))]` throughout the template

---

## Expected Output

1. Updated `tailwind.config.js` with the new `gridTemplateColumns` extension
2. Updated room list grid container with the 10-column responsive classes
3. Updated `RoomCard` component with the compact layout

For each file, provide the full updated code and a brief note on what was changed.

---

*This prompt is a targeted layout update for the Antigravity project, used with the Gemini model.*