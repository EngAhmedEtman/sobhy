<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'إدارة المبيعات']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'إدارة المبيعات']); ?>
    <div x-data="{ 
        showDetailsModal: false, 
        details: null, 
        loading: false, 
        viewInvoice(id) { 
            this.loading = true; 
            this.showDetailsModal = true; 
            fetch('/sales/' + id, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(d => { this.details = d; this.loading = false; }); 
        } 
    }">
     <?php $__env->slot('breadcrumb', null, []); ?> المبيعات <?php $__env->endSlot(); ?>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 border border-primary-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة المبيعات</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">سجل فواتير المبيعات</p>
            </div>
        </div>
        <button @click="$dispatch('create-sale')" type="button" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 w-full sm:w-auto justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            إضافة فاتورة مبيعات
        </button>
    </div>

    <?php if(session('success')): ?>
        <div x-data="{ show: true }" x-show="show" class="mb-4 bg-success-50 border border-success-200 text-success-800 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-success-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="text-sm font-bold"><?php echo e(session('success')); ?></span>
            </div>
            <button @click="show = false" class="text-success-600 hover:text-success-800"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse whitespace-nowrap">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">رقم الفاتورة</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">التاريخ</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">اسم العميل</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المنتجات</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الإجمالي</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">ملاحظات</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <td class="px-4 py-3 text-[0.8rem] font-bold text-primary-700 border-b border-slate-100"><?php echo e($sale->invoice_number); ?></td>
                        <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-100"><?php echo e($sale->created_at->format('Y-m-d')); ?></td>
                        <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100">
                            <a href="<?php echo e(route('customers.show', $sale->customer_id)); ?>" class="hover:underline hover:text-primary-600"><?php echo e($sale->customer->name); ?></a>
                        </td>
                        <td class="px-4 py-3 border-b border-slate-100 min-w-[150px] max-w-[250px] whitespace-normal">
                            <div class="flex flex-wrap justify-center gap-1">
                                <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="bg-primary-50 text-primary-700 px-2 py-0.5 rounded text-[0.7rem] border border-primary-100"><?php echo e($item->product->name); ?> (<?php echo e(number_format($item->quantity, 0)); ?>)</span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100" dir="ltr"><?php echo e(number_format($sale->total_amount, 2)); ?></td>
                        <td class="px-4 py-3 text-[0.8rem] text-slate-500 border-b border-slate-100 max-w-[150px] truncate"><?php echo e($sale->notes ?? '-'); ?></td>
                        <td class="px-4 py-3 border-b border-slate-100">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="viewInvoice(<?php echo e($sale->id); ?>)" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 hover:bg-primary-50 shadow-sm transition-all" title="عرض الفاتورة">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <!-- Edit Button -->
                                <button type="button" @click="$dispatch('edit-sale', <?php echo e($sale->id); ?>)" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-warning-600 hover:border-warning-600 hover:bg-warning-50 shadow-sm transition-all" title="تعديل الفاتورة">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <!-- Delete Button -->
                                <button type="button" x-data @click="$dispatch('open-modal', 'delete-sale-<?php echo e($sale->id); ?>')" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-danger-600 hover:border-danger-600 hover:bg-danger-50 shadow-sm transition-all" title="حذف الفاتورة">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                                <?php if (isset($component)) { $__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-modal','data' => ['name' => 'delete-sale-'.e($sale->id).'','action' => ''.e(route('sales.destroy', $sale->id)).'','title' => 'حذف فاتورة مبيعات رقم '.e($sale->invoice_number).'','message' => 'سيتم التراجع عن خصم الكميات من المخزن وإلغاء المديونية المتعلقة بها.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'delete-sale-'.e($sale->id).'','action' => ''.e(route('sales.destroy', $sale->id)).'','title' => 'حذف فاتورة مبيعات رقم '.e($sale->invoice_number).'','message' => 'سيتم التراجع عن خصم الكميات من المخزن وإلغاء المديونية المتعلقة بها.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd)): ?>
<?php $attributes = $__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd; ?>
<?php unset($__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd)): ?>
<?php $component = $__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd; ?>
<?php unset($__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd); ?>
<?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">لا توجد فواتير مسجلة حالياً.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 px-4 py-3">
            <?php echo e($sales->links()); ?>

        </div>
    </div>
    
    <!-- Sale Modal Component -->
    <?php if (isset($component)) { $__componentOriginalb58d02106d932813576cbe4d5ae1fafd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb58d02106d932813576cbe4d5ae1fafd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.sale-form','data' => ['products' => $products,'customers' => $customers]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.sale-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($products),'customers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($customers)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb58d02106d932813576cbe4d5ae1fafd)): ?>
<?php $attributes = $__attributesOriginalb58d02106d932813576cbe4d5ae1fafd; ?>
<?php unset($__attributesOriginalb58d02106d932813576cbe4d5ae1fafd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb58d02106d932813576cbe4d5ae1fafd)): ?>
<?php $component = $__componentOriginalb58d02106d932813576cbe4d5ae1fafd; ?>
<?php unset($__componentOriginalb58d02106d932813576cbe4d5ae1fafd); ?>
<?php endif; ?>

    <!-- View Details Modal -->
    <?php if (isset($component)) { $__componentOriginaldf21a5bd86b57b961f547a9ac815f4d9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf21a5bd86b57b961f547a9ac815f4d9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.invoice-details','data' => ['type' => 'sale']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.invoice-details'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'sale']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf21a5bd86b57b961f547a9ac815f4d9)): ?>
<?php $attributes = $__attributesOriginaldf21a5bd86b57b961f547a9ac815f4d9; ?>
<?php unset($__attributesOriginaldf21a5bd86b57b961f547a9ac815f4d9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf21a5bd86b57b961f547a9ac815f4d9)): ?>
<?php $component = $__componentOriginaldf21a5bd86b57b961f547a9ac815f4d9; ?>
<?php unset($__componentOriginaldf21a5bd86b57b961f547a9ac815f4d9); ?>
<?php endif; ?>
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