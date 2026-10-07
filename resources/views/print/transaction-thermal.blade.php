<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $typeLabel = '';
        $subLabel = '';
        $refPrefix = 'TRX';
        
        if ($transaction->type === 'payment_received') {
            $typeLabel = 'إيصال استلام نقدية (تحصيل)';
            $subLabel = 'سند تحصيل من عميل';
            $refPrefix = 'REC';
        } elseif ($transaction->type === 'payment_made' || $transaction->type === 'payment_sent') {
            $typeLabel = 'إيصال صرف نقدية (سداد)';
            $subLabel = 'سند سداد لمورد';
            $refPrefix = 'PAY';
        } elseif ($transaction->type === 'return_sale') {
            $typeLabel = 'إيصال مرتجع مبيعات';
            $subLabel = 'بضاعة مسترجعة من عميل';
            $refPrefix = 'RET-S';
        } elseif ($transaction->type === 'return_purchase') {
            $typeLabel = 'إيصال مرتجع مشتريات';
            $subLabel = 'بضاعة مسترجعة لمورد';
            $refPrefix = 'RET-P';
        } elseif ($transaction->type === 'cash_withdrawal') {
            $typeLabel = 'إيصال سحب نقدي';
            $subLabel = 'صرف نقدية للعميل';
            $refPrefix = 'WTH';
        } else {
            $typeLabel = 'إيصال عملية مالية';
            $subLabel = 'مستند قيد مالي';
            $refPrefix = 'TRX';
        }
        
        $referenceCode = $refPrefix . '-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
        $amount = in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent', 'cash_withdrawal']) ? $transaction->paid_amount : $transaction->total_amount;
        $transactionable = $transaction->transactionable;
        $partyType = in_array($transaction->type, ['payment_received', 'return_sale', 'cash_withdrawal']) ? 'العميل' : 'المورد';
    @endphp
    <title>{{ $typeLabel }} #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }} (حراري)</title>
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
            color: #cbd5e1;
        }
        .btn-close:hover { background: #475569; color: #fff; }

        /* Outer Paper Container */
        .thermal-paper {
            width: 80mm;
            max-width: 80mm;
            min-height: 100mm;
            margin: 16px auto;
            background: #fff;
            padding: 5mm 4mm;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Thermal Layout Content */
        .thermal-wrapper {
            width: 100%;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 6px;
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

        .dashed-line {
            border-top: 1.5px dashed #000;
            margin: 6px 0;
            width: 100%;
        }

        .solid-line {
            border-top: 2px solid #000;
            margin: 6px 0;
            width: 100%;
        }

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

        /* Highlight Amount Box */
        .highlight-amount-box {
            margin: 8px 0;
            padding: 8px 10px;
            border: 2px solid #000;
            border-radius: 6px;
            text-align: center;
            background: #f8fafc;
        }

        .highlight-label {
            font-size: 13px;
            font-weight: 700;
            color: #000;
            margin-bottom: 2px;
        }

        .highlight-value {
            font-size: 22px;
            font-weight: 800;
            color: #000;
            direction: ltr;
            line-height: 1.2;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 12.5px;
        }

        .items-table th {
            border-top: 1.5px dashed #000;
            border-bottom: 1.5px dashed #000;
            padding: 4px 2px;
            font-weight: 800;
            color: #000;
            text-align: center;
            font-size: 12px;
        }

        .items-table td {
            padding: 5px 2px;
            border-bottom: 1px dashed #cbd5e1;
            color: #000;
            vertical-align: middle;
            font-size: 12.5px;
            font-weight: 700;
        }

        .totals-box {
            width: 100%;
            margin: 5px 0;
            font-size: 13.5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3px 0;
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

        .balance-box {
            margin: 6px 0;
            padding: 6px 8px;
            border: 1.5px solid #000;
            border-radius: 4px;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13.5px;
            font-weight: 800;
        }

        .notes-box {
            margin: 6px 0;
            padding: 5px 8px;
            background: #f8fafc;
            border: 1px dashed #000;
            border-radius: 4px;
            font-size: 12px;
            line-height: 1.35;
            font-weight: 600;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 6px;
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
            margin: 8px auto 4px auto;
            text-align: center;
        }

        .barcode-container svg {
            max-width: 58mm;
            height: 38px;
            margin: 0 auto;
        }

        /* Print Media Styles */
        @media print {
            .no-print, .screen-toolbar {
                display: none !important;
            }

            body, html {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 80mm !important;
            }

            .thermal-paper {
                width: 80mm !important;
                max-width: 80mm !important;
                margin: 0 !important;
                padding: 3mm 2.5mm !important;
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Screen Preview Toolbar -->
    <div class="screen-toolbar no-print">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-weight: 700;">معاينة الإيصال الحراري (80mm)</span>
            <span style="background: #1e293b; padding: 2px 6px; border-radius: 4px; font-size: 10px; color: #94a3b8;">{{ $referenceCode }}</span>
        </div>
        <div style="display: flex; gap: 6px;">
            <button onclick="window.print()" class="btn btn-print">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                طباعة الآن
            </button>
            <button onclick="window.close()" class="btn btn-close">إغلاق</button>
        </div>
    </div>

    <!-- 80mm Thermal Paper -->
    <div class="thermal-paper">
        <div class="thermal-wrapper">

            <!-- Header Branding -->
            <div class="receipt-header">
                <div class="company-name">{{ \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا') }}</div>
                
                @php
                    $phone = \App\Models\Setting::get('company_phone') ?: \App\Models\Setting::get('phone');
                    $address = \App\Models\Setting::get('company_address') ?: \App\Models\Setting::get('address');
                @endphp

                <div class="company-meta">
                    @if($phone && $phone !== '---' && trim($phone) !== '')
                        <div>هاتف: <span dir="ltr">{{ $phone }}</span></div>
                    @endif
                    @if($address && trim($address) !== '')
                        <div>{{ $address }}</div>
                    @endif
                </div>

                <div style="margin-top: 4px;">
                    <span class="receipt-title-badge">{{ $typeLabel }}</span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <!-- Receipt Meta Details -->
            <div class="meta-list">
                <div class="meta-row">
                    <span class="meta-label">رقم الإيصال:</span>
                    <span class="meta-value">#{{ $referenceCode }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">التاريخ والوقت:</span>
                    <span class="meta-value">{{ $transaction->transaction_date->format('Y-m-d') }} {{ $transaction->created_at->format('h:i A') }}</span>
                </div>
                @if($transactionable)
                <div class="meta-row">
                    <span class="meta-label">{{ $partyType }}:</span>
                    <span class="meta-value-rtl">{{ $transactionable->name }}</span>
                </div>
                @if(!empty($transactionable->phone) && $transactionable->phone !== '---')
                <div class="meta-row">
                    <span class="meta-label">هاتف {{ $partyType }}:</span>
                    <span class="meta-value" dir="ltr">{{ $transactionable->phone }}</span>
                </div>
                @endif
                @endif
            </div>

            <div class="dashed-line"></div>

            <!-- If Return with items -->
            @if(in_array($transaction->type, ['return_sale', 'return_purchase']) && ($transaction->product || $transaction->quantity > 0))
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%; text-align: right;">الصنف المسترجع</th>
                        <th style="width: 25%;">الكمية</th>
                        <th style="width: 25%; text-align: left;">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600; text-align: right;">
                            {{ $transaction->product->name ?? 'بضاعة مسترجعة' }}
                            @if($transaction->unit_price > 0)
                            <div style="font-size: 10px; color: #333;" dir="ltr">@ {{ format_amount($transaction->unit_price) }} ج.م</div>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 700;" dir="ltr">
                            {{ format_quantity($transaction->quantity) }} {{ $transaction->product->unit ?? 'ك' }}
                        </td>
                        <td style="text-align: left; font-weight: 700;" dir="ltr">
                            {{ format_amount($amount) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif

            <!-- Main Amount Highlight Box -->
            <div class="highlight-amount-box">
                <div class="highlight-label">
                    @if(in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent']))
                        المبلغ المسدد
                    @elseif($transaction->type === 'cash_withdrawal')
                        المبلغ المصروف
                    @else
                        إجمالي قيمة المرتجع
                    @endif
                </div>
                <div class="highlight-value">
                    {{ format_amount($amount) }} <span style="font-size: 14px; font-weight: 600;">ج.م</span>
                </div>
            </div>

            <!-- Return cash refund info if applicable -->
            @if(in_array($transaction->type, ['return_sale', 'return_purchase']) && $transaction->paid_amount > 0)
            <div class="totals-box">
                <div class="total-row">
                    <span class="total-label">المبلغ المسترد نقداً:</span>
                    <span class="total-val" style="color: #000;">{{ format_amount($transaction->paid_amount) }} ج.م</span>
                </div>
            </div>
            @endif

            <!-- Balance After Operation -->
            @php
                $isCustomer = in_array($transaction->type, ['sale', 'payment_received', 'return_sale', 'cash_withdrawal']) || ($transactionable instanceof \App\Models\Customer);
                $absBal = abs($transaction->balance_after);
                $formattedBal = format_amount($absBal);
            @endphp
            <div class="balance-box">
                <span>الرصيد بعد العملية:</span>
                <span dir="ltr">
                    @if($transaction->balance_after < 0)
                        {{ $formattedBal }} ج.م {{ $isCustomer ? '(له عندنا)' : '(لنا عنده)' }}
                    @elseif($transaction->balance_after > 0)
                        {{ $formattedBal }} ج.م {{ $isCustomer ? '(مطلوب منه)' : '(له علينا)' }}
                    @else
                        0.00 ج.م (خالص)
                    @endif
                </span>
            </div>

            <!-- Notes -->
            @if($transaction->notes)
            <div class="notes-box">
                <strong>البيان / ملاحظات:</strong> {{ $transaction->notes }}
            </div>
            @endif

            <div class="dashed-line"></div>

            <!-- Footer & Barcode -->
            <div class="receipt-footer">
                <div class="thank-you-text">شكراً لتعاملكم معنا</div>
                <div class="footer-sub">سند رسمي صادر ومعتمد من النظام</div>

                <div class="barcode-container">
                    <svg id="receipt-barcode"></svg>
                    <div style="font-family: monospace; font-size: 10px; font-weight: 700; margin-top: 2px;">{{ $referenceCode }}</div>
                </div>
            </div>

        </div>
    </div>

    <!-- JsBarcode CDN for crisp thermal barcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            try {
                JsBarcode("#receipt-barcode", "{{ $referenceCode }}", {
                    format: "CODE128",
                    width: 1.6,
                    height: 34,
                    displayValue: false,
                    margin: 0
                });
            } catch(e) {
                console.warn("Barcode rendering fallback:", e);
            }

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('autoprint')) {
                setTimeout(() => { window.print(); }, 400);
            }
        });
    </script>
</body>
</html>
