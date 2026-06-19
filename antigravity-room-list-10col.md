# Antigravity — Room List Layout Prompt (Gemini Model)

## Objective

Rebuild the **Room Availability page** (`/room-availability`) so that rooms are displayed in a **floor-grouped grid**, with exactly **10 room cards per row**, closely matching the reference design. Each floor has its own labeled section header, and each card shows the room type badge, room number, and occupant/status label.

Do not change any business logic, data fetching, routing, or booking modal behavior — this is a **layout and visual change** scoped to the room list section only.

---

## Reference Design Summary

The target layout groups rooms by floor. Each floor section contains:
- A **floor header** with a dark badge showing the floor number and a bold floor label
- A **10-column grid** of room cards per row
- Each card has a **type badge** (top-left), a **large room number**, and a **status label** below it
- An occupied card shows a **red dot indicator**, a colored background, and the **occupant's name** instead of "VAKANT"

---

## Step 1 — Extend `tailwind.config.js`

Tailwind does not include `grid-cols-10` by default. Register it as a custom token:

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

## Step 2 — Page Layout Structure

The page renders a list of floor sections. Each floor section is stacked vertically:

```
/room-availability
└── Page content (no sidebar padding bleed, full content width)
    ├── FloorSection (Lantai 5)
    ├── FloorSection (Lantai 4)
    ├── FloorSection (Lantai 3)
    └── ... (one per floor, ordered top to bottom)
```

---

## Step 3 — Floor Section Component

Each floor section has a header and a grid of room cards:

```
FloorSection
├── Floor Header Row
│   ├── Floor Badge
│   │   └── w-12 h-12 rounded-xl bg-gray-900 text-white
│   │       flex flex-col items-center justify-center
│   │       text-[10px] font-semibold uppercase tracking-wide  ← "LT"
│   │       text-xl font-bold leading-none                     ← "5"
│   └── Floor Label
│       └── text-2xl font-bold text-gray-900  ← "LANTAI 5"
│
└── Room Grid
    └── grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-8 xl:grid-cols-10 gap-3 mt-4
        └── RoomCard × N
```

Floor header row classes:
```
flex items-center gap-4 mb-4
```

---

## Step 4 — RoomCard Component

Each card is compact, vertically centered, and has three visual states: **vacant**, **occupied**, and **reserved/pending**.

### Base card structure:

```
RoomCard
relative bg-white border border-gray-200 rounded-2xl shadow-sm
p-3 flex flex-col items-center justify-center gap-1 text-center
min-h-[100px]
hover:shadow-md hover:border-blue-300 transition-all duration-150 cursor-pointer
```

### Card internals (top to bottom):

```
├── Type badge (top-left, absolute)
│   └── absolute top-2 left-2
│       text-[9px] font-bold uppercase px-1.5 py-0.5 rounded-md
│       STA: bg-blue-100 text-blue-700
│       DEL: bg-yellow-100 text-yellow-700
│       SUI: bg-purple-100 text-purple-700
│       (add other types as needed following the same color convention)
│
├── Occupied indicator dot (top-right, absolute — only when occupied)
│   └── absolute top-2 right-2
│       w-2 h-2 rounded-full bg-red-500
│
├── Room number (center)
│   └── text-2xl font-bold
│       Vacant:   text-gray-800
│       Occupied: text-red-500
│
└── Status / Occupant label (below room number)
    └── text-[10px] font-semibold uppercase tracking-widest
        Vacant:   text-gray-400  — show "VAKANT"
        Occupied: text-red-400   — show occupant name (e.g., "ZUHDI")
```

### Card background by state:

| State     | Background                        | Border                     |
|-----------|-----------------------------------|----------------------------|
| Vacant    | `bg-white`                        | `border-gray-200`          |
| Occupied  | `bg-red-50`                       | `border-red-200`           |
| Reserved  | `bg-yellow-50`                    | `border-yellow-200`        |

---

## Step 5 — Full Card Example (Occupied)

```jsx
<div className="relative bg-red-50 border border-red-200 rounded-2xl shadow-sm p-3 flex flex-col items-center justify-center gap-1 text-center min-h-[100px] cursor-pointer hover:shadow-md transition-all duration-150">
  {/* Type badge */}
  <span className="absolute top-2 left-2 text-[9px] font-bold uppercase px-1.5 py-0.5 rounded-md bg-yellow-100 text-yellow-700">
    DEL
  </span>

  {/* Occupied dot */}
  <span className="absolute top-2 right-2 w-2 h-2 rounded-full bg-red-500" />

  {/* Room number */}
  <span className="text-2xl font-bold text-red-500">517</span>

  {/* Occupant name */}
  <span className="text-[10px] font-semibold uppercase tracking-widest text-red-400">
    ZUHDI
  </span>
</div>
```

---

## Step 6 — Full Card Example (Vacant)

```jsx
<div className="relative bg-white border border-gray-200 rounded-2xl shadow-sm p-3 flex flex-col items-center justify-center gap-1 text-center min-h-[100px] cursor-pointer hover:shadow-md hover:border-blue-300 transition-all duration-150">
  {/* Type badge */}
  <span className="absolute top-2 left-2 text-[9px] font-bold uppercase px-1.5 py-0.5 rounded-md bg-blue-100 text-blue-700">
    STA
  </span>

  {/* Room number */}
  <span className="text-2xl font-bold text-gray-800">501</span>

  {/* Vacant label */}
  <span className="text-[10px] font-semibold uppercase tracking-widest text-gray-400">
    VAKANT
  </span>
</div>
```

---

## Step 7 — Floor Section Example (Full)

```jsx
<section className="mb-10">
  {/* Floor header */}
  <div className="flex items-center gap-4 mb-4">
    <div className="w-12 h-12 rounded-xl bg-gray-900 text-white flex flex-col items-center justify-center">
      <span className="text-[9px] font-semibold uppercase tracking-wide leading-none">LT</span>
      <span className="text-xl font-bold leading-none">5</span>
    </div>
    <h2 className="text-2xl font-bold text-gray-900">LANTAI 5</h2>
  </div>

  {/* Room grid */}
  <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-8 xl:grid-cols-10 gap-3">
    {rooms.filter(r => r.floor === 5).map(room => (
      <RoomCard key={room.id} room={room} onClick={() => openBookingModal(room)} />
    ))}
  </div>
</section>
```

---

## Responsive Breakdown

| Breakpoint | Columns | Viewport     |
|------------|---------|--------------|
| default    | 2       | < 640px      |
| `sm:`      | 3       | ≥ 640px      |
| `md:`      | 5       | ≥ 768px      |
| `lg:`      | 8       | ≥ 1024px     |
| `xl:`      | 10      | ≥ 1280px     |

---

## Constraints

- **DO NOT** change the booking modal, filter bar, page header, or routing
- **DO NOT** change business logic, data fetching, or state management
- **DO NOT** remove Tailwind CSS or introduce another CSS framework
- **DO NOT** hardcode room data — the grid must render dynamically from existing data
- **YOU MUST** register `grid-cols-10` in `tailwind.config.js` — do not use arbitrary class syntax
- **YOU MUST** keep cards square-ish and compact — do not add extra content inside the card

---

## Expected Output

1. Updated `tailwind.config.js` with the `gridTemplateColumns['10']` extension
2. `FloorSection` component (or equivalent structure in the existing codebase)
3. Updated `RoomCard` component with the compact layout and three visual states
4. Updated Room Availability page that renders `FloorSection` per floor

For each file, provide the full updated code and a brief note on what was changed.

---

*This prompt is a targeted layout update for the Antigravity project, used with the Gemini model.*
