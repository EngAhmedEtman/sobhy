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
        } else {
            $typeLabel = 'إيصال عملية مالية';
            $subLabel = 'مستند قيد';
            $refPrefix = 'TRX';
        }
        
        $referenceCode = $refPrefix . '-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
        $amount = in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent']) ? $transaction->paid_amount : $transaction->total_amount;
        $transactionable = $transaction->transactionable;
        $partyType = $transaction->type === 'payment_received' || $transaction->type === 'return_sale' ? 'العميل' : 'المورد';
    @endphp
    <title>{{ $typeLabel }} #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</title>
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
        .invoice-info-col h3 { font-size: 13px; font-weight: 800; margin-bottom: 5px; color: #1e293b; }
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
        
        .totals-section { width: 45%; margin-right: auto; margin-left: 0; border: 1px solid #94a3b8; border-radius: 4px; overflow: hidden; margin-bottom: 15px; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 12px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .totals-row:last-child { border-bottom: none; background: #f1f5f9; font-weight: 800; }
        .totals-label { font-weight: 700; color: #475569; }
        .totals-value { font-weight: 800; direction: ltr; }

        .notes-box {
            background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 8px 12px; margin-bottom: 15px; font-size: 11px;
        }
        
        @media print {
            .no-print { display: none !important; }
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <!-- Header -->
        <x-print.header :title="$typeLabel" :subtitle="$subLabel" :referenceCode="$referenceCode" />

        <!-- Meta Details Box -->
        <div class="invoice-details-box">
            <div class="invoice-info-col">
                <h3>معلومات {{ $partyType }}</h3>
                <p><strong>الاسم:</strong> {{ $transactionable->name ?? '---' }}</p>
                <p><strong>الهاتف:</strong> {{ $transactionable->phone ?? '---' }}</p>
            </div>
            <div class="invoice-info-col" style="text-align: left;">
                <h3>تفاصيل الإيصال</h3>
                <p><strong>رقم العملية:</strong> #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p><strong>تاريخ العملية:</strong> {{ $transaction->transaction_date->format('Y-m-d') }}</p>
            </div>
        </div>

        <!-- Items Table if Return -->
        @if(in_array($transaction->type, ['return_sale', 'return_purchase']) && ($transaction->product || $transaction->quantity > 0))
        <table class="print-data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 42%;">المنتج المسترجع</th>
                    <th style="width: 15%;">الكمية</th>
                    <th style="width: 15%;">سعر الكيلو / الوحدة</th>
                    <th style="width: 20%;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td style="font-weight: 700;">{{ $transaction->product->name ?? 'بضاعة مسترجعة' }}</td>
                    <td style="direction: ltr;">{{ number_format($transaction->quantity, 2) }} ك</td>
                    <td style="direction: ltr;">{{ number_format($transaction->unit_price, 2) }} ج.م</td>
                    <td style="direction: ltr; font-weight: bold;">{{ number_format($amount, 2) }} ج.م</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Financial Totals Box -->
        <div class="totals-section">
            @if(in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent']))
                <div class="totals-row" style="color: #15803d; font-weight: 800; font-size: 13px;">
                    <span class="totals-label">المبلغ المسدد:</span>
                    <span class="totals-value">{{ (float)$amount == (int)$amount ? number_format($amount, 0) : number_format($amount, 2) }} ج.م</span>
                </div>
            @else
                <div class="totals-row">
                    <span class="totals-label">إجمالي قيمة المرتجع:</span>
                    <span class="totals-value">{{ (float)$amount == (int)$amount ? number_format($amount, 0) : number_format($amount, 2) }} ج.م</span>
                </div>
                @if($transaction->paid_amount > 0)
                <div class="totals-row" style="color: #15803d;">
                    <span class="totals-label">المبلغ المسترد / المدفوع نقداً:</span>
                    <span class="totals-value">{{ (float)$transaction->paid_amount == (int)$transaction->paid_amount ? number_format($transaction->paid_amount, 0) : number_format($transaction->paid_amount, 2) }} ج.م</span>
                </div>
                @endif
            @endif

            <div class="totals-row" style="background: #f8fafc;">
                <span class="totals-label">الرصيد بعد هذه العملية:</span>
                <span class="totals-value" style="{{ $transaction->balance_after > 0 ? 'color: #b91c1c;' : ($transaction->balance_after < 0 ? 'color: #15803d;' : 'color: #334155;') }}">
                    @php
                        $isCustomer = $transaction->transactionable_type === 'App\Models\Customer' || str_contains($transaction->type, 'sale') || $transaction->type === 'payment_received';
                        $absBal = abs($transaction->balance_after);
                        $formattedBal = (float)$absBal == (int)$absBal ? number_format($absBal, 0) : number_format($absBal, 2);
                    @endphp
                    @if($transaction->balance_after < 0)
                        {{ $formattedBal }} ج.م {{ $isCustomer ? '(رصيد للعميل / له عندنا)' : '(لنا عند المورد / زيادة)' }}
                    @elseif($transaction->balance_after > 0)
                        {{ $formattedBal }} ج.م {{ $isCustomer ? '(مطلوب من العميل / لنا عنده)' : '(مستحق للمورد / علينا له)' }}
                    @else
                        0 ج.م (خالص)
                    @endif
                </span>
            </div>
        </div>

        @if($transaction->notes)
        <div class="notes-box">
            <strong>ملاحظات وبيان:</strong> {{ $transaction->notes }}
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