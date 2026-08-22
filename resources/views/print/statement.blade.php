<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $party->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 6mm !important;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; min-height: 100%; }
        body {
            font-family: 'Cairo', sans-serif;
            background: #fff; color: #0f172a; font-size: 11px; line-height: 1.4;
            -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;
        }
        .page-container { width: 100%; margin: 0 auto; padding: 5mm; background: #fff; display: flex; flex-direction: column; min-height: 100%; }
        .report-repeat-table { width: 100%; border-collapse: collapse; border: none; }
        .report-repeat-cell { padding: 0; border: none; }
        .report-repeat-header { display: table-header-group; }
        .report-repeat-footer { display: table-footer-group; }

        .party-info-box {
            display: flex; justify-content: space-between; align-items: center;
            background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px;
            padding: 8px 12px; margin-bottom: 12px;
        }
        .party-name { font-size: 13px; font-weight: 800; color: #0f172a; }
        .party-badge { font-size: 10px; background: #e2e8f0; color: #334155; padding: 2px 8px; border-radius: 4px; font-weight: 700; }

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 12px; }
        .stat-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 10px; text-align: center; }
        .stat-label { font-size: 9px; font-weight: 700; color: #64748b; margin-bottom: 2px; }
        .stat-value { font-size: 12px; font-weight: 900; color: #0f172a; direction: ltr; }
        .stat-value.danger { color: #b91c1c; }
        .stat-value.success { color: #15803d; }

        .print-data-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; page-break-inside: auto; }
        .print-data-table th { background: #f1f5f9; color: #0f172a; padding: 5px 4px; border: 1px solid #475569; font-size: 10px; font-weight: 800; text-align: center; }
        .print-data-table td { padding: 5px 4px; border: 1px solid #94a3b8; font-size: 10px; text-align: center; color: #1e293b; direction: ltr;}
        .print-data-table tr { page-break-inside: avoid; }
        .print-data-table tr:nth-child(even) { background: #f8fafc; }
        
        @media print {
            .no-print { display: none !important; }
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; height: 100% !important; }
            .page-container { padding: 0 !important; min-height: 275mm !important; display: flex !important; flex-direction: column !important; }
            .report-repeat-table { flex: 1 0 auto !important; }
            .report-footer-wrapper { margin-top: auto !important; }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <table class="report-repeat-table">
            <thead class="report-repeat-header">
                <tr>
                    <td class="report-repeat-cell">
                        <x-print.header :title="$title" :subtitle="$subtitle" :referenceCode="'STMT-' . str_pad($party->id, 4, '0', STR_PAD_LEFT) . '-' . date('Ymd')" />
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
                        </div>

                        <!-- Stats -->
                        @php
                            $isOwed = $party->balance > 0;
                            $isCredit = $party->balance < 0;
                            $balanceColor = $isOwed ? 'danger' : ($isCredit ? 'success' : '');
                            $balanceLabel = $isOwed ? 'المتبقي له' : ($isCredit ? 'المتبقي عليه' : 'الرصيد');
                            
                            $totalPurchases = $transactions->where('type', 'purchase')->sum('total_amount');
                            $totalSales = $transactions->where('type', 'sale')->sum('total_amount');
                            $totalPayments = $transactions->where('type', 'payment_made')->sum('paid_amount') + $transactions->where('type', 'payment_received')->sum('paid_amount');
                        @endphp
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-label">إجمالي العمليات</div>
                                <div class="stat-value">{{ number_format($partyType === 'supplier' ? $totalPurchases : $totalSales, 0) }} ج.م</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">إجمالي المسدد</div>
                                <div class="stat-value success">{{ number_format($totalPayments, 0) }} ج.م</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">{{ $balanceLabel }} الحالي</div>
                                <div class="stat-value {{ $balanceColor }}">{{ number_format(abs($party->balance), 0) }} ج.م</div>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <table class="print-data-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">التاريخ</th>
                                    <th style="width: 15%;">نوع العملية</th>
                                    <th style="width: 20%;">المبلغ / الإجمالي</th>
                                    <th style="width: 20%;">المدفوع</th>
                                    <th style="width: 20%;">الرصيد بعد العملية</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $t)
                                <tr>
                                    <td>{{ $t->transaction_date->format('Y-m-d') }}</td>
                                    <td style="direction: rtl;">
                                        @if($t->type === 'purchase') شراء منتجات
                                        @elseif($t->type === 'sale') بيع منتجات
                                        @elseif($t->type === 'payment_made') سداد نقدية
                                        @elseif($t->type === 'payment_received') استلام نقدية
                                        @elseif($t->type === 'return_purchase') مرتجع شراء
                                        @elseif($t->type === 'return_sale') مرتجع مبيعات
                                        @else {{ $t->type }} @endif
                                    </td>
                                    <td>{{ $t->total_amount > 0 ? ((float)$t->total_amount == (int)$t->total_amount ? number_format($t->total_amount, 0) : number_format($t->total_amount, 2)) : '-' }}</td>
                                    <td>{{ $t->paid_amount > 0 ? ((float)$t->paid_amount == (int)$t->paid_amount ? number_format($t->paid_amount, 0) : number_format($t->paid_amount, 2)) : '-' }}</td>
                                    <td style="font-weight: bold; {{ $t->balance_after > 0 ? 'color:#b91c1c;' : ($t->balance_after < 0 ? 'color:#15803d;' : '') }}">
                                        @php
                                            $absTBal = abs($t->balance_after);
                                            $formattedTBal = (float)$absTBal == (int)$absTBal ? number_format($absTBal, 0) : number_format($absTBal, 2);
                                        @endphp
                                        @if($t->balance_after == 0)
                                            0 (خالص)
                                        @else
                                            {{ $formattedTBal }} {{ $partyType === 'supplier' ? ($t->balance_after > 0 ? '(له)' : '(عليه)') : ($t->balance_after > 0 ? '(عليه)' : '(له)') }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="padding: 15px; direction: rtl;">لا توجد عمليات مسجلة في هذه الفترة.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('autoprint')) {
                setTimeout(() => { window.print(); }, 500);
            }
        });
    </script>
</body>
</html>
