<x-layouts.app title="لوحة المعلومات">
    <x-slot:breadcrumb>الرئيسية</x-slot:breadcrumb>

    <div x-data="{
        showSaleModal: false,
        showPurchaseModal: false,
        showTransactionModal: false,
        transactionDetails: {},
        viewTransaction(t) {
            this.transactionDetails = t;
            this.showTransactionModal = true;
        }
    }" class="space-y-6">

        <!-- Top Header & Quick Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-xl font-black text-slate-800">لوحة التحكم والمعلومات</h1>
                    <p class="text-slate-500 text-xs mt-0.5">متابعة فورية للمبيعات، المشتريات، الخزينة، والمخزون</p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <button type="button" @click="showSaleModal = true" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-sm shadow-primary-600/20 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>فاتورة بيع</span>
                </button>
                <button type="button" @click="showPurchaseModal = true" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl border border-slate-200 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>فاتورة شراء</span>
                </button>
                <a href="{{ route('debts.index') }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-xl border border-slate-200 transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h3a2 2 0 012 2v10m-6 0a2 2 0 002 2h3a2 2 0 002-2V11a2 2 0 012-2h3a2 2 0 012 2v8a2 2 0 01-2 2h-3l-1-1z" /></svg>
                    <span>المديونيات</span>
                </a>
            </div>
        </div>

        <!-- Key Financial & Operational Metric Cards (6 Compact Grid Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
            
            <!-- Today Sales -->
            <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[0.7rem] font-bold text-slate-500">مبيعات اليوم</span>
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </span>
                </div>
                <div>
                    <div class="text-base sm:text-lg font-black text-slate-800" dir="ltr">{{ number_format($todaySalesTotal, 0) }} <span class="text-[0.65rem] font-bold text-slate-500">ج.م</span></div>
                    <div class="flex items-center justify-between text-[0.65rem] text-slate-400 mt-1">
                        <span>{{ $todaySalesCount }} فواتير اليوم</span>
                        <span>شهر: {{ number_format($monthSalesTotal, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Today Purchases -->
            <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[0.7rem] font-bold text-slate-500">مشتريات اليوم</span>
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </span>
                </div>
                <div>
                    <div class="text-base sm:text-lg font-black text-slate-800" dir="ltr">{{ number_format($todayPurchasesTotal, 0) }} <span class="text-[0.65rem] font-bold text-slate-500">ج.م</span></div>
                    <div class="flex items-center justify-between text-[0.65rem] text-slate-400 mt-1">
                        <span>{{ $todayPurchasesCount }} فواتير اليوم</span>
                        <span>شهر: {{ number_format($monthPurchasesTotal, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Cash Flow Today -->
            <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[0.7rem] font-bold text-slate-500">حركة الخزينة اليوم</span>
                    <span class="w-7 h-7 rounded-lg {{ $todayNetCash >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-danger-50 text-danger-600' }} flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                </div>
                <div>
                    <div class="text-base sm:text-lg font-black {{ $todayNetCash >= 0 ? 'text-emerald-600' : 'text-danger-600' }}" dir="ltr">
                        {{ $todayNetCash > 0 ? '+' : '' }}{{ number_format($todayNetCash, 0) }} <span class="text-[0.65rem] font-bold">ج.م</span>
                    </div>
                    <div class="flex items-center justify-between text-[0.65rem] text-slate-400 mt-1">
                        <span class="text-emerald-600 font-semibold">دخل: {{ number_format($todayCashIn, 0) }}</span>
                        <span class="text-danger-600 font-semibold">خرج: {{ number_format($todayCashOut, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Debts (لنا عندهم) -->
            <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[0.7rem] font-bold text-slate-500">مديونيات العملاء (لنا)</span>
                    <span class="w-7 h-7 rounded-lg bg-danger-50 text-danger-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </span>
                </div>
                <div>
                    <div class="text-base sm:text-lg font-black text-danger-600" dir="ltr">{{ number_format($totalCustomersDebt, 0) }} <span class="text-[0.65rem] font-bold">ج.م</span></div>
                    <div class="flex items-center justify-between text-[0.65rem] text-slate-400 mt-1">
                        <span>{{ $customersDebtCount }} عميل مدين</span>
                        <a href="{{ route('debts.index') }}" class="text-primary-600 hover:underline font-bold">عرض ></a>
                    </div>
                </div>
            </div>

            <!-- Supplier Debts (علينا لهم) -->
            <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[0.7rem] font-bold text-slate-500">مستحقات الموردين (علينا)</span>
                    <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </span>
                </div>
                <div>
                    <div class="text-base sm:text-lg font-black text-amber-800" dir="ltr">{{ number_format($totalSuppliersDebt, 0) }} <span class="text-[0.65rem] font-bold">ج.م</span></div>
                    <div class="flex items-center justify-between text-[0.65rem] text-slate-400 mt-1">
                        <span>{{ $suppliersDebtCount }} مورد مستحق</span>
                        <a href="{{ route('debts.index') }}" class="text-primary-600 hover:underline font-bold">عرض ></a>
                    </div>
                </div>
            </div>

            <!-- Total Warehouse Stock -->
            <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[0.7rem] font-bold text-slate-500">إجمالي رصيد المخزن</span>
                    <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </span>
                </div>
                <div>
                    <div class="text-base sm:text-lg font-black text-slate-800" dir="ltr">{{ number_format($totalStockWeight, 0) }} <span class="text-[0.65rem] font-bold text-slate-500">كجم</span></div>
                    <div class="flex items-center justify-between text-[0.65rem] text-slate-400 mt-1">
                        <span>{{ $productsCount }} صنف مسجل</span>
                        <a href="{{ route('products.index') }}" class="text-primary-600 hover:underline font-bold">المخزون ></a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Middle Section: Top Debtors & Creditors + Stock Levels -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Top Debtors & Creditors (7 Cols) -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 flex flex-col justify-between" x-data="{ activeTab: 'customers' }">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">أعلى المديونيات والمستحقات العاجلة</h3>
                        </div>

                        <!-- Mini Tab Toggle -->
                        <div class="flex bg-slate-100 p-0.5 rounded-lg text-xs">
                            <button type="button" @click="activeTab = 'customers'" :class="activeTab === 'customers' ? 'bg-white text-danger-700 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-2.5 py-1 rounded-md transition-all">
                                عملاء مدينين
                            </button>
                            <button type="button" @click="activeTab = 'suppliers'" :class="activeTab === 'suppliers' ? 'bg-white text-amber-800 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-2.5 py-1 rounded-md transition-all">
                                موردين دائنين
                            </button>
                        </div>
                    </div>

                    <!-- Customers List -->
                    <div x-show="activeTab === 'customers'" class="space-y-2">
                        @forelse($topDebtorCustomers as $c)
                            <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-slate-50 transition-colors">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-danger-50 text-danger-700 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ mb_substr($c->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('customers.show', $c->id) }}" class="text-xs font-bold text-slate-800 hover:text-primary-600 block truncate">{{ $c->name }}</a>
                                        <p class="text-[0.65rem] text-slate-400" dir="ltr">{{ $c->phone ?? 'بدون هاتف' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <div class="text-left">
                                        <span class="text-xs font-bold text-danger-600" dir="ltr">{{ number_format($c->balance, 0) }} ج.م</span>
                                        <span class="block text-[0.6rem] text-slate-400">مطلوب تحصيله</span>
                                    </div>
                                    <a href="{{ route('customers.show', $c->id) }}" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-500 hover:bg-primary-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض الحساب">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-6 text-xs text-slate-400">لا توجد مديونيات على العملاء حالياً.</p>
                        @endforelse
                    </div>

                    <!-- Suppliers List -->
                    <div x-show="activeTab === 'suppliers'" style="display: none;" class="space-y-2">
                        @forelse($topCreditorSuppliers as $s)
                            <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-slate-50 transition-colors">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-800 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ mb_substr($s->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('suppliers.show', $s->id) }}" class="text-xs font-bold text-slate-800 hover:text-primary-600 block truncate">{{ $s->name }}</a>
                                        <p class="text-[0.65rem] text-slate-400" dir="ltr">{{ $s->phone ?? 'بدون هاتف' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <div class="text-left">
                                        <span class="text-xs font-bold text-amber-800" dir="ltr">{{ number_format($s->balance, 0) }} ج.م</span>
                                        <span class="block text-[0.6rem] text-slate-400">مستحق للمورد</span>
                                    </div>
                                    <a href="{{ route('suppliers.show', $s->id) }}" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-500 hover:bg-primary-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض الحساب">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-6 text-xs text-slate-400">لا توجد مستحقات للموردين حالياً.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 text-center">
                    <a href="{{ route('debts.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700">الانتقال إلى جدول المديونيات الشامل &larr;</a>
                </div>
            </div>

            <!-- Products Stock Inventory (5 Cols) -->
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">حالة المخزون والأصناف</h3>
                        </div>
                        <a href="{{ route('products.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700">إدارة الأصناف</a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($topProducts as $p)
                            <div class="p-2.5 rounded-xl bg-slate-50/70 border border-slate-100">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-xs font-bold text-slate-800 truncate">{{ $p->name }}</span>
                                    <span class="text-xs font-black {{ $p->stock <= 100 ? 'text-danger-600' : 'text-slate-700' }}" dir="ltr">
                                        {{ number_format($p->stock, 0) }} {{ $p->unit ?? 'ك' }}
                                    </span>
                                </div>
                                <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden">
                                    @php
                                        $stockPct = $totalStockWeight > 0 ? min(100, round(($p->stock / $totalStockWeight) * 100)) : 0;
                                    @endphp
                                    <div class="h-1.5 rounded-full {{ $p->stock <= 100 ? 'bg-danger-500' : 'bg-primary-500' }}" style="width: {{ max(5, $stockPct) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-6 text-xs text-slate-400">لا توجد أصناف مضافة في المخزن.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>إجمالي الأصناف: <strong class="text-slate-800">{{ $productsCount }}</strong></span>
                    <span>الأصناف الحرجة: <strong class="text-danger-600">{{ $lowStockProductsCount }}</strong></span>
                </div>
            </div>

        </div>

        <!-- Recent Activity Feed (Moqlate Compact Standard Data Table) -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">أحدث العمليات والحركات المالية المسجلة</h3>
                        <p class="text-[0.65rem] text-slate-400">سجل فوري لجميع فواتير البيع والشراء والتحصيلات والسداد</p>
                    </div>
                </div>
                <a href="{{ route('reports.sales') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700">عرض كافة التقارير &larr;</a>
            </div>

            <!-- Moqlate Standard Table -->
            <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">#</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">التاريخ</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">نوع العملية</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الطرف الثاني</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">المبلغ / القيمة</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">المدفوع نقداً</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الرصيد بعد العملية</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $index => $t)
                            @php
                                $isCustomer = $t->transactionable_type === 'App\Models\Customer' || str_contains($t->type, 'sale') || $t->type === 'payment_received';
                                $absBal = abs($t->balance_after);
                                $formattedBal = (float)$absBal == (int)$absBal ? number_format($absBal, 0) : number_format($absBal, 2);
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                <td class="px-4 py-2.5 text-[0.8rem] text-slate-400 border-b border-slate-50 align-middle text-center">
                                    #{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-4 py-2.5 text-[0.8rem] text-slate-600 border-b border-slate-50 align-middle text-center" dir="ltr">
                                    {{ $t->transaction_date ? $t->transaction_date->format('Y-m-d') : $t->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-4 py-2.5 text-[0.8rem] border-b border-slate-50 align-middle text-center">
                                    @if($t->type === 'sale')
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">فاتورة مبيعات</span>
                                    @elseif($t->type === 'purchase')
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">فاتورة مشتريات</span>
                                    @elseif($t->type === 'payment_received')
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-green-50 text-green-700 border border-green-200/60">تحصيل من عميل</span>
                                    @elseif($t->type === 'payment_made')
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-purple-50 text-purple-700 border border-purple-200/60">سداد لمورد</span>
                                    @elseif($t->type === 'return_sale')
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-amber-50 text-amber-800 border border-amber-200/60">مرتجع مبيعات</span>
                                    @elseif($t->type === 'return_purchase')
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">مرتجع مشتريات</span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-slate-100 text-slate-700">{{ $t->type }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-[0.8rem] text-slate-800 font-bold border-b border-slate-50 align-middle text-center">
                                    @if($t->transactionable)
                                        <a href="{{ $isCustomer ? route('customers.show', $t->transactionable->id) : route('suppliers.show', $t->transactionable->id) }}" class="hover:text-primary-600">
                                            {{ $t->transactionable->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">---</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-[0.8rem] font-bold text-slate-800 border-b border-slate-50 align-middle text-center" dir="ltr">
                                    {{ $t->total_amount > 0 ? ((float)$t->total_amount == (int)$t->total_amount ? number_format($t->total_amount, 0) : number_format($t->total_amount, 2)) . ' ج.م' : '-' }}
                                </td>
                                <td class="px-4 py-2.5 text-[0.8rem] font-bold text-emerald-600 border-b border-slate-50 align-middle text-center" dir="ltr">
                                    {{ $t->paid_amount > 0 ? ((float)$t->paid_amount == (int)$t->paid_amount ? number_format($t->paid_amount, 0) : number_format($t->paid_amount, 2)) . ' ج.م' : '-' }}
                                </td>
                                <td class="px-4 py-2.5 text-[0.8rem] font-bold border-b border-slate-50 align-middle text-center whitespace-nowrap">
                                    @if($t->balance_after == 0)
                                        <span class="text-slate-500">0 ج.م (خالص)</span>
                                    @elseif($t->balance_after > 0)
                                        <span class="{{ $isCustomer ? 'text-danger-600' : 'text-amber-800' }}" dir="ltr">
                                            {{ $formattedBal }} ج.م {{ $isCustomer ? '(مطلوب منه)' : '(له علينا)' }}
                                        </span>
                                    @else
                                        <span class="text-emerald-600" dir="ltr">
                                            {{ $formattedBal }} ج.م {{ $isCustomer ? '(له عندنا)' : '(لنا عنده)' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-[0.8rem] border-b border-slate-50 align-middle text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button" @click="viewTransaction({
                                            id: '{{ $t->id }}',
                                            type: '{{ $t->type }}',
                                            date: '{{ $t->transaction_date ? $t->transaction_date->format('Y-m-d') : $t->created_at->format('Y-m-d') }}',
                                            party_name: '{{ $t->transactionable->name ?? '---' }}',
                                            amount: {{ $t->total_amount > 0 ? $t->total_amount : $t->paid_amount }},
                                            paid_amount: {{ (float)$t->paid_amount }},
                                            balance_after: {{ (float)$t->balance_after }},
                                            product_name: '{{ $t->product->name ?? '' }}',
                                            quantity: '{{ $t->quantity }}',
                                            unit_price: '{{ $t->unit_price }}',
                                            notes: '{{ addslashes($t->notes) }}'
                                        })" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-primary-600 hover:border-primary-500 hover:bg-primary-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض التفاصيل">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                        <a href="{{ route('transactions.print', $t->id) }}" target="_blank" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-slate-700 hover:border-slate-400 hover:bg-slate-50 shadow-sm transition-all inline-flex items-center justify-center" title="طباعة الإيصال">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-xs text-slate-400">
                                    لا توجد حركات أو عمليات مسجلة حديثاً في النظام.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reusable Purchase and Sale and Details Modals -->
        <x-modals.sale-form :customers="$customers" :products="$products" />
        <x-modals.purchase-form :suppliers="$suppliers" :products="$products" />
        <x-modals.transaction-details />

    </div>
</x-layouts.app>
