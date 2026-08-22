<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?> - <?php echo e($party->name); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 portrait; margin: 8mm 6mm !important; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; min-height: 100%; }
        body { font-family: 'Cairo', sans-serif; background: #fff; color: #0f172a; font-size: 11px; line-height: 1.4; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        .page-container { width: 100%; margin: 0 auto; padding: 5mm; background: #fff; }
        
        .report-repeat-table { width: 100%; border-collapse: collapse; border: 0; }
        .report-repeat-header { display: table-header-group; }
        .report-repeat-footer { display: table-footer-group; }
        .report-repeat-cell { padding: 0; border: 0; vertical-align: top !important; }

        .party-info-box {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 12px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 12px;
        }
        .party-name { font-size: 14px; font-weight: 800; color: #0f172a; }
        .party-badge { padding: 1px 6px; background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; color: #334155; font-size: 9.5px; margin-right: 8px;}

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 14px; }
        .stat-card { padding: 6px; background: #fff; border: 1px solid #0f172a; border-radius: 6px; text-align: center; }
        .stat-label { font-size: 9px; font-weight: 700; color: #475569; margin-bottom: 2px; }
        .stat-value { font-size: 12px; font-weight: 900; color: #0f172a; direction: ltr; }
        .stat-value.danger { color: #b91c1c; }
        .stat-value.success { color: #15803d; }

        .print-data-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; page-break-inside: auto; }
        .print-data-table th { background: #f1f5f9; color: #0f172a; padding: 5px 4px; border: 1px solid #475569; font-size: 10px; font-weight: 800; text-align: center; }
        .print-data-table td { padding: 5px 4px; border: 1px solid #94a3b8; font-size: 10px; text-align: center; color: #1e293b; direction: ltr;}
        .print-data-table tr { page-break-inside: avoid; }
        .print-data-table tr:nth-child(even) { background: #f8fafc; }

        .print-btn {
            position: fixed; top: 20px; left: 20px; background: #0f172a; color: #fff; border: none; width: 48px; height: 48px;
            border-radius: 50%; cursor: pointer; box-shadow: 0 4px 14px rgba(0,0,0,0.25); z-index: 9999;
            display: flex; align-items: center; justify-content: center;
        }
        .print-btn svg { width: 22px; height: 22px; stroke: currentColor; fill: none; }
        
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
    <button onclick="window.print()" class="print-btn no-print" title="طباعة">
        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"></path></svg>
    </button>

    <div class="page-container">
        <table class="report-repeat-table">
            <thead class="report-repeat-header">
                <tr>
                    <td class="report-repeat-cell">
                        <?php if (isset($component)) { $__componentOriginal290c8099c98cf9fb9f13d9638c125a9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print.header','data' => ['title' => $title,'subtitle' => $subtitle,'referenceCode' => 'STMT-' . str_pad($party->id, 4, '0', STR_PAD_LEFT) . '-' . date('Ymd')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subtitle),'referenceCode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('STMT-' . str_pad($party->id, 4, '0', STR_PAD_LEFT) . '-' . date('Ymd'))]); ?>
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
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="report-repeat-cell">
                        <!-- Party Info -->
                        <div class="party-info-box">
                            <div>
                                <span class="party-name"><?php echo e($party->name); ?></span>
                                <?php if($party->phone): ?>
                                <span class="party-badge">هاتف: <?php echo e($party->phone); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Stats -->
                        <?php
                            $isOwed = $party->balance > 0;
                            $isCredit = $party->balance < 0;
                            $balanceColor = $isOwed ? 'danger' : ($isCredit ? 'success' : '');
                            $balanceLabel = $isOwed ? 'المتبقي له' : ($isCredit ? 'المتبقي عليه' : 'الرصيد');
                            
                            $totalPurchases = $transactions->where('type', 'purchase')->sum('total_amount');
                            $totalSales = $transactions->where('type', 'sale')->sum('total_amount');
                            $totalPayments = $transactions->where('type', 'payment_made')->sum('paid_amount') + $transactions->where('type', 'payment_received')->sum('paid_amount');
                        ?>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-label">إجمالي العمليات</div>
                                <div class="stat-value"><?php echo e(number_format($partyType === 'supplier' ? $totalPurchases : $totalSales, 0)); ?> ج.م</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">إجمالي المسدد</div>
                                <div class="stat-value success"><?php echo e(number_format($totalPayments, 0)); ?> ج.م</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label"><?php echo e($balanceLabel); ?> الحالي</div>
                                <div class="stat-value <?php echo e($balanceColor); ?>"><?php echo e(number_format(abs($party->balance), 0)); ?> ج.م</div>
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
                                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($t->transaction_date->format('Y-m-d')); ?></td>
                                    <td style="direction: rtl;">
                                        <?php if($t->type === 'purchase'): ?> شراء منتجات
                                        <?php elseif($t->type === 'sale'): ?> بيع منتجات
                                        <?php elseif($t->type === 'payment_made'): ?> سداد نقدية
                                        <?php elseif($t->type === 'payment_received'): ?> استلام نقدية
                                        <?php elseif($t->type === 'return_purchase'): ?> مرتجع شراء
                                        <?php elseif($t->type === 'return_sale'): ?> مرتجع مبيعات
                                        <?php else: ?> <?php echo e($t->type); ?> <?php endif; ?>
                                    </td>
                                    <td><?php echo e($t->total_amount > 0 ? number_format($t->total_amount, 2) : '-'); ?></td>
                                    <td><?php echo e($t->paid_amount > 0 ? number_format($t->paid_amount, 2) : '-'); ?></td>
                                    <td style="font-weight: bold; <?php echo e($t->balance_after > 0 ? 'color:#b91c1c;' : ($t->balance_after < 0 ? 'color:#15803d;' : '')); ?>">
                                        <?php echo e(number_format(abs($t->balance_after), 2)); ?> <?php echo e($t->balance_after > 0 ? 'له' : ($t->balance_after < 0 ? 'عليه' : '')); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" style="padding: 15px; direction: rtl;">لا توجد عمليات مسجلة في هذه الفترة.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
            <tfoot class="report-repeat-footer">
                <tr>
                    <td class="report-repeat-cell">
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
