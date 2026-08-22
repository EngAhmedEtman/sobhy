<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
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

        .print-btn {
            position: fixed; top: 20px; left: 20px; background: #0f172a; color: #fff; border: none; width: 48px; height: 48px;
            border-radius: 50%; cursor: pointer; box-shadow: 0 4px 14px rgba(0,0,0,0.25); z-index: 9999;
            display: flex; align-items: center; justify-content: center;
        }
        .print-btn svg { width: 22px; height: 22px; stroke: currentColor; fill: none; }
        
        @media print {
            .no-print { display: none !important; }
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <!-- Header -->
        <?php if (isset($component)) { $__componentOriginal290c8099c98cf9fb9f13d9638c125a9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print.header','data' => ['title' => $title,'subtitle' => $type === 'purchase' ? 'فاتورة مورد' : 'فاتورة عميل','referenceCode' => 'INV-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($type === 'purchase' ? 'فاتورة مورد' : 'فاتورة عميل'),'referenceCode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('INV-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c)): ?>
<?php $attributes = $__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c; ?>
<?php unset($__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal290c8099c98cf9fb9f13d9638c125a9c)): ?>
<?php $component = $__componentOriginal290c8099c98cf9fb9f13d9638c125a9c; ?>
<?php unset($__componentOriginal290c8099c98cf9fb9f13d9638c125a9c); ?>
<?php endif; ?>

        <!-- Invoice Meta -->
        <div class="invoice-details-box">
            <div class="invoice-info-col">
                <h3>معلومات <?php echo e($type === 'purchase' ? 'المورد' : 'العميل'); ?></h3>
                <?php $party = $type === 'purchase' ? $invoice->supplier : $invoice->customer; ?>
                <p><strong>الاسم:</strong> <?php echo e($party->name); ?></p>
                <p><strong>الهاتف:</strong> <?php echo e($party->phone ?? '---'); ?></p>
            </div>
            <div class="invoice-info-col" style="text-align: left;">
                <h3>تفاصيل الفاتورة</h3>
                <p><strong>رقم الفاتورة:</strong> #<?php echo e($invoice->invoice_number ?? $invoice->id); ?></p>
                <p><strong>التاريخ:</strong> <?php echo e($invoice->created_at->format('Y-m-d')); ?></p>
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
                <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($item->product->name); ?></td>
                    <td style="direction: ltr;"><?php echo e($item->quantity); ?> <?php echo e($item->product->unit ?? 'ك'); ?></td>
                    <td style="direction: ltr;"><?php echo e(number_format($item->unit_price, 2)); ?> ج.م</td>
                    <td style="direction: ltr; font-weight: bold;"><?php echo e(number_format($item->total, 2)); ?> ج.م</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-row">
                <span class="totals-label">إجمالي الفاتورة:</span>
                <span class="totals-value"><?php echo e(number_format($invoice->total_amount, 2)); ?> ج.م</span>
            </div>
            
            <div class="totals-row" style="color: #15803d;">
                <span class="totals-label">المدفوع نقداً:</span>
                <span class="totals-value"><?php echo e(number_format($invoice->transaction ? $invoice->transaction->paid_amount : 0, 2)); ?> ج.م</span>
            </div>
            <?php if($invoice->total_amount - ($invoice->transaction ? $invoice->transaction->paid_amount : 0) > 0): ?>
            <div class="totals-row" style="color: #b91c1c;">
                <span class="totals-label">المتبقي (آجل):</span>
                <span class="totals-value"><?php echo e(number_format($invoice->total_amount - ($invoice->transaction ? $invoice->transaction->paid_amount : 0), 2)); ?> ج.م</span>
            </div>
            <?php endif; ?>
        </div>

        <div style="flex-grow: 1;"></div>

        <!-- Footer -->
        <?php if (isset($component)) { $__componentOriginal5f46920fa4699efb6971e3542070016d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5f46920fa4699efb6971e3542070016d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5f46920fa4699efb6971e3542070016d)): ?>
<?php $attributes = $__attributesOriginal5f46920fa4699efb6971e3542070016d; ?>
<?php unset($__attributesOriginal5f46920fa4699efb6971e3542070016d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5f46920fa4699efb6971e3542070016d)): ?>
<?php $component = $__componentOriginal5f46920fa4699efb6971e3542070016d; ?>
<?php unset($__componentOriginal5f46920fa4699efb6971e3542070016d); ?>
<?php endif; ?>
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
