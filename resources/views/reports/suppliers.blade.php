<x-dynamic-component :component="request('print_mode') ? 'layouts.print' : 'layouts.app'" title="كشف حساب مورد">
    <x-slot name="breadcrumb">التقارير والإحصائيات / تقرير مورد</x-slot>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full print:hidden mb-6">
        <div class="flex items-start gap-3.5 text-right">
            <div class="p-2.5 bg-warning-50 text-warning-600 rounded-2xl shadow-sm shrink-0 self-center border border-warning-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">كشف حساب مورد</h1>
                <p class="text-xs lg:text-sm font-medium text-slate-500 leading-relaxed mt-0.5 hidden sm:block">
                    متابعة دقيقة لحركة حساب المورد وفواتير التوريد والمدفوعات.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
            @if(isset($supplier) && $supplier)
            <button type="button" @click="$dispatch('open-print-report')" class="flex-1 sm:flex-none px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg text-xs transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>طباعة التقرير</span>
            </button>
            @endif
            <a href="{{ route('reports.index') }}" class="flex-1 sm:flex-none px-3.5 py-2 bg-white text-slate-700 border border-slate-200 font-bold rounded-lg text-xs hover:bg-slate-50 transition-colors shadow-sm inline-flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                <span>العودة للتقارير</span>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 print:hidden">
        <form id="filterForm" action="{{ route('reports.suppliers') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">المورد</label>
                <select name="supplier_id" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs" required>
                    <option value="">-- اختر المورد --</option>
                    @foreach($suppliersList as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[130px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">العمليات</label>
                <select name="transaction_type" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
                    <option value="all" {{ request('transaction_type') == 'all' ? 'selected' : '' }}>الكل</option>
                    <option value="purchase" {{ request('transaction_type') == 'purchase' ? 'selected' : '' }}>مشتريات فقط</option>
                    <option value="payment_made" {{ request('transaction_type') == 'payment_made' ? 'selected' : '' }}>مدفوعات مسددة</option>
                </select>
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">الفترة</label>
                <select name="filter_type" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
                    <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>حسب التاريخ</option>
                    <option value="since_last_zero" {{ request('filter_type') == 'since_last_zero' ? 'selected' : '' }}>من آخر تصفية</option>
                </select>
            </div>
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">من</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
            </div>
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">إلى</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
            </div>
            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                <button type="submit" class="w-full sm:w-auto px-5 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-xs font-bold transition-colors shadow-sm shadow-primary-600/20">
                    تطبيق
                </button>
            </div>
        </form>
    </div>

    @if($supplier)
        <!-- Printable Header Branding -->
        <div class="hidden print:block mb-6">
            <x-print.header :title="'كشف حساب: ' . $supplier->name" :subtitle="(request('start_date') || request('end_date')) ? 'الفترة من ' . (request('start_date') ?? 'البداية') . ' إلى ' . (request('end_date') ?? 'الآن') : 'جميع التعاملات'" />
        </div>

        <!-- Report Content -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 p-6 print:border-none print:shadow-none print:bg-transparent print:p-0 print:m-0">
            <div class="border-b border-slate-100 pb-4 mb-4 print:hidden">
                <h2 class="text-lg font-black text-slate-800">كشف حساب: {{ $supplier->name }}</h2>
                <p class="text-xs text-slate-500 mt-1">
                    @if(request('start_date') || request('end_date'))
                        الفترة من {{ request('start_date') ?? 'البداية' }} إلى {{ request('end_date') ?? 'الآن' }}
                    @else
                        جميع التعاملات
                    @endif
                </p>
            </div>

            <!-- Compact Web Metrics Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 print:hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x md:divide-x-reverse divide-slate-100">
                    <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors rounded-r-xl">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            <span class="text-[0.7rem] font-bold">إجمالي المشتريات (خلال الفترة)</span>
                        </div>
                        <span class="text-xl font-black text-slate-800" dir="ltr">{{ number_format($totalPurchases, 2) }}</span>
                    </div>
                    
                    <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                            <svg class="w-4 h-4 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[0.7rem] font-bold">المدفوعات المسددة (خلال الفترة)</span>
                        </div>
                        <span class="text-xl font-black text-warning-600" dir="ltr">{{ number_format($totalPayments, 2) }}</span>
                    </div>
                    
                    <div class="p-4 flex flex-col justify-center text-center bg-primary-50 hover:bg-primary-100 transition-colors rounded-l-xl">
                        <div class="flex items-center justify-center gap-1.5 mb-1.5 text-primary-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            <span class="text-[0.7rem] font-bold">الرصيد النهائي الحالي</span>
                        </div>
                        <p class="text-xl font-black {{ $supplier->balance > 0 ? 'text-amber-700' : 'text-emerald-700' }}" dir="ltr">
                            {{ number_format(abs($supplier->balance), 2) }}
                            <span class="text-xs font-normal text-slate-500">
                                {{ $supplier->balance > 0 ? 'مستحق للمورد (له)' : 'مستحق لنا (عليه)' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Print Formal Summary Table -->
            <div class="hidden print:block mb-8">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr>
                            <th>إجمالي المشتريات (خلال الفترة)</th>
                            <th>المدفوعات المسددة (خلال الفترة)</th>
                            <th>الرصيد النهائي الحالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td dir="ltr">{{ number_format($totalPurchases, 2) }}</td>
                            <td dir="ltr">{{ number_format($totalPayments, 2) }}</td>
                            <td dir="ltr">
                                {{ number_format(abs($supplier->balance), 2) }}
                                ({{ $supplier->balance > 0 ? 'له علينا' : 'لنا عنده' }})
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Transactions Table -->
            <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white print:border-none print:shadow-none print:rounded-none print:bg-transparent">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">التاريخ</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">نوع المعاملة</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">البيان</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المبلغ الكلي</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المدفوع</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الرصيد بعد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-50">{{ $t->transaction_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-[0.8rem] font-bold border-b border-slate-50">
                                @if($t->type == 'purchase')
                                    <span class="text-primary-600">فاتورة مشتريات</span>
                                @elseif($t->type == 'payment_made')
                                    <span class="text-warning-600">سداد دفعة</span>
                                @elseif($t->type == 'return_purchase')
                                    <span class="text-danger-600">مرتجع مشتريات</span>
                                @else
                                    <span class="text-slate-600">{{ $t->type }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-50">{{ $t->notes ?? '-' }}</td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-50" dir="ltr">{{ $t->total_amount > 0 ? number_format($t->total_amount, 2) : '-' }}</td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-warning-600 border-b border-slate-50" dir="ltr">{{ $t->paid_amount > 0 ? number_format($t->paid_amount, 2) : '-' }}</td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-50" dir="ltr">
                                {{ number_format(abs($t->balance_after), 2) }}
                                <span class="text-[0.65rem] text-slate-400 font-normal">{{ $t->balance_after > 0 ? '(له)' : '(عليه)' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">لا توجد تعاملات مسجلة لهذا المورد في هذه الفترة.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Initial State (Before Selection) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-warning-50 text-warning-500 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">تقرير المورد</h3>
            <p class="text-sm text-slate-500">يرجى اختيار المورد من القائمة العلوية والضغط على "تطبيق" لاستعراض كشف الحساب التفصيلي.</p>
        </div>
    @endif

    @if(!request('print_mode'))
        <x-modals.print-report />
    @endif
</x-dynamic-component>
