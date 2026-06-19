# Antigravity — Room Card, Popup & Booking Form Fix Prompt (Gemini Model)

## Fix 1 — Room Card Width (10 Columns Per Row)

The room cards are currently rendering in 2 wide columns instead of 10 compact cards per row. The root cause is likely a fixed width or incorrect grid class on the card or its wrapper.

Do the following:

1. Find the grid container that wraps all RoomCard components inside the FloorSection. Replace whatever grid class is there with:
```
grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-8 xl:grid-cols-10 gap-3
```

2. Find the RoomCard component. Remove any fixed `width`, `min-width`, `max-width`, or `w-[Xpx]` or `w-X/X` class. Replace with `w-full` so the card always fills its column.

3. In `tailwind.config.js`, register the custom 10-column token if not already there:
```js
theme: {
  extend: {
    gridTemplateColumns: {
      '10': 'repeat(10, minmax(0, 1fr))',
    },
  },
}
```

4. Make the card compact and vertically stacked to fit narrow columns:
- Remove any horizontal (side-by-side) layout inside the card
- Card container: `relative w-full bg-white border border-gray-200 rounded-2xl p-3 flex flex-col items-center justify-center text-center gap-1 min-h-[90px] cursor-pointer hover:shadow-md hover:border-blue-300 transition-all duration-150`
- Type badge: `absolute top-2 left-2 text-[9px] font-bold uppercase px-1.5 py-0.5 rounded-md`
- Occupied dot: `absolute top-2 right-2 w-2 h-2 rounded-full bg-red-500` (only when occupied)
- Room number: `text-xl font-bold` (gray when vacant, red when occupied, yellow when reserved)
- Status/name label: `text-[10px] font-semibold uppercase tracking-widest` (gray "VAKANT", red occupant name, yellow "RESERVED")

Card background by state:
- Vacant: `bg-white border-gray-200`
- Occupied: `bg-red-50 border-red-200`
- Reserved: `bg-yellow-50 border-yellow-200`

---

## Fix 2 — Room Ordering (Ascending by Room Number)

The rooms inside each floor section must be ordered **ascending by room number** (e.g. 101, 102, 103... not random order).

Find where the rooms array is passed into the FloorSection or mapped into RoomCard components. Before rendering, sort the array ascending by room number:

```js
const sortedRooms = [...rooms].sort((a, b) => a.roomNumber - b.roomNumber);
```

Apply this sort per floor group. The floor sections themselves should also be ordered ascending by floor number (Floor 1 first, highest floor last).

---

## Fix 3 — Room Detail Popup (onClick Not Working)

Clicking a room card currently does nothing. Fix the click handler so a detail popup appears.

**What to fix:**
- Locate the RoomCard component. Ensure it has an `onClick` prop wired to a handler that opens the popup/modal
- Locate the modal/popup component. Ensure it is rendered in the component tree (not accidentally commented out or conditionally hidden by a broken state)
- The modal must be controlled by a state variable, e.g. `selectedRoom` — set it on card click, clear it on close
- Ensure the modal renders at the root level or inside a portal so it is not clipped by overflow-hidden parents

**Popup content and layout:**

```
Modal backdrop: fixed inset-0 bg-black/40 z-50 flex items-center justify-center
Modal card: bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm

Contents (top to bottom):
├── Header row
│   ├── Left: Type badge + Room number (text-2xl font-bold)
│   └── Right: Close button (X icon, text-gray-400 hover:text-gray-700)
│
├── Divider: border-t border-gray-100 my-3
│
├── Info rows (label + value pairs):
│   ├── Floor: "Lantai X"
│   ├── Room Type: badge colored by type
│   ├── Status: colored badge (Vacant / Occupied / Reserved / Cleaning)
│   └── Occupant: shown only if occupied (name in text-red-500)
│
├── Divider
│
└── Footer:
    ├── If VACANT: "Book This Room" button
    │   → bg-blue-600 hover:bg-blue-700 text-white font-semibold w-full py-2.5 rounded-lg
    │   → Clicking this navigates to /booking with the room pre-selected
    └── If OCCUPIED / RESERVED / CLEANING: disabled gray button "Not Available"
```

