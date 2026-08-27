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
        editFromDetails(t) {
            this.editType = t.type;
            this.editId = t.id;
            this.editDate = t.transaction_date;
            this.editAmount = t.amount;
            this.editQuantity = t.quantity || '';
            this.editNotes = t.notes || '';
            this.editTransactionModal = true;
        },
        executePrint() {
            openPrintPreviewModal('printPreviewModal', `/customers/{{ $customer->id }}/print?filter=${this.printFilter}&n=${this.printLimit}`);
            this.showPrintModal = false;
        }
    }"
    @edit-transaction.window="editFromDetails($event.detail)">
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
                    @if(auth()->user()?->hasPermission('sales.create'))
                    <button @click="$dispatch('create-sale')" class="px-3.5 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        بيع منتجات
                    </button>
                    @endif
                    @if(auth()->user()?->hasPermission('customers.update'))
                    <button @click="showPaymentModal = true" class="px-3.5 py-2 bg-[#008f50] text-white rounded-lg hover:bg-[#007542] text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        تسجيل تحصيل
                    </button>
                    <button @click="showReturnModal = true" class="px-3.5 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                        مرتجع بيع
                    </button>
                    @endif
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
                            <p class="text-sm sm:text-base font-black text-slate-800 mt-0.5" dir="ltr">{{ number_format($totalsales ?? ($totalSales ?? 0), 0) }} <span class="text-[0.65rem] text-slate-400 font-normal">ج.م</span></p>
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
                    $balanceLabel = $isOwed ? 'مطلوب من العميل (لنا عنده)' : ($isCredit ? 'رصيد للعميل (له عندنا)' : 'حالة الحساب');
                    $badgeText = $isOwed ? 'مطلوب منه' : ($isCredit ? 'رصيد للعميل' : 'خالص');
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
                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-blue-50 text-blue-600 border border-blue-100">سداد دفعة</span>
                    @elseif($transaction->type === 'return_sale')
                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-amber-50 text-amber-600 border border-amber-100">مرتجع بيع</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-600">{{ transaction_type_label($transaction->type) }}</span>
                    @endif
                    <span class="text-[0.65rem] text-slate-400 font-bold">{{ $transaction->transaction_date->format('d/m/Y') }}</span>
                </div>
                @if($transaction->notes)<p class="text-xs text-slate-500 mb-2 truncate">{{ $transaction->notes }}</p>@endif
                <div class="flex justify-between items-center text-xs border-t border-slate-50 pt-2 mb-2">
                    <div>
                        <span class="text-slate-400">الكمية:</span>
                        <span class="font-bold text-slate-700" dir="ltr">{{ $transaction->quantity ? format_quantity($transaction->quantity) . ' ك' : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400">سعر الكيلو:</span>
                        <span class="font-bold text-slate-700" dir="ltr">
                            @if($transaction->quantity > 0)
                                {{ format_amount($transaction->total_amount / $transaction->quantity) }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center text-xs border-t border-slate-50 pt-2">
                    <div>
                        <span class="text-slate-400">المبلغ:</span>
                        <span class="font-bold text-slate-700" dir="ltr">{{ format_amount($transaction->total_amount) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400">مسدد:</span>
                        <span class="font-bold text-primary-600" dir="ltr">{{ format_amount($transaction->paid_amount) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400">المتبقي:</span>
                        <span class="font-black text-danger-600" dir="ltr">{{ format_amount($transaction->balance_after) }}</span>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-2 pt-2 border-t border-slate-50">
                    <div class="flex items-center justify-center gap-1.5">
                        @if($transaction->type === 'sale' && $transaction->invoice_id)
                            <button type="button" @click="viewSale({{ $transaction->invoice_id }})" class="p-1.5 rounded border border-slate-200 bg-white text-emerald-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض الفاتورة">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            @if((int) $transaction->invoice_id === (int) $latestSaleId && auth()->user()?->hasPermission('sales.update'))
                                <button type="button" @click="editSale({{ $transaction->invoice_id }})" class="p-1.5 rounded border border-slate-200 bg-white text-blue-600 hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                            @endif
                        @else
                            <button type="button" @click="$dispatch('view-transaction', {{ $transaction->id }})" class="p-1.5 rounded border border-slate-200 bg-white text-emerald-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض التفاصيل / الإيصال">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            @if(auth()->user()?->hasPermission('customers.update'))
                            <button type="button" @click="editTransactionModal = true; editType = '{{ $transaction->type }}'; editId = '{{ $transaction->id }}'; editDate = '{{ $transaction->transaction_date->format('Y-m-d') }}'; editAmount = '{{ in_array($transaction->type, ['payment_received', 'payment_made']) ? $transaction->paid_amount : $transaction->total_amount }}'; editQuantity = '{{ $transaction->quantity ?? '' }}'; editNotes = '{{ addslashes($transaction->notes ?? '') }}'" class="p-1.5 rounded border border-slate-200 bg-white text-blue-600 hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            @endif
                        @endif
                    </div>
                    @php
                        $deleteAction = route('transactions.destroy', $transaction->id);
                    @endphp
                    @if(auth()->user()?->hasPermission('customers.update'))
                    <button type="button" x-on:click="$dispatch('open-modal', 'delete-transaction-{{ $transaction->id }}')" class="p-1.5 rounded border border-slate-200 bg-white text-danger-600 hover:text-danger-700 hover:border-danger-300 hover:bg-danger-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                    <x-delete-modal name="delete-transaction-{{ $transaction->id }}" action="{{ $deleteAction }}" />
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">لا توجد عمليات مسجلة.</div>
            @endforelse
        </div>

        <!-- Desktop Transactions Table -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 mb-6">
            <div class="p-5 border-b border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    سجل العمليات
                </h4>
            </div>
            <div class="p-5">
                <div class="overflow-x-auto relative rounded-xl border border-slate-100 bg-white">
                    <table class="w-full text-center border-collapse whitespace-nowrap">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">التاريخ</th>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">نوع العملية</th>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">البيان</th>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">الكمية</th>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">سعر الكيلو</th>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">إجمالي العملية</th>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">المدفوع منها</th>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">المتبقي منها</th>
                                <th class="px-2.5 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 font-medium">{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                <td class="px-2.5 py-3 border-b border-slate-100">
                                    @if($transaction->type === 'sale')
                                        <span class="px-2 py-1 rounded text-[0.7rem] font-bold bg-primary-50 text-primary-600 border border-primary-100">بيع</span>
                                    @elseif($transaction->type === 'payment_received')
                                        <span class="px-2 py-1 rounded text-[0.7rem] font-bold bg-blue-50 text-blue-600 border border-blue-100">سداد دفعة</span>
                                    @elseif($transaction->type === 'return_sale')
                                        <span class="px-2 py-1 rounded text-[0.7rem] font-bold bg-amber-50 text-amber-600 border border-amber-100">مرتجع بيع</span>
                                    @else
                                        <span class="px-2 py-1 rounded text-[0.7rem] font-bold bg-slate-100 text-slate-600">{{ transaction_type_label($transaction->type) }}</span>
                                    @endif
                                </td>
                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100">{{ $transaction->notes ?? '-' }}</td>
                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100" dir="ltr">{{ $transaction->quantity ? format_quantity($transaction->quantity) . ' ك' : '-' }}</td>
                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100" dir="ltr">
                                    @if($transaction->quantity > 0)
                                        {{ format_amount($transaction->total_amount / $transaction->quantity) }} <span class="text-[0.65rem] text-slate-400">ج.م</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100" dir="ltr">{{ $transaction->total_amount > 0 ? format_amount($transaction->total_amount) . ' ج.م' : '-' }}</td>
                                <td class="px-2.5 py-3 text-[0.8rem] text-emerald-600 font-bold border-b border-slate-100" dir="ltr">
                                    {{ $transaction->paid_amount > 0 ? format_amount($transaction->paid_amount) . ' ج.م' : '-' }}
                                </td>
                                <td class="px-2.5 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100" dir="ltr">
                                    @if(in_array($transaction->type, ['sale', 'return_sale', 'purchase', 'return_purchase']))
                                        @php
                                            $uncovered = $transaction->total_amount - $transaction->paid_amount;
                                        @endphp
                                        @if($uncovered <= 0)
                                            <span class="text-emerald-500 font-bold text-xs bg-emerald-50 px-2 py-0.5 rounded">خالصة</span>
                                        @else
                                            <span class="text-danger-500 font-bold">{{ number_format($uncovered, 0) }} ج.م</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-2.5 py-3 border-b border-slate-100 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($transaction->type === 'sale' && $transaction->invoice_id)
                                            <button type="button" @click="viewSale({{ $transaction->invoice_id }})" class="p-1 rounded border border-slate-200 bg-white text-emerald-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض الفاتورة">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </button>
                                            @if((int) $transaction->invoice_id === (int) $latestSaleId && auth()->user()?->hasPermission('sales.update'))
                                                <button type="button" @click="editSale({{ $transaction->invoice_id }})" class="p-1 rounded border border-slate-200 bg-white text-blue-600 hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </button>
                                            @endif
                                        @else
                                            <button type="button" @click="$dispatch('view-transaction', {{ $transaction->id }})" class="p-1 rounded border border-slate-200 bg-white text-emerald-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض التفاصيل / الإيصال">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </button>
                                            @if(auth()->user()?->hasPermission('customers.update'))
                                            <button type="button" @click="editTransactionModal = true; editType = '{{ $transaction->type }}'; editId = '{{ $transaction->id }}'; editDate = '{{ $transaction->transaction_date->format('Y-m-d') }}'; editAmount = '{{ in_array($transaction->type, ['payment_received', 'payment_made']) ? $transaction->paid_amount : $transaction->total_amount }}'; editQuantity = '{{ $transaction->quantity ?? '' }}'; editNotes = '{{ addslashes($transaction->notes ?? '') }}'" class="p-1 rounded border border-slate-200 bg-white text-blue-600 hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                            @endif
                                        @endif
                                        @php
                                            $deleteAction = route('transactions.destroy', $transaction->id);
                                        @endphp
                                        @if(auth()->user()?->hasPermission('customers.update'))
                                        <button type="button" x-on:click="$dispatch('open-modal', 'delete-transaction-{{ $transaction->id }}')" class="p-1 rounded border border-slate-200 bg-white text-danger-600 hover:text-danger-700 hover:border-danger-300 hover:bg-danger-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                        <x-delete-modal name="delete-transaction-{{ $transaction->id }}" action="{{ $deleteAction }}" />
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="px-4 py-8 text-sm text-slate-500 text-center">لا توجد عمليات مسجلة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($transactions->hasPages())
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        @endif

        <!-- Payment Modal (Wide Layout with Live Balance Indicator) -->
        <div x-show="showPaymentModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-start justify-center min-h-screen pt-10 sm:pt-16 p-4 text-center" @click.self="showPaymentModal = false">
                <div x-show="showPaymentModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showPaymentModal = false"></div>
                <div x-show="showPaymentModal" 
                     @click.outside="showPaymentModal = false"
                     x-data="{
                         paymentType: 'account',
                         amount: '',
                         date: '{{ date('Y-m-d') }}',
                         notes: '',
                         saleId: '',
                         currentBalance: {{ (float)$customer->balance }},
                         unpaidSales: {{ Js::from($unpaidSales) }},
                         get selectedSale() {
                             if (!this.saleId) return null;
                             return this.unpaidSales.find(s => s.id == this.saleId) || null;
                         },
                         get newBalance() {
                             return this.currentBalance - (parseFloat(this.amount) || 0);
                         },
                         get invoiceNewRemaining() {
                             if (!this.selectedSale) return 0;
                             return Math.max(0, this.selectedSale.remaining_amount - (parseFloat(this.amount) || 0));
                         },
                         get amountExceedsRemaining() {
                             if (this.paymentType !== 'invoice' || !this.selectedSale) return false;
                             return (parseFloat(this.amount) || 0) > this.selectedSale.remaining_amount;
                         },
                         switchType(type) {
                             this.paymentType = type;
                             this.saleId = '';
                             this.amount = '';
                         },
                         formatBalance(val) {
                             if (!val || val == 0) return '0 ج.م (خالص)';
                             if (val > 0) return Number(val).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م (مطلوب منه)';
                             return Number(Math.abs(val)).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م (له عندنا)';
                         }
                     }"
                     x-transition class="relative w-full max-w-2xl p-5 sm:p-6 overflow-hidden text-right transition-all transform bg-white shadow-xl rounded-2xl z-10">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">تسجيل تحصيل من العميل: {{ $customer->name }}</h3>
                        </div>
                        <button type="button" @click="showPaymentModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    {{-- Payment Type Toggle --}}
                    <div class="flex items-center gap-2 mb-5 p-1 bg-slate-100 rounded-xl">
                        <button type="button" @click="switchType('account')"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                                :class="paymentType === 'account' ? 'bg-white text-slate-800 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700'">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            تحصيل على الحساب العام
                        </button>
                        <button type="button" @click="switchType('invoice')"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                                :class="paymentType === 'invoice' ? 'bg-white text-slate-800 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700'">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            تحصيل لحساب فاتورة
                        </button>
                    </div>

                    <form action="{{ route('customers.payment', $customer->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="sale_id" :value="paymentType === 'invoice' ? saleId : ''">

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 mb-5">
                            <!-- Main Inputs (7 cols) -->
                            <div class="md:col-span-7 space-y-4">

                                {{-- Invoice selector (only for invoice-level payment) --}}
                                <div x-show="paymentType === 'invoice'" x-collapse.duration.200ms>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">اختر الفاتورة <span class="text-danger-500">*</span></label>
                                    <template x-if="unpaidSales.length === 0">
                                        <div class="px-3 py-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-700 font-medium flex items-center gap-2">
                                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                            لا توجد فواتير غير مسددة بالكامل لهذا العميل
                                        </div>
                                    </template>
                                    <template x-if="unpaidSales.length > 0">
                                        <select x-model="saleId" :required="paymentType === 'invoice'" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 bg-slate-50 text-xs font-bold text-slate-800">
                                            <option value="">-- اختر فاتورة --</option>
                                            <template x-for="sale in unpaidSales" :key="sale.id">
                                                <option :value="sale.id" x-text="sale.invoice_number + ' — إجمالي: ' + Number(sale.total_amount).toLocaleString() + ' — متبقي: ' + Number(sale.remaining_amount).toLocaleString() + ' ج.م (' + sale.date + ')'"></option>
                                            </template>
                                        </select>
                                    </template>

                                    {{-- Selected invoice info card --}}
                                    <div x-show="selectedSale" x-collapse.duration.200ms class="mt-2.5 p-3 bg-blue-50/70 border border-blue-100 rounded-xl space-y-1.5">
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-slate-500 font-medium">إجمالي الفاتورة:</span>
                                            <span class="font-bold text-slate-700" dir="ltr" x-text="selectedSale ? Number(selectedSale.total_amount).toLocaleString() + ' ج.م' : ''"></span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-slate-500 font-medium">المدفوع:</span>
                                            <span class="font-bold text-emerald-600" dir="ltr" x-text="selectedSale ? Number(selectedSale.paid_amount).toLocaleString() + ' ج.م' : ''"></span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs pt-1.5 border-t border-blue-200">
                                            <span class="text-slate-600 font-bold">المتبقي على الفاتورة:</span>
                                            <span class="font-black text-danger-600" dir="ltr" x-text="selectedSale ? Number(selectedSale.remaining_amount).toLocaleString() + ' ج.م' : ''"></span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">المبلغ المحصل (ج.م) <span class="text-danger-500">*</span></label>
                                    <input type="number" step="0.01" name="amount" x-model="amount" min="0.01" 
                                           :max="paymentType === 'invoice' && selectedSale ? selectedSale.remaining_amount : undefined"
                                           required placeholder="0.00" class="w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 text-left bg-slate-50 text-base font-bold text-slate-800" 
                                           :class="amountExceedsRemaining ? 'border-danger-400 ring-2 ring-danger-100' : 'border-slate-200'" dir="ltr">
                                    <p x-show="amountExceedsRemaining" class="mt-1 text-[0.7rem] text-danger-600 font-bold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>المبلغ أكبر من المتبقي على الفاتورة</span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ التحصيل <span class="text-danger-500">*</span></label>
                                    <input type="date" name="date" required x-model="date" max="{{ date('Y-m-d') }}" lang="en" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 text-left bg-slate-50 text-xs font-bold" dir="ltr">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">البيان / ملاحظات (اختياري)</label>
                                    <input type="text" name="notes" x-model="notes" placeholder="دفعة نقدية، تحويل بنكي..." class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 bg-slate-50 text-xs">
                                </div>
                            </div>

                            <!-- Live Balance Card (5 cols) -->
                            <div class="md:col-span-5 flex flex-col justify-between p-3.5 sm:p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mb-3">
                                        <svg class="w-3.5 h-3.5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                        مؤشر الحساب المالي
                                    </h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-slate-500">الرصيد الحالي:</span>
                                            <div class="text-left font-bold text-slate-800" dir="ltr">
                                                <span x-text="Number(Math.abs(currentBalance)).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                                <span class="text-[0.7rem] text-slate-400 font-normal">ج.م</span>
                                                <span class="text-[0.65rem] px-1.5 py-0.5 rounded font-bold mr-1"
                                                      :class="currentBalance > 0 ? 'bg-rose-50 text-rose-600' : (currentBalance < 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500')"
                                                      x-text="currentBalance > 0 ? 'مطلوب منه' : (currentBalance < 0 ? 'له دائن' : 'خالص')">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-slate-500">المبلغ المحصل:</span>
                                            <div class="text-left font-bold text-emerald-600" dir="ltr">
                                                <span x-text="(parseFloat(amount) || 0).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                                <span class="text-[0.7rem] font-normal">ج.م</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-200">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700">الرصيد بعد التحصيل:</span>
                                        <span class="text-[0.7rem] font-bold px-2 py-0.5 rounded-full"
                                              :class="newBalance > 0 ? 'bg-rose-100 text-rose-700' : (newBalance < 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700')"
                                              x-text="newBalance > 0 ? 'مطلوب منه' : (newBalance < 0 ? 'له دائن' : 'خالص')">
                                        </span>
                                    </div>
                                    <div class="text-left font-black text-sm sm:text-base" dir="ltr"
                                         :class="newBalance > 0 ? 'text-rose-600' : (newBalance < 0 ? 'text-emerald-600' : 'text-slate-700')">
                                        <span x-text="Number(Math.abs(newBalance)).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                        <span class="text-xs font-normal text-slate-400">ج.م</span>
                                    </div>
                                </div>

                                {{-- Invoice remaining after payment (only for invoice-level) --}}
                                <div x-show="paymentType === 'invoice' && selectedSale" x-collapse.duration.200ms class="pt-3 border-t border-slate-200">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-700">متبقي الفاتورة بعد التحصيل:</span>
                                    </div>
                                    <div class="text-left font-black text-sm" dir="ltr"
                                         :class="invoiceNewRemaining > 0 ? 'text-amber-600' : 'text-emerald-600'">
                                        <span x-text="Number(invoiceNewRemaining).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                        <span class="text-xs font-normal text-slate-400">ج.م</span>
                                        <span x-show="invoiceNewRemaining <= 0" class="text-[0.65rem] px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 font-bold mr-1">مسددة بالكامل</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-3 border-t border-slate-100">
                            <button type="submit" :disabled="amountExceedsRemaining || (paymentType === 'invoice' && !saleId)" 
                                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-sm shadow-emerald-600/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all">حفظ التحصيل</button>
                            <button type="button" @click="showPaymentModal = false" class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Return Modal (Wide Layout with Live Price Hint and Balance Projection) -->
        <div x-show="showReturnModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-start justify-center min-h-screen pt-10 sm:pt-16 p-4 text-center" @click.self="showReturnModal = false">
                <div x-show="showReturnModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showReturnModal = false"></div>
                <div x-show="showReturnModal" 
                     @click.outside="showReturnModal = false"
                     x-data="{
                         openProduct: false,
                         searchProduct: '',
                         products: {{ Js::from($products) }},
                         selectedId: '',
                         selectedProduct: null,
                         productError: false,
                         quantity: 1,
                         unitPrice: '',
                         amount: '',
                         paidAmount: 0,
                         date: '{{ date('Y-m-d') }}',
                         notes: '',
                         currentBalance: {{ (float)$customer->balance }},
                         priceInfo: null,
                         priceInfoLoading: false,

                         get filteredProducts() {
                             if (!this.searchProduct) return this.products;
                             return this.products.filter(p => p.name.toLowerCase().includes(this.searchProduct.toLowerCase()));
                         },

                         selectProduct(p) {
                             this.selectedId = p.id;
                             this.selectedProduct = p;
                             this.openProduct = false;
                             this.searchProduct = '';
                             this.fetchPriceInfo(p.id);
                         },

                         fetchPriceInfo(productId) {
                             this.priceInfoLoading = true;
                             this.priceInfo = null;
                             fetch(`/api/products/${productId}/price-info?type=sale&entity_id={{ $customer->id }}`)
                                 .then(res => res.json())
                                 .then(data => {
                                     this.priceInfo = data;
                                     this.priceInfoLoading = false;
                                     if (data.entity && data.entity !== 'لا يوجد' && !this.unitPrice) {
                                         this.unitPrice = parseFloat(data.entity.replace(/,/g, ''));
                                         this.calcAmount();
                                     } else if (data.overall && data.overall !== 'لا يوجد' && !this.unitPrice) {
                                         this.unitPrice = parseFloat(data.overall.replace(/,/g, ''));
                                         this.calcAmount();
                                     }
                                 }).catch(() => {
                                     this.priceInfoLoading = false;
                                 });
                         },

                         calcAmount() {
                             if (this.quantity && this.unitPrice !== '') {
                                 const val = (parseFloat(this.quantity) || 0) * (parseFloat(this.unitPrice) || 0);
                                 this.amount = Number(val.toFixed(2));
                             }
                         },

                         calcUnitPrice() {
                             if (this.quantity && this.amount !== '' && parseFloat(this.quantity) > 0) {
                                 const val = (parseFloat(this.amount) || 0) / (parseFloat(this.quantity) || 1);
                                 this.unitPrice = Number(val.toFixed(2));
                             }
                         },

                         get newBalance() {
                             const retVal = parseFloat(this.amount) || 0;
                             const refunded = parseFloat(this.paidAmount) || 0;
                             return this.currentBalance - (retVal - refunded);
                         },

                         formatBalance(val) {
                             if (!val || val == 0) return '0 ج.م (خالص)';
                             if (val > 0) return Number(val).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م (مطلوب منه)';
                             return Number(Math.abs(val)).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م (له عندنا)';
                         },

                         submitForm(e) {
                             if (!this.selectedId) {
                                 this.productError = true;
                                 this.openProduct = true;
                                 window.dispatchEvent(new CustomEvent('show-toast', { 
                                     detail: { message: 'يرجى اختيار الصنف / المنتج المراد إرجاعه أولاً', type: 'error' } 
                                 }));
                                 return;
                             }
                             if (!this.amount || parseFloat(this.amount) <= 0) {
                                 window.dispatchEvent(new CustomEvent('show-toast', { 
                                     detail: { message: 'يرجى إدخال إجمالي قيمة المرتجع بشكل صحيح', type: 'error' } 
                                 }));
                                 return;
                             }
                             this.productError = false;
                             const form = e.target.tagName === 'FORM' ? e.target : e.target.closest('form');
                             if (form) form.submit();
                         }
                     }"
                     x-transition class="relative w-full max-w-4xl p-5 sm:p-6 text-right transition-all transform bg-white shadow-xl rounded-2xl z-10">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">تسجيل مرتجع بيع من العميل: {{ $customer->name }}</h3>
                        </div>
                        <button type="button" @click="showReturnModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    <form action="{{ route('customers.return', $customer->id) }}" method="POST" @submit.prevent="if (!selectedId) { productError = true; return; } productError = false; $el.submit();">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-5">
                            
                            <!-- Left Info Cards (5 cols) -->
                            <div class="md:col-span-5 space-y-4">
                                <!-- Live Projected Balance Card -->
                                <div class="p-3.5 sm:p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                                    <h4 class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                        تأثير المرتجع على رصيد العميل
                                    </h4>
                                    <div class="space-y-2 text-xs border-b border-slate-200 pb-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500">الرصيد الحالي:</span>
                                            <div class="text-left font-bold text-slate-800" dir="ltr">
                                                <span x-text="Number(Math.abs(currentBalance)).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                                <span class="text-[0.7rem] text-slate-400 font-normal">ج.م</span>
                                                <span class="text-[0.65rem] px-1.5 py-0.5 rounded font-bold mr-1"
                                                      :class="currentBalance > 0 ? 'bg-rose-50 text-rose-600' : (currentBalance < 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500')"
                                                      x-text="currentBalance > 0 ? 'مطلوب منه' : (currentBalance < 0 ? 'له دائن' : 'خالص')">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500">قيمة المرتجع (يخصم):</span>
                                            <div class="text-left font-bold text-amber-600" dir="ltr">
                                                <span x-text="(parseFloat(amount) || 0).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                                <span class="text-[0.7rem] font-normal">ج.م</span>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center" x-show="parseFloat(paidAmount) > 0">
                                            <span class="text-slate-500">المسترد نقداً:</span>
                                            <div class="text-left font-bold text-blue-600" dir="ltr">
                                                <span x-text="(parseFloat(paidAmount) || 0).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                                <span class="text-[0.7rem] font-normal">ج.م</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-1">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs font-bold text-slate-700">الرصيد بعد المرتجع:</span>
                                            <span class="text-[0.7rem] font-bold px-2 py-0.5 rounded-full"
                                                  :class="newBalance > 0 ? 'bg-rose-100 text-rose-700' : (newBalance < 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700')"
                                                  x-text="newBalance > 0 ? 'مطلوب منه' : (newBalance < 0 ? 'له دائن' : 'خالص')">
                                            </span>
                                        </div>
                                        <div class="text-left font-black text-sm sm:text-base" dir="ltr"
                                             :class="newBalance > 0 ? 'text-rose-600' : (newBalance < 0 ? 'text-emerald-600' : 'text-slate-700')">
                                            <span x-text="Number(Math.abs(newBalance)).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                            <span class="text-xs font-normal text-slate-400">ج.م</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Reference Card -->
                                <div class="p-3.5 bg-blue-50/50 border border-blue-100 rounded-xl space-y-2">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-xs font-bold text-blue-800 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            أسعار المنتج السابقة
                                        </h5>
                                        <span x-show="priceInfoLoading" class="text-[0.65rem] text-blue-500 font-bold">جاري الجلب...</span>
                                    </div>
                                    <div class="space-y-1.5 text-xs">
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500 text-[0.7rem]">آخر سعر للعميل:</span>
                                            <span class="font-bold text-primary-700" dir="ltr" x-text="priceInfo && priceInfo.entity !== 'لا يوجد' ? priceInfo.entity + ' ج.م' : 'لا يوجد'"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500 text-[0.7rem]">آخر سعر بيع عام:</span>
                                            <span class="font-bold text-slate-700" dir="ltr" x-text="priceInfo && priceInfo.overall !== 'لا يوجد' ? priceInfo.overall + ' ج.م' : 'لا يوجد'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Main Inputs (7 cols) -->
                            <div class="md:col-span-7 space-y-4">
                                <!-- Searchable Product Combobox -->
                                <div class="relative">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">المنتج المسترجع من العميل <span class="text-danger-500">*</span></label>
                                    <input type="hidden" name="product_id" :value="selectedId">
                                    
                                    <button type="button" 
                                            @click="openProduct = !openProduct" 
                                            :class="productError && !selectedId ? 'border-danger-500 bg-danger-50 text-danger-700 ring-2 ring-danger-100' : 'border-slate-200 bg-slate-50 hover:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100'"
                                            class="w-full flex items-center justify-between px-3.5 py-2.5 border rounded-xl focus:outline-none transition-all text-right">
                                        <span class="text-xs font-bold truncate" :class="selectedId ? 'text-slate-800' : (productError ? 'text-danger-600' : 'text-slate-400')" x-text="selectedProduct ? selectedProduct.name + ' (متوفر: ' + Number(selectedProduct.stock).toLocaleString('en-US') + ' ك)' : 'اختر المنتج المطلوب...'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 mr-2" :class="openProduct ? 'rotate-180 text-primary-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <!-- Error message -->
                                    <p x-show="productError && !selectedId" class="text-[0.7rem] text-danger-600 font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        يرجى اختيار الصنف / المنتج المراد إرجاعه أولاً
                                    </p>

                                    <!-- Dropdown Menu -->
                                    <div x-show="openProduct" 
                                         @click.outside="openProduct = false" 
                                         class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" 
                                         style="display: none;">
                                        <div class="p-2 border-b border-slate-100 bg-slate-50/70">
                                            <input type="text" 
                                                   x-model="searchProduct" 
                                                   placeholder="ابحث عن الصنف بالاسم..." 
                                                   class="w-full pl-3 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-primary-500"
                                                   @keydown.escape="openProduct = false">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto divide-y divide-slate-50">
                                            <template x-for="p in filteredProducts" :key="p.id">
                                                <button type="button" 
                                                        @click="selectProduct(p)" 
                                                        class="w-full px-3.5 py-2 text-right flex items-center justify-between hover:bg-primary-50/60 transition-colors group"
                                                        :class="selectedId == p.id ? 'bg-primary-50 font-bold' : ''">
                                                    <span class="text-xs text-slate-800 group-hover:text-primary-700 font-medium" x-text="p.name"></span>
                                                    <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-600 group-hover:bg-primary-100 group-hover:text-primary-800 shrink-0" dir="ltr" x-text="'متوفر: ' + Number(p.stock).toLocaleString('en-US') + ' ك'"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity, Unit Price, and Total Amount -->
                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الكمية (ك) <span class="text-danger-500">*</span></label>
                                        <input type="number" step="0.01" name="quantity" x-model="quantity" @input="calcAmount()" min="0.01" required class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-center bg-slate-50 text-sm font-bold" dir="ltr">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">سعر الكيلو</label>
                                        <input type="number" step="0.01" x-model="unitPrice" @input="calcAmount()" min="0" placeholder="0.00" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-center bg-slate-50 text-sm font-bold" dir="ltr">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">إجمالي المرتجع <span class="text-danger-500">*</span></label>
                                        <input type="number" step="0.01" name="amount" x-model="amount" @input="calcUnitPrice()" min="0.01" required class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-center bg-slate-50 text-sm font-bold text-amber-700" dir="ltr">
                                    </div>
                                </div>

                                <!-- Paid / Refunded Amount -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">المبلغ المسترد نقداً للعميل (اختياري)</label>
                                    <input type="number" step="0.01" name="paid_amount" x-model="paidAmount" min="0" placeholder="0.00" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-xs font-bold" dir="ltr">
                                    <p class="text-[0.65rem] text-slate-400 mt-1">اتركه 0 إذا كان المرتجع آجل ويخصم من مديونية العميل فقط دون دفع نقدية له.</p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ المرتجع <span class="text-danger-500">*</span></label>
                                        <input type="date" name="date" required x-model="date" max="{{ date('Y-m-d') }}" lang="en" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-xs font-bold" dir="ltr">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">البيان / ملاحظات (اختياري)</label>
                                        <input type="text" name="notes" x-model="notes" placeholder="ملاحظات..." class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-xs">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-3 border-t border-slate-100">
                            <button type="button" @click="submitForm($event)" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-slate-800 rounded-xl hover:bg-slate-900 shadow-sm">حفظ المرتجع</button>
                            <button type="button" @click="showReturnModal = false" class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Transaction Modal -->
        <div x-show="editTransactionModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-start justify-center min-h-screen pt-10 sm:pt-16 p-4 text-center" @click.self="editTransactionModal = false">
                <div x-show="editTransactionModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="editTransactionModal = false"></div>
                <div x-show="editTransactionModal" @click.outside="editTransactionModal = false" x-transition class="relative w-full max-w-md p-5 sm:p-6 text-right transition-all transform bg-white shadow-2xl rounded-2xl z-10">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">تعديل العملية</h3>
                        <button type="button" @click="editTransactionModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    <form :action="'{{ url('transactions') }}/' + editId" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div x-show="editType === 'return_sale' || editType === 'sale'">
                                <label class="block text-sm font-medium text-slate-700 mb-1">الكمية</label>
                                <input type="number" step="0.01" name="quantity" x-model="editQuantity" min="0.01" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">المبلغ (ج.م)</label>
                                <input type="number" step="0.01" name="amount" x-model="editAmount" min="0" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">تاريخ العملية</label>
                                <input type="date" name="date" x-model="editDate" max="{{ date('Y-m-d') }}" lang="en" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left bg-slate-50 text-base" dir="ltr">
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

        <!-- Sale Modal Component -->
        <x-modals.sale-form :products="$products" :customers="[]" :fixedCustomer="$customer" />

        <!-- View Sale Invoice Details Modal -->
        <x-modals.invoice-details type="sale" />

        <!-- View Transaction Details Modal (for Payments and Returns) -->
        <x-modals.transaction-details />

        <!-- Print Statement Modal -->
        <x-modals.print-statement :entityId="$customer->id" type="customer" />

    </div>
</x-layouts.app>
