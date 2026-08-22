<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'التقارير والإحصائيات']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'التقارير والإحصائيات']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> التقارير <?php $__env->endSlot(); ?>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full mb-6">
        <div class="flex items-center gap-3.5 text-right">
            <div class="p-3 bg-primary-50 text-primary-600 rounded-2xl shadow-sm shrink-0 border border-primary-100 transition-transform duration-300 hover:scale-105">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-xl sm:text-2xl font-black text-slate-800">مركز التقارير والإحصائيات</h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">تتبع نشاط النظام، المبيعات، المشتريات، والمديونيات بكل تفاصيلها.</p>
            </div>
        </div>
    </div>

    <!-- Staggered Entrance Animations Grid with Alpine.js -->
    <div x-data="{ loaded: false }" x-init="requestAnimationFrame(() => loaded = true)" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Category 1: Sales Reports -->
        <div class="group/card bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-start hover:-translate-y-1 hover:shadow-md hover:border-slate-300 transition-all duration-300 transform opacity-0 translate-y-4"
             :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition-delay: 50ms;">
            
            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                <div class="p-1.5 rounded-md bg-success-50 text-success-600 transition-transform duration-300 group-hover/card:scale-110">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">تقارير المبيعات</h3>
            </div>
            <div class="p-2 divide-y divide-slate-50">
                <a href="<?php echo e(route('reports.sales')); ?>" class="group/item flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 group-hover/item:text-success-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="font-semibold text-slate-700 text-sm group-hover/item:text-success-700 transition-colors duration-200">تقرير المبيعات الشامل</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover/item:text-success-600 group-hover/item:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <a href="<?php echo e(route('reports.customers')); ?>" class="group/item flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 group-hover/item:text-success-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="font-semibold text-slate-700 text-sm group-hover/item:text-success-700 transition-colors duration-200">كشف حساب عميل مفصل</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover/item:text-success-600 group-hover/item:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Category 2: Purchases Reports -->
        <div class="group/card bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-start hover:-translate-y-1 hover:shadow-md hover:border-slate-300 transition-all duration-300 transform opacity-0 translate-y-4"
             :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition-delay: 100ms;">
            
            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                <div class="p-1.5 rounded-md bg-warning-50 text-warning-600 transition-transform duration-300 group-hover/card:scale-110">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">تقارير المشتريات</h3>
            </div>
            <div class="p-2 divide-y divide-slate-50">
                <a href="<?php echo e(route('reports.purchases')); ?>" class="group/item flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 group-hover/item:text-warning-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="font-semibold text-slate-700 text-sm group-hover/item:text-warning-700 transition-colors duration-200">تقرير المشتريات الشامل</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover/item:text-warning-600 group-hover/item:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <a href="<?php echo e(route('reports.suppliers')); ?>" class="group/item flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 group-hover/item:text-warning-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="font-semibold text-slate-700 text-sm group-hover/item:text-warning-700 transition-colors duration-200">كشف حساب مورد مفصل</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover/item:text-warning-600 group-hover/item:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Category: Product Reports -->
        <div class="group/card bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-start hover:-translate-y-1 hover:shadow-md hover:border-slate-300 transition-all duration-300 transform opacity-0 translate-y-4"
             :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition-delay: 125ms;">
            
            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                <div class="p-1.5 rounded-md bg-indigo-50 text-indigo-600 transition-transform duration-300 group-hover/card:scale-110">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">تقارير المنتجات</h3>
            </div>
            <div class="p-2 divide-y divide-slate-50">
                <a href="<?php echo e(route('reports.products')); ?>" class="group/item flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span class="font-semibold text-slate-700 text-sm group-hover/item:text-indigo-700 transition-colors duration-200">تقرير حركة منتج</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover/item:text-indigo-600 group-hover/item:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Category 3: Debts & Financials -->
        <div class="group/card bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-start hover:-translate-y-1 hover:shadow-md hover:border-slate-300 transition-all duration-300 transform opacity-0 translate-y-4"
             :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition-delay: 150ms;">
            
            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
                <div class="p-1.5 rounded-md bg-danger-50 text-danger-600 transition-transform duration-300 group-hover/card:scale-110">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">المديونيات والماليات</h3>
            </div>
            <div class="p-2 divide-y divide-slate-50">
                <a href="<?php echo e(route('reports.debts')); ?>" class="group/item flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 group-hover/item:text-danger-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="font-semibold text-slate-700 text-sm group-hover/item:text-danger-700 transition-colors duration-200">تقرير المديونيات العامة</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover/item:text-danger-600 group-hover/item:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <a href="<?php echo e(route('reports.profit')); ?>" class="group/item flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 group-hover/item:text-danger-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold text-slate-700 text-sm group-hover/item:text-danger-700 transition-colors duration-200">تقرير الأرباح الشامل</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover/item:text-danger-600 group-hover/item:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
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
