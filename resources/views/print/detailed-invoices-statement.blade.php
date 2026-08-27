<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 6mm !important;
        }
        *, ::before, ::after { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Cairo', sans-serif !important; }
        html, body {
            background: #fff !important;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.4;
            height: auto !important;
            min-height: auto !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .page-container {
            width: 100%;
            margin: 0 auto;
            padding: 4mm 2mm;
            background: #fff;
            display: block;
        }
        .report-repeat-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin: 0;
        }
        .report-repeat-cell {
            padding: 0;
            border: none;
            vertical-align: top !important;
            text-align: right;
        }
        .report-repeat-header {
            display: table-header-group;
        }
        .report-repeat-footer {
            display: table-footer-group;
        }

        .party-info-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 12px;
            margin-bottom: 10px;
        }
        .party-name { font-size: 13px; font-weight: 800; color: #0f172a; }
        .party-badge { font-size: 10px; background: #e2e8f0; color: #334155; padding: 2px 8px; border-radius: 4px; font-weight: 700; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 12px; }
        .stat-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 10px; text-align: center; }
        .stat-label { font-size: 9px; font-weight: 700; color: #64748b; margin-bottom: 2px; }
        .stat-value { font-size: 12px; font-weight: 900; color: #0f172a; direction: ltr; }
        .stat-value.danger { color: #b91c1c; }
        .stat-value.success { color: #15803d; }
        .stat-value.primary { color: #0284c7; }

        /* Invoice Container */
        .invoice-card {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-bottom: 12px;
            overflow: hidden;
            page-break-inside: avoid;
            background: #fff;
        }
        .invoice-card-header {
            background: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            padding: 6px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }
        .inv-title { font-size: 11px; font-weight: 800; color: #0f172a; }
        .inv-date { font-size: 10px; color: #64748b; font-weight: 600; }
        .inv-pills { display: flex; gap: 6px; align-items: center; }
        .inv-pill {
            font-size: 9.5px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            direction: ltr;
        }
        .inv-pill.total { background: #e2e8f0; color: #1e293b; }
        .inv-pill.paid { background: #dcfce7; color: #166534; }
        .inv-pill.remaining { background: #fee2e2; color: #991b1b; }

        /* Print Table */
        .print-data-table { width: 100%; border-collapse: collapse; margin: 0; }
        .print-data-table th { background: #f8fafc; color: #334155; padding: 4px 6px; border: 1px solid #cbd5e1; font-size: 9.5px; font-weight: 800; text-align: center; }
        .print-data-table td { padding: 4px 6px; border: 1px solid #e2e8f0; font-size: 9.5px; text-align: center; color: #1e293b; }
        .print-data-table tr:nth-child(even) { background: #fafafa; }
        
        .inv-notes {
            padding: 4px 8px;
            background: #fffbeb;
            border-top: 1px dashed #fde68a;
            font-size: 9px;
            color: #92400e;
        }

        /* Grand Total Summary Box */
        .grand-summary-box {
            background: #f8fafc;
            border: 1.5px solid #0f172a;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 10px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .grand-summary-title { font-size: 11px; font-weight: 800; color: #0f172a; margin-bottom: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 3px; }
        .grand-summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; text-align: center; }

        @media print {
            .no-print { display: none !important; }
            html, body {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
                height: auto !important;
                min-height: auto !important;
            }
            #print-wrapper {
                zoom: 1 !important;
                transform: none !important;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
            }
            .page-container {
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                width: 100% !important;
                min-height: auto !important;
            }
            .invoice-card {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body style="overflow-x: hidden; margin: 0; padding: 0;">

    <div id="print-wrapper" style="width: 100%; transform-origin: top right;">
        <div class="page-container">
            <table class="report-repeat-table">
                <thead class="report-repeat-header">
                    <tr>
                        <td class="report-repeat-cell">
                            <x-print.header :title="$title" :subtitle="$subtitle" :referenceCode="'STMT-INV-' . str_pad($party->id, 4, '0', STR_PAD_LEFT) . '-' . date('Ymd')" />
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="report-repeat-cell">
                            <!-- Party Info -->
                            <div class="party-info-box">
                                <div>
                                    <span class="party-name">{{ $party->name }}</span>
                                    @if($party->phone)
                                    <span class="party-badge">هاتف: {{ $party->phone }}</span>
                                    @endif
                                </div>
                                <div>
                                    <span style="font-size: 10px; color: #475569; font-weight: bold;">
                                        نوع الحساب: {{ $partyType === 'supplier' ? 'مورد' : 'عميل' }}
                                    </span>
                                </div>
                            </div>

                            @php
                                $totalInvoicesCount = $invoices->count();
                                $grandTotal = $invoices->sum('total_amount');
                                $grandPaid = $invoices->sum('paid_amount');
                                $grandRemaining = $invoices->sum('remaining_amount');
                            @endphp

                            <!-- Top Stats Summary -->
                            <div class="stats-grid">
                                <div class="stat-card">
                                    <div class="stat-label">عدد الفواتير المختارة</div>
                                    <div class="stat-value primary">{{ $totalInvoicesCount }} فاتورة</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">إجمالي مبالغ الفواتير</div>
                                    <div class="stat-value">{{ format_amount($grandTotal) }} ج.م</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">إجمالي المسدد</div>
                                    <div class="stat-value success">{{ format_amount($grandPaid) }} ج.م</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">إجمالي المتبقي</div>
                                    <div class="stat-value {{ $grandRemaining > 0 ? 'danger' : 'success' }}">{{ format_amount($grandRemaining) }} ج.م</div>
                                </div>
                            </div>

                            <!-- Invoices Loop -->
                            @forelse($invoices as $index => $invoice)
                                <div class="invoice-card">
                                    <!-- Invoice Header Strip -->
                                    <div class="invoice-card-header">
                                        <div class="flex items-center gap-2">
                                            <span class="inv-title">فاتورة رقم #{{ $invoice->invoice_number ?? $invoice->id }}</span>
                                            <span class="inv-date">({{ ($invoice->invoice_date ?? $invoice->created_at)->format('Y-m-d') }})</span>
                                        </div>
                                        <div class="inv-pills">
                                            <span class="inv-pill total">الإجمالي: {{ format_amount($invoice->total_amount) }} ج.م</span>
                                            <span class="inv-pill paid">المدفوع: {{ format_amount($invoice->paid_amount) }} ج.م</span>
                                            @if($invoice->remaining_amount > 0)
                                                <span class="inv-pill remaining">المتبقي: {{ format_amount($invoice->remaining_amount) }} ج.م</span>
                                            @else
                                                <span class="inv-pill paid">خالصة بالكامل</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Items Table -->
                                    <table class="print-data-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 45%; text-align: right; padding-right: 10px;">الصنف / المنتج</th>
                                                <th style="width: 15%;">الكمية</th>
                                                <th style="width: 15%;">سعر الوحدة</th>
                                                <th style="width: 20%;">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($invoice->items as $itemIdx => $item)
                                            <tr>
                                                <td>{{ $itemIdx + 1 }}</td>
                                                <td style="text-align: right; padding-right: 10px; font-weight: 700;">
                                                    {{ $item->product->name ?? 'منتج غير محدد' }}
                                                </td>
                                                <td style="direction: ltr;">
                                                    {{ format_quantity($item->quantity) }} {{ $item->product->unit ?? 'ك' }}
                                                </td>
                                                <td style="direction: ltr;">
                                                    {{ format_amount($item->unit_price) }} ج.م
                                                </td>
                                                <td style="direction: ltr; font-weight: 800;">
                                                    {{ format_amount($item->total) }} ج.م
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" style="padding: 6px; color: #94a3b8;">لا توجد أصناف مسجلة لهذه الفاتورة</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    @if($invoice->notes)
                                    <div class="inv-notes">
                                        <strong>ملاحظات الفاتورة:</strong> {{ $invoice->notes }}
                                    </div>
                                    @endif
                                </div>
                            @empty
                                <div style="text-align: center; padding: 25px; border: 1px dashed #cbd5e1; border-radius: 6px; color: #64748b;">
                                    لم يتم العثور على فواتير محددة.
                                </div>
                            @endforelse

                            <!-- Grand Summary Box -->
                            <div class="grand-summary-box">
                                <div class="grand-summary-title">ملخص إجمالي الفواتير المختارة أعلاه</div>
                                <div class="grand-summary-grid">
                                    <div>
                                        <span style="font-size: 9.5px; color: #64748b; display: block;">إجمالي قيمة الفواتير:</span>
                                        <strong style="font-size: 13px; color: #0f172a; direction: ltr; display: inline-block;">{{ format_amount($grandTotal) }} ج.م</strong>
                                    </div>
                                    <div>
                                        <span style="font-size: 9.5px; color: #64748b; display: block;">إجمالي المسدد:</span>
                                        <strong style="font-size: 13px; color: #166534; direction: ltr; display: inline-block;">{{ format_amount($grandPaid) }} ج.م</strong>
                                    </div>
                                    <div>
                                        <span style="font-size: 9.5px; color: #64748b; display: block;">إجمالي المتبقي (آجل):</span>
                                        <strong style="font-size: 13px; color: {{ $grandRemaining > 0 ? '#991b1b' : '#166534' }}; direction: ltr; display: inline-block;">{{ format_amount($grandRemaining) }} ج.م</strong>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot class="report-repeat-footer">
                    <tr>
                        <td class="report-repeat-cell">
                            <x-print.footer />
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <script>
        function autoFitPrintSheet() {
            if (window.matchMedia('print').matches) return;
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('autoprint')) return;

            const w = window.innerWidth;
            const wrapper = document.getElementById('print-wrapper');
            if (!wrapper) return;
            
            const targetWidth = 794;
            if (w > 0 && w < targetWidth) {
                const scale = (w - 4) / targetWidth;
                wrapper.style.width = targetWidth + 'px';
                wrapper.style.minWidth = targetWidth + 'px';
                wrapper.style.zoom = scale;
                if (!('zoom' in wrapper.style) || navigator.userAgent.includes('Firefox')) {
                    wrapper.style.transform = `scale(${scale})`;
                    wrapper.style.transformOrigin = 'top right';
                }
            } else {
                wrapper.style.width = '100%';
                wrapper.style.minWidth = 'unset';
                wrapper.style.zoom = 1;
                wrapper.style.transform = 'none';
            }
        }

        window.addEventListener('resize', autoFitPrintSheet);
        window.addEventListener('load', autoFitPrintSheet);
        setTimeout(autoFitPrintSheet, 100);
        setTimeout(autoFitPrintSheet, 500);
    </script>
</body>
</html>
