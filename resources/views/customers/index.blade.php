<x-layouts.app title="إدارة العملاء">
    <x-slot name="breadcrumb">العملاء</x-slot>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        search: '',
        editData: { id: '', name: '', phone: '' },
        deleteId: '' 
    }">
        
        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 border border-primary-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة العملاء</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">العملاء المسجلين في النظام</p>
                </div>
            </div>
            <button @click="showAddModal = true" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                إضافة عميل
            </button>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 bg-success-50 border border-success-200 text-success-800 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-success-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-success-600 hover:text-success-800"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 bg-danger-50 border border-danger-200 text-danger-800 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-danger-600 hover:text-danger-800"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
        @endif

        <!-- Mobile Cards (visible on small screens only) -->
        <div class="sm:hidden space-y-3 mb-6">
            @forelse($customers as $customer)
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <a href="{{ route('customers.show', $customer->id) }}" class="text-base font-bold text-primary-700 hover:underline">{{ $customer->name }}</a>
                        @if($customer->phone)
                            <p class="text-xs text-slate-500 font-mono mt-0.5" dir="ltr">{{ $customer->phone }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('customers.show', $customer->id) }}" class="p-1.5 rounded border border-slate-200 bg-white text-emerald-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 shadow-sm transition-all" title="كشف حساب العميل">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </a>
                        <button @click="editData = { id: '{{ $customer->id }}', name: '{{ addslashes($customer->name) }}', phone: '{{ $customer->phone }}' }; showEditModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-blue-600 hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all" title="تعديل">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button @click="deleteId = '{{ $customer->id }}'; showDeleteModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-danger-600 hover:text-danger-700 hover:border-danger-300 hover:bg-danger-50 shadow-sm transition-all" title="حذف">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between items-center text-sm border-t border-slate-50 pt-2">
                    <span class="text-slate-500">حالة الرصيد:</span>
                    @if($customer->balance > 0)
                        <span class="font-bold text-danger-600" dir="ltr">{{ number_format($customer->balance, 0) }} <span class="text-xs font-bold text-danger-700">ج.م (مطلوب منه)</span></span>
                    @elseif($customer->balance < 0)
                        <span class="font-bold text-emerald-600" dir="ltr">{{ number_format(abs($customer->balance), 0) }} <span class="text-xs font-bold text-emerald-700">ج.م (رصيد للعميل)</span></span>
                    @else
                        <span class="font-bold text-slate-500">خالص (لا يوجد مديونية)</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">
                لا يوجد عملاء مسجلين حالياً.
            </div>
            @endforelse
        </div>

        <!-- Desktop Table (hidden on small screens) -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">#</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم العميل</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رقم الهاتف</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">مطلوب منه (لنا عنده)</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رصيد له (دافع زيادة)</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 align-middle text-center font-medium">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100 align-middle text-center">
                                <a href="{{ route('customers.show', $customer->id) }}" class="hover:underline">{{ $customer->name }}</a>
                            </td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-100 align-middle text-center" dir="ltr">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100 align-middle text-center" dir="ltr">
                                @if($customer->balance > 0)
                                    <span class="text-danger-600 font-bold">{{ number_format($customer->balance, 0) }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100 align-middle text-center" dir="ltr">
                                @if($customer->balance < 0)
                                    <span class="text-emerald-600 font-bold">{{ number_format(abs($customer->balance), 0) }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 border-b border-slate-100 align-middle text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('customers.show', $customer->id) }}" class="p-1.5 rounded border border-slate-200 bg-white text-emerald-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 shadow-sm transition-all" title="كشف حساب العميل">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <button @click="editData = { id: '{{ $customer->id }}', name: '{{ addslashes($customer->name) }}', phone: '{{ $customer->phone }}' }; showEditModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-blue-600 hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all" title="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button @click="deleteId = '{{ $customer->id }}'; showDeleteModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-danger-600 hover:text-danger-700 hover:border-danger-300 hover:bg-danger-50 shadow-sm transition-all" title="حذف">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">لا يوجد عملاء مسجلين حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($customers->hasPages())
            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        @endif

        <!-- Add Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showAddModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-lg font-bold text-slate-800">إضافة عميل جديد</h3>
                                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">اسم العميل <span class="text-danger-500">*</span></label>
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
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-bold transition-colors shadow-sm">إضافة العميل</button>
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
                    <form :action="`/customers/${editData.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-lg font-bold text-slate-800">تعديل بيانات العميل</h3>
                                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">اسم العميل <span class="text-danger-500">*</span></label>
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
                
                <form :action="`/customers/${deleteId}`" method="POST">
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
                        هل أنت متأكد من رغبتك في حذف هذا العميل؟ لن يمكنك التراجع عن هذا الإجراء.
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