Close the modal when:
- The X button is clicked
- The backdrop is clicked
- The Escape key is pressed

---

## Fix 4 — Booking Page (Accessible Only via Popup "Book This Room" Button)

The booking page at `/booking` must only be reachable by clicking the **"Book This Room"** button inside the room detail popup. Do not add it to the sidebar navigation. Do not make it directly accessible by URL without a room context (redirect back to `/room-availability` if accessed without a room).

**The booking form must match the hotel registration format with these sections and fields:**

### Header
- Full-width banner with app logo, title (e.g. "PPKD HOTEL"), and subtitle "Formulir Pendaftaran / Registration Form"
- Background: blue gradient (`bg-gradient-to-r from-blue-600 to-blue-800 text-white`)

### Form Layout
Two-column layout on desktop (`grid grid-cols-1 lg:grid-cols-2 gap-6`), single column on mobile.

---

### Section 1 — INFORMASI KAMAR / ROOM DETAILS (left column)
Fields:
- ROOM NO. — pre-filled from selected room, read-only
- NO. OF ROOM — number input, default 1
- NO. OF PERSON — number input
- ROOM TYPE — pre-filled from selected room, read-only
- RECEPTIONIST — pre-filled with logged-in user name, read-only
- CATATAN TAMBAHAN / ADDITIONAL NOTES (OPTIONAL) — textarea
- Static info box: "CHECK-OUT TIME — 12.00 Noon / Jam 12.00 Siang"

---

### Section 2 — DATA TAMU / GUEST INFORMATION (left column, below Section 1)
Note: "Harap tulis dengan huruf cetak — Please print in block letters"

Fields:
- NAMA / NAME * — text input, required
- PEKERJAAN / PROFESSION — text input
- PERUSAHAAN / COMPANY — text input
- KEBANGSAAN / NATIONALITY — text input
- NO. KTP / PASSPORT — text input
- TANGGAL LAHIR / BIRTH DATE — date input

---

### Section 3 — KONTAK / CONTACT DETAILS (right column)
Fields:
- ALAMAT / ADDRESS — textarea
- TELEPON / HP — tel input
- EMAIL — email input
- NO. MEMBER / MEMBER NO. — text input

---

### Section 4 — TANGGAL MENGINAP / STAY DATES (right column, below Section 3)
Fields:
- ARRIVAL TIME — time input
- ARRIVAL DATE * — date input, required
- DEPARTURE DATE * — date input, required

---

### Section 5 — KOTAK DEPOSIT / SAFETY DEPOSIT BOX (right column, below Section 4)
Fields:
- NOMOR KOTAK / BOX NO. — text input
- DIKELUARKAN OLEH / ISSUED BY — pre-filled with logged-in user name, read-only
- TANGGAL / DATE — date input

---

### Form Footer
- Full-width submit button: "Confirm Booking" or "Submit Pendaftaran"
  - `bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg`
- On success: show a success message or redirect to `/room-availability` with a toast notification

---

### Field Styling (apply consistently to all fields)
- Section title: `text-sm font-bold text-blue-600 uppercase tracking-wide mb-3`
- Field label: `block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1`
- Required indicator: `text-red-500 ml-0.5`
- Input / Textarea / Select: `w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition`
- Read-only field: add `bg-gray-50 text-gray-500 cursor-not-allowed`
- Section card wrapper: `bg-white rounded-xl border border-gray-200 p-5 shadow-sm`

---

## Constraints

- DO NOT add a booking link to the sidebar
- DO NOT allow `/booking` to be accessed without a room being selected — redirect to `/room-availability` if no room context exists
- DO NOT change any existing API calls or business logic
- DO NOT change the room availability page layout or the floor section structure
- All styling must use Tailwind CSS only
