<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'فريق العمل والمستخدمين']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'فريق العمل والمستخدمين']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> فريق العمل <?php $__env->endSlot(); ?>

    <div x-data="{ showAddModal: false, showEditModal: false, showDeleteModal: false, editData: {}, deleteId: '' }">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black text-slate-800">إدارة المستخدمين</h2>
            <button @click="showAddModal = true" class="px-4 py-2.5 bg-primary-600 text-white font-bold rounded-lg shadow-sm hover:bg-primary-700 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>مستخدم جديد</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الاسم</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">البريد الإلكتروني</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الدور (Role)</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100 align-middle text-center"><?php echo e($user->name); ?></td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 align-middle text-center"><?php echo e($user->email); ?></td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 align-middle text-center">
                                <?php if($user->role): ?>
                                    <span class="bg-primary-50 text-primary-600 px-2.5 py-1 rounded-md text-xs font-bold"><?php echo e($user->role->name); ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs">بدون دور</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 border-b border-slate-100 align-middle text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="editData = { id: '<?php echo e($user->id); ?>', name: '<?php echo e($user->name); ?>', email: '<?php echo e($user->email); ?>', role_id: '<?php echo e($user->role_id); ?>' }; showEditModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 hover:bg-primary-50 shadow-sm transition-all"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                    <?php if(auth()->id() !== $user->id): ?>
                                    <button @click="deleteId = '<?php echo e($user->id); ?>'; showDeleteModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-danger-600 hover:border-danger-600 hover:bg-danger-50 shadow-sm transition-all"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="px-4 py-8 text-sm text-slate-500 text-center">لا يوجد مستخدمين مسجلين غيرك.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showAddModal = false"></div>
                <div x-show="showAddModal" x-transition class="relative w-full max-w-md p-5 overflow-hidden text-right transition-all transform bg-white shadow-xl rounded-2xl">
                    <h3 class="text-lg font-bold text-slate-800 mb-5 border-b pb-3 border-slate-100">مستخدم جديد</h3>
                    <form action="<?php echo e(route('users.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-4">
                            <div><label class="block text-sm font-medium text-slate-700 mb-1">الاسم</label><input type="text" name="name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50"></div>
                            <div><label class="block text-sm font-medium text-slate-700 mb-1">البريد الإلكتروني</label><input type="email" name="email" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-left" dir="ltr"></div>
                            <div><label class="block text-sm font-medium text-slate-700 mb-1">كلمة المرور</label><input type="password" name="password" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-left" dir="ltr"></div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">الدور (الصلاحية)</label>
                                <select name="role_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50">
                                    <option value="">-- بدون دور --</option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700">حفظ</button>
                            <button type="button" @click="showAddModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showEditModal = false"></div>
                <div x-show="showEditModal" x-transition class="relative w-full max-w-md p-5 overflow-hidden text-right transition-all transform bg-white shadow-xl rounded-2xl">
                    <h3 class="text-lg font-bold text-slate-800 mb-5 border-b pb-3 border-slate-100">تعديل بيانات المستخدم</h3>
                    <form :action="'<?php echo e(url('users')); ?>/' + editData.id" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="space-y-4">
                            <div><label class="block text-sm font-medium text-slate-700 mb-1">الاسم</label><input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50"></div>
                            <div><label class="block text-sm font-medium text-slate-700 mb-1">البريد الإلكتروني</label><input type="email" name="email" x-model="editData.email" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-left" dir="ltr"></div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">كلمة المرور (اختياري)</label>
                                <input type="password" name="password" placeholder="اتركه فارغاً إذا لم ترغب بتغييره" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-left" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">الدور (الصلاحية)</label>
                                <select name="role_id" x-model="editData.role_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50">
                                    <option value="">-- بدون دور --</option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700">تعديل</button>
                            <button type="button" @click="showEditModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showDeleteModal = false"></div>
                <div x-show="showDeleteModal" x-transition class="relative w-full max-w-sm p-5 overflow-hidden transition-all transform bg-white shadow-xl rounded-2xl text-center">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">تأكيد الحذف</h3>
                    <p class="text-sm text-slate-500 mb-6">هل أنت متأكد من حذف هذا المستخدم؟</p>
                    <form :action="'<?php echo e(url('users')); ?>/' + deleteId" method="POST">
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
