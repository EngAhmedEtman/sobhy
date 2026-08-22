<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'إدارة الأدوار والصلاحيات']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'إدارة الأدوار والصلاحيات']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> الصلاحيات <?php $__env->endSlot(); ?>

    <div x-data="{ showAddDrawer: false, showEditDrawer: false, showDeleteModal: false, editData: { permissions: [] }, deleteId: '' }">
        
        <div class="flex flex-col gap-3 w-full print:hidden mb-6">
            <div class="flex items-center justify-between gap-3 text-right flex-wrap lg:flex-nowrap">
                <div class="flex flex-wrap items-center gap-2 min-w-0">
                    <!-- Icon Box -->
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-xl border border-amber-100/90 shadow-sm shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>

                    <h1 class="text-lg font-black text-slate-800 tracking-tight shrink-0">إدارة الأدوار والصلاحيات</h1>

                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[0.68rem] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">
                        <?php echo e($roles->count()); ?> أدوار مسجلة
                    </span>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="<?php echo e(route('users.index')); ?>" 
                       class="px-5 py-2.5 bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 font-bold rounded-xl text-sm transition-colors shadow-sm inline-flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>إدارة المستخدمين</span>
                    </a>

                    <button type="button" 
                            @click="showAddDrawer = true"
                            class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5 ml-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>إضافة دور جديد</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Roles Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden group">
                    <!-- Top Accent Header -->
                    <div class="absolute top-0 right-0 left-0 h-1 bg-gradient-to-l from-primary-600 to-sky-400"></div>

                    <div>
                        <!-- Title & Badge -->
                        <div class="flex items-start justify-between gap-3 mb-2 pt-1">
                            <h3 class="text-base font-bold text-slate-800 tracking-tight"><?php echo e($role->name); ?></h3>
                            <span class="px-2.5 py-0.5 rounded text-[0.68rem] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                <?php echo e($role->name); ?>

                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-xs text-slate-500 leading-relaxed mb-4 min-h-[36px]">
                            دور مخصص لمنح المستخدم صلاحيات محددة على أجزاء النظام.
                        </p>

                        <!-- Stats Indicators -->
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-100 text-xs">
                            <div class="flex items-center gap-1.5 text-slate-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span class="font-bold text-slate-800"><?php echo e($role->users_count); ?></span>
                                <span class="text-slate-500">مستخدم</span>
                            </div>
                            <span class="text-slate-200">•</span>
                            <div class="flex items-center gap-1.5 text-slate-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span class="font-bold text-slate-800"><?php echo e(is_array($role->permissions) ? count($role->permissions) : 0); ?></span>
                                <span class="text-slate-500">صلاحية</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <button type="button" 
                                @click="editData = { id: '<?php echo e($role->id); ?>', name: '<?php echo e($role->name); ?>', permissions: <?php echo e(json_encode($role->permissions ?? [])); ?> }; showEditDrawer = true"
                                class="px-3.5 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>تعديل الصلاحيات</span>
                        </button>

                        <?php if($role->users_count == 0): ?>
                        <button type="button" 
                                @click="deleteId = '<?php echo e($role->id); ?>'; showDeleteModal = true"
                                class="p-1.5 rounded border border-slate-200 text-slate-400 hover:text-danger-600 hover:border-danger-200 hover:bg-danger-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php
            $permissionGroups = [
                'الرئيسية والتقارير' => [
                    'dashboard.view' => 'عرض لوحة المعلومات (الرئيسية)',
                    'reports.view' => 'عرض التقارير والإحصائيات',
                    'debts.view' => 'عرض تقارير المديونيات',
                ],
                'المبيعات' => [
                    'sales.view' => 'عرض المبيعات',
                    'sales.create' => 'إضافة فاتورة مبيعات',
                    'sales.update' => 'تعديل الفواتير',
                    'sales.delete' => 'حذف الفواتير',
                ],
                'المشتريات' => [
                    'purchases.view' => 'عرض المشتريات',
                    'purchases.create' => 'إضافة فاتورة مشتريات',
                    'purchases.update' => 'تعديل الفواتير',
                    'purchases.delete' => 'حذف الفواتير',
                ],
                'المنتجات والمخزون' => [
                    'products.view' => 'عرض المنتجات',
                    'products.create' => 'إضافة منتج',
                    'products.update' => 'تعديل بيانات المنتج',
                    'products.delete' => 'حذف منتج',
                ],
                'العملاء' => [
                    'customers.view' => 'عرض العملاء',
                    'customers.create' => 'إضافة عميل',
                    'customers.update' => 'تعديل بيانات العميل',
                    'customers.delete' => 'حذف عميل',
                ],
                'الموردين' => [
                    'suppliers.view' => 'عرض الموردين',
                    'suppliers.create' => 'إضافة مورد',
                    'suppliers.update' => 'تعديل بيانات المورد',
                    'suppliers.delete' => 'حذف مورد',
                ],
                'الإدارة والمستخدمين' => [
                    'settings.manage' => 'إدارة إعدادات النظام',
                    'users.view' => 'عرض المستخدمين',
                    'users.create' => 'إضافة مستخدم',
                    'users.update' => 'تعديل مستخدم',
                    'users.delete' => 'حذف مستخدم',
                    'roles.view' => 'عرض الأدوار والصلاحيات',
                    'roles.create' => 'إضافة دور جديد',
                    'roles.update' => 'تعديل الأدوار',
                    'roles.delete' => 'حذف الأدوار',
                ],
            ];
        ?>

        <!-- Add Drawer -->
        <div x-show="showAddDrawer" x-cloak class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="showAddDrawer" x-transition.opacity class="absolute inset-0 bg-slate-900/50 transition-opacity" @click="showAddDrawer = false"></div>
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="showAddDrawer" 
                         x-transition:enter="transform transition ease-in-out duration-300"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="pointer-events-auto w-screen max-w-md">
                        
                        <div class="flex h-full flex-col bg-white shadow-xl">
                            <div class="px-4 py-6 sm:px-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                <h2 class="text-lg font-black text-slate-800" id="slide-over-title">إضافة دور جديد</h2>
                                <button type="button" @click="showAddDrawer = false" class="rounded-md bg-slate-50 text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    <span class="sr-only">إغلاق</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            
                            <div class="relative flex-1 px-4 py-6 sm:px-6 overflow-y-auto">
                                <form action="<?php echo e(route('roles.store')); ?>" method="POST" id="addRoleForm">
                                    <?php echo csrf_field(); ?>
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">اسم الدور</label>
                                            <input type="text" name="name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-white focus:ring-primary-500 focus:border-primary-500">
                                            <p class="mt-1 text-xs text-slate-500">مثال: محاسب، مدير المبيعات</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-3">الصلاحيات الممنوحة</label>
                                            <div class="space-y-4">
                                                <?php $__currentLoopData = $permissionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                                    <h3 class="text-sm font-black text-slate-800 mb-3 border-b border-slate-200 pb-2"><?php echo e($groupName); ?></h3>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <label class="flex items-start gap-3 cursor-pointer group">
                                                            <div class="flex h-5 items-center">
                                                                <input type="checkbox" name="permissions[]" value="<?php echo e($key); ?>" class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600 transition-colors cursor-pointer group-hover:border-primary-400">
                                                            </div>
                                                            <div class="text-sm">
                                                                <span class="font-semibold text-slate-700 group-hover:text-primary-700 transition-colors"><?php echo e($label); ?></span>
                                                            </div>
                                                        </label>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="flex flex-shrink-0 justify-end gap-3 px-4 py-4 border-t border-slate-100 bg-slate-50">
                                <button type="button" @click="showAddDrawer = false" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg text-sm hover:bg-slate-50">إلغاء</button>
                                <button type="submit" form="addRoleForm" class="px-6 py-2.5 bg-primary-600 text-white font-bold rounded-lg text-sm hover:bg-primary-700 shadow-sm">حفظ الدور</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Drawer -->
        <div x-show="showEditDrawer" x-cloak class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="showEditDrawer" x-transition.opacity class="absolute inset-0 bg-slate-900/50 transition-opacity" @click="showEditDrawer = false"></div>
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="showEditDrawer" 
                         x-transition:enter="transform transition ease-in-out duration-300"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="pointer-events-auto w-screen max-w-md">
                        
                        <div class="flex h-full flex-col bg-white shadow-xl">
                            <div class="px-4 py-6 sm:px-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                <h2 class="text-lg font-black text-slate-800" id="slide-over-title">تعديل الدور والصلاحيات</h2>
                                <button type="button" @click="showEditDrawer = false" class="rounded-md bg-slate-50 text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    <span class="sr-only">إغلاق</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            
                            <div class="relative flex-1 px-4 py-6 sm:px-6 overflow-y-auto">
                                <form :action="'<?php echo e(url('roles')); ?>/' + editData.id" method="POST" id="editRoleForm">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">اسم الدور</label>
                                            <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-white focus:ring-primary-500 focus:border-primary-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-3">الصلاحيات الممنوحة</label>
                                            <div class="space-y-4">
                                                <?php $__currentLoopData = $permissionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                                    <h3 class="text-sm font-black text-slate-800 mb-3 border-b border-slate-200 pb-2"><?php echo e($groupName); ?></h3>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <label class="flex items-start gap-3 cursor-pointer group">
                                                            <div class="flex h-5 items-center">
                                                                <input type="checkbox" name="permissions[]" value="<?php echo e($key); ?>" 
                                                                    x-bind:checked="editData.permissions && editData.permissions.includes('<?php echo e($key); ?>')"
                                                                    class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600 transition-colors cursor-pointer group-hover:border-primary-400">
                                                            </div>
                                                            <div class="text-sm">
                                                                <span class="font-semibold text-slate-700 group-hover:text-primary-700 transition-colors"><?php echo e($label); ?></span>
                                                            </div>
                                                        </label>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="flex flex-shrink-0 justify-end gap-3 px-4 py-4 border-t border-slate-100 bg-slate-50">
                                <button type="button" @click="showEditDrawer = false" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg text-sm hover:bg-slate-50">إلغاء</button>
                                <button type="submit" form="editRoleForm" class="px-6 py-2.5 bg-primary-600 text-white font-bold rounded-lg text-sm hover:bg-primary-700 shadow-sm">تعديل الدور</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showDeleteModal = false"></div>
                <div x-show="showDeleteModal" x-transition class="relative w-full max-w-sm p-5 overflow-hidden transition-all transform bg-white shadow-xl rounded-2xl text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-danger-100 mb-4">
                        <svg class="h-6 w-6 text-danger-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">تأكيد الحذف</h3>
                    <p class="text-sm text-slate-500 mb-6">هل أنت متأكد من حذف هذا الدور نهائياً؟ هذا الإجراء لا يمكن التراجع عنه.</p>
                    <form :action="'<?php echo e(url('roles')); ?>/' + deleteId" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div class="flex gap-3">
                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-danger-600 rounded-lg hover:bg-danger-700">نعم، احذف</button>
                            <button type="button" @click="showDeleteModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 border border-slate-200 rounded-lg">إلغاء</button>
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
