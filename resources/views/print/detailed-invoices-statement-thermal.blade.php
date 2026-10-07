<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} (حراري)</title>
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

        /* Thermal Receipt Container */
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
        .receipt-header { text-align: center; padding-bottom: 6px; }

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
        .dashed-line { border-top: 1px dashed #000; margin: 6px 0; width: 100%; }
        .double-dashed-line { border-top: 2px dashed #000; margin: 7px 0; width: 100%; }

        /* Title Badge */
        .receipt-title-badge {
            text-align: center;
            font-size: 14px;
            font-weight: 800;
            padding: 4px 10px;
            margin: 5px auto;
            border: 2px solid #000;
            border-radius: 4px;
            display: inline-block;
            background: #000;
            color: #fff;
        }

        /* Meta */
        .meta-list { width: 100%; margin: 5px 0; font-size: 13px; }
        .meta-row { display: flex; justify-content: space-between; align-items: center; padding: 2.5px 0; }
        .meta-label { color: #000; font-weight: 700; font-size: 13px; }
        .meta-value { font-weight: 800; color: #000; direction: ltr; text-align: left; font-size: 13px; }
        .meta-value-rtl { font-weight: 800; color: #000; direction: rtl; text-align: left; font-size: 13px; }

        /* Stats Row */
        .stats-row { display: flex; justify-content: space-between; gap: 4px; margin: 6px 0; }
        .stat-box { flex: 1; text-align: center; padding: 5px 2px; border: 1.5px solid #000; border-radius: 4px; background: #fff; }
        .stat-box-label { font-size: 11px; font-weight: 700; color: #000; margin-bottom: 2px; }
        .stat-box-value { font-size: 13px; font-weight: 800; color: #000; direction: ltr; }

        /* Operation Card */
        .op-card {
            border: 1.5px solid #000;
            border-radius: 4px;
            margin: 7px 0;
            overflow: hidden;
            background: #fff;
        }

        .op-card-header {
            padding: 5px 6px;
            border-bottom: 1px dashed #000;
            font-size: 12.5px;
            font-weight: 800;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
        }

        .op-type-badge {
            font-size: 11.5px;
            font-weight: 800;
            padding: 2px 6px;
            border: 1px solid #000;
            border-radius: 3px;
        }

        .op-card-body { padding: 4px 5px; }

        /* Items mini table */
        .items-mini-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .items-mini-table th {
            border-bottom: 1.5px dashed #000;
            padding: 3px 2px;
            font-weight: 800;
            font-size: 11.5px;
            text-align: center;
        }

        .items-mini-table td {
            padding: 3px 2px;
            border-bottom: 1px dotted #888;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
        }

        .items-mini-table tr:last-child td { border-bottom: none; }

        .op-summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 6px;
            font-size: 12.5px;
            border-top: 1px dashed #000;
        }

        .op-summary-label { font-weight: 700; }
        .op-summary-val { font-weight: 800; direction: ltr; }

        .op-payment-info {
            padding: 5px 6px;
            font-size: 12.5px;
        }

        .op-notes {
            padding: 4px 6px;
            font-size: 11.5px;
            border-top: 1px dotted #888;
            font-weight: 600;
        }

        /* Grand Totals */
        .grand-box {
            border: 2px solid #000;
            border-radius: 4px;
            margin: 7px 0;
            padding: 6px;
            background: #fff;
        }

        .grand-title {
            font-size: 13.5px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 4px;
            border-bottom: 1.5px dashed #000;
            padding-bottom: 3px;
        }

        .grand-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 13px;
        }

        .grand-label { font-weight: 700; }
        .grand-val { font-weight: 800; direction: ltr; }

        /* Financial Position Card */
        .financial-position-card {
            border: 2px solid #000;
            border-radius: 4px;
            margin: 8px 0;
            padding: 6px 8px;
            background: #fff;
            text-align: center;
        }

        .financial-position-card .pos-card-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.5px dashed #000;
            padding-bottom: 4px;
            margin-bottom: 5px;
        }

        .financial-position-card .pos-tag {
            font-size: 11.5px;
            font-weight: 700;
            color: #000;
        }

        .financial-position-card .pos-badge {
            font-size: 12px;
            font-weight: 800;
            padding: 2px 8px;
            border: 1.5px solid #000;
            border-radius: 4px;
            background: #000;
            color: #fff;
        }

        .financial-position-card .pos-title {
            font-size: 14.5px;
            font-weight: 800;
            color: #000;
            margin-bottom: 3px;
            line-height: 1.3;
        }

        .financial-position-card .pos-detail {
            font-size: 12.5px;
            font-weight: 700;
            color: #000;
            direction: rtl;
            line-height: 1.35;
        }

        /* Footer */
        .receipt-footer { text-align: center; margin-top: 8px; padding-top: 5px; }
        .thank-you-text { font-size: 13.5px; font-weight: 800; margin-bottom: 3px; }
        .footer-sub { font-size: 12px; color: #000; font-weight: 600; }
        .cut-indicator { margin-top: 12px; border-top: 1px dotted #999; text-align: center; font-size: 10px; color: #777; padding-top: 2px; }

        .empty-msg { text-align: center; padding: 8px; font-size: 11px; color: #000; }

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

            .receipt-title-badge, .op-type-badge {
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
            <strong>كشف تفصيلي حراري (80mm)</strong>
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
                <span class="receipt-title-badge">كشف عمليات تفصيلي</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Party Info -->
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
        </div>

        <div class="dashed-line"></div>

        <!-- Stats -->
        @php
            $totalOpsCount = $transactions->count();
            $invoicesSum = $transactions->whereIn('type', ['sale', 'purchase'])->sum('total_amount');
            $paymentsSum = $transactions->whereIn('type', ['payment_received', 'payment_made'])->sum(fn($t) => $t->paid_amount ?: $t->total_amount);
            $returnsSum = $transactions->whereIn('type', ['return_sale', 'return_purchase'])->sum('total_amount');
        @endphp

        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-box-label">العمليات</div>
                <div class="stat-box-value">{{ $totalOpsCount }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-label">الفواتير</div>
                <div class="stat-box-value">{{ format_amount($invoicesSum) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-label">النقدية</div>
                <div class="stat-box-value">{{ format_amount($paymentsSum) }}</div>
            </div>
        </div>

        <div class="double-dashed-line"></div>

        <!-- Operations Loop -->
        @forelse($transactions as $index => $t)
            @php
                $isInvoice = in_array($t->type, ['sale', 'purchase']);
                $isReturn = in_array($t->type, ['return_sale', 'return_purchase']);
                $isPayment = in_array($t->type, ['payment_received', 'payment_made']);
                $isWithdrawal = $t->type === 'cash_withdrawal';

                $typeLabel = match($t->type) {
                    'sale' => 'فاتورة بيع',
                    'purchase' => 'فاتورة شراء',
                    'return_sale' => 'مرتجع بيع',
                    'return_purchase' => 'مرتجع شراء',
                    'payment_received' => 'تحصيل',
                    'payment_made' => 'سداد',
                    'cash_withdrawal' => 'سحب نقدي',
                    'opening_balance' => 'رصيد افتتاحي',
                    default => transaction_type_label($t->type)
                };
            @endphp

            <div class="op-card">
                <!-- Card Header -->
                <div class="op-card-header">
                    <div>
                        <span class="op-type-badge">{{ $typeLabel }}</span>
                        @if($isInvoice)
                            <span style="font-size: 10px;"> #{{ $t->source?->invoice_number ?? $t->invoice_id ?? $t->id }}</span>
                        @endif
                    </div>
                    <span style="font-size: 10px; font-weight: 500;">{{ $t->transaction_date->format('m/d') }}</span>
                </div>

                <!-- Card Body -->
                <div class="op-card-body">
                    @if($isInvoice && $t->source && $t->source->items && $t->source->items->isNotEmpty())
                        <!-- Invoice Items -->
                        <table class="items-mini-table">
                            <thead>
                                <tr>
                                    <th style="width: 44%; text-align: right;">الصنف</th>
                                    <th style="width: 16%;">الكمية</th>
                                    <th style="width: 20%;">السعر</th>
                                    <th style="width: 20%;">المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($t->source->items as $item)
                                <tr>
                                    <td style="text-align: right; font-weight: 600;">{{ $item->product->name ?? 'صنف' }}</td>
                                    <td dir="ltr">{{ format_quantity($item->quantity) }}</td>
                                    <td dir="ltr">{{ format_amount($item->unit_price) }}</td>
                                    <td dir="ltr" style="font-weight: 700;">{{ format_amount($item->total) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif($isReturn && $t->product)
                        <table class="items-mini-table">
                            <thead>
                                <tr>
                                    <th style="width: 44%; text-align: right;">المرتجع</th>
                                    <th style="width: 16%;">الكمية</th>
                                    <th style="width: 20%;">السعر</th>
                                    <th style="width: 20%;">المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: right; font-weight: 600;">{{ $t->product->name }}</td>
                                    <td dir="ltr">{{ format_quantity($t->quantity) }}</td>
                                    <td dir="ltr">{{ format_amount($t->unit_price) }}</td>
                                    <td dir="ltr" style="font-weight: 700;">{{ format_amount($t->total_amount) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @elseif($isPayment || $isWithdrawal)
                        <div class="op-payment-info">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="font-weight: 600;">المبلغ:</span>
                                <span style="font-weight: 700; direction: ltr;">{{ format_amount($t->paid_amount ?: $t->total_amount) }} ج.م</span>
                            </div>
                            @if($t->notes)
                            <div style="font-size: 10px; color: #000; margin-top: 2px;">{{ $t->notes }}</div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Operation Summary -->
                @if($isInvoice)
                <div class="op-summary-row">
                    <span class="op-summary-label">الإجمالي: {{ format_amount($t->total_amount) }}</span>
                    <span class="op-summary-label">المدفوع: {{ format_amount($t->paid_amount) }}</span>
                    @php $rem = max(0, $t->total_amount - $t->paid_amount); @endphp
                    @if($rem > 0)
                        <span class="op-summary-val">متبقي: {{ format_amount($rem) }}</span>
                    @else
                        <span class="op-summary-val">خالصة</span>
                    @endif
                </div>
                @endif

                <!-- Notes -->
                @if($t->notes && !$isPayment && !$isWithdrawal)
                <div class="op-notes">
                    <strong>ملاحظات:</strong> {{ $t->notes }}
                </div>
                @endif
            </div>
        @empty
            <div class="empty-msg">لم يتم تحديد أي عمليات</div>
        @endforelse

        <div class="double-dashed-line"></div>

        @php
            $balanceVal = (float) $party->balance;
            $absBalance = abs($balanceVal);
            $formattedAbsBalance = format_amount($absBalance);

            if ($partyType === 'supplier') {
                if ($balanceVal > 0) {
                    $positionBadge = 'علينا فلوس للمورد';
                    $positionTitle = 'علينا فلوس للمورد (مستحق له)';
                    $positionDesc = 'مبلغ مستحق السداد للمورد: ' . $formattedAbsBalance . ' ج.م';
                    $balanceStatusText = 'علينا فلوس للمورد';
                    $balanceLabel = 'له علينا';
                } elseif ($balanceVal < 0) {
                    $positionBadge = 'لنا فلوس عند المورد';
                    $positionTitle = 'لنا فلوس عند المورد (مطلوب منه)';
                    $positionDesc = 'مبلغ رصيد لنا عند المورد: ' . $formattedAbsBalance . ' ج.م';
                    $balanceStatusText = 'لنا فلوس عند المورد';
                    $balanceLabel = 'لنا عنده';
                } else {
                    $positionBadge = 'خالص بالكامل';
                    $positionTitle = 'الحساب خالص بالكامل (متزن)';
                    $positionDesc = 'لا توجد أي مبالغ متبقية (0.00 ج.م)';
                    $balanceStatusText = 'الحساب خالص (لا له ولا عليه)';
                    $balanceLabel = 'خالص';
                }
            } else {
                if ($balanceVal > 0) {
                    $positionBadge = 'لنا فلوس عند العميل';
                    $positionTitle = 'لنا فلوس عند العميل (مطلوب منه)';
                    $positionDesc = 'مبلغ مطلوب تحصيله من العميل: ' . $formattedAbsBalance . ' ج.م';
                    $balanceStatusText = 'لنا فلوس عند العميل';
                    $balanceLabel = 'مطلوب منه';
                } elseif ($balanceVal < 0) {
                    $positionBadge = 'علينا فلوس للعميل';
                    $positionTitle = 'علينا فلوس للعميل (مستحق له)';
                    $positionDesc = 'رصيد دائن للعميل عندنا: ' . $formattedAbsBalance . ' ج.م';
                    $balanceStatusText = 'علينا فلوس للعميل';
                    $balanceLabel = 'له عندنا';
                } else {
                    $positionBadge = 'خالص بالكامل';
                    $positionTitle = 'الحساب خالص بالكامل (متزن)';
                    $positionDesc = 'لا توجد أي مبالغ متبقية (0.00 ج.م)';
                    $balanceStatusText = 'الحساب خالص (لا له ولا عليه)';
                    $balanceLabel = 'خالص';
                }
            }
        @endphp

        <!-- Grand Summary -->
        <div class="grand-box">
            <div class="grand-title">ملخص العمليات والحساب</div>
            <div class="grand-row">
                <span class="grand-label">إجمالي الفواتير:</span>
                <span class="grand-val">{{ format_amount($invoicesSum) }} ج.م</span>
            </div>
            <div class="grand-row">
                <span class="grand-label">إجمالي النقدية:</span>
                <span class="grand-val">{{ format_amount($paymentsSum) }} ج.م</span>
            </div>
            @if($returnsSum > 0)
            <div class="grand-row">
                <span class="grand-label">إجمالي المرتجعات:</span>
                <span class="grand-val">{{ format_amount($returnsSum) }} ج.م</span>
            </div>
            @endif
            <div class="grand-row">
                <span class="grand-label">عدد العمليات:</span>
                <span class="grand-val">{{ $totalOpsCount }} عملية</span>
            </div>
            <div class="grand-row" style="border-top: 1.5px dashed #000; padding-top: 3.5px; margin-top: 3.5px;">
                <span class="grand-label">موقف المعاملة:</span>
                <span class="grand-val" style="font-weight: 800;">{{ $balanceStatusText }}</span>
            </div>
            <div class="grand-row" style="border-top: 1.5px solid #000; padding-top: 4px; margin-top: 3px; font-size: 13.5px;">
                <span class="grand-label" style="font-weight: 800;">رصيد الحساب الحالي:</span>
                <span class="grand-val" style="font-weight: 800;">{{ $formattedAbsBalance }} ج.م ({{ $balanceLabel }})</span>
            </div>
        </div>

        <!-- Distinct Clear Financial Position Box -->
        <div class="financial-position-card">
            <div class="pos-card-head">
                <span class="pos-tag">موقف الحساب المالي</span>
                <span class="pos-badge">{{ $positionBadge }}</span>
            </div>
            <div class="pos-card-body">
                <div class="pos-title">{{ $positionTitle }}</div>
                <div class="pos-detail">{{ $positionDesc }}</div>
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
