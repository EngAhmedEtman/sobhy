<x-layouts.app title="فريق العمل والمستخدمين">
    <x-slot:breadcrumb>فريق العمل</x-slot:breadcrumb>

    <div x-data="{ showAddModal: false, showEditModal: false, showDeleteModal: false, editData: {}, deleteId: '' }">
        
        <!-- Header Section -->
        <div class="flex flex-col gap-3 w-full print:hidden mb-6">
            <div class="flex items-center justify-between gap-3 text-right flex-wrap lg:flex-nowrap">
                <div class="flex flex-wrap items-center gap-2 min-w-0">
                    <div class="p-2 bg-primary-50 text-primary-600 rounded-xl border border-primary-100 shadow-sm shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <h1 class="text-lg font-black text-slate-800 tracking-tight shrink-0">إدارة المستخدمين وفريق العمل</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[0.68rem] font-bold bg-primary-50 text-primary-700 border border-primary-200 shrink-0">
                        {{ $users->count() }} مستخدمين
                    </span>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('roles.index') }}" 
                       class="px-5 py-2.5 bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 font-bold rounded-xl text-sm transition-colors shadow-sm inline-flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>الأدوار والصلاحيات</span>
                    </a>

                    <button @click="showAddModal = true" class="px-5 py-2.5 bg-primary-600 text-white font-bold rounded-xl shadow-sm shadow-primary-600/20 hover:bg-primary-700 transition flex items-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>مستخدم جديد</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white shadow-sm mb-6">
            <table class="w-full text-center border-collapse whitespace-nowrap">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الاسم</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">البريد الإلكتروني</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الدور والصلاحية</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-800 border-b border-slate-50 align-middle text-center">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-50 align-middle text-center" dir="ltr">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-50 align-middle text-center">
                            @if($user->role)
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-primary-50 text-primary-700 border border-primary-200">
                                    {{ $user->role->name }}
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-slate-100 text-slate-500">
                                    بدون دور
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border-b border-slate-50 align-middle text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="editData = { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', role_id: '{{ $user->role_id }}' }; showEditModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-600 hover:bg-primary-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                @if(auth()->id() !== $user->id)
                                <button @click="deleteId = '{{ $user->id }}'; showDeleteModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-danger-600 hover:border-danger-600 hover:bg-danger-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-8 text-sm text-slate-500 text-center">لا يوجد مستخدمين مسجلين.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Add Modal -->
        <div x-show="showAddModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 min-h-screen">
            
            <div x-show="showAddModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.away="showAddModal = false"
                 class="relative w-full max-w-md p-6 overflow-hidden text-right transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-base font-bold text-slate-800">إضافة مستخدم جديد</h3>
                    <button type="button" @click="showAddModal = false" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">الاسم</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none text-left" dir="ltr" placeholder="name@company.com">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">كلمة المرور</label>
                            <input type="password" name="password" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none text-left" dir="ltr" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">الدور (الصلاحيات المخصصة)</label>
                            <select name="role_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none">
                                <option value="">-- بدون دور --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }} ({{ is_array($role->permissions) ? count($role->permissions) : 0 }} صلاحية)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-2.5 justify-end">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 text-xs font-bold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
                        <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700 shadow-sm">حفظ المستخدم</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 min-h-screen">
            
            <div x-show="showEditModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.away="showEditModal = false"
                 class="relative w-full max-w-md p-6 overflow-hidden text-right transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-base font-bold text-slate-800">تعديل بيانات المستخدم</h3>
                    <button type="button" @click="showEditModal = false" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form :action="`{{ url('users') }}/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">الاسم</label>
                            <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني</label>
                            <input type="email" name="email" x-model="editData.email" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none text-left" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">كلمة المرور (اتركه فارغاً إذا لم ترغب بتغييره)</label>
                            <input type="password" name="password" placeholder="••••••••" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none text-left" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">الدور (الصلاحيات المخصصة)</label>
                            <select name="role_id" x-model="editData.role_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none">
                                <option value="">-- بدون دور --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }} ({{ is_array($role->permissions) ? count($role->permissions) : 0 }} صلاحية)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-2.5 justify-end">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 text-xs font-bold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
                        <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700 shadow-sm">حفظ التعديلات</button>
                    </div>
                </form>
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
                
                <form :action="`{{ url('users') }}/${deleteId}`" method="POST">
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
                        هل أنت متأكد من رغبتك في حذف هذا المستخدم؟ لن يمكنك التراجع عن هذا الإجراء.
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
