<x-layouts.app title="إدارة الأدوار والصلاحيات">
    <x-slot:breadcrumb>الصلاحيات</x-slot:breadcrumb>

    <div x-data="{ 
        showAddDrawer: false, 
        showEditDrawer: false, 
        showViewModal: false,
        showDeleteModal: false, 
        selectedRole: null,
        editData: { id: '', name: '', permissions: [] }, 
        deleteId: '',
        allFlatPermissions: {{ json_encode(\App\Models\Role::flatPermissions()) }},
        openViewModal(role) {
            this.selectedRole = role;
            this.showViewModal = true;
        },
        toggleGroup(groupKeys, formType) {
            // Helper for select all in group
        }
    }">
        
        <!-- Header Actions -->
        <div class="flex flex-col gap-3 w-full print:hidden mb-6">
            <div class="flex items-center justify-between gap-3 text-right flex-wrap lg:flex-nowrap">
                <div class="flex flex-wrap items-center gap-2 min-w-0">
                    <!-- Icon Box -->
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-xl border border-amber-100/90 shadow-sm shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>

                    <h1 class="text-lg font-black text-slate-800 tracking-tight shrink-0">إدارة الأدوار والصلاحيات</h1>

                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[0.68rem] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">
                        {{ $roles->count() }} أدوار مسجلة
                    </span>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('users.index') }}" 
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

        @php
            $permissionGroups = \App\Models\Role::allPermissions();
            $flatPermissions = \App\Models\Role::flatPermissions();
        @endphp

        <!-- Roles Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
            @foreach($roles as $role)
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden group">
                    <!-- Top Accent Header -->
                    <div class="absolute top-0 right-0 left-0 h-1 bg-gradient-to-l from-primary-600 to-sky-400"></div>

                    <div>
                        <!-- Title & Badge -->
                        <div class="flex items-start justify-between gap-3 mb-2 pt-1">
                            <h3 class="text-base font-black text-slate-800 tracking-tight">{{ $role->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[0.7rem] font-bold bg-primary-50 text-primary-700 border border-primary-200">
                                {{ is_array($role->permissions) ? count($role->permissions) : 0 }} صلاحية
                            </span>
                        </div>

                        <!-- Stats Indicators -->
                        <div class="flex items-center gap-3 py-2 border-b border-slate-100 text-xs mb-3">
                            <div class="flex items-center gap-1.5 text-slate-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span class="font-bold text-slate-800">{{ $role->users_count }}</span>
                                <span class="text-slate-500">مستخدم مرتبط</span>
                            </div>
                        </div>

                        <!-- Permissions Preview Badges in Arabic -->
                        <div class="mb-4">
                            <p class="text-[0.7rem] font-bold text-slate-400 mb-2">أبرز الصلاحيات الممنوحة:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @php
                                    $rolePerms = $role->permissions ?? [];
                                    $previewPerms = array_slice($rolePerms, 0, 4);
                                    $remainingCount = count($rolePerms) - count($previewPerms);
                                @endphp

                                @forelse($previewPerms as $permKey)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[0.68rem] font-semibold bg-slate-50 text-slate-700 border border-slate-200">
                                        {{ $flatPermissions[$permKey] ?? $permKey }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400 italic">لا توجد صلاحيات مخصصة</span>
                                @endforelse

                                @if($remainingCount > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[0.68rem] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        + {{ $remainingCount }} صلاحيات أخرى
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5">
                            <!-- View Modal Trigger -->
                            <button type="button" 
                                    @click="openViewModal({{ json_encode([
                                        'id' => $role->id,
                                        'name' => $role->name,
                                        'permissions' => $role->permissions ?? [],
                                        'users_count' => $role->users_count
                                    ]) }})"
                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <span>عرض</span>
                            </button>

                            <!-- Edit Drawer Trigger -->
                            <button type="button" 
                                    @click="editData = { id: '{{ $role->id }}', name: '{{ $role->name }}', permissions: {{ json_encode($role->permissions ?? []) }} }; showEditDrawer = true"
                                    class="px-2.5 py-1.5 bg-primary-50 hover:bg-primary-100 border border-primary-200 text-primary-700 text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>تعديل</span>
                            </button>
                        </div>

                        @if($role->users_count == 0)
                            <button type="button" 
                                    @click="deleteId = '{{ $role->id }}'; showDeleteModal = true"
                                    class="p-1.5 rounded border border-slate-200 text-slate-400 hover:text-danger-600 hover:border-danger-200 hover:bg-danger-50 transition-colors"
                                    title="حذف الدور">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View Role Permissions Modal -->
        <div x-show="showViewModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 min-h-screen">
            
            <div x-show="showViewModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.away="showViewModal = false"
                 class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-slate-100 p-6 overflow-hidden text-right">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-800" x-text="selectedRole ? 'صلاحيات دور: ' + selectedRole.name : 'عرض الصلاحيات'"></h3>
                            <p class="text-xs text-slate-400 font-medium" x-text="selectedRole ? (selectedRole.permissions ? selectedRole.permissions.length : 0) + ' صلاحيات مفعلة' : ''"></p>
                        </div>
                    </div>
                    <button type="button" @click="showViewModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="py-4 max-h-[60vh] overflow-y-auto space-y-4">
                    @foreach($permissionGroups as $groupName => $permissions)
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100"
                             x-show="selectedRole && selectedRole.permissions && selectedRole.permissions.some(p => {{ json_encode(array_keys($permissions)) }}.includes(p))">
                            <h4 class="text-xs font-black text-slate-800 mb-2.5 border-b border-slate-200/80 pb-1.5 flex items-center justify-between">
                                <span>{{ $groupName }}</span>
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($permissions as $key => $label)
                                    <template x-if="selectedRole && selectedRole.permissions && selectedRole.permissions.includes('{{ $key }}')">
                                        <div class="flex items-center gap-2 p-2 rounded-lg bg-white border border-emerald-100 text-xs font-bold text-emerald-800 shadow-2.5">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                            <span>{{ $label }}</span>
                                        </div>
                                    </template>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end">
                    <button type="button" @click="showViewModal = false" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>

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
                         class="pointer-events-auto w-screen max-w-lg">
                        
                        <div class="flex h-full flex-col bg-white shadow-xl">
                            <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                <h2 class="text-base font-black text-slate-800" id="slide-over-title">إضافة دور جديد</h2>
                                <button type="button" @click="showAddDrawer = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            
                            <div class="relative flex-1 px-4 py-5 sm:px-6 overflow-y-auto">
                                <form action="{{ route('roles.store') }}" method="POST" id="addRoleForm">
                                    @csrf
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم الدور</label>
                                            <input type="text" name="name" required placeholder="مثال: محاسب عام، أمين مخزن" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-xs font-medium outline-none transition-all">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-2">تحديد الصلاحيات الممنوحة</label>
                                            <div class="space-y-3">
                                                @foreach($permissionGroups as $groupName => $permissions)
                                                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                                    <h3 class="text-xs font-black text-slate-800 mb-2.5 border-b border-slate-200 pb-1.5 flex items-center justify-between">
                                                        <span>{{ $groupName }}</span>
                                                        <span class="text-[0.65rem] font-bold text-slate-400">({{ count($permissions) }} صلاحيات)</span>
                                                    </h3>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        @foreach($permissions as $key => $label)
                                                        <label class="flex items-start gap-2.5 p-2 rounded-lg bg-white border border-slate-100 hover:border-primary-200 cursor-pointer group transition-all">
                                                            <input type="checkbox" name="permissions[]" value="{{ $key }}" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600 cursor-pointer">
                                                            <span class="text-xs font-semibold text-slate-700 group-hover:text-primary-700 transition-colors">{{ $label }}</span>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="flex flex-shrink-0 justify-end gap-2.5 px-4 py-3 border-t border-slate-100 bg-slate-50">
                                <button type="button" @click="showAddDrawer = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-50">إلغاء</button>
                                <button type="submit" form="addRoleForm" class="px-5 py-2 bg-primary-600 text-white font-bold rounded-xl text-xs hover:bg-primary-700 shadow-sm shadow-primary-600/20">حفظ الدور</button>
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
                         class="pointer-events-auto w-screen max-w-lg">
                        
                        <div class="flex h-full flex-col bg-white shadow-xl">
                            <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                <h2 class="text-base font-black text-slate-800" id="slide-over-title">تعديل الدور والصلاحيات</h2>
                                <button type="button" @click="showEditDrawer = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            
                            <div class="relative flex-1 px-4 py-5 sm:px-6 overflow-y-auto">
                                <form :action="`{{ url('roles') }}/${editData.id}`" method="POST" id="editRoleForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم الدور</label>
                                            <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-xs font-medium outline-none transition-all">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-2">تحديد الصلاحيات الممنوحة</label>
                                            <div class="space-y-3">
                                                @foreach($permissionGroups as $groupName => $permissions)
                                                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                                    <h3 class="text-xs font-black text-slate-800 mb-2.5 border-b border-slate-200 pb-1.5 flex items-center justify-between">
                                                        <span>{{ $groupName }}</span>
                                                        <span class="text-[0.65rem] font-bold text-slate-400">({{ count($permissions) }} صلاحيات)</span>
                                                    </h3>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        @foreach($permissions as $key => $label)
                                                        <label class="flex items-start gap-2.5 p-2 rounded-lg bg-white border border-slate-100 hover:border-primary-200 cursor-pointer group transition-all">
                                                            <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                                                :checked="editData.permissions && editData.permissions.includes('{{ $key }}')"
                                                                class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600 cursor-pointer">
                                                            <span class="text-xs font-semibold text-slate-700 group-hover:text-primary-700 transition-colors">{{ $label }}</span>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="flex flex-shrink-0 justify-end gap-2.5 px-4 py-3 border-t border-slate-100 bg-slate-50">
                                <button type="button" @click="showEditDrawer = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-50">إلغاء</button>
                                <button type="submit" form="editRoleForm" class="px-5 py-2 bg-primary-600 text-white font-bold rounded-xl text-xs hover:bg-primary-700 shadow-sm shadow-primary-600/20">تعديل الدور</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 min-h-screen">
            
            <div x-show="showDeleteModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.away="showDeleteModal = false"
                 class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl border border-slate-100 p-6 text-center overflow-hidden">
                
                <form :action="`{{ url('roles') }}/${deleteId}`" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <!-- Centered Danger Icon -->
                    <div class="mx-auto w-12 h-12 rounded-2xl bg-danger-50 text-danger-600 flex items-center justify-center mb-4 border border-danger-100 shadow-sm">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>

                    <!-- Centered Title & Message -->
                    <h3 class="text-base sm:text-lg font-black text-slate-800 mb-1.5 text-center">تأكيد الحذف</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 text-center px-1">
                        هل أنت متأكد من رغبتك في حذف هذا الدور؟ لن يمكنك التراجع عن هذا الإجراء.
                    </p>

                    <!-- Centered Buttons Grid -->
                    <div class="grid grid-cols-2 gap-2.5">
                        <button type="button" @click="showDeleteModal = false" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" class="w-full px-4 py-2.5 bg-danger-600 hover:bg-danger-700 text-white rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-danger-500/20">
                            تأكيد الحذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
