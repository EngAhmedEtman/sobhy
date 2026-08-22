<x-dynamic-component :component="request('print_mode') ? 'layouts.print' : 'layouts.app'" title="تقرير الأرباح الشامل">
    <x-slot name="breadcrumb">التقارير والإحصائيات / تقرير الأرباح الشامل</x-slot>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full print:hidden mb-6">
        <div class="flex items-start gap-3.5 text-right">
            <div class="p-2.5 bg-success-50 text-success-600 rounded-2xl shadow-sm shrink-0 self-center border border-success-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">تقرير الأرباح الشامل</h1>
                <p class="text-xs lg:text-sm font-medium text-slate-500 leading-relaxed mt-0.5 hidden sm:block">
                    متابعة شاملة للأرباح والمصروفات النقدية، بالإضافة للأرباح المتوقعة باحتساب ديون العملاء والموردين.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
            <button type="button" @click="$dispatch('open-print-report')" class="flex-1 sm:flex-none px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg text-xs transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>طباعة التقرير</span>
            </button>
            <a href="{{ route('reports.index') }}" class="flex-1 sm:flex-none px-3.5 py-2 bg-white text-slate-700 border border-slate-200 font-bold rounded-lg text-xs hover:bg-slate-50 transition-colors shadow-sm inline-flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                <span>العودة للتقارير</span>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 print:hidden">
        <form id="filterForm" action="{{ route('reports.profit') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">من تاريخ</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 text-xs focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">إلى تاريخ</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 text-xs focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
            </div>
            <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                <button type="submit" class="flex-1 sm:flex-none px-5 py-1.5 text-xs font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm shadow-primary-600/20">تطبيق</button>
                <a href="{{ route('reports.profit') }}" class="flex-1 sm:flex-none px-5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-lg hover:bg-slate-200 transition-colors text-center">تفريغ</a>
            </div>
        </form>
    </div>

    <!-- Printable Header Branding -->
    <div class="hidden print:block mb-6">
        <x-print.header title="تقرير الأرباح الشامل" :subtitle="(request('start_date') || request('end_date')) ? 'الفترة من ' . (request('start_date') ?? 'البداية') . ' إلى ' . (request('end_date') ?? 'الآن') : 'جميع الفترات'" />
    </div>

    <!-- Report Content -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 p-6 print:border-none print:shadow-none print:bg-transparent print:p-0 print:m-0">
        <div class="border-b border-slate-100 pb-4 mb-4 print:hidden">
            <h2 class="text-lg font-black text-slate-800">تفاصيل الأرباح</h2>
            <p class="text-xs text-slate-500 mt-1">
                @if(request('start_date') || request('end_date'))
                    الفترة من {{ request('start_date') ?? 'البداية' }} إلى {{ request('end_date') ?? 'الآن' }}
                @else
                    جميع الفترات
                @endif
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Cash Profit (بدون الديون) -->
            <div class="border border-slate-100 rounded-xl overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-100 p-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        الأرباح النقدية الفعلية (بدون الديون)
                    </h3>
                    <p class="text-[0.7rem] text-slate-500 mt-1">تعتمد على السيولة النقدية المستلمة والمسددة فعلياً خلال الفترة.</p>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 border-dashed">
                        <span class="text-sm font-medium text-slate-600">إجمالي النقدية المستلمة (من العملاء)</span>
                        <span class="font-bold text-slate-800" dir="ltr">{{ number_format($totalPaymentsReceived, 2) }} EGP</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 border-dashed">
                        <span class="text-sm font-medium text-slate-600">إجمالي النقدية المسددة (للموردين)</span>
                        <span class="font-bold text-slate-800" dir="ltr">{{ number_format($totalPaymentsMade, 2) }} EGP</span>
                    </div>
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-slate-200">
                        <span class="text-sm font-black text-slate-800">صافي الربح النقدي</span>
                        <span class="text-xl font-black {{ $cashProfit >= 0 ? 'text-emerald-600' : 'text-danger-600' }}" dir="ltr">
                            {{ number_format(abs($cashProfit), 2) }} EGP
                            <span class="text-[0.65rem] {{ $cashProfit >= 0 ? 'text-emerald-500' : 'text-danger-500' }}">
                                {{ $cashProfit >= 0 ? '(ربح)' : '(خسارة)' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Accrual Profit (مع الديون) -->
            <div class="border border-slate-100 rounded-xl overflow-hidden">
                <div class="bg-primary-50/50 border-b border-slate-100 p-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        الأرباح الشاملة المتوقعة (مبيعات ومشتريات)
                    </h3>
                    <p class="text-[0.7rem] text-slate-500 mt-1">تعتمد على إجمالي فواتير المبيعات والمشتريات بغض النظر عن التحصيل.</p>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 border-dashed">
                        <span class="text-sm font-medium text-slate-600">إجمالي المبيعات الآجلة والنقدية</span>
                        <span class="font-bold text-slate-800" dir="ltr">{{ number_format($totalSales, 2) }} EGP</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 border-dashed">
                        <span class="text-sm font-medium text-slate-600">إجمالي المشتريات الآجلة والنقدية</span>
                        <span class="font-bold text-slate-800" dir="ltr">{{ number_format($totalPurchases, 2) }} EGP</span>
                    </div>
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-slate-200">
                        <span class="text-sm font-black text-slate-800">صافي الربح الشامل</span>
                        <span class="text-xl font-black {{ $accrualProfit >= 0 ? 'text-primary-600' : 'text-danger-600' }}" dir="ltr">
                            {{ number_format(abs($accrualProfit), 2) }} EGP
                            <span class="text-[0.65rem] {{ $accrualProfit >= 0 ? 'text-primary-500' : 'text-danger-500' }}">
                                {{ $accrualProfit >= 0 ? '(ربح)' : '(خسارة)' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Global Debts Summary -->
        <div class="mt-8 border border-slate-100 rounded-xl overflow-hidden">
            <div class="bg-amber-50/50 border-b border-slate-100 p-4">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    موقف المديونيات العام (خارج الفلتر)
                </h3>
                <p class="text-[0.7rem] text-slate-500 mt-1">هذه الأرقام تمثل إجمالي الديون القائمة حالياً في النظام بغض النظر عن فترة التقرير.</p>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col p-4 bg-slate-50 rounded-lg border border-slate-100 text-center">
                    <span class="text-xs font-bold text-slate-500 mb-2">إجمالي الفلوس اللي لنا (عند العملاء)</span>
                    <span class="text-xl font-black text-success-600" dir="ltr">{{ number_format($customersDebt, 2) }} EGP</span>
                </div>
                <div class="flex flex-col p-4 bg-slate-50 rounded-lg border border-slate-100 text-center">
                    <span class="text-xs font-bold text-slate-500 mb-2">إجمالي الفلوس اللي علينا (للتجار/الموردين)</span>
                    <span class="text-xl font-black text-danger-600" dir="ltr">{{ number_format($suppliersDebt, 2) }} EGP</span>
                </div>
            </div>
        </div>

    </div>

    @if(!request('print_mode'))
        <x-modals.print-report />
    @endif
</x-dynamic-component>
