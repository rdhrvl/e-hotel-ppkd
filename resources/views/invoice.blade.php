<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #HMS-{{ str_pad((string)$booking->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 30px;
            font-size: 14px;
            line-height: 1.6;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            background: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .brand h1 {
            margin: 0;
            font-size: 28px;
            color: #6c5ce7;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand p {
            margin: 2px 0 0;
            font-size: 10px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-details h2 {
            margin: 0 0 5px;
            font-size: 20px;
            color: #111;
        }

        .invoice-details p {
            margin: 2px 0;
            color: #555;
        }

        .billing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .billing-col h3 {
            margin: 0 0 10px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #777;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .billing-col p {
            margin: 4px 0;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table th {
            background-color: #fcfcfc;
            border-bottom: 2px solid #eee;
            color: #555;
            font-weight: 600;
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .summary-box {
            display: flex;
            justify-content: flex-end;
        }

        .summary-table {
            width: 300px;
            margin-bottom: 0;
        }

        .summary-table td {
            padding: 6px 12px;
            border-bottom: none;
        }

        .summary-table tr.total td {
            font-size: 16px;
            font-weight: 700;
            color: #111;
            border-top: 2px solid #eee;
            padding-top: 10px;
        }

        .footer {
            margin-top: 60px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            color: #888;
            font-size: 12px;
        }

        .print-btn {
            background-color: #6c5ce7;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .print-btn:hover {
            background-color: #5b4cc4;
        }

        @media print {
            .print-btn {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <button class="print-btn" onclick="window.print()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline-block;"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Invoice
        </button>

        <div class="header">
            <div class="brand">
                <h1>HMS</h1>
                <p>Hotel Management System</p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <p><strong>Invoice No:</strong> #HMS-{{ str_pad((string)$booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>
            </div>
        </div>

        <div class="billing-grid">
            <div class="billing-col">
                <h3>Hotel Details</h3>
                <p><strong>Grand Horizon Hotel</strong></p>
                <p>Jl. Premium Boulevard No. 88</p>
                <p>Jakarta, Indonesia</p>
                <p>Phone: +62 21-555-0199</p>
            </div>
            <div class="billing-col">
                <h3>Guest Details</h3>
                <p><strong>Name:</strong> {{ $booking->guest_name }}</p>
                <p><strong>ID Document:</strong> {{ $booking->guest_id }}</p>
                <p><strong>Stay:</strong> {{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }} ({{ $booking->nights }} Nights)</p>
                <p><strong>Room Assigned:</strong> Room {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Quantity/Nights</th>
                    <th>Unit Rate (Rp)</th>
                    <th style="text-align: right;">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Room Charge — Room {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</td>
                    <td>{{ $booking->nights }}</td>
                    <td>Rp {{ number_format($booking->room->effective_price) }}</td>
                    <td style="text-align: right; font-weight: 500;">Rp {{ number_format($booking->guestBill->total_room_charges) }}</td>
                </tr>
                @foreach($booking->bookingItems as $item)
                    <tr>
                        <td>
                            {{ $item->service->name }}
                            @if($item->notes)
                                <div style="font-size: 11px; color: #666; margin-top: 2px;">{{ $item->notes }}</div>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format((float)$item->price) }}</td>
                        <td style="text-align: right; font-weight: 500;">Rp {{ number_format($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-box">
            @php
                $bill = $booking->guestBill;
                $grandTotal = $bill->total_room_charges + $bill->total_extra_charges;
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
                    <td style="text-align: right; font-weight: 500;">Rp {{ number_format($grandTotal) }}</td>
                </tr>
                <tr>
                    <td style="color: #27ae60;"><strong>Security Deposit:</strong></td>
                    <td style="text-align: right; color: #27ae60;">- Rp {{ number_format($bill->deposit_amount) }}</td>
                </tr>
                @if($bill->paid_amount > 0)
                    <tr>
                        <td style="color: #27ae60;"><strong>Amount Paid:</strong></td>
                        <td style="text-align: right; color: #27ae60;">- Rp {{ number_format($bill->paid_amount) }}</td>
                    </tr>
                @endif
                <tr class="total">
                    <td><strong>Balance Due:</strong></td>
                    <td style="text-align: right;">Rp {{ number_format(max(0, $balance)) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for choosing Grand Horizon Hotel. We hope you enjoyed your stay!</p>
            <p style="font-size: 10px; margin-top: 10px;">This is a computer-generated invoice and requires no physical signature.</p>
        </div>
    </div>
</body>
</html>
