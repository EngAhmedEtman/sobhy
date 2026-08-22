<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الإيصال</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        body { font-family: 'Cairo', sans-serif; background: #fff; margin: 0; padding: 20px; color: #1e293b; }
        .receipt-container { max-width: 800px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: 700; color: #0f172a; }
        .receipt-title { font-size: 20px; font-weight: 700; color: #3b82f6; text-align: center; margin: 20px 0; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; }
        .info-label { font-size: 12px; color: #64748b; margin-bottom: 5px; font-weight: 600; }
        .info-value { font-size: 16px; font-weight: 700; color: #0f172a; }
        
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-table th, .details-table td { padding: 12px; text-align: right; border-bottom: 1px solid #e2e8f0; }
        .details-table th { background: #f8fafc; font-size: 13px; color: #64748b; font-weight: 600; }
        .details-table td { font-size: 14px; font-weight: 600; }
        
        .financial-summary { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin-top: 30px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px; }
        .summary-row:last-child { margin-bottom: 0; padding-top: 10px; border-top: 1px solid #bbf7d0; font-size: 18px; font-weight: 700; color: #166534; }
        
        .footer { text-align: center; margin-top: 40px; color: #64748b; font-size: 13px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        
        @media print {
            body { padding: 0; }
            .receipt-container { border: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    @php
        $typeLabel = '';
        if(in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent'])) {
            $typeLabel = 'إيصال سداد دفعة';
        } elseif(in_array($transaction->type, ['return_sale'])) {
            $typeLabel = 'إيصال مرتجع مبيعات';
        } elseif(in_array($transaction->type, ['return_purchase'])) {
            $typeLabel = 'إيصال مرتجع مشتريات';
        } else {
            $typeLabel = 'إيصال عملية';
        }
        
        $amount = in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent']) ? $transaction->paid_amount : $transaction->total_amount;
    @endphp

    <div class="receipt-container">
        <div class="header">
            <div class="logo">النظام المحاسبي</div>
            <div style="text-align: left;">
                <div style="font-size: 14px; color: #64748b;">التاريخ</div>
                <div style="font-size: 16px; font-weight: 700;">{{ $transaction->transaction_date->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="receipt-title">{{ $typeLabel }}</div>

        <div class="info-grid">
            <div class="info-box">
                <div class="info-label">الطرف الثاني</div>
                <div class="info-value">{{ $transactionable->name ?? '-' }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">رقم العملية</div>
                <div class="info-value">#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        @if(in_array($transaction->type, ['return_sale', 'return_purchase']) && $transaction->product)
        <table class="details-table">
            <thead>
                <tr>
                    <th>المنتج المسترجع</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $transaction->product->name }}</td>
                    <td dir="ltr">{{ number_format($transaction->quantity, 2) }} ك</td>
                    <td dir="ltr">{{ number_format($transaction->unit_price, 2) }}</td>
                    <td dir="ltr">{{ number_format($amount, 2) }} ج.م</td>
                </tr>
            </tbody>
        </table>
        @endif

        <div class="financial-summary">
            @if(in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent']))
                <div class="summary-row">
                    <span>المبلغ المسدد:</span>
                    <span dir="ltr">{{ number_format($amount, 2) }} ج.م</span>
                </div>
            @else
                <div class="summary-row">
                    <span>إجمالي المرتجع:</span>
                    <span dir="ltr">{{ number_format($amount, 2) }} ج.م</span>
                </div>
                @if($transaction->paid_amount > 0)
                <div class="summary-row" style="color: #64748b; font-size: 14px;">
                    <span>المبلغ المدفوع/المسترد نقداً:</span>
                    <span dir="ltr">{{ number_format($transaction->paid_amount, 2) }} ج.م</span>
                </div>
                @endif
            @endif

            <div class="summary-row">
                <span>الرصيد بعد العملية:</span>
                <span dir="ltr">
                    @if($transaction->balance_after < 0)
                        {{ number_format(abs($transaction->balance_after), 2) }} ج.م (لنا)
                    @elseif($transaction->balance_after > 0)
                        {{ number_format($transaction->balance_after, 2) }} ج.م (عليه)
                    @else
                        خالص
                    @endif
                </span>
            </div>
        </div>

        @if($transaction->notes)
        <div style="margin-top: 20px; font-size: 14px;">
            <strong>ملاحظات:</strong> {{ $transaction->notes }}
        </div>
        @endif

        <div style="text-align: center; margin-top: 30px;" class="no-print">
            <button onclick="window.print()" style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-family: 'Cairo'; font-weight: bold; cursor: pointer; font-size: 16px;">طباعة الآن</button>
        </div>

        <div class="footer">
            تم إصدار هذا الإيصال من النظام آلياً.
        </div>
    </div>
</body>
</html>