<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $party->name }} (حراري)</title>
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
            font-size: 12px;
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

        .btn-print { background: #2563eb; color: #fff; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-close { background: #334155; color: #e2e8f0; }
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

        /* Header */
        .receipt-header {
            text-align: center;
            padding-bottom: 6px;
        }

        .company-name {
            font-size: 20px;
            font-weight: 800;
            color: #000;
            line-height: 1.25;
            margin-bottom: 3px;
            letter-spacing: -0.3px;
        }

        .company-meta {
            font-size: 12.5px;
            color: #000;
            line-height: 1.4;
            font-weight: 600;
        }

        /* Dividers */
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 6px 0;
            width: 100%;
        }

        .double-dashed-line {
            border-top: 2px dashed #000;
            margin: 7px 0;
            width: 100%;
        }

        /* Title Badge */
        .receipt-title-badge {
            text-align: center;
            font-size: 14.5px;
            font-weight: 800;
            padding: 4px 12px;
            margin: 5px auto;
            border: 2px solid #000;
            border-radius: 4px;
            display: inline-block;
            background: #000;
            color: #fff;
        }

        /* Meta */
        .meta-list {
            width: 100%;
            margin: 5px 0;
            font-size: 13px;
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
            font-size: 13px;
        }

        .meta-value {
            font-weight: 800;
            color: #000;
            direction: ltr;
            text-align: left;
            font-size: 13px;
        }

        .meta-value-rtl {
            font-weight: 800;
            color: #000;
            direction: rtl;
            text-align: left;
            font-size: 13px;
        }

        /* Stats Row */
        .stats-row {
            display: flex;
            justify-content: space-between;
            gap: 4px;
            margin: 6px 0;
        }

        .stat-box {
            flex: 1;
            text-align: center;
            padding: 5px 2px;
            border: 1.5px solid #000;
            border-radius: 4px;
            background: #fff;
        }

        .stat-box-label {
            font-size: 11px;
            font-weight: 700;
            color: #000;
            margin-bottom: 2px;
        }

        .stat-box-value {
            font-size: 13.5px;
            font-weight: 800;
            color: #000;
            direction: ltr;
        }

        /* Transactions Table */
        .txn-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 12px;
        }

        .txn-table th {
            border-top: 1.5px dashed #000;
            border-bottom: 1.5px dashed #000;
            padding: 4px 1px;
            font-weight: 800;
            color: #000;
            text-align: center;
            font-size: 11.5px;
        }

        .txn-table td {
            padding: 4px 1px;
            border-bottom: 1px dotted #888;
            color: #000;
            vertical-align: middle;
            text-align: center;
            font-size: 12px;
        }

        .txn-table tr:last-child td {
            border-bottom: none;
        }

        .col-date { width: 20%; font-weight: 600; direction: ltr; }
        .col-type { width: 25%; font-weight: 700; text-align: right !important; }
        .col-amount { width: 25%; font-weight: 700; direction: ltr; }
        .col-balance { width: 30%; font-weight: 800; direction: ltr; }

        /* Totals */
        .totals-box {
            width: 100%;
            margin: 5px 0;
            font-size: 13.5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3.5px 0;
        }

        .total-row.grand-total {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 6px 0;
            margin: 4px 0;
            font-size: 15px;
            font-weight: 800;
        }

        .total-label { font-weight: 700; font-size: 13.5px; }
        .total-val { font-weight: 800; font-size: 13.5px; direction: ltr; }

        /* Footer */
        .receipt-footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 5px;
        }

        .thank-you-text {
            font-size: 13.5px;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .footer-sub {
            font-size: 12px;
            color: #000;
            font-weight: 600;
        }

        .cut-indicator {
            margin-top: 12px;
            border-top: 1px dotted #999;
            text-align: center;
            font-size: 10px;
            color: #777;
            padding-top: 2px;
        }

        .empty-msg {
            text-align: center;
            padding: 8px;
            font-size: 11px;
            color: #000;
            font-weight: 500;
        }

        /* Print */
        @media print {
            .no-print { display: none !important; }

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
        }
    </style>
</head>
<body>

    <!-- Screen Toolbar -->
    <div class="screen-toolbar no-print" id="screenToolbar">
        <div style="display: flex; align-items: center; gap: 8px;">
            <svg style="width: 16px; height: 16px; color: #f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <strong>كشف حساب حراري (80mm)</strong>
        </div>
        <div style="display: flex; align-items: center; gap: 6px;">
            <button onclick="window.print()" class="btn btn-print">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>طباعة الآن</span>
            </button>
            <button onclick="window.close()" class="btn btn-close">إغلاق</button>
        </div>
    </div>

    <!-- Thermal 80mm Body -->
    <div class="thermal-wrapper">

        <!-- Header -->
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
                <span class="receipt-title-badge">كشف حساب {{ $partyType === 'supplier' ? 'مورد' : 'عميل' }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Party & Statement Info -->
        <div class="meta-list">
            <div class="meta-row">
                <span class="meta-label">{{ $partyType === 'supplier' ? 'المورد:' : 'العميل:' }}</span>
                <span class="meta-value-rtl">{{ $party->name }}</span>
            </div>
            @if($party->phone && $party->phone !== '---')
            <div class="meta-row">
                <span class="meta-label">هاتف {{ $partyType === 'supplier' ? 'المورد' : 'العميل' }}:</span>
                <span class="meta-value" dir="ltr">{{ $party->phone }}</span>
            </div>
            @endif
            <div class="meta-row">
                <span class="meta-label">تاريخ الاستخراج:</span>
                <span class="meta-value">{{ now()->format('Y-m-d h:i A') }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">نوع الكشف:</span>
                <span class="meta-value-rtl" style="font-size: 12px;">{{ $subtitle }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Stats Summary -->
        @php
            if ($partyType === 'supplier') {
                $isOwed = $party->balance > 0;
                $isCredit = $party->balance < 0;
                $balanceLabel = $isOwed ? 'له علينا' : ($isCredit ? 'لنا عنده' : 'خالص');
            } else {
                $isOwed = $party->balance > 0;
                $isCredit = $party->balance < 0;
                $balanceLabel = $isOwed ? 'مطلوب منه' : ($isCredit ? 'له عندنا' : 'خالص');
            }

            $totalPurchases = $transactions->where('type', 'purchase')->sum('total_amount');
            $totalSales = $transactions->where('type', 'sale')->sum('total_amount');
            $totalPayments = $transactions->where('type', 'payment_made')->sum('paid_amount') + $transactions->where('type', 'payment_received')->sum('paid_amount');
        @endphp

        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-box-label">العمليات</div>
                <div class="stat-box-value">{{ format_amount($partyType === 'supplier' ? $totalPurchases : $totalSales) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-label">{{ $partyType === 'supplier' ? 'المسدد' : 'المحصل' }}</div>
                <div class="stat-box-value">{{ format_amount($totalPayments) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-label">الرصيد</div>
                <div class="stat-box-value">{{ format_amount(abs($party->balance)) }}</div>
            </div>
        </div>

        <div class="double-dashed-line"></div>

        <!-- Transactions Table -->
        <table class="txn-table">
            <thead>
                <tr>
                    <th class="col-date">التاريخ</th>
                    <th class="col-type">النوع</th>
                    <th class="col-amount">المبلغ</th>
                    <th class="col-balance">الرصيد</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td class="col-date">{{ $t->transaction_date->format('m/d') }}</td>
                    <td class="col-type">
                        @if($t->type === 'purchase') شراء
                        @elseif($t->type === 'sale') بيع
                        @elseif($t->type === 'payment_made') سداد
                        @elseif($t->type === 'payment_received') تحصيل
                        @elseif($t->type === 'return_purchase') مرتجع شراء
                        @elseif($t->type === 'return_sale') مرتجع بيع
                        @elseif($t->type === 'cash_withdrawal') سحب
                        @elseif($t->type === 'opening_balance') رصيد افتتاحي
                        @else {{ transaction_type_label($t->type) }}
                        @endif
                    </td>
                    <td class="col-amount">
                        @if($t->total_amount > 0)
                            {{ format_amount($t->total_amount) }}
                        @elseif($t->paid_amount > 0)
                            {{ format_amount($t->paid_amount) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="col-balance" style="{{ $t->balance_after > 0 ? 'font-weight:700;' : '' }}">
                        @if($t->balance_after == 0)
                            0 خالص
                        @else
                            {{ format_amount(abs($t->balance_after)) }}
                            @if($partyType === 'supplier')
                                {{ $t->balance_after > 0 ? '(له)' : '(لنا)' }}
                            @else
                                {{ $t->balance_after > 0 ? '(عليه)' : '(له)' }}
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-msg">لا توجد عمليات</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="double-dashed-line"></div>

        <!-- Balance Summary -->
        <div class="totals-box">
            <div class="total-row">
                <span class="total-label">عدد العمليات:</span>
                <span class="total-val">{{ $transactions->count() }} عملية</span>
            </div>
            <div class="total-row grand-total">
                <span class="total-label">الرصيد الحالي:</span>
                <span class="total-val">
                    {{ format_amount(abs($party->balance)) }} ج.م
                    ({{ $balanceLabel }})
                </span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Footer -->
        <div class="receipt-footer">
            <div class="thank-you-text">شكراً لتعاملكم معنا</div>
            <div class="footer-sub">نسعد بخدمتكم دائماً</div>

            <div class="cut-indicator">
                - - - - - - - - - - - - - - - - - - - - - -
            </div>
        </div>

    </div>

    <script>
        if (window.self !== window.top) {
            const tb = document.getElementById('screenToolbar');
            if (tb) tb.style.display = 'none';
        }

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
