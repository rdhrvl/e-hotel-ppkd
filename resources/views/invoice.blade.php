<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation & Invoice #{{ $booking->booking_code }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: #334155;
            margin: 0;
            padding: 30px;
            font-size: 12px;
            line-height: 1.5;
            background-color: #f8fafc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ==========================================================================
           SCREEN VIEW STYLING
           ========================================================================== */
        .invoice-box {
            max-width: 850px;
            margin: auto;
            border: 1px solid #e2e8f0;
            padding: 24px;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        /* Banner Header */
        .banner {
            background: #2563eb;
            color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .banner-brand h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .banner-brand p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #dbeafe;
            letter-spacing: 0.3px;
            font-weight: 500;
        }

        .banner-details {
            text-align: right;
        }

        .banner-details h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .banner-details p {
            margin: 3px 0 0;
            font-size: 11px;
            color: #dbeafe;
        }

        /* Grid Layout for Sections */
        .section-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        /* Section Container */
        .section-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            background-color: #f8fafc;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 0.3px;
            border-bottom: 1px solid #e2e8f0;
            margin: 0 0 10px;
            padding-bottom: 6px;
        }

        .section-title span {
            color: #64748b;
            font-weight: 500;
            font-size: 10px;
            text-transform: none;
            margin-left: 4px;
        }

        .field-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed #f1f5f9;
        }

        .field-row:last-child {
            border-bottom: none;
        }

        .field-label {
            color: #64748b;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
        }

        .field-label span {
            font-weight: 400;
            text-transform: none;
            color: #94a3b8;
        }

        .field-value {
            font-weight: 700;
            color: #0f172a;
        }

        /* Invoice Table */
        .bill-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.2px;
            margin-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
        }

        .screen-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .screen-table th {
            background-color: #f1f5f9;
            border-bottom: 2px solid #cbd5e1;
            color: #475569;
            font-weight: 700;
            padding: 8px 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        .screen-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
        }

        .summary-table {
            width: 320px;
            margin-bottom: 0;
        }

        .summary-table td {
            padding: 5px 12px;
            border-bottom: none;
        }

        .summary-table tr.total td {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            border-top: 2px solid #cbd5e1;
            padding-top: 8px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }

        .print-btn {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.15s ease-in-out;
            text-decoration: none;
        }

        .print-btn:hover {
            background: #1d4ed8;
        }

        .print-btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            margin-right: auto;
        }

        .print-btn-secondary:hover {
            background: #e2e8f0;
        }

        .controls-row {
            display: flex;
            justify-content: space-between;
            max-width: 850px;
            margin: 0 auto 20px;
        }

        /* ==========================================================================
           1:1 PRINT VIEW STYLING (HIDDEN ON SCREEN)
           ========================================================================== */
        .invoice-print-view {
            display: none;
        }

        /* ==========================================================================
           PRINT OVERRIDES
           ========================================================================== */
        @media print {
            body {
                padding: 0;
                background-color: #ffffff;
                color: #000000;
                font-size: 11px;
                line-height: 1.4;
            }

            .controls-row,
            .invoice-screen-view {
                display: none !important;
            }

            .invoice-print-view {
                display: block !important;
                max-width: 100%;
                margin: 0;
                background: #ffffff;
            }

            .print-brand-title {
                font-size: 15px;
                font-weight: 800;
                margin: 0 0 4px 0;
                text-transform: uppercase;
            }

            .print-doc-title {
                font-size: 13px;
                font-weight: 700;
                margin: 0 0 10px 0;
            }

            .print-separator {
                border-bottom: 1.5px solid #000000;
                margin: 8px 0;
            }

            .print-field-table {
                width: 100%;
                border-collapse: collapse;
                margin: 8px 0;
            }

            .print-field-table td {
                padding: 3px 0;
                vertical-align: top;
                font-size: 11px;
            }

            .print-dots-col {
                width: 10px;
                text-align: center;
            }

            .print-field-val {
                font-weight: 700;
            }

            .print-indent-section {
                padding-left: 20px;
                margin-top: 10px;
            }

            .print-policy-list {
                margin: 8px 0;
                padding-left: 15px;
                list-style-type: decimal;
            }

            .print-policy-list li {
                margin-bottom: 6px;
                text-align: justify;
            }

            .print-sig-block {
                margin-top: 30px;
                text-align: right;
            }

            .print-sig-line {
                display: inline-block;
                border-top: 1px solid #000000;
                width: 200px;
                margin-top: 50px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    {{-- Screen controls row --}}
    <div class="controls-row">
        <a href="{{ route('bookings') }}" class="print-btn print-btn-secondary">
            &larr; Back to Registry
        </a>
        <button class="print-btn" onclick="window.print()">
            Print Reservation Confirmation
        </button>
    </div>

    <!-- =======================================================================
         1. ON-SCREEN VIEWER (PREMIUM ADMINISTRATIVE INVOICE CANVAS)
         ======================================================================= -->
    <div class="invoice-screen-view">
        <div class="invoice-box">
            {{-- Top Header banner --}}
            <div class="banner">
                <div class="banner-brand">
                    <h1>PPKD HOTEL</h1>
                    <p>Registration & Billing Dashboard</p>
                </div>
                <div class="banner-details">
                    <h2>INVOICE</h2>
                    <p><strong>Code:</strong> {{ $booking->booking_code }}</p>
                    <p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Sections Grid --}}
            <div class="section-grid">
                
                {{-- Room Details Section --}}
                <div class="section-card">
                    <h3 class="section-title">I. Room Details</h3>
                    <div class="field-row">
                        <span class="field-label">Room No.</span>
                        <span class="field-value">{{ $booking->room->room_number }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Room Type</span>
                        <span class="field-value">{{ $booking->room->roomType->name }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">No. of Person</span>
                        <span class="field-value">{{ $booking->number_of_guests }} Pax</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Check-Out Time</span>
                        <span class="field-value">12.00 PM</span>
                    </div>
                </div>

                {{-- Guest Info Section --}}
                <div class="section-card">
                    <h3 class="section-title">II. Guest Information</h3>
                    <div class="field-row">
                        <span class="field-label">Name</span>
                        <span class="field-value">{{ $booking->guest->name }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Profession</span>
                        <span class="field-value">{{ $booking->guest->profession ?: 'N/A' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Nationality</span>
                        <span class="field-value">{{ $booking->guest->nationality ?: 'Indonesian' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">ID No.</span>
                        <span class="field-value">{{ $booking->guest->identity_number }}</span>
                    </div>
                </div>

                {{-- Contact Details Section --}}
                <div class="section-card">
                    <h3 class="section-title">III. Contact Details</h3>
                    <div class="field-row">
                        <span class="field-label">Address</span>
                        <span class="field-value" style="font-size: 10px; max-width: 180px; text-align: right;">{{ $booking->guest->address ?: 'N/A' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Phone</span>
                        <span class="field-value">{{ $booking->guest->phone ?: 'N/A' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Email</span>
                        <span class="field-value">{{ $booking->guest->email ?: 'N/A' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Member No.</span>
                        <span class="field-value">{{ $booking->guest->member_no ?: 'N/A' }}</span>
                    </div>
                </div>

                {{-- Stay & Box Section --}}
                <div class="section-card">
                    <h3 class="section-title">IV. Stay & Safe Box</h3>
                    <div class="field-row">
                        <span class="field-label">Arrival Date</span>
                        <span class="field-value">{{ $booking->check_in_date->format('d M Y') }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Departure Date</span>
                        <span class="field-value">{{ $booking->check_out_date->format('d M Y') }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Arrival Time</span>
                        <span class="field-value">{{ $booking->arrival_time ?: 'N/A' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Safety Box No.</span>
                        <span class="field-value">{{ $booking->box_no ?: 'N/A' }}</span>
                    </div>
                </div>

            </div>

            {{-- Billing breakdown list --}}
            <h3 class="bill-title">Billing Details / Rincian Transaksi</h3>
            <table class="screen-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Rate (Rp)</th>
                        <th style="text-align: right;">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Room Charge - Room {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</td>
                        <td>{{ $booking->nights }}</td>
                        <td>Rp {{ number_format($booking->room->effective_price) }}</td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($booking->guestBill->total_room_charges) }}</td>
                    </tr>
                    @foreach($booking->bookingItems as $item)
                        <tr>
                            <td>
                                {{ $item->service->name }}
                                @if($item->notes)
                                    <div style="font-size: 10px; color: #64748b; margin-top: 2px;">{{ $item->notes }}</div>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format((float)$item->price) }}</td>
                            <td style="text-align: right; font-weight: 700;">Rp {{ number_format($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals Summary --}}
            <div class="summary-wrapper">
                @php
                    $bill = $booking->guestBill;
                    $grandTotal = $bill->total_room_charges + $bill->total_extra_charges;
                    
                    $upfrontPayment = $booking->payments->first(function($p) use ($booking) {
                        return $p->created_at <= $booking->created_at->addMinutes(5); 
                    });
                    $upfrontAmount = $upfrontPayment ? (float)$upfrontPayment->amount : 0.0;
                    $otherPrepaid = (float)$bill->deposit_amount - $upfrontAmount;
                    
                    $balance = $grandTotal - $bill->deposit_amount - $bill->paid_amount;
                @endphp
                <table class="summary-table">
                    <tr>
                        <td><strong>Room Cost:</strong></td>
                        <td style="text-align: right;">Rp {{ number_format($bill->total_room_charges) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Extra Charges:</strong></td>
                        <td style="text-align: right;">Rp {{ number_format($bill->total_extra_charges) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Grand Total:</strong></td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($grandTotal) }}</td>
                    </tr>
                    @if($upfrontAmount > 0)
                        <tr>
                            <td style="color: #10b981;"><strong>Upfront Payment:</strong></td>
                            <td style="text-align: right; color: #10b981;">- Rp {{ number_format($upfrontAmount) }}</td>
                        </tr>
                    @endif
                    @if($otherPrepaid > 0)
                        <tr>
                            <td style="color: #10b981;"><strong>Security Deposit:</strong></td>
                            <td style="text-align: right; color: #10b981;">- Rp {{ number_format($otherPrepaid) }}</td>
                        </tr>
                    @endif
                    @if($bill->paid_amount > 0)
                        <tr>
                            <td style="color: #10b981;"><strong>Amount Paid:</strong></td>
                            <td style="text-align: right; color: #10b981;">- Rp {{ number_format($bill->paid_amount) }}</td>
                        </tr>
                    @endif
                    <tr class="total">
                        <td><strong>Balance Due:</strong></td>
                        <td style="text-align: right; color: #ef4444;">Rp {{ number_format(max(0, $balance)) }}</td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <p>PPKD Hotel Administrative Billing Console. Press print to generate official confirmation letter layout.</p>
            </div>
        </div>
    </div>


    <!-- =======================================================================
         2. PRINT OUTPUT (1:1 CORRESPONDENCE TO ORIGINAL RESERVATION CONFIRMATION PDF)
         ======================================================================= -->
    <div class="invoice-print-view">
        <div class="print-brand-title">PPKD HOTEL</div>
        <div class="print-doc-title">Reservation Confirmation</div>

        <div class="print-separator"></div>

        <table class="print-field-table">
            <tr>
                <td width="15%">To.</td>
                <td class="print-dots-col">:</td>
                <td width="35%" class="print-field-val">{{ $booking->guest->name }}</td>
                <td width="10%"></td>
                <td width="10%"></td>
                <td width="30%"></td>
            </tr>
            <tr>
                <td>Company / Agent</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->guest->company ?: 'Individual' }}</td>
                <td></td>
                <td>Telp</td>
                <td class="print-field-val">: {{ $booking->guest->phone ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td>Booking No.</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->booking_code }}</td>
                <td></td>
                <td>Fax</td>
                <td class="print-field-val">: N/A</td>
            </tr>
            <tr>
                <td>Book By</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->book_by ?: 'Front Desk' }}</td>
                <td></td>
                <td>Email</td>
                <td class="print-field-val">: {{ $booking->guest->email ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td>Phone</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->guest->phone ?: 'N/A' }}</td>
                <td></td>
                <td>Date</td>
                <td class="print-field-val">: {{ $booking->created_at ? $booking->created_at->format('d M Y') : now()->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val" colspan="4">: {{ $booking->guest->email ?: 'N/A' }}</td>
            </tr>
        </table>

        <div class="print-separator"></div>

        <table class="print-field-table">
            <tr>
                <td width="20%">First Name</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->guest->name }}</td>
            </tr>
            <tr>
                <td>Arrival Date</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->check_in_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Departure Date</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->check_out_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Total Night</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->nights }} Night(s)</td>
            </tr>
            <tr>
                <td>Room/Unit Type</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->room->roomType->name }} (Room {{ $booking->room->room_number }})</td>
            </tr>
            <tr>
                <td>Person Pax</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">{{ $booking->number_of_guests }} Pax</td>
            </tr>
            <tr>
                <td>Room Rate Net</td>
                <td class="print-dots-col">:</td>
                <td class="print-field-val">Rp {{ number_format($booking->room->effective_price) }}</td>
            </tr>
        </table>

        <div class="print-separator"></div>

        <div style="text-align: justify; margin-bottom: 12px;">
            Please guarantee this booking with credit card number with clear copy of the card both sides and card holder signature in the column provided the copy of credit card both sides should be faxed to hotel fax number.
            <br>Please settle your outstanding to our account:
        </div>

        <div class="print-indent-section">
            <div style="font-weight: 700; margin-bottom: 4px;">Bank Transfer</div>
            <table class="print-field-table" style="width: auto;">
                <tr>
                    <td width="150">Mandiri Account</td>
                    <td class="print-dots-col">:</td>
                    <td class="print-field-val" style="padding-left: 8px;">124-00-1234567-8</td>
                </tr>
                <tr>
                    <td>Mandiri Name Account</td>
                    <td class="print-dots-col">:</td>
                    <td class="print-field-val" style="padding-left: 8px;">PPKD HOTEL</td>
                </tr>
            </table>
        </div>

        <div class="print-separator" style="border-bottom-style: dashed; margin-top: 15px;"></div>

        <div style="margin-top: 10px;">
            <div style="font-weight: 700; margin-bottom: 6px;">Reservation guaranteed by the following credit card:</div>
            <table class="print-field-table">
                <tr>
                    <td width="20%">Card Number</td>
                    <td class="print-dots-col">:</td>
                    <td width="30%">_______________________________</td>
                    <td width="20%">Card holder name</td>
                    <td class="print-dots-col">:</td>
                    <td width="30%">_______________________________</td>
                </tr>
                <tr>
                    <td>Card Type</td>
                    <td class="print-dots-col">:</td>
                    <td>_______________________________</td>
                    <td>Or by Bank Transfer to</td>
                    <td class="print-dots-col">:</td>
                    <td>_______________________________</td>
                </tr>
                <tr>
                    <td>Expired date/month/year</td>
                    <td class="print-dots-col">:</td>
                    <td>_______________________________</td>
                    <td>Card holder signature</td>
                    <td class="print-dots-col">:</td>
                    <td>_______________________________</td>
                </tr>
            </table>
        </div>

        <div class="print-separator" style="margin-top: 15px;"></div>

        <div>
            <div style="font-weight: 700;">Cancellation policy:</div>
            <ol class="print-policy-list">
                <li>Please note that check in time is 02.00 pm and check out time 12.00 pm.</li>
                <li>All non guarantined reservations will automatically be released on 6 pm.</li>
                <li>The Hotel will charge 1 night for guaranteed reservations that have not been canceling before the day of arrival. Please carefully note your cancellation number.</li>
            </ol>
        </div>

        <div class="print-sig-block">
            <div class="print-sig-line">
                Authorized Signature
            </div>
        </div>
    </div>

</body>
</html>
