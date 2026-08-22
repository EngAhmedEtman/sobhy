<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'إدارة الموردين']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'إدارة الموردين']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> الموردين <?php $__env->endSlot(); ?>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        editData: { id: '', name: '', phone: '' },
        deleteId: '' 
    }">
        
        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 border border-primary-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة الموردين</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">الموردين المسجلين في النظام</p>
                </div>
            </div>
            <button @click="showAddModal = true" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                إضافة مورد
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
        <?php if(session('error')): ?>
            <div x-data="{ show: true }" x-show="show" class="mb-4 bg-danger-50 border border-danger-200 text-danger-800 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-sm font-bold"><?php echo e(session('error')); ?></span>
                </div>
                <button @click="show = false" class="text-danger-600 hover:text-danger-800"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
        <?php endif; ?>

        <!-- Desktop Table (hidden on small screens) -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">#</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم المورد</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رقم الهاتف</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الرصيد المتبقي له</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الرصيد المتبقي عليه</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 align-middle text-center font-medium"><?php echo e($loop->iteration); ?></td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100 align-middle text-center">
                                <a href="<?php echo e(route('suppliers.show', $supplier->id)); ?>" class="hover:underline"><?php echo e($supplier->name); ?></a>
                            </td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-100 align-middle text-center" dir="ltr"><?php echo e($supplier->phone ?? '-'); ?></td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100 align-middle text-center" dir="ltr">
                                <?php if($supplier->balance < 0): ?>
                                    <span class="text-danger-600"><?php echo e(number_format(abs($supplier->balance), 0)); ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100 align-middle text-center" dir="ltr">
                                <?php if($supplier->balance > 0): ?>
                                    <span class="text-primary-600"><?php echo e(number_format($supplier->balance, 0)); ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 border-b border-slate-100 align-middle text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('suppliers.show', $supplier->id)); ?>" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 hover:bg-primary-50 shadow-sm transition-all" title="كشف حساب المورد">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <button @click="editData = { id: '<?php echo e($supplier->id); ?>', name: '<?php echo e($supplier->name); ?>', phone: '<?php echo e($supplier->phone); ?>' }; showEditModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 hover:bg-primary-50 shadow-sm transition-all" title="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                    <button @click="deleteId = '<?php echo e($supplier->id); ?>'; showDeleteModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-danger-600 hover:border-danger-600 hover:bg-danger-50 shadow-sm transition-all" title="حذف">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">لا يوجد عملاء مسجلين حالياً.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showAddModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="<?php echo e(route('suppliers.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-lg font-bold text-slate-800">إضافة مورد جديد</h3>
                                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">اسم المورد <span class="text-danger-500">*</span></label>
                                    <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">رقم الهاتف</label>
                                    <input type="text" name="phone" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm" dir="ltr">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">الرصيد الافتتاحي (اختياري)</label>
                                    <input type="number" step="0.01" name="balance" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm" dir="ltr">
                                    <p class="text-[0.65rem] text-slate-500 mt-1">رقم موجب = الرصيد المتبقي عليه، رقم سالب = الرصيد المتبقي له.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 flex justify-end gap-2">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 text-sm font-bold transition-colors">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-bold transition-colors shadow-sm">إضافة المورد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEditModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form :action="`/suppliers/${editData.id}`" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-lg font-bold text-slate-800">تعديل بيانات المورد</h3>
                                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">اسم المورد <span class="text-danger-500">*</span></label>
                                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">رقم الهاتف</label>
                                    <input type="text" name="phone" x-model="editData.phone" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm" dir="ltr">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 flex justify-end gap-2">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 text-sm font-bold transition-colors">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-bold transition-colors shadow-sm">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true"><div class="absolute inset-0 bg-slate-900 opacity-75"></div></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showDeleteModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                    <form :action="`/suppliers/${deleteId}`" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-danger-100 sm:mx-0 sm:h-10 sm:w-10 mb-4">
                                <svg class="h-6 w-6 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">تأكيد الحذف</h3>
                            <p class="text-sm text-slate-500">هل أنت متأكد من رغبتك في حذف هذا المورد؟ لن يمكنك التراجع عن هذا الإجراء.</p>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 flex justify-end gap-2">
                            <button type="button" @click="showDeleteModal = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 text-sm font-bold transition-colors">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-danger-600 text-white rounded-lg hover:bg-danger-700 text-sm font-bold transition-colors shadow-sm">حذف المورد</button>
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