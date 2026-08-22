<x-dynamic-component :component="request('print_mode') ? 'layouts.print' : 'layouts.app'" title="تقرير المشتريات الشامل">
    <x-slot name="breadcrumb">التقارير > مشتريات</x-slot>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full print:hidden mb-6">
        <div class="flex items-start gap-3.5 text-right">
            <div class="p-2.5 bg-warning-50 text-warning-600 rounded-2xl shadow-sm shrink-0 self-center border border-warning-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">تقرير المشتريات الشامل</h1>
                <p class="text-xs lg:text-sm font-medium text-slate-500 leading-relaxed mt-0.5 hidden sm:block">
                    متابعة دقيقة لجميع فواتير التوريد والمبالغ المدفوعة والآجلة لقطاع المشتريات خلال فترة زمنية محددة.
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

    <!-- Printable Header Branding -->
    <div class="hidden print:block mb-6">
        <x-print.header title="تقرير المشتريات الشامل" :subtitle="request('start_date') ? 'الفترة من: ' . request('start_date') . ' إلى: ' . (request('end_date') ?? 'اليوم') : 'التقرير الشامل'" />
    </div>

    <!-- Compact Web Metrics Panel -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 print:hidden">
        @php
            $totalWeight = $purchases->sum(function($purchase) {
                return collect($purchase->items)->sum('quantity');
            });
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-5 divide-y lg:divide-y-0 lg:divide-x lg:divide-x-reverse divide-slate-100">
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors rounded-r-xl">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي الفواتير</span>
                </div>
                <span class="text-xl font-black text-slate-800" dir="ltr">{{ number_format($purchases->count()) }}</span>
            </div>
            
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي المشتريات</span>
                </div>
                <span class="text-xl font-black text-slate-800" dir="ltr">{{ format_amount($totalAmount) }} <span class="text-xs text-slate-400">ج.م</span></span>
            </div>
            
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي المدفوع</span>
                </div>
                <span class="text-xl font-black text-slate-800" dir="ltr">{{ format_amount($totalPaid) }} <span class="text-xs text-slate-400">ج.م</span></span>
            </div>
            
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي المتبقي (آجل)</span>
                </div>
                <span class="text-xl font-black text-danger-600" dir="ltr">{{ format_amount($totalRemaining) }} <span class="text-xs text-danger-400">ج.م</span></span>
            </div>
            
            <div class="p-4 flex flex-col justify-center text-center hover:bg-slate-50 transition-colors rounded-l-xl">
                <div class="flex items-center justify-center gap-1.5 mb-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    <span class="text-[0.7rem] font-bold">إجمالي الأوزان</span>
                </div>
                <span class="text-xl font-black text-slate-800" dir="ltr">{{ format_quantity($totalWeight) }} <span class="text-xs text-slate-400">كجم</span></span>
            </div>
        </div>
    </div>

    <!-- Print Formal Summary Table -->
    <div class="hidden print:block mb-8">
        <h3 class="text-sm font-bold text-slate-800 mb-2 border-b border-slate-300 pb-1">ملخص الإحصائيات للفترة المحددة</h3>
        <table class="w-full text-center border-collapse">
            <thead>
                <tr>
                    <th>عدد الفواتير</th>
                    <th>إجمالي المشتريات</th>
                    <th>إجمالي المدفوع</th>
                    <th>إجمالي الآجل</th>
                    <th>إجمالي الوزن</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($purchases->count()) }}</td>
                    <td dir="ltr">{{ format_amount($totalAmount) }} ج.م</td>
                    <td dir="ltr">{{ format_amount($totalPaid) }} ج.م</td>
                    <td dir="ltr">{{ format_amount($totalRemaining) }} ج.م</td>
                    <td dir="ltr">{{ format_quantity($totalWeight) }} كجم</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 print:hidden">
        <form id="filterForm" action="{{ route('reports.purchases') }}" method="GET" class="flex flex-wrap items-end gap-3">
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
                <a href="{{ route('reports.purchases') }}" class="flex-1 sm:flex-none px-5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-lg hover:bg-slate-200 transition-colors text-center">تفريغ</a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden print:border-none print:shadow-none print:rounded-none print:bg-transparent">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse whitespace-nowrap">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">رقم الفاتورة</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">التاريخ</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المورد</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الإجمالي</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المدفوع</th>
                        <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المتبقي</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <td class="px-4 py-3 text-[0.8rem] font-bold text-slate-700 border-b border-slate-100">{{ $purchase->invoice_number }}</td>
                        <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-100">{{ \Carbon\Carbon::parse($purchase->date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100">{{ $purchase->supplier->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-[0.85rem] text-slate-800 font-bold border-b border-slate-100" dir="ltr">{{ number_format($purchase->total_amount, 0) }}</td>
                        <td class="px-4 py-3 text-[0.85rem] text-warning-600 font-bold border-b border-slate-100" dir="ltr">{{ number_format($purchase->paid_amount, 0) }}</td>
                        <td class="px-4 py-3 text-[0.85rem] text-danger-600 font-bold border-b border-slate-100" dir="ltr">{{ number_format($purchase->remaining_amount, 0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-sm text-slate-500 text-center">لا توجد بيانات متاحة في هذه الفترة الزمنية.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(!request('print_mode'))
        <x-modals.print-report />
    @endif
</x-dynamic-component>
