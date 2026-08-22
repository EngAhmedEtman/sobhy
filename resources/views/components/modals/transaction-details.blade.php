<!-- View Transaction Details Modal -->
<div x-data="{
    showTransactionDetailsModal: false,
    transactionDetails: null,
    loadingTransaction: false,
    viewTransaction(id) {
        this.loadingTransaction = true;
        this.showTransactionDetailsModal = true;
        fetch(`/transactions/${id}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            this.transactionDetails = data;
            this.loadingTransaction = false;
        });
    }
}"
@view-transaction.window="viewTransaction($event.detail)">

    <div x-show="showTransactionDetailsModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center sm:p-0">
            <div x-show="showTransactionDetailsModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showTransactionDetailsModal = false"></div>
            <div x-show="showTransactionDetailsModal" x-transition class="relative w-full max-w-2xl p-5 sm:p-6 text-right transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">تفاصيل العملية</h3>
                    <div class="flex items-center gap-2">
                        <template x-if="transactionDetails && transactionDetails.id">
                            <div class="flex items-center gap-2">
                                <!-- Print Button -->
                                <button @click="openPrintPreviewModal('printPreviewModal', `/transactions/${transactionDetails.id}/print`)" class="px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-200 text-xs font-bold flex items-center gap-1.5 transition-colors" title="طباعة الإيصال">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    <span>طباعة</span>
                                </button>
                                <!-- Edit Button -->
                                <button @click="$dispatch('edit-transaction', transactionDetails); showTransactionDetailsModal = false" class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-800 text-xs font-bold flex items-center gap-1.5 transition-colors" title="تعديل العملية">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    <span>تعديل</span>
                                </button>
                            </div>
                        </template>
                        <button @click="showTransactionDetailsModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                </div>

                <div x-show="loadingTransaction" class="py-12 flex justify-center">
                    <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>

                <div x-show="!loadingTransaction && transactionDetails" class="space-y-6">
                    <template x-if="transactionDetails">
                        <div>
                            <!-- Header Info -->
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">نوع العملية</p>
                                    <p class="font-bold text-primary-700" x-text="
                                        transactionDetails.type === 'payment_received' ? 'سداد دفعة من عميل' :
                                        transactionDetails.type === 'payment_made' ? 'سداد دفعة لمورد' :
                                        transactionDetails.type === 'return_sale' ? 'مرتجع مبيعات' :
                                        transactionDetails.type === 'return_purchase' ? 'مرتجع مشتريات' : transactionDetails.type
                                    "></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">الطرف الثاني</p>
                                    <p class="font-bold text-slate-800" x-text="transactionDetails.party_name"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">رقم العملية</p>
                                    <p class="font-bold text-slate-800" x-text="'#' + String(transactionDetails.id).padStart(6, '0')"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">التاريخ</p>
                                    <p class="font-bold text-slate-800" x-text="transactionDetails.transaction_date" dir="ltr"></p>
                                </div>
                            </div>

                            <!-- Item Info if Return -->
                            <template x-if="['return_sale', 'return_purchase'].includes(transactionDetails.type) && transactionDetails.product_name">
                                <div class="bg-white p-4 rounded-xl border border-slate-200 mb-4">
                                    <h4 class="text-sm font-bold text-slate-800 mb-3">تفاصيل المنتج المسترجع</h4>
                                    <div class="grid grid-cols-3 gap-4 text-center">
                                        <div>
                                            <p class="text-xs text-slate-500 mb-1">المنتج</p>
                                            <p class="font-bold text-slate-800 text-sm" x-text="transactionDetails.product_name"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 mb-1">الكمية</p>
                                            <p class="font-bold text-slate-800 text-sm" dir="ltr" x-text="transactionDetails.quantity + ' ك'"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 mb-1">سعر الوحدة</p>
                                            <p class="font-bold text-slate-800 text-sm" dir="ltr" x-text="Number(transactionDetails.unit_price).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م'"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Financial details -->
                            <div class="p-4 bg-emerald-50/50 rounded-xl border border-emerald-100 space-y-3">
                                <h4 class="text-sm font-bold text-emerald-800 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    الملخص المالي
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center pb-2 border-b border-emerald-100">
                                        <span class="text-xs font-bold text-slate-600" x-text="['return_sale', 'return_purchase'].includes(transactionDetails.type) ? 'إجمالي المرتجع' : 'المبلغ المسدد'"></span>
                                        <span class="text-sm font-bold text-emerald-700" dir="ltr" x-text="Number(transactionDetails.amount).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م'"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-slate-600">الرصيد بعد العملية</span>
                                        <span class="text-sm font-bold text-slate-800" dir="ltr" x-text="
                                            transactionDetails.balance_after < 0 ? Number(Math.abs(transactionDetails.balance_after)).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م (لنا)' : 
                                             (transactionDetails.balance_after > 0 ? Number(transactionDetails.balance_after).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م (عليه)' : 'خالص')
                                        "></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes -->
                            <div x-show="transactionDetails.notes" class="mt-4 p-3 bg-slate-50 rounded-lg border border-slate-100">
                                <p class="text-xs font-bold text-slate-500 mb-1">ملاحظات</p>
                                <p class="text-sm text-slate-700" x-text="transactionDetails.notes"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
