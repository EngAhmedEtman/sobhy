<x-layouts.app title="حساب العميل">
    <x-slot name="pageTitle">
        حساب العميل <span class="text-sm font-normal text-slate-500 mr-2">{{ $customer->name }}</span>
    </x-slot>
    <x-slot name="breadcrumb">
        العملاء > تفاصيل العميل
    </x-slot>

    <div x-data="{ 
        showPaymentModal: false, 
        showReturnModal: false, 
        showDetailsModal: false,
        showPrintModal: false,
        printFilter: 'all',
        printLimit: 10,
        details: null,
        loading: false,
        editTransactionModal: false, 
        editType: '', 
        editId: '', 
        editDate: '', 
        editAmount: '', 
        editQuantity: '', 
        editNotes: '',
        editSale(id) {
            this.$dispatch('edit-sale', id);
        },
        viewSale(id) {
            this.loading = true;
            this.showDetailsModal = true;
            fetch(`/sales/${id}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                this.details = data;
                this.loading = false;
            });
        },
        executePrint() {
            openPrintPreviewModal('printPreviewModal', `/customers/{{ $customer->id }}/print?filter=${this.printFilter}&n=${this.printLimit}`);
            this.showPrintModal = false;
        }
    }">
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5 mb-5">
            <!-- Top Section: Customer Profile & Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <!-- Customer Info -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-full flex items-center justify-center font-bold border border-primary-100 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">{{ $customer->name }}</h3>
                            @if($customer->phone)
                                <span class="text-xs text-slate-500 font-mono bg-slate-50 px-2 py-0.5 rounded border border-slate-100" dir="ltr">{{ $customer->phone }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">تفاصيل الحساب والعمليات المالية للعميل</p>
                    </div>
                </div>

                <!-- Action Buttons (Top Left) -->
                <div class="flex flex-wrap items-center gap-2">
                    <button @click="$dispatch('create-sale')" class="px-3.5 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        بيع منتجات
                    </button>
                    <button @click="showPaymentModal = true" class="px-3.5 py-2 bg-[#008f50] text-white rounded-lg hover:bg-[#007542] text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        تسجيل تحصيل
                    </button>
                    <button @click="showReturnModal = true" class="px-3.5 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                        مرتجع بيع
                    </button>
                    <button @click="showPrintModal = true" class="px-3.5 py-2 bg-slate-100 border border-slate-200 text-slate-800 rounded-lg hover:bg-slate-200 text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        طباعة كشف الحساب
                    </button>
                    <a href="{{ route('customers.index') }}" class="p-2 bg-white border border-slate-200 text-slate-500 rounded-lg hover:bg-slate-50 text-xs font-bold shadow-sm transition-colors" title="العودة للقائمة">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                </div>
            </div>

            <!-- Printable Header Branding -->
            <div class="hidden print:block text-center border-b border-slate-200 pb-4 mb-4 mt-4">
                <h1 class="text-xl font-black text-slate-900">{{ \App\Models\Setting::get('company_name', 'حديد مصر') }} - كشف حساب عميل</h1>
                <h2 class="text-base font-bold text-slate-700 mt-1">{{ $customer->name }} ({{ $customer->phone }})</h2>
                <p class="text-xs text-slate-500 mt-1">تاريخ استخراج الكشف: {{ now()->format('Y-m-d g:i A') }}</p>
            </div>

            <!-- Bottom Section: 3 Stats in a clean balanced row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3.5">
                <!-- Total Sales -->
                <div class="bg-slate-50/80 rounded-lg p-3 border border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <div>
                            <p class="text-[0.7rem] text-slate-400 font-bold">إجمالي المبيعات</p>
                            <p class="text-sm sm:text-base font-black text-slate-800 mt-0.5" dir="ltr">{{ number_format($totalSales ?? 0, 0) }} <span class="text-[0.65rem] text-slate-400 font-normal">ج.م</span></p>
                        </div>
                    </div>
                </div>

                <!-- Total Received -->
                <div class="bg-slate-50/80 rounded-lg p-3 border border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-[0.7rem] text-slate-400 font-bold">إجمالي المحصل</p>
                            <p class="text-sm sm:text-base font-black text-slate-800 mt-0.5" dir="ltr">{{ number_format($totalPayments ?? 0, 0) }} <span class="text-[0.65rem] text-slate-400 font-normal">ج.م</span></p>
                        </div>
                    </div>
                </div>

                <!-- Current Balance -->
                @php
                    $isOwed = $customer->balance > 0;
                    $isCredit = $customer->balance < 0;
                    $balanceColor = $isOwed ? 'text-danger-600' : ($isCredit ? 'text-emerald-600' : 'text-slate-700');
                    $cardBg = $isOwed ? 'bg-danger-50/50 border-danger-100/80' : ($isCredit ? 'bg-emerald-50/50 border-emerald-100/80' : 'bg-slate-50/80 border-slate-100');
                    $badgeBg = $isOwed ? 'bg-danger-100 text-danger-700' : ($isCredit ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700');
                    $balanceLabel = $isOwed ? 'المتبقي عليه' : ($isCredit ? 'له' : 'الرصيد');
                    $badgeText = $isOwed ? 'مدين' : ($isCredit ? 'دائن' : 'خالص');
                    $absBalance = abs($customer->balance);
                @endphp
                <div class="{{ $cardBg }} rounded-lg p-3 border flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg {{ $isOwed ? 'bg-danger-100/80 text-danger-600' : ($isCredit ? 'bg-emerald-100/80 text-emerald-600' : 'bg-slate-200 text-slate-600') }} flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <p class="text-[0.7rem] font-bold text-slate-600">{{ $balanceLabel }}</p>
                                <span class="px-1.5 py-0.2 rounded text-[0.6rem] font-black {{ $badgeBg }}">{{ $badgeText }}</span>
                            </div>
                            <p class="text-sm sm:text-base font-black {{ $balanceColor }} mt-0.5" dir="ltr">{{ number_format($absBalance, 0) }} <span class="text-[0.65rem] font-bold">ج.م</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Transaction Cards -->
        <div class="sm:hidden space-y-3 mb-6">
            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                سجل العمليات
            </h4>
            @forelse($transactions as $transaction)
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-3">
                <div class="flex justify-between items-center mb-2">
                    @if($transaction->type === 'sale')
                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-primary-50 text-primary-600 border border-primary-100">بيع</span>
                    @elseif($transaction->type === 'payment_received')
                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-blue-50 text-blue-600 border border-blue-100">تحصيل</span>
                    @elseif($transaction->type === 'return_sale')
                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-amber-50 text-amber-600 border border-amber-100">مرتجع</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-600">{{ $transaction->type }}</span>
                    @endif
                    <span class="text-[0.65rem] text-slate-400 font-bold">{{ $transaction->transaction_date->format('d/m/Y') }}</span>
                </div>
                @if($transaction->notes)<p class="text-xs text-slate-500 mb-2 truncate">{{ $transaction->notes }}</p>@endif
                <div class="flex justify-between items-center text-xs border-t border-slate-50 pt-2 mb-2">
                    <div>
                        <span class="text-slate-400">الكمية:</span>
                        <span class="font-bold text-slate-700" dir="ltr">{{ $transaction->quantity ? number_format($transaction->quantity, 2) . ' ك' : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400">سعر الكيلو:</span>
                        <span class="font-bold text-slate-700" dir="ltr">
                            @if($transaction->quantity > 0)
                                {{ number_format($transaction->total_amount / $transaction->quantity, 2) }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center text-xs border-t border-slate-50 pt-2">
                    <div>
                        <span class="text-slate-400">المبلغ:</span>
                        <span class="font-bold text-slate-700" dir="ltr">{{ number_format($transaction->total_amount, 0) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400">مسدد:</span>
                        <span class="font-bold text-primary-600" dir="ltr">{{ number_format($transaction->paid_amount, 0) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400">المتبقي:</span>
                        <span class="font-black text-danger-600" dir="ltr">{{ number_format($transaction->balance_after, 0) }}</span>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-2 pt-2 border-t border-slate-50">
                    <div class="flex items-center justify-center gap-1.5">
                        @if($transaction->type === 'sale' && $transaction->invoice_id)
                            <button type="button" @click="viewSale({{ $transaction->invoice_id }})" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 hover:border-emerald-600 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض التفاصيل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            <button type="button" @click="editSale({{ $transaction->invoice_id }})" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-600 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </button>
                        @else
                            <button type="button" @click="$dispatch('view-transaction', {{ $transaction->id }})" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 hover:border-emerald-600 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض التفاصيل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            <button type="button" @click="editTransactionModal = true; editType = '{{ $transaction->type }}'; editId = '{{ $transaction->id }}'; editDate = '{{ $transaction->transaction_date->format('Y-m-d') }}'; editAmount = '{{ in_array($transaction->type, ['payment_received', 'payment_made']) ? $transaction->paid_amount : $transaction->total_amount }}'; editQuantity = '{{ $transaction->quantity ?? '' }}'; editNotes = '{{ $transaction->notes }}'" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-600 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </button>
                        @endif
                    </div>
                    <button type="button" x-on:click="$dispatch('open-modal', 'delete-transaction-{{ $transaction->id }}')" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-danger-600 hover:border-danger-600 hover:bg-danger-50 shadow-sm transition-all inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                    @php
                        $deleteAction = route('transactions.destroy', $transaction->id);
                        if ($transaction->type === 'purchase' && $transaction->invoice_id) $deleteAction = route('purchases.destroy', $transaction->invoice_id);
                        if ($transaction->type === 'sale' && $transaction->invoice_id) $deleteAction = route('sales.destroy', $transaction->invoice_id);
                    @endphp
                    <x-delete-modal name="delete-transaction-{{ $transaction->id }}" action="{{ $deleteAction }}" />
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">لا توجد عمليات مسجلة.</div>
            @endforelse
        </div>

        <!-- Desktop Transactions Table -->
        <div class="hidden sm:block overflow-x-auto relative rounded-xl border border-slate-100 bg-white mb-6">
            <table class="w-full text-center border-collapse whitespace-nowrap">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle">الرقم</th>
                        <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle w-1/4">البيان</th>
                        <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle">الكمية</th>
                        <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle">سعر الكيلو</th>
                        <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle">المبلغ</th>
                        <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle">مسدد</th>
                        <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle">المتبقي</th>
                        <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle w-32">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)

                            <tr class="hover:bg-slate-50/60 transition-colors">

                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 font-medium">{{ $transaction->transaction_date->format('d/m/Y') }}</td>

                                <td class="px-2.5 py-3 border-b border-slate-100">

                                    @if($transaction->type === 'sale')<span class="px-2 py-1 rounded text-[0.7rem] font-bold bg-primary-50 text-primary-600 border border-primary-100">شراء</span>

                                    @elseif($transaction->type === 'payment_received')<span class="px-2 py-1 rounded text-[0.7rem] font-bold bg-blue-50 text-blue-600 border border-blue-100">سداد دفعة</span>

                                    @elseif($transaction->type === 'return_sale')<span class="px-2 py-1 rounded text-[0.7rem] font-bold bg-amber-50 text-amber-600 border border-amber-100">مرتجع شراء</span>

                                    @else<span class="px-2 py-1 rounded text-[0.7rem] font-bold bg-slate-100 text-slate-600">{{ $transaction->type }}</span>@endif

                                </td>

                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100">{{ $transaction->notes ?? '-' }}</td>

                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100" dir="ltr">{{ $transaction->quantity ? number_format($transaction->quantity, 2) . ' ك' : '-' }}</td>

                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100" dir="ltr">

                                    @if($transaction->quantity > 0)

                                        {{ number_format($transaction->total_amount / $transaction->quantity, 2) }} <span class="text-[0.65rem] text-slate-400">ج.م</span>

                                    @else

                                        -



        <!-- Return Modal -->

        <div x-show="showReturnModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">

                <div x-show="showReturnModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showReturnModal = false"></div>

                <div x-show="showReturnModal" x-transition class="relative w-full max-w-md p-5 sm:p-6 text-right transition-all transform bg-white shadow-xl rounded-2xl">

                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">

                        <h3 class="text-lg font-bold text-slate-800">تسجيل مرتجع شراء</h3>

                        <button @click="showReturnModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>

                    </div>

                    <form action="{{ route('customers.return', $customer->id) }}" method="POST">

                        @csrf

                        <div class="space-y-4">

                            <!-- Searchable Product Combobox -->

                            <div x-data="{

                                open: false,

                                search: '',

                                selectedId: '',

                                selectedName: '',

                                products: {{ Js::from($products) }},

                                get filteredProducts() {

                                    if (!this.search) return this.products;

                                    return this.products.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));

                                },

                                selectProduct(product) {

                                    this.selectedId = product.id;

                                    this.selectedName = product.name + ' (متوفر: ' + Number(product.stock).toLocaleString('en-US') + ' ك)';

                                    this.open = false;

                                    this.search = '';

                                }

                            }" class="relative">

                                <label class="block text-sm font-bold text-slate-700 mb-1.5">المنتج المسترجع للعميل <span class="text-danger-500">*</span></label>

                                <input type="hidden" name="product_id" :value="selectedId" required>

                                

                                <!-- Trigger Button -->

                                <button type="button" 

                                        @click="open = !open" 

                                        class="w-full flex items-center justify-between px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-right">

                                    <span class="text-sm font-bold truncate" :class="selectedId ? 'text-slate-800' : 'text-slate-400'" x-text="selectedId ? selectedName : 'اختر المنتج المطلوب...'"></span>

                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 mr-2" :class="open ? 'rotate-180 text-primary-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />

                                    </svg>

                                </button>



                                <!-- Dropdown Menu -->

                                <div x-show="open" 

                                     @click.outside="open = false" 

                                     x-transition:enter="transition ease-out duration-150"

                                     x-transition:enter-start="opacity-0 translate-y-1"

                                     x-transition:enter-end="opacity-100 translate-y-0"

                                     x-transition:leave="transition ease-in duration-100"

                                     x-transition:leave-start="opacity-100 translate-y-0"

                                     x-transition:leave-end="opacity-0 translate-y-1"

                                     class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" 

                                     style="display: none;">

                                    

                                    <!-- Search Input -->

                                    <div class="p-2 border-b border-slate-100 bg-slate-50/70">

                                        <div class="relative">

                                            <input type="text" 

                                                   x-model="search" 

                                                   placeholder="ابحث عن الصنف بالاسم..." 

                                                   class="w-full pl-3 pr-8 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-400"

                                                   @keydown.escape="open = false">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 absolute right-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                                            </svg>

                                        </div>

                                    </div>



                                    <!-- Products List -->

                                    <div class="max-h-56 overflow-y-auto divide-y divide-slate-50">

                                        <template x-for="product in filteredProducts" :key="product.id">

                                            <button type="button" 

                                                    @click="selectProduct(product)" 

                                                    class="w-full px-3.5 py-2 text-right flex items-center justify-between hover:bg-primary-50/60 transition-colors group"

                                                    :class="selectedId == product.id ? 'bg-primary-50 font-bold' : ''">

                                                <div class="flex items-center gap-2">

                                                    <div class="w-2 h-2 rounded-full shrink-0" :class="product.stock > 0 ? 'bg-emerald-500' : 'bg-slate-300'"></div>

                                                    <span class="text-xs text-slate-800 group-hover:text-primary-700 font-medium" x-text="product.name"></span>

                                                </div>

                                                <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-600 group-hover:bg-primary-100 group-hover:text-primary-800 shrink-0" dir="ltr" x-text="'متوفر: ' + Number(product.stock).toLocaleString('en-US') + ' ك'"></span>

                                            </button>

                                        </template>

                                        <div x-show="filteredProducts.length === 0" class="p-4 text-center text-xs text-slate-400">

                                            لا توجد منتجات مطابقة للبحث

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="grid grid-cols-2 gap-3">

                                <div><label class="block text-sm font-medium text-slate-700 mb-1">الكمية المرتجعة</label><input type="number" step="0.01" name="quantity" min="0.01" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr"></div>

                                <div><label class="block text-sm font-medium text-slate-700 mb-1">إجمالي المبلغ (يخصم)</label><input type="number" step="0.01" name="amount" min="1" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr"></div>

                            </div>

                            <div><label class="block text-sm font-medium text-slate-700 mb-1">المبلغ المدفوع/المسترد (إن وجد)</label><input type="number" step="0.01" name="paid_amount" min="0" value="0" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr"></div>

                            <div><label class="block text-sm font-medium text-slate-700 mb-1">تاريخ المرتجع</label><input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr"></div>

                            <div><label class="block text-sm font-medium text-slate-700 mb-1">ملاحظات (اختياري)</label><input type="text" name="notes" placeholder="ملاحظات إضافية..." class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base"></div>

                        </div>

                        <div class="mt-6 flex gap-3">

                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-slate-800 rounded-lg hover:bg-slate-900">حفظ المرتجع</button>

                            <button type="button" @click="showReturnModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>



        <!-- Edit Transaction Modal -->

        <div x-show="editTransactionModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center">

                <div x-show="editTransactionModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="editTransactionModal = false"></div>

                <div x-show="editTransactionModal" x-transition class="relative w-full max-w-md p-5 sm:p-6 text-right transition-all transform bg-white shadow-xl rounded-2xl">

                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">

                        <h3 class="text-lg font-bold text-slate-800">تعديل العملية</h3>

                        <button @click="editTransactionModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>

                    </div>

                    <form :action="'{{ url('transactions') }}/' + editId" method="POST">

                        @csrf

                        @method('PUT')

                        <div class="space-y-4">

                            <div x-show="editType === 'return_sale' || editType === 'return_sale' || editType === 'sale' || editType === 'sale'">

                                <label class="block text-sm font-medium text-slate-700 mb-1">الكمية</label>

                                <input type="number" step="0.01" name="quantity" x-model="editQuantity" min="0.01" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr">

                            </div>

                            <div>

                                <label class="block text-sm font-medium text-slate-700 mb-1">المبلغ (ج.م)</label>

                                <input type="number" step="0.01" name="amount" x-model="editAmount" min="0" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr">

                            </div>

                            <div>

                                <label class="block text-sm font-medium text-slate-700 mb-1">تاريخ العملية</label>

                                <input type="date" name="date" x-model="editDate" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr">

                            </div>

                            <div>

                                <label class="block text-sm font-medium text-slate-700 mb-1">ملاحظات (اختياري)</label>

                                <input type="text" name="notes" x-model="editNotes" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">

                            </div>

                        </div>

                        <div class="mt-6 flex gap-3">

                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">حفظ التعديلات</button>

                            <button type="button" @click="editTransactionModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>



        <!-- Purchase Modal Component -->

        <x-modals.sale-form :products="$products" :customers="[]" :fixedCustomer="$customer" />



        <!-- View Details Modal -->

        <x-modals.invoice-details type="sale" />

        

        <x-modals.transaction-details />
        <x-modals.print-statement type="customer" />


    </div>

</x-layouts.app>
