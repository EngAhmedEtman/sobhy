<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'المنتجات والمخزون']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'المنتجات والمخزون']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> المنتجات والمخزون <?php $__env->endSlot(); ?>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        editData: { id: '', name: '', notes: '', hasOpening: false },
        deleteId: '' 
    }">
        
        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة المنتجات والمخزون</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">أنواع الخردة المتاحة في النظام</p>
                </div>
            </div>
            <button @click="showAddModal = true" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                إضافة منتج جديد
            </button>
        </div>

        <!-- Mobile Cards (visible on small screens only) -->
        <div class="sm:hidden space-y-3 mb-6">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <div class="flex justify-between items-start mb-3">
                    <a href="<?php echo e(route('products.show', $product->id)); ?>" class="text-base font-bold text-primary-700 hover:underline"><?php echo e($product->name); ?></a>
                    <div class="flex items-center gap-1.5">
                        <a href="<?php echo e(route('products.show', $product->id)); ?>" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 shadow-sm transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </a>
                        <button @click="editData = { id: '<?php echo e($product->id); ?>', name: '<?php echo e($product->name); ?>', notes: '<?php echo e($product->notes); ?>', hasOpening: <?php echo e($product->transactions_count > 0 ? 'true' : 'false'); ?> }; showEditModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 shadow-sm transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </button>
                        <button @click="deleteId = '<?php echo e($product->id); ?>'; showDeleteModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-danger-600 hover:border-danger-600 shadow-sm transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">الرصيد:</span>
                    <span class="font-black text-lg <?php echo e($product->stock < 0 ? 'text-danger-600' : 'text-primary-600'); ?>" dir="ltr"><?php echo e(number_format($product->stock, 2)); ?> <span class="text-xs text-slate-400 font-normal">كيلو</span></span>
                </div>
                <?php if($product->notes): ?>
                <p class="text-xs text-slate-400 mt-2 truncate"><?php echo e($product->notes); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">
                لا يوجد منتجات مضافة حتى الآن.
            </div>
            <?php endif; ?>
        </div>

        <!-- Desktop Table (hidden on small screens) -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">#</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم المنتج</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الرصيد المتبقي</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">ملاحظات</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 align-middle text-center font-medium"><?php echo e($loop->iteration); ?></td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100 align-middle text-center">
                                <a href="<?php echo e(route('products.show', $product->id)); ?>" class="hover:underline"><?php echo e($product->name); ?></a>
                            </td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100 align-middle text-center" dir="ltr">
                                <?php echo e(number_format($product->stock, 2)); ?> <span class="text-xs text-slate-500 font-normal">كيلو</span>
                            </td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-100 align-middle text-center"><?php echo e($product->notes ?? '-'); ?></td>
                            <td class="px-4 py-3 border-b border-slate-100 align-middle text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('products.show', $product->id)); ?>" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 hover:bg-primary-50 shadow-sm transition-all" title="سجل حركات المنتج">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <button @click="editData = { id: '<?php echo e($product->id); ?>', name: '<?php echo e($product->name); ?>', notes: '<?php echo e($product->notes); ?>', hasOpening: <?php echo e($product->transactions_count > 0 ? 'true' : 'false'); ?> }; showEditModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 hover:bg-primary-50 shadow-sm transition-all" title="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                    <button @click="deleteId = '<?php echo e($product->id); ?>'; showDeleteModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-danger-600 hover:border-danger-600 hover:bg-danger-50 shadow-sm transition-all" title="حذف">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-sm text-slate-500 text-center">
                                لا يوجد منتجات مضافة حتى الآن.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showAddModal = false"></div>
                <div x-show="showAddModal" x-transition class="relative w-full max-w-md p-5 sm:p-6 overflow-hidden text-right transition-all transform bg-white shadow-xl rounded-2xl">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">إضافة منتج جديد</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    <form action="<?php echo e(route('products.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">اسم المنتج</label>
                                <input type="text" name="name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">رصيد افتتاحي (اختياري)</label>
                                <div class="relative">
                                    <input type="number" name="stock" step="0.01" placeholder="مثال: 50" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-left text-base" dir="ltr">
                                    <span class="absolute inset-y-0 right-3 flex items-center text-slate-400 text-sm">كيلو</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">ملاحظات (اختياري)</label>
                                <input type="text" name="notes" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700">حفظ</button>
                            <button type="button" @click="showAddModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showEditModal = false"></div>
                <div x-show="showEditModal" x-transition class="relative w-full max-w-md p-5 sm:p-6 overflow-hidden text-right transition-all transform bg-white shadow-xl rounded-2xl">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">تعديل المنتج</h3>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    <form :action="'<?php echo e(url('products')); ?>/' + editData.id" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">اسم المنتج</label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                            </div>
                            <div x-show="!editData.hasOpening" x-cloak>
                                <label class="block text-sm font-medium text-slate-700 mb-1">رصيد افتتاحي</label>
                                <div class="relative">
                                    <input type="number" name="stock" step="0.01" placeholder="مثال: 50" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-left text-base" dir="ltr">
                                    <span class="absolute inset-y-0 right-3 flex items-center text-slate-400 text-sm">كيلو</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">ملاحظات</label>
                                <input type="text" name="notes" x-model="editData.notes" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700">تعديل</button>
                            <button type="button" @click="showEditModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showDeleteModal = false"></div>
                <div x-show="showDeleteModal" x-transition class="relative w-full max-w-sm p-5 sm:p-6 overflow-hidden transition-all transform bg-white shadow-xl rounded-2xl text-center">
                    <div class="w-14 h-14 rounded-full bg-danger-50 text-danger-600 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">تأكيد الحذف</h3>
                    <p class="text-sm text-slate-500 mb-6">هل أنت متأكد من رغبتك في حذف هذا المنتج وجميع حركاته؟</p>
                    <form :action="'<?php echo e(url('products')); ?>/' + deleteId" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div class="flex gap-3">
                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-danger-600 rounded-lg hover:bg-danger-700">نعم، احذف</button>
                            <button type="button" @click="showDeleteModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
                        </div>
                    </form>
                </div>
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
