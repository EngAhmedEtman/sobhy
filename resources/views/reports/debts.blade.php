<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => request('print_mode') ? 'layouts.print' : 'layouts.app'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'تقرير المديونيات العامة']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> التقارير والإحصائيات / تقرير المديونيات العامة <?php $__env->endSlot(); ?>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full print:hidden mb-6">
        <div class="flex items-start gap-3.5 text-right">
            <div class="p-2.5 bg-danger-50 text-danger-600 rounded-2xl shadow-sm shrink-0 self-center border border-danger-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">تقرير المديونيات العامة</h1>
                <p class="text-xs lg:text-sm font-medium text-slate-500 leading-relaxed mt-0.5 hidden sm:block">
                    تقرير شامل بكافة الأرصدة المتبقية على العملاء (لك) والمتبقية للموردين (عليك).
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
        <form id="filterForm" action="<?php echo e(route('reports.debts')); ?>" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">نوع التقرير</label>
                <select name="report_type" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
                    <option value="all" <?php echo e(request('report_type') == 'all' ? 'selected' : ''); ?>>التقرير الشامل (إجمالي + عملاء + موردين)</option>
                    <option value="summary_only" <?php echo e(request('report_type') == 'summary_only' ? 'selected' : ''); ?>>إجمالي الأموال والديون فقط</option>
                    <option value="customers_only" <?php echo e(request('report_type') == 'customers_only' ? 'selected' : ''); ?>>ديون العملاء فقط (الأموال التي لك)</option>
                    <option value="suppliers_only" <?php echo e(request('report_type') == 'suppliers_only' ? 'selected' : ''); ?>>ديون الموردين فقط (الالتزامات التي عليك)</option>
                </select>
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
        <?php
            $reportTitle = 'تقرير المديونيات العامة';
            $reportSubtitle = 'يشمل ديون العملاء وديون الموردين';
            
            if(request('report_type') == 'summary_only') {
                $reportSubtitle = 'ملخص إجمالي الأموال فقط';
            } elseif(request('report_type') == 'customers_only') {
                $reportTitle = 'تقرير ديون العملاء';
                $reportSubtitle = 'الأموال المستحقة لك بالأسواق';
            } elseif(request('report_type') == 'suppliers_only') {
                $reportTitle = 'تقرير ديون الموردين';
                $reportSubtitle = 'الالتزامات المستحقة عليك للتجار والموردين';
            }
        ?>
        <?php if (isset($component)) { $__componentOriginal290c8099c98cf9fb9f13d9638c125a9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal290c8099c98cf9fb9f13d9638c125a9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print.header','data' => ['title' => $reportTitle,'subtitle' => $reportSubtitle]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reportTitle),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reportSubtitle)]); ?>
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
        
        <?php
            $reportType = request('report_type', 'all');
            $showSummary = in_array($reportType, ['all', 'summary_only']);
            $showCustomers = in_array($reportType, ['all', 'customers_only']);
            $showSuppliers = in_array($reportType, ['all', 'suppliers_only']);
        ?>

        <?php if($showSummary): ?>
        <!-- Summary Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="flex items-center gap-4 p-4 rounded-xl border border-success-100 bg-success-50">
                <div class="w-12 h-12 rounded-full bg-success-100 flex items-center justify-center text-success-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-success-600 mb-1">إجمالي أموالك بالسوق (مديونيات العملاء)</p>
                    <p class="text-xl font-black text-success-700" dir="ltr"><?php echo e(number_format($totalCustomersDebt, 2)); ?> EGP</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4 p-4 rounded-xl border border-danger-100 bg-danger-50">
                <div class="w-12 h-12 rounded-full bg-danger-100 flex items-center justify-center text-danger-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-danger-600 mb-1">إجمالي الالتزامات عليك (ديون الموردين/التجار)</p>
                    <p class="text-xl font-black text-danger-700" dir="ltr"><?php echo e(number_format($totalSuppliersDebt, 2)); ?> EGP</p>
                </div>
            </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($showCustomers || $showSuppliers): ?>
        <div class="grid grid-cols-1 <?php echo e(($showCustomers && $showSuppliers) ? 'lg:grid-cols-2' : ''); ?> gap-8 lg:gap-6">
            <?php if($showCustomers): ?>
            <!-- Customers Debts List -->
            <div>
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success-500"></span>
                    العملاء المديونين (عليك تحصيلها)
                </h3>
                <?php if($customers->count() > 0): ?>
                    <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white print:border-none print:shadow-none print:rounded-none print:bg-transparent">
                        <table class="w-full text-center border-collapse whitespace-nowrap">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">العميل</th>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">رقم الهاتف</th>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المبلغ المستحق</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors group">
                                        <td class="px-4 py-2.5 text-[0.8rem] text-slate-800 font-bold border-b border-slate-50 align-middle">
                                            <?php echo e($c->name); ?>

                                        </td>
                                        <td class="px-4 py-2.5 text-[0.8rem] text-slate-500 border-b border-slate-50 align-middle" dir="ltr">
                                            <?php echo e($c->phone ?? '-'); ?>

                                        </td>
                                        <td class="px-4 py-2.5 text-[0.8rem] font-black text-success-600 border-b border-slate-50 align-middle" dir="ltr">
                                            <?php echo e(number_format($c->balance, 2)); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-8 text-center bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500">لا يوجد ديون مستحقة على العملاء حالياً.</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if($showSuppliers): ?>
            <!-- Suppliers Debts List -->
            <div>
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-danger-500"></span>
                    الموردين / التجار (عليك سدادها)
                </h3>
                <?php if($suppliers->count() > 0): ?>
                    <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white print:border-none print:shadow-none print:rounded-none print:bg-transparent">
                        <table class="w-full text-center border-collapse whitespace-nowrap">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المورد/التاجر</th>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">رقم الهاتف</th>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المبلغ المطلوب</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-slate-50/60 transition-colors group">
                                        <td class="px-4 py-2.5 text-[0.8rem] text-slate-800 font-bold border-b border-slate-50 align-middle">
                                            <?php echo e($s->name); ?>

                                        </td>
                                        <td class="px-4 py-2.5 text-[0.8rem] text-slate-500 border-b border-slate-50 align-middle" dir="ltr">
                                            <?php echo e($s->phone ?? '-'); ?>

                                        </td>
                                        <td class="px-4 py-2.5 text-[0.8rem] font-black text-danger-600 border-b border-slate-50 align-middle" dir="ltr">
                                            <?php echo e(number_format($s->balance, 2)); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-8 text-center bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500">لا يوجد التزامات عليك للموردين حالياً.</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

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
