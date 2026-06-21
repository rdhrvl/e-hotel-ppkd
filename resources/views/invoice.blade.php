<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Card & Invoice #{{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 30px;
            font-size: 12px;
            line-height: 1.5;
            background-color: #fff;
        }

        .invoice-box {
            max-width: 850px;
            margin: auto;
            border: 1px solid #cbd5e1;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        /* Banner Header */
        .banner {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
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
            margin: 2px 0 0;
            font-size: 10px;
            color: #c7d2fe;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
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
            margin: 2px 0 0;
            font-size: 11px;
            color: #e0e7ff;
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
            font-weight: 800;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-b: 1px solid #e2e8f0;
            margin: 0 0 10px;
            padding-bottom: 4px;
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
            font-size: 12px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        table th {
            background-color: #f1f5f9;
            border-bottom: 2px solid #cbd5e1;
            color: #475569;
            font-weight: 700;
            padding: 8px 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        table td {
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
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 4px 6px -1px rgb(79 70 229 / 0.2);
        }

        .print-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 10px -1px rgb(79 70 229 / 0.3);
        }

        @media print {
            .print-btn {
                display: none;
            }
            body {
                padding: 0;
            }
            .invoice-box {
                border: none;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    @php
        // Parse structured fields from the room notes column (whichCreateBooking saves to)
        $notes = $booking->room->notes ?? '';
        
        $arrivalTime = 'N/A';
        if (preg_match('/Arrival Time:\s*([^\.]+)/i', $notes, $matches)) {
            $arrivalTime = trim($matches[1]);
        }
        
        $profession = 'N/A';
        if (preg_match('/Profession:\s*([^\.]+)/i', $notes, $matches)) {
            $profession = trim($matches[1]);
        }
        
        $memberNo = 'N/A';
        if (preg_match('/Member No:\s*([^\.]+)/i', $notes, $matches)) {
            $memberNo = trim($matches[1]);
        }
        
        $safetyBox = 'N/A';
        if (preg_match('/Safety Box:\s*([^\.]+)/i', $notes, $matches)) {
            $safetyBox = trim($matches[1]);
        }

        $additionalNotes = '';
        if (preg_match('/Notes:\s*([^\.]+)/i', $notes, $matches)) {
            $additionalNotes = trim($matches[1]);
        }
    @endphp

    <div class="invoice-box">
        <button class="print-btn" onclick="window.print()">
            Print Invoice / Cetak Tagihan
        </button>

        {{-- Top Header banner --}}
        <div class="banner">
            <div class="banner-brand">
                <h1>PPKD HOTEL</h1>
                <p>Formulir Pendaftaran & Tagihan / Registration & Bill</p>
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
                <h3 class="section-title">I. Informasi Kamar <span>/ Room Details</span></h3>
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
                @if($additionalNotes)
                    <div class="field-row">
                        <span class="field-label">Notes</span>
                        <span class="field-value" style="font-size: 10px; max-width: 180px; text-align: right;">{{ $additionalNotes }}</span>
                    </div>
                @endif
            </div>

            {{-- Guest Info Section --}}
            <div class="section-card">
                <h3 class="section-title">II. Data Tamu <span>/ Guest Information</span></h3>
                <div class="field-row">
                    <span class="field-label">Name <span>/ Nama</span></span>
                    <span class="field-value">{{ $booking->guest->name }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Profession <span>/ Pekerjaan</span></span>
                    <span class="field-value">{{ $profession }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Nationality <span>/ Kebangsaan</span></span>
                    <span class="field-value">Indonesian</span>
                </div>
                <div class="field-row">
                    <span class="field-label">ID No. <span>/ KTP/Passport</span></span>
                    <span class="field-value">{{ $booking->guest->identity_number }}</span>
                </div>
            </div>

            {{-- Contact Details Section --}}
            <div class="section-card">
                <h3 class="section-title">III. Kontak <span>/ Contact Details</span></h3>
                <div class="field-row">
                    <span class="field-label">Address <span>/ Alamat</span></span>
                    <span class="field-value" style="font-size: 10px; max-width: 180px; text-align: right;">{{ $booking->guest->address ?: 'N/A' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Phone <span>/ Telp</span></span>
                    <span class="field-value">{{ $booking->guest->phone ?: 'N/A' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Email</span>
                    <span class="field-value">{{ $booking->guest->email ?: 'N/A' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Member No.</span>
                    <span class="field-value">{{ $memberNo }}</span>
                </div>
            </div>

            {{-- Stay & Box Section --}}
            <div class="section-card">
                <h3 class="section-title">IV. Menginap & Safe Box <span>/ Stay & Safe Box</span></h3>
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
                    <span class="field-value">{{ $arrivalTime }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Safety Box No.</span>
                    <span class="field-value">{{ $safetyBox }}</span>
                </div>
            </div>

        </div>

        {{-- Billing breakdown list --}}
        <h3 class="bill-title">Rincian Transaksi / Billing Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Description <span>/ Rincian</span></th>
                    <th>Nights/Qty <span>/ Jumlah</span></th>
                    <th>Rate <span>/ Tarif (Rp)</span></th>
                    <th style="text-align: right;">Total <span>/ Jumlah (Rp)</span></th>
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

        {{-- Invoice Footer --}}
        <div class="footer">
            <p>Terima kasih atas kunjungan Anda di PPKD Hotel! / Thank you for choosing PPKD Hotel!</p>
            <p style="font-size: 9px; margin-top: 8px; color: #94a3b8;">This is a computer-generated invoice and requires no physical signature.</p>
        </div>
    </div>
</body>
</html>
