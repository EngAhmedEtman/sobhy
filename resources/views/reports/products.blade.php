<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => request('print_mode') ? 'layouts.print' : 'layouts.app'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'تقرير حركة منتج']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> التقارير والإحصائيات / تقرير حركة منتج <?php $__env->endSlot(); ?>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full print:hidden mb-6">
        <div class="flex items-start gap-3.5 text-right">
            <div class="p-2.5 bg-primary-50 text-primary-600 rounded-2xl shadow-sm shrink-0 self-center border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">تقرير حركة منتج</h1>
                <p class="text-xs lg:text-sm font-medium text-slate-500 leading-relaxed mt-0.5 hidden sm:block">
                    متابعة دقيقة لحركة المنتج من عمليات السحب والإضافة وتتبع الرصيد عبر فترة زمنية.
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

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 print:hidden">
        <form id="filterForm" action="<?php echo e(route('reports.products')); ?>" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">المنتج</label>
                <select name="product_id" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs" required>
                    <option value="">-- اختر المنتج --</option>
                    <?php $__currentLoopData = $productsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>" <?php echo e(request('product_id') == $p->id ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex-1 min-w-[130px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">العمليات</label>
                <select name="transaction_type" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
                    <option value="all" <?php echo e(request('transaction_type') == 'all' ? 'selected' : ''); ?>>الكل</option>
                    <option value="in" <?php echo e(request('transaction_type') == 'in' ? 'selected' : ''); ?>>دخول (مشتريات/إضافة)</option>
                    <option value="out" <?php echo e(request('transaction_type') == 'out' ? 'selected' : ''); ?>>سحب (مبيعات/نقص)</option>
                </select>
            </div>
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">من تاريخ</label>
                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
            </div>
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">إلى تاريخ</label>
                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
            </div>
            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                <button type="submit" class="w-full sm:w-auto px-5 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-xs font-bold transition-colors shadow-sm shadow-primary-600/20">
                    تطبيق
                </button>
            </div>
        </form>
    </div>

    <!-- Printable Header Branding -->
    <div class="hidden print:block mb-6">
        <?php if (isset($component)) { $__componentOriginal290c8099c98cf9fb9f13d9638c125a9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print.header','data' => ['title' => $product ? 'تقرير حركة منتج: ' . $product->name : 'تقرير حركة منتج','subtitle' => (request('start_date') || request('end_date')) ? 'الفترة من ' . (request('start_date') ?? 'البداية') . ' إلى ' . (request('end_date') ?? 'الآن') : 'جميع الحركات']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product ? 'تقرير حركة منتج: ' . $product->name : 'تقرير حركة منتج'),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((request('start_date') || request('end_date')) ? 'الفترة من ' . (request('start_date') ?? 'البداية') . ' إلى ' . (request('end_date') ?? 'الآن') : 'جميع الحركات')]); ?>
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

    <?php if($product): ?>

        <!-- Report Content -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 p-6 print:border-none print:shadow-none print:bg-transparent print:p-0 print:m-0">
            <div class="border-b border-slate-100 pb-4 mb-4 print:hidden">
                <h2 class="text-lg font-black text-slate-800">حركة منتج: <?php echo e($product->name); ?></h2>
                <p class="text-xs text-slate-500 mt-1">
                    <?php if(request('start_date') || request('end_date')): ?>
                        الفترة من <?php echo e(request('start_date') ?? 'البداية'); ?> إلى <?php echo e(request('end_date') ?? 'الآن'); ?>

                    <?php else: ?>
                        جميع الحركات
                    <?php endif; ?>
                </p>
            </div>

            <!-- Compact Web Metrics Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 print:hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x md:divide-x-reverse divide-slate-100">
                    <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors rounded-r-xl">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                            <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            <span class="text-[0.7rem] font-bold">إجمالي السحب (مبيعات)</span>
                        </div>
                        <span class="text-xl font-black text-slate-800" dir="ltr"><?php echo e(number_format($totalOut, 2)); ?> <span class="text-xs text-slate-400"><?php echo e($product->unit); ?></span></span>
                    </div>
                    
                    <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            <span class="text-[0.7rem] font-bold">إجمالي الدخول (مشتريات)</span>
                        </div>
                        <span class="text-xl font-black text-slate-800" dir="ltr"><?php echo e(number_format($totalIn, 2)); ?> <span class="text-xs text-slate-400"><?php echo e($product->unit); ?></span></span>
                    </div>
                    
                    <div class="p-4 flex flex-col justify-center text-center bg-primary-50 hover:bg-primary-100 transition-colors rounded-l-xl">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-primary-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            <span class="text-[0.7rem] font-bold">المخزون الحالي</span>
                        </div>
                        <p class="text-xl font-black text-primary-700" dir="ltr">
                            <?php echo e(number_format($product->stock, 2)); ?> <span class="text-xs font-normal text-primary-500"><?php echo e($product->unit); ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Print Formal Summary Table -->
            <div class="hidden print:block mb-8">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr>
                            <th>إجمالي الدخول (المشتريات)</th>
                            <th>إجمالي السحب (المبيعات)</th>
                            <th>المخزون الحالي (الرصيد)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td dir="ltr"><?php echo e(number_format($totalIn, 2)); ?> <?php echo e($product->unit); ?></td>
                            <td dir="ltr"><?php echo e(number_format($totalOut, 2)); ?> <?php echo e($product->unit); ?></td>
                            <td dir="ltr"><?php echo e(number_format($product->stock, 2)); ?> <?php echo e($product->unit); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white print:border-none print:shadow-none print:rounded-none print:bg-transparent">
                <?php if($transactions->count() > 0): ?>
                    <table class="w-full text-center border-collapse whitespace-nowrap">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">التاريخ</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">نوع الحركة</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الكمية</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الرصيد بعد</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide print:hidden">البيان</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50/60 transition-colors group">
                                    <td class="px-4 py-2.5 text-[0.8rem] text-slate-700 border-b border-slate-50 align-middle">
                                        <div class="font-semibold" dir="ltr"><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d')); ?></div>
                                        <div class="text-[0.65rem] text-slate-400" dir="ltr"><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('h:i A')); ?></div>
                                    </td>
                                    <td class="px-4 py-2.5 text-[0.8rem] text-slate-700 border-b border-slate-50 align-middle">
                                        <?php if($transaction->type == 'sale'): ?>
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[0.7rem] font-bold bg-danger-50 text-danger-600">مبيعات (سحب)</span>
                                        <?php elseif($transaction->type == 'purchase'): ?>
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[0.7rem] font-bold bg-success-50 text-success-600">مشتريات (دخول)</span>
                                        <?php elseif($transaction->type == 'adjustment_add'): ?>
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[0.7rem] font-bold bg-primary-50 text-primary-600">تسوية بالزيادة</span>
                                        <?php elseif($transaction->type == 'adjustment_sub'): ?>
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[0.7rem] font-bold bg-warning-50 text-warning-600">تسوية بالنقص</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[0.7rem] font-bold bg-slate-50 text-slate-600"><?php echo e($transaction->type); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-2.5 text-[0.8rem] font-bold text-slate-800 border-b border-slate-50 align-middle" dir="ltr">
                                        <?php echo e(number_format($transaction->quantity, 2)); ?>

                                    </td>
                                    <td class="px-4 py-2.5 text-[0.8rem] font-bold text-slate-800 border-b border-slate-50 align-middle" dir="ltr">
                                        <?php echo e(number_format($transaction->balance_after, 2)); ?>

                                    </td>
                                    <td class="px-4 py-2.5 text-[0.8rem] text-slate-600 border-b border-slate-50 align-middle print:hidden">
                                        <?php echo e($transaction->notes ?? '-'); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="p-12 text-center flex flex-col items-center justify-center bg-slate-50/50 print:bg-transparent rounded-xl">
                        <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 border border-slate-100 print:hidden">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700 mb-1">لا توجد حركات مسجلة</h3>
                        <p class="text-xs text-slate-500 max-w-sm">لم يتم العثور على أي حركات للمنتج خلال الفترة المحددة.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 p-12 text-center flex flex-col items-center justify-center print:border-none print:shadow-none print:p-0">
            <div class="w-20 h-20 bg-primary-50 rounded-full flex items-center justify-center mb-4 border border-primary-100 print:hidden">
                <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-lg font-black text-slate-800 mb-2 print:hidden">تقرير حركة منتج</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto print:hidden">
                يرجى اختيار منتج من الفلتر بالأعلى لاستعراض حركته من سحب وإضافة خلال أي فترة زمنية.
            </p>
            
            <!-- Print specific empty message -->
            <div class="hidden print:block p-8 border-2 border-dashed border-slate-300 rounded-xl text-center">
                <h3 class="text-lg font-bold text-slate-700 mb-2">لم يتم اختيار منتج</h3>
                <p class="text-sm text-slate-500">يرجى العودة للنظام واختيار منتج أولاً لعرض تفاصيل الحركة الخاصة به.</p>
            </div>
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
