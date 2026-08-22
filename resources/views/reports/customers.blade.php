<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => request('print_mode') ? 'layouts.print' : 'layouts.app'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'تقرير عميل']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> التقارير والإحصائيات / تقرير عميل <?php $__env->endSlot(); ?>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full print:hidden mb-6">
        <div class="flex items-start gap-3.5 text-right">
            <div class="p-2.5 bg-primary-50 text-primary-600 rounded-2xl shadow-sm shrink-0 self-center border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">كشف حساب عميل</h1>
                <p class="text-xs lg:text-sm font-medium text-slate-500 leading-relaxed mt-0.5 hidden sm:block">
                    متابعة دقيقة لحركة حساب العميل واستخراج كشوفات مفصلة أو من آخر تصفية.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap shrink-0">
            <?php if(isset($customer) && $customer): ?>
            <button type="button" @click="$dispatch('open-print-report')" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg text-xs transition-all shadow-sm flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>طباعة التقرير</span>
            </button>
            <?php endif; ?>
            <a href="<?php echo e(route('reports.index')); ?>" class="px-3.5 py-1.5 bg-white text-slate-700 border border-slate-200 font-bold rounded-lg text-xs hover:bg-slate-50 transition-colors shadow-sm inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                <span>العودة للتقارير</span>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 print:hidden">
        <form id="filterForm" action="<?php echo e(route('reports.customers')); ?>" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">العميل</label>
                <select name="customer_id" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs" required>
                    <option value="">-- اختر العميل --</option>
                    <?php $__currentLoopData = $customersList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(request('customer_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex-1 min-w-[130px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">العمليات</label>
                <select name="transaction_type" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
                    <option value="all" <?php echo e(request('transaction_type') == 'all' ? 'selected' : ''); ?>>الكل</option>
                    <option value="sale" <?php echo e(request('transaction_type') == 'sale' ? 'selected' : ''); ?>>مبيعات فقط</option>
                    <option value="payment_received" <?php echo e(request('transaction_type') == 'payment_received' ? 'selected' : ''); ?>>دفعات مستلمة</option>
                </select>
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">الفترة</label>
                <select name="filter_type" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
                    <option value="date_range" <?php echo e(request('filter_type') == 'date_range' ? 'selected' : ''); ?>>حسب التاريخ</option>
                    <option value="since_last_zero" <?php echo e(request('filter_type') == 'since_last_zero' ? 'selected' : ''); ?>>من آخر تصفية</option>
                </select>
            </div>
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">من</label>
                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
            </div>
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">إلى</label>
                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
            </div>
            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                <button type="submit" class="w-full sm:w-auto px-5 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-xs font-bold transition-colors shadow-sm shadow-primary-600/20">
                    تطبيق
                </button>
            </div>
        </form>
    </div>

    <?php if($customer): ?>
        <!-- Printable Header Branding -->
        <div class="hidden print:block mb-6">
            <?php if (isset($component)) { $__componentOriginal290c8099c98cf9fb9f13d9638c125a9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print.header','data' => ['title' => 'كشف حساب: ' . $customer->name,'subtitle' => (request('start_date') || request('end_date')) ? 'الفترة من ' . (request('start_date') ?? 'البداية') . ' إلى ' . (request('end_date') ?? 'الآن') : 'جميع التعاملات']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('كشف حساب: ' . $customer->name),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((request('start_date') || request('end_date')) ? 'الفترة من ' . (request('start_date') ?? 'البداية') . ' إلى ' . (request('end_date') ?? 'الآن') : 'جميع التعاملات')]); ?>
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

        <!-- Report Content -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 p-6 print:border-none print:shadow-none print:bg-transparent print:p-0 print:m-0">
            <div class="border-b border-slate-100 pb-4 mb-4 print:hidden">
                <h2 class="text-lg font-black text-slate-800">كشف حساب: <?php echo e($customer->name); ?></h2>
                <p class="text-xs text-slate-500 mt-1">
                    <?php if(request('start_date') || request('end_date')): ?>
                        الفترة من <?php echo e(request('start_date') ?? 'البداية'); ?> إلى <?php echo e(request('end_date') ?? 'الآن'); ?>

                    <?php else: ?>
                        جميع التعاملات
                    <?php endif; ?>
                </p>
            </div>

            <!-- Compact Web Metrics Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 print:hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x md:divide-x-reverse divide-slate-100">
                    <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors rounded-r-xl">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            <span class="text-[0.7rem] font-bold">إجمالي المبيعات (خلال الفترة)</span>
                        </div>
                        <span class="text-xl font-black text-slate-800" dir="ltr"><?php echo e(number_format($totalsales, 2)); ?></span>
                    </div>
                    
                    <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                            <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[0.7rem] font-bold">المدفوعات المستلمة (خلال الفترة)</span>
                        </div>
                        <span class="text-xl font-black text-success-600" dir="ltr"><?php echo e(number_format($totalPayments, 2)); ?></span>
                    </div>
                    
                    <div class="p-4 flex flex-col justify-center text-center bg-primary-50 hover:bg-primary-100 transition-colors rounded-l-xl">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-primary-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            <span class="text-[0.7rem] font-bold">الرصيد النهائي الحالي</span>
                        </div>
                        <p class="text-xl font-black <?php echo e($customer->balance < 0 ? 'text-danger-600' : 'text-primary-700'); ?>" dir="ltr">
                            <?php echo e(number_format(abs($customer->balance), 2)); ?>

                            <span class="text-xs font-normal <?php echo e($customer->balance < 0 ? 'text-danger-500' : 'text-primary-500'); ?>">
                                <?php echo e($customer->balance < 0 ? 'المتبقي له' : 'المتبقي عليه'); ?>

                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Print Formal Summary Table -->
            <div class="hidden print:block mb-8">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr>
                            <th>إجمالي المبيعات (خلال الفترة)</th>
                            <th>المدفوعات المستلمة (خلال الفترة)</th>
                            <th>الرصيد النهائي الحالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td dir="ltr"><?php echo e(number_format($totalsales, 2)); ?></td>
                            <td dir="ltr"><?php echo e(number_format($totalPayments, 2)); ?></td>
                            <td dir="ltr">
                                <?php echo e(number_format(abs($customer->balance), 2)); ?>

                                (<?php echo e($customer->balance < 0 ? 'المتبقي له' : 'المتبقي عليه'); ?>)
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Transactions Table -->
            <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white print:border-none print:shadow-none print:rounded-none print:bg-transparent">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">التاريخ</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">نوع المعاملة</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">البيان</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المبلغ الكلي</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المدفوع</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الرصيد بعد</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-50"><?php echo e($t->transaction_date->format('Y-m-d')); ?></td>
                            <td class="px-4 py-3 text-[0.8rem] font-bold border-b border-slate-50">
                                <?php if($t->type == 'sale'): ?>
                                    <span class="text-primary-600">فاتورة مبيعات</span>
                                <?php elseif($t->type == 'payment_received'): ?>
                                    <span class="text-success-600">دفعة مستلمة</span>
                                <?php elseif($t->type == 'return_sale'): ?>
                                    <span class="text-danger-600">مرتجع مبيعات</span>
                                <?php else: ?>
                                    <span class="text-slate-600"><?php echo e($t->type); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-50"><?php echo e($t->notes ?? '-'); ?></td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-50" dir="ltr"><?php echo e($t->total_amount > 0 ? number_format($t->total_amount, 2) : '-'); ?></td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-success-600 border-b border-slate-50" dir="ltr"><?php echo e($t->paid_amount > 0 ? number_format($t->paid_amount, 2) : '-'); ?></td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-50" dir="ltr">
                                <?php echo e(number_format(abs($t->balance_after), 2)); ?>

                                <span class="text-[0.65rem] text-slate-400 font-normal"><?php echo e($t->balance_after < 0 ? '(له)' : '(عليه)'); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">لا توجد تعاملات مسجلة لهذا العميل في هذه الفترة.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <!-- Initial State (Before Selection) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-primary-50 text-primary-500 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">تقرير العميل</h3>
            <p class="text-sm text-slate-500">يرجى اختيار العميل من القائمة العلوية والضغط على "عرض التقرير" لاستعراض كشف الحساب التفصيلي.</p>
        </div>
    <?php endif; ?>

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