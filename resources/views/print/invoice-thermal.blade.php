<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 80mm auto;
            margin: 0 !important;
        }

        *, ::before, ::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'IBM Plex Sans Arabic', 'Segoe UI', Tahoma, Arial, sans-serif !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            text-rendering: geometricPrecision;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html, body {
            background-color: #f1f5f9;
            color: #000;
            font-size: 13.5px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Screen Preview Toolbar */
        .screen-toolbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #0f172a;
            color: #fff;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            font-size: 12px;
        }

        .screen-toolbar .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-print {
            background: #2563eb;
            color: #fff;
        }
        .btn-print:hover { background: #1d4ed8; }

        .btn-close {
            background: #334155;
            color: #e2e8f0;
        }
        .btn-close:hover { background: #475569; }

        /* Thermal Receipt Container (80mm) */
        .thermal-wrapper {
            width: 80mm;
            max-width: 80mm;
            margin: 15px auto 40px auto;
            background: #fff;
            padding: 4mm 3mm 10mm 3mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        /* Header Branding */
        .receipt-header {
            text-align: center;
            padding-bottom: 6px;
        }

        .company-name {
            font-size: 21px;
            font-weight: 800;
            color: #000;
            line-height: 1.25;
            margin-bottom: 3px;
            letter-spacing: -0.3px;
        }

        .company-meta {
            font-size: 13px;
            color: #000;
            line-height: 1.4;
            font-weight: 600;
        }

        /* Dashed Dividers */
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 5px 0;
            width: 100%;
        }

        .double-dashed-line {
            border-top: 2px dashed #000;
            margin: 6px 0;
            width: 100%;
        }

        /* Title Badge */
        .receipt-title-badge {
            text-align: center;
            font-size: 15px;
            font-weight: 800;
            padding: 3px 10px;
            margin: 4px auto;
            border: 2px solid #000;
            border-radius: 4px;
            display: inline-block;
            background: #000;
            color: #fff;
        }

        /* Meta Table / Key Values */
        .meta-list {
            width: 100%;
            margin: 4px 0;
            font-size: 13.5px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2.5px 0;
        }

        .meta-label {
            color: #000;
            font-weight: 700;
            font-size: 13.5px;
        }

        .meta-value {
            font-weight: 800;
            color: #000;
            direction: ltr;
            text-align: left;
            font-size: 13.5px;
        }

        .meta-value-rtl {
            font-weight: 800;
            color: #000;
            direction: rtl;
            text-align: left;
            font-size: 13.5px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            font-size: 12.5px;
        }

        .items-table th {
            border-top: 1.5px dashed #000;
            border-bottom: 1.5px dashed #000;
            padding: 4px 1.5px;
            font-weight: 800;
            color: #000;
            text-align: center;
            font-size: 12px;
        }

        .items-table td {
            padding: 4px 1.5px;
            border-bottom: 1px dotted #ccc;
            color: #000;
            vertical-align: middle;
            font-size: 12.5px;
            font-weight: 700;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .col-item {
            width: 44%;
            text-align: right !important;
            font-weight: 700;
            word-break: break-word;
        }

        .col-qty {
            width: 16%;
            text-align: center !important;
            font-weight: 800;
            direction: ltr;
        }

        .col-price {
            width: 20%;
            text-align: center !important;
            direction: ltr;
            font-weight: 700;
        }

        .col-total {
            width: 20%;
            text-align: left !important;
            font-weight: 800;
            direction: ltr;
        }

        /* Financial Summary Box */
        .totals-box {
            width: 100%;
            margin: 4px 0;
            font-size: 13.5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3px 0;
        }

        .total-row.grand-total {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 0;
            margin: 3px 0;
            font-size: 16px;
            font-weight: 800;
        }

        .total-row.grand-total .val {
            font-size: 17px;
            font-weight: 800;
        }

        .total-label {
            font-weight: 700;
            font-size: 13.5px;
        }

        .total-val {
            font-weight: 800;
            font-size: 13.5px;
            direction: ltr;
        }

        /* Notes Box */
        .notes-box {
            margin: 5px 0;
            padding: 5px 6px;
            background: #f8fafc;
            border: 1px dashed #000;
            border-radius: 4px;
            font-size: 12px;
            line-height: 1.35;
        }

        /* Footer & Barcode */
        .receipt-footer {
            text-align: center;
            margin-top: 6px;
            padding-top: 4px;
        }

        .thank-you-text {
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .footer-sub {
            font-size: 12px;
            color: #000;
            font-weight: 600;
        }

        .barcode-container {
            margin: 6px auto 2px auto;
            text-align: center;
        }

        .barcode-container svg {
            display: inline-block;
            max-width: 160px;
            height: 32px;
        }

        .barcode-text {
            font-family: monospace !important;
            font-size: 10px;
            letter-spacing: 2px;
            font-weight: 700;
            direction: ltr;
            margin-top: 1px;
        }

        .cut-indicator {
            margin-top: 12px;
            border-top: 1px dotted #999;
            text-align: center;
            font-size: 9px;
            color: #777;
            padding-top: 2px;
        }

        /* Print Media Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            html, body {
                background: #fff !important;
                color: #000 !important;
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .thermal-wrapper {
                width: 100% !important;
                max-width: 80mm !important;
                margin: 0 !important;
                padding: 2mm 2.5mm 6mm 2.5mm !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
            }

            .receipt-title-badge {
                background: #000 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .items-table td {
                border-bottom: 1px dotted #888 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Screen Toolbar (hidden in print and auto-hidden if inside iframe) -->
    <div class="screen-toolbar no-print" id="screenToolbar">
        <div style="display: flex; align-items: center; gap: 8px;">
            <svg style="width: 16px; height: 16px; color: #f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <strong>معاينة طباعة حرارية (80mm)</strong>
        </div>
        <div style="display: flex; align-items: center; gap: 6px;">
            <button onclick="window.print()" class="btn btn-print">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>طباعة الآن</span>
            </button>
            <button onclick="window.close()" class="btn btn-close">إغلاق</button>
        </div>
    </div>

    <!-- Thermal 80mm Receipt Body -->
    <div class="thermal-wrapper">
        
        <!-- Header Branding -->
        <div class="receipt-header">
            <div class="company-name">{{ \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا') }}</div>
            
            @php
                $commercialRegister = \App\Models\Setting::get('commercial_register');
                $taxNumber = \App\Models\Setting::get('tax_number');
                $phone = \App\Models\Setting::get('company_phone') ?: \App\Models\Setting::get('phone');
                $address = \App\Models\Setting::get('company_address') ?: \App\Models\Setting::get('address');
                $party = $type === 'purchase' ? $invoice->supplier : $invoice->customer;
                $invoiceNum = $invoice->invoice_number ?? $invoice->id;
                $barcodeVal = ($type === 'purchase' ? 'PUR-' : 'INV-') . str_pad($invoice->id, 5, '0', STR_PAD_LEFT);
            @endphp

            <div class="company-meta">
                @if($phone && $phone !== '---' && trim($phone) !== '')
                    <div>هاتف: <span dir="ltr">{{ $phone }}</span></div>
                @endif
                @if($taxNumber && $taxNumber !== '---' && trim($taxNumber) !== '')
                    <div>رقم ضريبي: <span dir="ltr">{{ $taxNumber }}</span></div>
                @endif
                @if($commercialRegister && $commercialRegister !== '---' && trim($commercialRegister) !== '')
                    <div>سجل تجاري: <span dir="ltr">{{ $commercialRegister }}</span></div>
                @endif
                @if($address && trim($address) !== '')
                    <div>{{ $address }}</div>
                @endif
            </div>

            <div style="margin-top: 4px;">
                <span class="receipt-title-badge">
                    {{ $type === 'purchase' ? 'فاتورة مشتريات' : 'فاتورة مبيعات' }}
                </span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Invoice Meta Details -->
        <div class="meta-list">
            <div class="meta-row">
                <span class="meta-label">رقم الفاتورة:</span>
                <span class="meta-value">#{{ $invoiceNum }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">التاريخ والوقت:</span>
                <span class="meta-value">{{ ($invoice->invoice_date ?? $invoice->created_at)->format('Y-m-d') }} {{ $invoice->created_at->format('h:i A') }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">{{ $type === 'purchase' ? 'المورد:' : 'العميل:' }}</span>
                <span class="meta-value-rtl">{{ $party->name ?? 'عميل عام' }}</span>
            </div>
            @if($party && !empty($party->phone) && $party->phone !== '---')
            <div class="meta-row">
                <span class="meta-label">هاتف {{ $type === 'purchase' ? 'المورد' : 'العميل' }}:</span>
                <span class="meta-value" dir="ltr">{{ $party->phone }}</span>
            </div>
            @endif
            <div class="meta-row">
                <span class="meta-label">المستخدم / الكاشير:</span>
                <span class="meta-value-rtl">{{ auth()->user()?->name ?? 'مدير النظام' }}</span>
            </div>
        </div>

        <div class="double-dashed-line"></div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-item">الصنف</th>
                    <th class="col-qty">الكمية</th>
                    <th class="col-price">السعر</th>
                    <th class="col-total">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td class="col-item">
                        <div>{{ $item->product->name }}</div>
                    </td>
                    <td class="col-qty">
                        {{ format_quantity($item->quantity) }}
                    </td>
                    <td class="col-price">
                        {{ format_amount($item->unit_price) }}
                    </td>
                    <td class="col-total">
                        {{ format_amount($item->total) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="double-dashed-line"></div>

        <!-- Financial Summary -->
        @php
            $transaction = $invoice->transaction;
            $paidCash = $transaction ? (float) $transaction->paid_amount : 0;
            $totalAmount = (float) $invoice->total_amount;
            $paidFromBalance = 0;
            if ($transaction && $transaction->paid_amount > $totalAmount) {
                $paidCash = $totalAmount;
                $paidFromBalance = $transaction->paid_amount - $totalAmount;
            }
            $remaining = max(0, round($totalAmount - $paidCash, 2));
        @endphp

        <div class="totals-box">
            <div class="total-row grand-total">
                <span class="total-label">إجمالي الفاتورة:</span>
                <span class="total-val val">{{ format_amount($totalAmount) }} ج.م</span>
            </div>
            
            <div class="total-row">
                <span class="total-label">المدفوع نقداً:</span>
                <span class="total-val">{{ format_amount($paidCash) }} ج.م</span>
            </div>

            @if($paidFromBalance > 0)
            <div class="total-row">
                <span class="total-label">مسدد من الرصيد:</span>
                <span class="total-val">{{ format_amount($paidFromBalance) }} ج.م</span>
            </div>
            @endif

            @if($remaining > 0)
            <div class="total-row" style="font-weight: 800;">
                <span class="total-label">المتبقي (آجل):</span>
                <span class="total-val">{{ format_amount($remaining) }} ج.م</span>
            </div>
            @endif

            @if($party && isset($party->balance))
            <div class="dashed-line" style="margin: 5px 0;"></div>
            <div class="total-row" style="font-size: 13.5px; font-weight: 800;">
                <span class="total-label">رصيد الحساب الحالي:</span>
                <span class="total-val">
                    {{ format_amount(abs($party->balance)) }} ج.م
                    @if($type === 'purchase')
                        @if($party->balance > 0)
                            (له علينا)
                        @elseif($party->balance < 0)
                            (لنا عنده)
                        @else
                            (خالص)
                        @endif
                    @else
                        @if($party->balance > 0)
                            (مطلوب منه)
                        @elseif($party->balance < 0)
                            (له عندنا)
                        @else
                            (خالص)
                        @endif
                    @endif
                </span>
            </div>
            @endif
        </div>

        @if(!empty($invoice->notes))
        <div class="notes-box">
            <strong>ملاحظات:</strong> {{ $invoice->notes }}
        </div>
        @endif

        <div class="dashed-line"></div>

        <!-- Receipt Footer & Barcode -->
        <div class="receipt-footer">
            <div class="thank-you-text">شكراً لتعاملكم معنا</div>
            <div class="footer-sub">نسعد بخدمتكم دائماً</div>

            <!-- Barcode SVG Simulation -->
            <div class="barcode-container">
                <svg viewBox="0 0 160 30" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0" y="0" width="160" height="30" fill="#fff"/>
                    @php
                        // Deterministic barcode pattern based on barcodeVal
                        $pattern = [2,1,3,1,1,2,1,3,2,1,1,2,3,1,2,1,1,3,2,1,3,1,1,2,1,3,1,2,2,1,1,3,2,1,1,2];
                        $x = 8;
                    @endphp
                    @foreach($pattern as $idx => $width)
                        @if($idx % 2 === 0)
                            <rect x="{{ $x }}" y="2" width="{{ $width * 1.6 }}" height="26" fill="#000"/>
                        @endif
                        @php $x += $width * 1.6; @endphp
                    @endforeach
                </svg>
                <div class="barcode-text">{{ $barcodeVal }}</div>
            </div>

            <div class="cut-indicator">
                - - - - - - - - - - - - - - - - - - - - - -
            </div>
        </div>

    </div>

    <script>
        // If loaded in iframe inside preview modal, hide the top toolbar
        if (window.self !== window.top) {
            const tb = document.getElementById('screenToolbar');
            if (tb) tb.style.display = 'none';
        }

        // Automatic print trigger if autoprint=1 query param is given
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('autoprint')) {
                setTimeout(() => {
                    window.print();
                }, 350);
            }
        });
    </script>
</body>
</html>
