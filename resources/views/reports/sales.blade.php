<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => request('print_mode') ? 'layouts.print' : 'layouts.app'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'تقرير المبيعات الشامل']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> التقارير > مبيعات <?php $__env->endSlot(); ?>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full print:hidden mb-6">
        <div class="flex items-start gap-3.5 text-right">
            <div class="p-2.5 bg-success-50 text-success-600 rounded-2xl shadow-sm shrink-0 self-center border border-success-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">تقرير المبيعات الشامل</h1>
                <p class="text-xs lg:text-sm font-medium text-slate-500 leading-relaxed mt-0.5 hidden sm:block">
                    متابعة دقيقة لجميع الفواتير والمبالغ المحصلة والآجلة لقطاع المبيعات خلال فترة زمنية محددة.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap shrink-0">
            <button type="button" @click="$dispatch('open-print-report')" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg text-xs transition-all shadow-sm flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>طباعة التقرير</span>
            </button>
            <a href="<?php echo e(route('reports.index')); ?>" class="px-3.5 py-1.5 bg-white text-slate-700 border border-slate-200 font-bold rounded-lg text-xs hover:bg-slate-50 transition-colors shadow-sm inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                <span>العودة للتقارير</span>
            </a>
        </div>
    </div>

    <!-- Printable Header Branding -->
    <div class="hidden print:block mb-6">
        <?php if (isset($component)) { $__componentOriginal290c8099c98cf9fb9f13d9638c125a9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print.header','data' => ['title' => 'تقرير المبيعات الشامل','subtitle' => request('start_date') ? 'الفترة من: ' . request('start_date') . ' إلى: ' . (request('end_date') ?? 'اليوم') : 'التقرير الشامل']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'تقرير المبيعات الشامل','subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('start_date') ? 'الفترة من: ' . request('start_date') . ' إلى: ' . (request('end_date') ?? 'اليوم') : 'التقرير الشامل')]); ?>
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
    </div>

    <!-- Compact Web Metrics Panel -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 print:hidden">
        <?php
            $totalWeight = $sales->sum(function($sale) {
                return collect($sale->items)->sum('quantity');
            });
        ?>
        <div class="grid grid-cols-2 lg:grid-cols-5 divide-y lg:divide-y-0 lg:divide-x lg:divide-x-reverse divide-slate-100">
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors rounded-r-xl">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي الفواتير</span>
                </div>
                <span class="text-xl font-black text-slate-800" dir="ltr"><?php echo e(number_format($sales->count())); ?></span>
            </div>
            
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي المبيعات</span>
                </div>
                <span class="text-xl font-black text-slate-800" dir="ltr"><?php echo e(number_format($totalAmount, 0)); ?> <span class="text-xs text-slate-400">EGP</span></span>
            </div>
            
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي المدفوع</span>
                </div>
                <span class="text-xl font-black text-slate-800" dir="ltr"><?php echo e(number_format($totalPaid, 0)); ?> <span class="text-xs text-slate-400">EGP</span></span>
            </div>
            
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي المتبقي (آجل)</span>
                </div>
                <span class="text-xl font-black text-danger-600" dir="ltr"><?php echo e(number_format($totalRemaining, 0)); ?> <span class="text-xs text-danger-400">EGP</span></span>
            </div>
            
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors rounded-l-xl">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي الأوزان المباعة</span>
                </div>
                <span class="text-xl font-black text-slate-800" dir="ltr"><?php echo e(number_format($totalWeight, 0)); ?> <span class="text-xs text-slate-400">كجم</span></span>
            </div>
        </div>
    </div>

    <!-- Print Formal Summary Table -->
    <div class="hidden print:block mb-8">
        <h3 class="text-sm font-bold text-slate-800 mb-2 border-b border-slate-300 pb-1">ملخص الإحصائيات للفترة المحددة</h3>
        <table class="w-full text-center border-collapse">
            <thead>
                <tr>
                    <th>عدد الفواتير</th>
                    <th>إجمالي المبيعات</th>
                    <th>إجمالي المدفوع</th>
                    <th>إجمالي الآجل</th>
                    <th>إجمالي الوزن</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo e(number_format($sales->count())); ?></td>
                    <td dir="ltr"><?php echo e(number_format($totalAmount, 0)); ?> EGP</td>
                    <td dir="ltr"><?php echo e(number_format($totalPaid, 0)); ?> EGP</td>
                    <td dir="ltr"><?php echo e(number_format($totalRemaining, 0)); ?> EGP</td>
                    <td dir="ltr"><?php echo e(number_format($totalWeight, 0)); ?> كجم</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 print:hidden">
        <form id="filterForm" action="<?php echo e(route('reports.sales')); ?>" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">من تاريخ</label>
                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 text-xs focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">إلى تاريخ</label>
                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 text-xs focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
            </div>
            <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                <button type="submit" class="flex-1 sm:flex-none px-5 py-1.5 text-xs font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm shadow-primary-600/20">تطبيق</button>
                <a href="<?php echo e(route('reports.sales')); ?>" class="flex-1 sm:flex-none px-5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-lg hover:bg-slate-200 transition-colors text-center">تفريغ</a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden print:border-none print:shadow-none print:rounded-none print:bg-transparent">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse whitespace-nowrap">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">رقم الفاتورة</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">التاريخ</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">العميل</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الإجمالي</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المدفوع</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المتبقي</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <td class="px-4 py-3 text-[0.8rem] font-bold text-slate-700 border-b border-slate-100"><?php echo e($sale->invoice_number); ?></td>
                        <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-100"><?php echo e(\Carbon\Carbon::parse($sale->date)->format('Y-m-d')); ?></td>
                        <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100"><?php echo e($sale->customer->name ?? '-'); ?></td>
                        <td class="px-4 py-3 text-[0.85rem] text-slate-800 font-bold border-b border-slate-100" dir="ltr"><?php echo e(number_format($sale->total_amount, 0)); ?></td>
                        <td class="px-4 py-3 text-[0.85rem] text-success-600 font-bold border-b border-slate-100" dir="ltr"><?php echo e(number_format($sale->paid_amount, 0)); ?></td>
                        <td class="px-4 py-3 text-[0.85rem] text-danger-600 font-bold border-b border-slate-100" dir="ltr"><?php echo e(number_format($sale->remaining_amount, 0)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="px-4 py-8 text-sm text-slate-500 text-center">لا توجد بيانات متاحة في هذه الفترة الزمنية.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if(!request('print_mode')): ?>
        <?php if (isset($component)) { $__componentOriginal12fa23a1cf371a30e3d60140e4e460e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal12fa23a1cf371a30e3d60140e4e460e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.print-report','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.print-report'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal12fa23a1cf371a30e3d60140e4e460e8)): ?>
<?php $attributes = $__attributesOriginal12fa23a1cf371a30e3d60140e4e460e8; ?>
<?php unset($__attributesOriginal12fa23a1cf371a30e3d60140e4e460e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal12fa23a1cf371a30e3d60140e4e460e8)): ?>
<?php $component = $__componentOriginal12fa23a1cf371a30e3d60140e4e460e8; ?>
<?php unset($__componentOriginal12fa23a1cf371a30e3d60140e4e460e8); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalba30c15cd74879269c11bdbb21af4a82 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalba30c15cd74879269c11bdbb21af4a82 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print.preview-modal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print.preview-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalba30c15cd74879269c11bdbb21af4a82)): ?>
<?php $attributes = $__attributesOriginalba30c15cd74879269c11bdbb21af4a82; ?>
<?php unset($__attributesOriginalba30c15cd74879269c11bdbb21af4a82); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalba30c15cd74879269c11bdbb21af4a82)): ?>
<?php $component = $__componentOriginalba30c15cd74879269c11bdbb21af4a82; ?>
<?php unset($__componentOriginalba30c15cd74879269c11bdbb21af4a82); ?>
<?php endif; ?>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
