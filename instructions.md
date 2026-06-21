# Prompt: Make Upfront Payment the Primary Flow (Pay-Later Becomes Optional)

## Context
Currently, room payment happens entirely at the end of the stay, during **Guest Checkout & Settlement**. There is already a **deposit** system in the app, but it currently only appears/triggers **after the check-in process**.

I want to flip the priority: **upfront payment becomes the primary/default path**, while paying everything at checkout becomes just an optional fallback (for guests who don't pay upfront). The checkout flow itself (Guest Checkout & Settlement page, its steps, its UI) should **not be restructured** — the only change there is how the **Grand Total is calculated** (it must now subtract whatever was already paid upfront).

Before starting, locate and review:
- The existing deposit system: where it's defined, where its trigger currently lives (post check-in), the data model used to store a deposit/payment record per booking.
- The **"Confirm & Register"** flow/handler (the same one used for the cart/booking submission from earlier work) — this is where the upfront payment step needs to be introduced.
- The **Guest Checkout & Settlement** page and its current Grand Total calculation logic (likely: room total + extras + F&B + other charges).

---

## Requirements

### 1. Move the deposit/payment trigger to "Confirm & Register" (booking creation), not after check-in
- When a booking is created via **Confirm & Register**, present the option to pay upfront at that point — covering **room price + any extras already selected at that time** (F&B is excluded, since it's typically ordered later, after check-in).
- This upfront payment is **optional, not mandatory** — front desk/guest can choose to skip it and pay everything later at checkout instead, preserving the current pay-at-end behavior as a fallback.
- Reuse the existing deposit system/data model for this — don't build a separate parallel payment record. Just change *when* it can be triggered (now available at Confirm & Register, in addition to — or instead of — after check-in, depending on what's needed; flag this as a decision point and default to keeping it available at Confirm & Register as the primary entry point).

### 2. Grand Total at Guest Checkout & Settlement subtracts the upfront payment only
- **Do not change the checkout flow/steps/UI structure.** The only change is the calculation:
  - `Grand Total (Checkout) = Room Total + Extras Total + F&B Total + Other Charges − Upfront Payment Already Paid`
- If no upfront payment was made for that booking (guest chose to skip it), the Grand Total calculation behaves exactly as it does today (full amount, nothing subtracted).
- If an upfront payment was made, clearly display it as a line item (e.g. "Upfront Payment: − Rp X") in the Settlement breakdown so it's transparent to both staff and guest why the Grand Total is lower than the raw sum of charges.
- Make sure F&B charges (from the earlier F&B ordering feature) and any other charges added after check-in are **never** reduced by the upfront payment — only room + extras-at-booking-time were covered by it, so the subtraction should reflect exactly what was actually prepaid, not an arbitrary flat deduction.

### 3. Don't break the existing post-check-in deposit behavior unless explicitly removed
- If the current post-check-in deposit entry point still makes sense to keep (e.g. for bookings where upfront payment was skipped, or for guests who want to add a partial payment mid-stay), keep it working as-is.
- The two payment touchpoints (at Confirm & Register, and the existing post-check-in one) should both ultimately write to the same underlying payment/deposit data structure, so the Grand Total subtraction logic in point 2 correctly reflects total amount paid regardless of when it was paid — not just upfront-at-booking specifically.

---

## Expected output
1. List of files changed/added with a brief summary of each change.
2. Full implementation for the requirements above.
3. Confirmation of how the existing deposit data model was reused (not duplicated) and exactly where the new Confirm & Register payment trigger was added.
4. Notes on any assumptions made — particularly around whether the post-check-in deposit entry point should remain enabled in parallel, since this wasn't fully specified.