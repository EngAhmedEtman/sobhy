<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
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
            background: #f8fafc; color: #0f172a; font-size: 11px; line-height: 1.4;
            -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;
        }
        .page-container { width: 100%; max-width: 210mm; margin: 0 auto; padding: 6mm; background: #fff; display: flex; flex-direction: column; min-height: 100%; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .report-repeat-table { width: 100%; border-collapse: collapse; border: none; }
        .report-repeat-cell { padding: 0; border: none; }
        .report-repeat-header { display: table-header-group; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 12px; }
        .stat-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 8px; text-align: center; }
        .stat-label { font-size: 9px; font-weight: 700; color: #64748b; margin-bottom: 2px; }
        .stat-value { font-size: 11px; font-weight: 900; color: #0f172a; direction: ltr; font-family: monospace; }
        .stat-value.danger { color: #b91c1c; }
        .stat-value.success { color: #15803d; }

        .print-data-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; page-break-inside: auto; }
        .print-data-table th { background: #f1f5f9; color: #0f172a; padding: 6px 4px; border: 1px solid #475569; font-size: 10px; font-weight: 800; text-align: center; }
        .print-data-table td { padding: 5px 4px; border: 1px solid #94a3b8; font-size: 10px; text-align: center; color: #1e293b; }
        .print-data-table tr { page-break-inside: avoid; }
        .print-data-table tr:nth-child(even) { background: #f8fafc; }
        .print-data-table tfoot td { background: #f1f5f9; font-weight: 800; border: 1.5px solid #334155; }
        
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 700; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-neutral { background: #f1f5f9; color: #475569; }

        .no-print-bar {
            position: sticky; top: 0; z-index: 50;
            background: #0f172a; color: #fff; padding: 10px 20px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); margin-bottom: 12px;
        }
        .btn-print {
            background: #16a34a; color: #fff; border: none; padding: 6px 14px;
            border-radius: 6px; font-family: 'Cairo', sans-serif; font-size: 12px;
            font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-print:hover { background: #15803d; }
        .btn-close {
            background: #334155; color: #fff; border: none; padding: 6px 12px;
            border-radius: 6px; font-family: 'Cairo', sans-serif; font-size: 12px;
            font-weight: 600; cursor: pointer;
        }
        .btn-close:hover { background: #475569; }

        @media print {
            .no-print, .no-print-bar { display: none !important; }
            body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
            .page-container { padding: 0 !important; max-width: 100% !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div style="font-weight: 700; font-size: 13px;">{{ $title }}</div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-print">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                طباعة التقرير (Ctrl + P)
            </button>
            <button onclick="window.close()" class="btn-close">إغلاق</button>
        </div>
    </div>

    <div class="page-container">
        <table class="report-repeat-table">
            <thead class="report-repeat-header">
                <tr>
                    <td class="report-repeat-cell">
                        <x-print.header :title="$title" :subtitle="$subtitle" :referenceCode="'SUPP-REP-' . date('Ymd-Hi')" />
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="report-repeat-cell">
                        <!-- Stats Grid -->
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-label">إجمالي عدد الموردين</div>
                                <div class="stat-value">{{ $totalSuppliers }} مورد</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">إجمالي مستحق للموردين (له علينا)</div>
                                <div class="stat-value danger">{{ format_amount($totalDebt) }} ج.م</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">إجمالي رصيد لنا عندهم (دافعين زيادة)</div>
                                <div class="stat-value success">{{ format_amount($totalCredit) }} ج.م</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">صافي إجمالي الأرصدة</div>
                                <div class="stat-value {{ $netBalance > 0 ? 'danger' : ($netBalance < 0 ? 'success' : '') }}">
                                    {{ format_amount($netBalance) }} ج.م
                                </div>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <table class="print-data-table">
                            <thead>
                                <tr>
                                    <th style="width: 35px;">#</th>
                                    <th>اسم المورد / الشركة</th>
                                    <th style="width: 90px;">رقم الهاتف</th>
                                    <th style="width: 100px;">إجمالي المشتريات</th>
                                    <th style="width: 100px;">الرصيد المستحق</th>
                                    <th style="width: 90px;">حالة الحساب</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $index => $supplier)
                                    @php
                                        $purchasesTotal = \App\Models\Purchase::where('supplier_id', $supplier->id)->sum('total_amount');
                                        $isDebt = $supplier->balance > 0;
                                        $isCredit = $supplier->balance < 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td style="font-weight: 700; text-align: right; padding-right: 6px;">{{ $supplier->name }}</td>
                                        <td style="direction: ltr; font-family: monospace;">{{ $supplier->phone ?? '---' }}</td>
                                        <td style="direction: ltr; font-family: monospace;">{{ format_amount($purchasesTotal) }} ج.م</td>
                                        <td style="direction: ltr; font-family: monospace; font-weight: 800;" class="{{ $isDebt ? 'danger' : ($isCredit ? 'success' : '') }}">
                                            {{ format_amount($supplier->balance) }} ج.م
                                        </td>
                                        <td>
                                            @if($isDebt)
                                                <span class="badge badge-danger">مستحق للمورد</span>
                                            @elseif($isCredit)
                                                <span class="badge badge-success">لنا عنده</span>
                                            @else
                                                <span class="badge badge-neutral">خالص</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="padding: 16px; color: #64748b;">لا توجد سجلات مطابقة للبحث أو الفلاتر المحددة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" style="text-align: right; padding-right: 6px;">الإجمالي العام ({{ $totalSuppliers }} مورد)</td>
                                    <td style="direction: ltr; font-family: monospace;">---</td>
                                    <td style="direction: ltr; font-family: monospace; font-size: 11px;" class="{{ $netBalance > 0 ? 'danger' : ($netBalance < 0 ? 'success' : '') }}">
                                        {{ format_amount($netBalance) }} ج.م
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

                        <x-print.footer />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
