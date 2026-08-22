<x-layouts.app title="المديونيات">
    <x-slot:breadcrumb>المديونيات</x-slot:breadcrumb>

    <div x-data="{ tab: 'customers' }">
        <!-- Header & Tabs -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة المديونيات</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">متابعة المبالغ المستحقة لك وعليك</p>
                </div>
            </div>

            <!-- Tab Buttons -->
            <div class="flex bg-slate-200/60 p-1 rounded-xl w-full sm:w-auto">
                <button @click="tab = 'customers'" :class="{'bg-white text-primary-700 shadow-sm font-bold': tab === 'customers', 'text-slate-500 hover:text-slate-700 font-medium': tab !== 'customers'}" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 rounded-lg text-sm transition-all">
                    مديونيات لي (العملاء)
                </button>
                <button @click="tab = 'suppliers'" :class="{'bg-white text-primary-700 shadow-sm font-bold': tab === 'suppliers', 'text-slate-500 hover:text-slate-700 font-medium': tab !== 'suppliers'}" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 rounded-lg text-sm transition-all">
                    مديونيات علي (التجار)
                </button>
            </div>
        </div>

        <!-- Customers Tab -->
        <div x-show="tab === 'customers'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            
            <!-- Mobile Cards -->
            <div class="sm:hidden space-y-3 mb-6">
                @forelse($customers as $customer)
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="min-w-0">
                            <a href="{{ route('customers.show', $customer->id) }}" class="text-base font-bold text-primary-700 hover:underline block truncate">{{ $customer->name }}</a>
                            @if($customer->phone)<p class="text-xs text-slate-500 mt-0.5" dir="ltr">{{ $customer->phone }}</p>@endif
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('customers.show', $customer->id) }}" class="p-1.5 px-3 text-xs font-bold rounded-lg border border-primary-200 bg-primary-50 text-primary-700 hover:bg-primary-600 hover:text-white transition-all">
                                سداد / عرض
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-slate-50">
                        <span class="text-xs text-slate-500">المبلغ المستحق لك:</span>
                        <span class="text-lg font-black text-danger-600" dir="ltr">{{ number_format($customer->balance, 0) }} <span class="text-xs text-danger-400">ج.م</span></span>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">لا يوجد مديونيات مستحقة على العملاء.</div>
                @endforelse
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse whitespace-nowrap">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم العميل</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رقم الهاتف</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الرصيد المتبقي عليه</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100 align-middle text-center"><a href="{{ route('customers.show', $customer->id) }}" class="hover:underline">{{ $customer->name }}</a></td>
                                <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 align-middle text-center" dir="ltr">{{ $customer->phone ?? '-' }}</td>
                                <td class="px-4 py-3 text-[0.85rem] font-bold text-danger-600 border-b border-slate-100 align-middle text-center" dir="ltr">{{ number_format($customer->balance, 0) }} <span class="text-xs text-danger-400">ج.م</span></td>
                                <td class="px-4 py-3 border-b border-slate-100 align-middle text-center">
                                    <a href="{{ route('customers.show', $customer->id) }}" class="px-4 py-1.5 text-xs font-bold rounded-lg border border-primary-200 bg-primary-50 text-primary-700 hover:bg-primary-600 hover:text-white transition-all inline-block">سداد المديونية</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-4 py-8 text-sm text-slate-500 text-center">لا يوجد مديونيات مستحقة على العملاء.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Suppliers Tab -->
        <div x-show="tab === 'suppliers'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            
            <!-- Mobile Cards -->
            <div class="sm:hidden space-y-3 mb-6">
                @forelse($suppliers as $supplier)
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="min-w-0">
                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="text-base font-bold text-primary-700 hover:underline block truncate">{{ $supplier->name }}</a>
                            @if($supplier->phone)<p class="text-xs text-slate-500 mt-0.5" dir="ltr">{{ $supplier->phone }}</p>@endif
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="p-1.5 px-3 text-xs font-bold rounded-lg border border-primary-200 bg-primary-50 text-primary-700 hover:bg-primary-600 hover:text-white transition-all">
                                سداد / عرض
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-slate-50">
                        <span class="text-xs text-slate-500">المبلغ المستحق له:</span>
                        <span class="text-lg font-black text-danger-600" dir="ltr">{{ number_format($supplier->balance, 0) }} <span class="text-xs text-danger-400">ج.م</span></span>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">لا يوجد ديون مستحقة للتجار.</div>
                @endforelse
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse whitespace-nowrap">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم المورد</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رقم الهاتف</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">المبلغ المستحق له</th>
                                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100 align-middle text-center"><a href="{{ route('suppliers.show', $supplier->id) }}" class="hover:underline">{{ $supplier->name }}</a></td>
                                <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 align-middle text-center" dir="ltr">{{ $supplier->phone ?? '-' }}</td>
                                <td class="px-4 py-3 text-[0.85rem] font-bold text-danger-600 border-b border-slate-100 align-middle text-center" dir="ltr">{{ number_format($supplier->balance, 0) }} <span class="text-xs text-danger-400">ج.م</span></td>
                                <td class="px-4 py-3 border-b border-slate-100 align-middle text-center">
                                    <a href="{{ route('suppliers.show', $supplier->id) }}" class="px-4 py-1.5 text-xs font-bold rounded-lg border border-primary-200 bg-primary-50 text-primary-700 hover:bg-primary-600 hover:text-white transition-all inline-block">سداد الديون</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-4 py-8 text-sm text-slate-500 text-center">لا يوجد ديون مستحقة للتجار.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
