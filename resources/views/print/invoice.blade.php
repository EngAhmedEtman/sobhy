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
            background: #fff; color: #0f172a; font-size: 12px; line-height: 1.4;
            -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;
        }
        .page-container { width: 100%; margin: 0 auto; padding: 5mm; background: #fff; display: flex; flex-direction: column; min-height: 100%; }
        
        .invoice-details-box {
            display: flex; justify-content: space-between;
            padding: 10px 15px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 15px;
        }
        .invoice-info-col { flex: 1; }
        .invoice-info-col h3 { font-size: 14px; font-weight: 800; margin-bottom: 5px; color: #1e293b; }
        .invoice-info-col p { font-size: 11px; color: #475569; margin-bottom: 3px; }
        
        .print-data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .print-data-table th {
            background: #f1f5f9; color: #0f172a; padding: 8px 6px; border: 1px solid #475569;
            font-size: 11px; font-weight: 800; text-align: center;
        }
        .print-data-table td {
            padding: 8px 6px; border: 1px solid #94a3b8; font-size: 11px; text-align: center; color: #1e293b;
        }
        .print-data-table tr:nth-child(even) { background: #f8fafc; }
        
        .totals-section { width: 40%; margin-right: auto; margin-left: 0; border: 1px solid #94a3b8; border-radius: 4px; overflow: hidden; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 12px; border-bottom: 1px solid #e2e8f0; }
        .totals-row:last-child { border-bottom: none; background: #f1f5f9; font-weight: 800; }
        .totals-label { font-weight: 700; color: #475569; }
        .totals-value { font-weight: 800; direction: ltr; }

        @media print {
            .no-print { display: none !important; }
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <!-- Header -->
        <x-print.header :title="$title" :subtitle="$type === 'purchase' ? 'فاتورة مورد' : 'فاتورة عميل'" :referenceCode="'INV-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT)" />

        <!-- Invoice Meta -->
        <div class="invoice-details-box">
            <div class="invoice-info-col">
                <h3>معلومات {{ $type === 'purchase' ? 'المورد' : 'العميل' }}</h3>
                @php $party = $type === 'purchase' ? $invoice->supplier : $invoice->customer; @endphp
                <p><strong>الاسم:</strong> {{ $party->name }}</p>
                <p><strong>الهاتف:</strong> {{ $party->phone ?? '---' }}</p>
            </div>
            <div class="invoice-info-col" style="text-align: left;">
                <h3>تفاصيل الفاتورة</h3>
                <p><strong>رقم الفاتورة:</strong> #{{ $invoice->invoice_number ?? $invoice->id }}</p>
                <p><strong>التاريخ:</strong> {{ $invoice->created_at->format('Y-m-d') }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="print-data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">المنتج</th>
                    <th style="width: 15%;">الكمية</th>
                    <th style="width: 15%;">سعر الوحدة</th>
                    <th style="width: 20%;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td style="direction: ltr;">{{ $item->quantity }} {{ $item->product->unit ?? 'ك' }}</td>
                    <td style="direction: ltr;">{{ number_format($item->unit_price, 2) }} ج.م</td>
                    <td style="direction: ltr; font-weight: bold;">{{ number_format($item->total, 2) }} ج.م</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-row">
                <span class="totals-label">إجمالي الفاتورة:</span>
                <span class="totals-value">{{ (float)$invoice->total_amount == (int)$invoice->total_amount ? number_format($invoice->total_amount, 0) : number_format($invoice->total_amount, 2) }} ج.م</span>
            </div>
            
            @php
                $paidCash = $invoice->transaction ? $invoice->transaction->paid_amount : 0;
                $remaining = $invoice->total_amount - $paidCash;
            @endphp
            <div class="totals-row" style="color: #15803d;">
                <span class="totals-label">المدفوع نقداً:</span>
                <span class="totals-value">{{ (float)$paidCash == (int)$paidCash ? number_format($paidCash, 0) : number_format($paidCash, 2) }} ج.م</span>
            </div>
            @if($remaining > 0)
            <div class="totals-row" style="color: #b91c1c;">
                <span class="totals-label">المتبقي (آجل):</span>
                <span class="totals-value">{{ (float)$remaining == (int)$remaining ? number_format($remaining, 0) : number_format($remaining, 2) }} ج.م</span>
            </div>
            @endif
        </div>

        @php
            $settingNotes = \App\Models\Setting::get('invoice_notes');
            $customNotes = $invoice->notes ?? null;
        @endphp

        @if($customNotes || $settingNotes)
        <div style="margin-top: 15px; padding: 8px 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 4px; font-size: 11px;">
            @if($customNotes)
                <p><strong>ملاحظات الفاتورة:</strong> {{ $customNotes }}</p>
            @endif
            @if($settingNotes)
                <p style="color: #475569; margin-top: {{ $customNotes ? '4px' : '0' }};">{{ $settingNotes }}</p>
            @endif
        </div>
        @endif

        <div style="flex-grow: 1;"></div>

        <!-- Footer -->
        <x-print.footer />
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
