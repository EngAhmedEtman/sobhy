<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => ''.e($product->name).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($product->name).'']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> المنتجات > <?php echo e($product->name); ?> <?php $__env->endSlot(); ?>

    <!-- Top Bar -->
    <div class="flex flex-col-reverse sm:flex-row items-start sm:items-center gap-3 mb-4 sm:mb-6">
        <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h2 class="text-lg sm:text-xl font-black text-slate-800 truncate"><?php echo e($product->name); ?></h2>
            </div>
            
            <div class="bg-slate-50 sm:bg-transparent rounded-lg p-3 sm:p-0 border border-slate-100 sm:border-none flex items-center justify-between sm:justify-end gap-3 shrink-0">
                <span class="text-xs sm:text-sm text-slate-500 font-bold block">الرصيد المتاح بالمخزن:</span>
                <div class="flex items-center gap-1.5">
                    <span class="text-lg sm:text-xl font-black <?php echo e($product->stock < 0 ? 'text-danger-600' : 'text-primary-600'); ?>" dir="ltr"><?php echo e(number_format($product->stock, 2)); ?></span>
                    <span class="text-xs text-slate-400 font-bold">كيلو</span>
                </div>
            </div>
        </div>
        <a href="<?php echo e(route('products.index')); ?>" class="px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 hover:text-primary-600 text-sm font-bold flex items-center justify-center gap-2 shrink-0 transition-all shadow-sm w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            العودة للقائمة
        </a>
    </div>

    <!-- Mobile Cards for Transactions -->
    <div class="sm:hidden space-y-3">
        <h3 class="font-bold text-slate-800 text-base">سجل الحركات</h3>
        <?php $__empty_1 = true; $__currentLoopData = $product->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <div class="flex justify-between items-center mb-2">
                <span class="px-2 py-0.5 rounded text-[0.7rem] font-bold <?php echo e($transaction->quantity > 0 ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'bg-danger-50 text-danger-700 border border-danger-200'); ?>"><?php echo e($transaction->type); ?></span>
                <span class="text-[0.7rem] text-slate-400 font-bold" dir="ltr"><?php echo e($transaction->created_at->format('m/d H:i')); ?></span>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <?php if($transaction->quantity > 0): ?>
                    <span class="text-sm text-slate-500">وارد:</span>
                    <span class="text-base font-black text-primary-600" dir="ltr"><?php echo e(number_format($transaction->quantity, 2)); ?></span>
                    <?php else: ?>
                    <span class="text-sm text-slate-500">منصرف:</span>
                    <span class="text-base font-black text-danger-600" dir="ltr"><?php echo e(number_format(abs($transaction->quantity), 2)); ?></span>
                    <?php endif; ?>
                </div>
                <div class="text-left">
                    <span class="text-xs text-slate-400">الرصيد بعدها:</span>
                    <span class="text-sm font-black text-slate-700" dir="ltr"><?php echo e(number_format($transaction->balance_after, 2)); ?></span>
                </div>
            </div>
            <?php if($transaction->notes): ?>
            <p class="text-xs text-slate-400 mt-2 truncate"><?php echo e($transaction->notes); ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">لم يتم تسجيل أي حركات بعد.</div>
        <?php endif; ?>
    </div>

    <!-- Desktop Table -->
    <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center gap-3">
            <div class="p-2 bg-primary-50 text-primary-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-lg">سجل حركات الصنف</h3>
                <p class="text-xs text-slate-500">تتبع مفصل للكميات الواردة والمنصرفة</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse whitespace-nowrap">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">التاريخ</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">العميل / المورد</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">نوع الحركة</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الكمية الواردة</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الكمية المنصرفة</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الرصيد بعد الحركة</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">البيان</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $product->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <td class="px-4 py-3 text-[0.75rem] text-slate-500 border-b border-slate-100 align-middle text-center font-bold" dir="ltr"><?php echo e($transaction->created_at->format('Y-m-d H:i')); ?></td>
                        <td class="px-4 py-3 text-[0.8rem] font-bold text-slate-700 border-b border-slate-100 align-middle text-center"><?php if($transaction->related): ?><?php echo e($transaction->related->party_name ?? 'فاتورة'); ?><?php else: ?> - <?php endif; ?></td>
                        <td class="px-4 py-3 text-[0.8rem] font-bold text-slate-700 border-b border-slate-100 align-middle text-center">
                            <span class="px-2 py-1 rounded text-[0.7rem] <?php echo e($transaction->quantity > 0 ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'bg-danger-50 text-danger-700 border border-danger-200'); ?>"><?php echo e($transaction->type); ?></span>
                        </td>
                        <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-600 border-b border-slate-100 align-middle text-center" dir="ltr"><?php echo e($transaction->quantity > 0 ? number_format($transaction->quantity, 2) : '-'); ?></td>
                        <td class="px-4 py-3 text-[0.85rem] font-bold text-danger-600 border-b border-slate-100 align-middle text-center" dir="ltr"><?php echo e($transaction->quantity < 0 ? number_format(abs($transaction->quantity), 2) : '-'); ?></td>
                        <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-800 border-b border-slate-100 align-middle text-center" dir="ltr"><?php echo e(number_format($transaction->balance_after, 2)); ?></td>
                        <td class="px-4 py-3 text-[0.8rem] text-slate-500 border-b border-slate-100 align-middle text-center max-w-xs truncate"><?php echo e($transaction->notes ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="px-4 py-8 text-sm text-slate-500 text-center">لم يتم تسجيل أي حركات لهذا المنتج بعد.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
