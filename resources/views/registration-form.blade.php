<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran / Registration Form - {{ $booking->booking_code }}</title>
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
            color: #0f172a;
            margin: 0;
            padding: 30px;
            font-size: 11px;
            line-height: 1.4;
            background-color: #f8fafc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .container {
            max-width: 850px;
            margin: auto;
            border: 1px solid #e2e8f0;
            padding: 30px;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        /* Print Controls */
        .print-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #2563eb;
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

        .btn:hover {
            background-color: #1d4ed8;
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        /* Form Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px double #2563eb;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header-brand h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -0.5px;
        }

        .header-brand p {
            margin: 2px 0 0;
            font-size: 10px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-meta {
            text-align: right;
        }

        .header-meta h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .header-meta p {
            margin: 2px 0 0;
            font-size: 11px;
            font-family: monospace;
            font-weight: 700;
            color: #2563eb;
        }

        /* Grid Table Form */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #94a3b8;
        }

        .grid-table td {
            border: 1px solid #94a3b8;
            padding: 8px 12px;
            vertical-align: top;
        }

        .label-lang {
            display: block;
            font-size: 9px;
            color: #64748b;
            font-weight: 500;
            margin-top: 1px;
        }

        .field-title {
            font-weight: 700;
            color: #334155;
            font-size: 10px;
            text-transform: uppercase;
        }

        .field-content {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 4px;
        }

        .bg-gray-tint {
            background-color: #f8fafc;
        }

        /* Block Info banner inside table */
        .banner-row {
            text-align: center;
            font-weight: 700;
            color: #2563eb;
            font-size: 10px;
            background-color: #eff6ff;
            padding: 6px !important;
        }

        /* Payment Checkbox Section */
        .payment-method {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-top: 6px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .checkbox-box {
            width: 14px;
            height: 14px;
            border: 1.5px solid #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 10px;
        }

        /* Terms & Policy Area */
        .terms-section {
            font-size: 9.5px;
            color: #334155;
            border: 1px solid #94a3b8;
            border-radius: 6px;
            padding: 12px;
            background-color: #f8fafc;
            margin-bottom: 20px;
            text-align: justify;
        }

        .terms-title {
            font-weight: 700;
            margin-bottom: 6px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .terms-text-en {
            color: #64748b;
            margin-top: 4px;
            font-style: italic;
        }

        .rules-warning {
            border-top: 1px dashed #cbd5e1;
            margin-top: 8px;
            padding-top: 8px;
            color: #ef4444;
            font-weight: 600;
        }

        /* Signature Blocks */
        .signatures-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 25px;
            text-align: center;
        }

        .signature-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 110px;
        }

        .signature-title {
            font-weight: 700;
            color: #475569;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 4px;
        }

        .signature-line {
            margin-top: 45px;
            border-top: 1.5px solid #0f172a;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            padding-top: 4px;
            font-weight: 700;
            font-size: 10px;
        }

        /* Print Media Styles */
        @media print {
            body {
                padding: 0;
                background-color: #ffffff;
            }
            .container {
                max-width: 100%;
                border: none;
                padding: 0;
                box-shadow: none;
            }
            .print-controls {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        
        {{-- Print Action Controls --}}
        <div class="print-controls">
            <a href="{{ route('bookings') }}" class="btn btn-secondary">
                &larr; Back to Registry / Kembali
            </a>
            <button class="btn" onclick="window.print()">
                Print Registration Form / Cetak Formulir
            </button>
        </div>

        {{-- Form Header --}}
        <div class="header">
            <div class="header-brand">
                <h1>PPKD HOTEL</h1>
                <p>Formulir Pendaftaran / Registration Form</p>
            </div>
            <div class="header-meta">
                <h2>REGISTRATION CARD</h2>
                <p>{{ $booking->booking_code }}</p>
            </div>
        </div>

        {{-- Table Grid --}}
        <table class="grid-table">
            <tr>
                <td width="30%" class="bg-gray-tint">
                    <span class="field-title">Room No.</span>
                    <span class="label-lang">Nomor Kamar</span>
                    <div class="field-content">{{ $booking->room->room_number }}</div>
                </td>
                <td width="20%">
                    <span class="field-title">No. of Person</span>
                    <span class="label-lang">Jumlah Tamu</span>
                    <div class="field-content">{{ $booking->number_of_guests }} Pax</div>
                </td>
                <td width="20%">
                    <span class="field-title">No. of Room</span>
                    <span class="label-lang">Jumlah Kamar</span>
                    <div class="field-content">1</div>
                </td>
                <td width="30%" class="bg-gray-tint">
                    <span class="field-title">Room Type</span>
                    <span class="label-lang">Jenis Kamar</span>
                    <div class="field-content">{{ $booking->room->roomType->name }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="banner-row">
                    CHECK OUT TIME : 12.00 NOON / WAKTU LAPOR KELUAR : JAM 12.00 SIANG
                </td>
            </tr>
            <tr>
                <td colspan="3" class="bg-gray-tint">
                    <span class="field-title">Nama / Name</span>
                    <span class="label-lang">Harap tulis dengan huruf cetak — Please print in block letters</span>
                    <div class="field-content">{{ $booking->guest->name }}</div>
                </td>
                <td>
                    <span class="field-title">Arrival Time</span>
                    <span class="label-lang">Waktu Kedatangan</span>
                    <div class="field-content">{{ $booking->arrival_time ?: 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="field-title">Profession</span>
                    <span class="label-lang">Pekerjaan</span>
                    <div class="field-content">{{ $booking->guest->profession ?: 'N/A' }}</div>
                </td>
                <td colspan="2">
                    <span class="field-title">Company</span>
                    <span class="label-lang">Perusahaan</span>
                    <div class="field-content">{{ $booking->guest->company ?: 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="bg-gray-tint">
                    <span class="field-title">Nationality</span>
                    <span class="label-lang">Kebangsaan</span>
                    <div class="field-content">{{ $booking->guest->nationality ?: 'Indonesian' }}</div>
                </td>
                <td>
                    <span class="field-title">ID / Passport No.</span>
                    <span class="label-lang">No. KTP</span>
                    <div class="field-content" style="font-family: monospace;">{{ $booking->guest->identity_number }}</div>
                </td>
                <td class="bg-gray-tint">
                    <span class="field-title">Birth Date</span>
                    <span class="label-lang">Tanggal Lahir</span>
                    <div class="field-content">
                        {{ $booking->guest->birth_date ? $booking->guest->birth_date->format('d M Y') : 'N/A' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="field-title">Address</span>
                    <span class="label-lang">Alamat Lengkap</span>
                    <div class="field-content" style="font-size: 10px;">{{ $booking->guest->address ?: 'N/A' }}</div>
                </td>
                <td class="bg-gray-tint">
                    <span class="field-title">Arrival Date</span>
                    <span class="label-lang">Tanggal Kedatangan</span>
                    <div class="field-content">{{ $booking->check_in_date->format('d M Y') }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="field-title">Telephone / Phone</span>
                    <span class="label-lang">Nomor Telepon</span>
                    <div class="field-content">{{ $booking->guest->phone ?: 'N/A' }}</div>
                </td>
                <td>
                    <span class="field-title">Email Address</span>
                    <span class="label-lang">Alamat Email</span>
                    <div class="field-content">{{ $booking->guest->email ?: 'N/A' }}</div>
                </td>
                <td class="bg-gray-tint">
                    <span class="field-title">Departure Date</span>
                    <span class="label-lang">Tanggal Keberangkatan</span>
                    <div class="field-content">{{ $booking->check_out_date->format('d M Y') }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="bg-gray-tint">
                    <span class="field-title">Member Card No.</span>
                    <span class="label-lang">No. Member</span>
                    <div class="field-content">{{ $booking->guest->member_no ?: 'N/A' }}</div>
                </td>
                <td colspan="2">
                    <span class="field-title">Method of Payment</span>
                    <span class="label-lang">Cara Pembayaran</span>
                    <div class="payment-method">
                        <div class="checkbox-item">
                            <div class="checkbox-box">{{ $booking->payment_method === 'credit_card' || $booking->payment_method === 'visa' ? '✓' : '' }}</div>
                            <span>VISA</span>
                        </div>
                        <div class="checkbox-item">
                            <div class="checkbox-box">{{ $booking->payment_method === 'debit_card' ? '✓' : '' }}</div>
                            <span>Debit Card</span>
                        </div>
                        <div class="checkbox-item">
                            <div class="checkbox-box">{{ !in_array($booking->payment_method, ['credit_card', 'visa', 'debit_card']) ? '✓' : '' }}</div>
                            <span>Other ({{ ucfirst($booking->payment_method) }})</span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="bg-gray-tint">
                    <span class="field-title">Safety Deposit Box Number</span>
                    <span class="label-lang">Nomor Kotak Deposit</span>
                    <div class="field-content">{{ $booking->box_no ?: 'N/A' }}</div>
                </td>
                <td>
                    <span class="field-title">Issued By</span>
                    <span class="label-lang">Dikeluarkan oleh</span>
                    <div class="field-content">{{ $booking->box_issued_by ?: 'N/A' }}</div>
                </td>
                <td class="bg-gray-tint">
                    <span class="field-title">Box Issued Date</span>
                    <span class="label-lang">Tanggal</span>
                    <div class="field-content">
                        {{ $booking->box_date ? $booking->box_date->format('d M Y') : 'N/A' }}
                    </div>
                </td>
            </tr>
        </table>

        {{-- Terms & Policies --}}
        <div class="terms-section">
            <div class="terms-title">Pernyataan Tanggung Jawab / Liability Statement</div>
            <div>
                Kepada Park Hotel, Saya menyatakan bahwa saya baik sendiri ataupun bersama-sama dengan perusahaan, asosiasi, perorangan atau semuanya bertanggung jawab atas pembayaran semua tagihan yang terjadi sehubungan dengan seluruh pelayanan yang Anda berikan sesuai formulir pendaftaran ini.
            </div>
            <div class="terms-text-en">
                To Park Hotel: I acknowledge that I am jointly and severally liable with the fore-going person, company or association (and if more than one all of them) for payment of the amount of any charges payable or incurred in connection with all service provided by you under registration.
            </div>
            
            <div class="rules-warning">
                ⚠️ PERATURAN HOTEL / HOTEL RULES:
                <br>1. Anda tidak diperkenankan untuk membawa durian ke area hotel. / <em>Please be informed that you are not allowed to bring Durian into the hotel premises.</em>
                <br>2. Barang berharga (perhiasan, uang dsb) dapat disimpan dalam brankas di kamar anda atau di kantor depan. / <em>Valuable belongings (jewels, money, etc) should be secured in the safe deposit box in your room or in the front office.</em>
                <br>3. Kamar ini bebas rokok. Denda sebesar Rp. 1.000.000,- akan ditagihkan apabila Anda merokok di kamar ini. / <em>This room is designed as a non-smoking room. A fine of Rp. 1,000,000,- will be charged for smoking in this room.</em>
            </div>
        </div>

        {{-- Signatures block --}}
        <div class="signatures-container">
            <div class="signature-box">
                <span class="signature-title">Tanda Tangan Tamu / Guest Signature</span>
                <div class="signature-line">{{ $booking->guest->name }}</div>
            </div>
            <div class="signature-box">
                <span class="signature-title">Melapor Masuk Oleh / Check in by</span>
                <div class="signature-line">{{ $booking->book_by ?: 'Front Desk' }}</div>
            </div>
            <div class="signature-box">
                <span class="signature-title">Melapor Keluar Oleh / Check out by</span>
                <div class="signature-line">&nbsp;</div>
            </div>
        </div>

    </div>

</body>
</html>
