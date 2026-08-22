<x-dynamic-component :component="request('print_mode') ? 'layouts.print' : 'layouts.app'" title="تقرير المديونيات العامة">
    <x-slot name="breadcrumb">التقارير والإحصائيات / تقرير المديونيات العامة</x-slot>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full print:hidden mb-6">
        <div class="flex items-start gap-3.5 text-right">
            <div class="p-2.5 bg-danger-50 text-danger-600 rounded-2xl shadow-sm shrink-0 self-center border border-danger-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">تقرير المديونيات العامة</h1>
                <p class="text-xs lg:text-sm font-medium text-slate-500 leading-relaxed mt-0.5 hidden sm:block">
                    تقرير شامل بكافة الأرصدة المتبقية على العملاء (لك) والمتبقية للموردين (عليك).
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
        <form id="filterForm" action="{{ route('reports.debts') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-700 mb-1">نوع التقرير</label>
                <select name="report_type" class="w-full px-3 py-1.5 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs">
                    <option value="all" {{ request('report_type') == 'all' ? 'selected' : '' }}>التقرير الشامل (إجمالي + عملاء + موردين)</option>
                    <option value="summary_only" {{ request('report_type') == 'summary_only' ? 'selected' : '' }}>إجمالي الأموال والديون فقط</option>
                    <option value="customers_only" {{ request('report_type') == 'customers_only' ? 'selected' : '' }}>ديون العملاء فقط (الأموال التي لك)</option>
                    <option value="suppliers_only" {{ request('report_type') == 'suppliers_only' ? 'selected' : '' }}>ديون الموردين فقط (الالتزامات التي عليك)</option>
                </select>
            </div>
            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                <button type="submit" class="w-full sm:w-auto px-5 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-xs font-bold transition-colors shadow-sm shadow-primary-600/20">
                    تطبيق
                </button>
            </div>
        </form>
    </div>

    <!-- Printable Header Branding -->
    <div class="hidden print:block mb-6">
        @php
            $reportTitle = 'تقرير المديونيات العامة';
            $reportSubtitle = 'يشمل ديون العملاء وديون الموردين';
            
            if(request('report_type') == 'summary_only') {
                $reportSubtitle = 'ملخص إجمالي الأموال فقط';
            } elseif(request('report_type') == 'customers_only') {
                $reportTitle = 'تقرير ديون العملاء';
                $reportSubtitle = 'الأموال المستحقة لك بالأسواق';
            } elseif(request('report_type') == 'suppliers_only') {
                $reportTitle = 'تقرير ديون الموردين';
                $reportSubtitle = 'الالتزامات المستحقة عليك للتجار والموردين';
            }
        @endphp
        <x-print.header :title="$reportTitle" :subtitle="$reportSubtitle" />
    </div>

    <!-- Report Content -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 p-6 print:border-none print:shadow-none print:bg-transparent print:p-0 print:m-0">
        
        @php
            $reportType = request('report_type', 'all');
            $showSummary = in_array($reportType, ['all', 'summary_only']);
            $showCustomers = in_array($reportType, ['all', 'customers_only']);
            $showSuppliers = in_array($reportType, ['all', 'suppliers_only']);
        @endphp

        @if($showSummary)
        <!-- Summary Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="flex items-center gap-4 p-4 rounded-xl border border-success-100 bg-success-50">
                <div class="w-12 h-12 rounded-full bg-success-100 flex items-center justify-center text-success-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-success-600 mb-1">إجمالي أموالك بالسوق (مديونيات العملاء)</p>
                    <p class="text-xl font-black text-success-700" dir="ltr">{{ number_format($totalCustomersDebt, 2) }} EGP</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4 p-4 rounded-xl border border-danger-100 bg-danger-50">
                <div class="w-12 h-12 rounded-full bg-danger-100 flex items-center justify-center text-danger-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-danger-600 mb-1">إجمالي الالتزامات عليك (ديون الموردين/التجار)</p>
                    <p class="text-xl font-black text-danger-700" dir="ltr">{{ number_format($totalSuppliersDebt, 2) }} EGP</p>
                </div>
            </div>
        </div>
        @endif

        @if($showCustomers || $showSuppliers)
        <div class="grid grid-cols-1 {{ ($showCustomers && $showSuppliers) ? 'lg:grid-cols-2' : '' }} gap-8 lg:gap-6">
            @if($showCustomers)
            <!-- Customers Debts List -->
            <div>
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success-500"></span>
                    العملاء المديونين (عليك تحصيلها)
                </h3>
                @if($customers->count() > 0)
                    <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white print:border-none print:shadow-none print:rounded-none print:bg-transparent">
                        <table class="w-full text-center border-collapse whitespace-nowrap">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">العميل</th>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">رقم الهاتف</th>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المبلغ المستحق</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($customers as $c)
                                    <tr class="hover:bg-slate-50/60 transition-colors group">
                                        <td class="px-4 py-2.5 text-[0.8rem] text-slate-800 font-bold border-b border-slate-50 align-middle">
                                            {{ $c->name }}
                                        </td>
                                        <td class="px-4 py-2.5 text-[0.8rem] text-slate-500 border-b border-slate-50 align-middle" dir="ltr">
                                            {{ $c->phone ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2.5 text-[0.8rem] font-black text-success-600 border-b border-slate-50 align-middle" dir="ltr">
                                            {{ number_format($c->balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500">لا يوجد ديون مستحقة على العملاء حالياً.</p>
                    </div>
                @endif
            </div>
            @endif

            @if($showSuppliers)
            <!-- Suppliers Debts List -->
            <div>
                <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-danger-500"></span>
                    الموردين / التجار (عليك سدادها)
                </h3>
                @if($suppliers->count() > 0)
                    <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white print:border-none print:shadow-none print:rounded-none print:bg-transparent">
                        <table class="w-full text-center border-collapse whitespace-nowrap">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المورد/التاجر</th>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">رقم الهاتف</th>
                                    <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المبلغ المطلوب</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($suppliers as $s)
                                    <tr class="hover:bg-slate-50/60 transition-colors group">
                                        <td class="px-4 py-2.5 text-[0.8rem] text-slate-800 font-bold border-b border-slate-50 align-middle">
                                            {{ $s->name }}
                                        </td>
                                        <td class="px-4 py-2.5 text-[0.8rem] text-slate-500 border-b border-slate-50 align-middle" dir="ltr">
                                            {{ $s->phone ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2.5 text-[0.8rem] font-black text-danger-600 border-b border-slate-50 align-middle" dir="ltr">
                                            {{ number_format($s->balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500">لا يوجد التزامات عليك للموردين حالياً.</p>
                    </div>
                @endif
            </div>
            @endif
        </div>
        @endif

    </div>
    
    @if(!request('print_mode'))
        <x-modals.print-report />
    @endif
</x-dynamic-component>
