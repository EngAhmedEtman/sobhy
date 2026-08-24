<template x-teleport="body">
    <div
        x-show="transactionModalOpen"
        x-cloak
        @keydown.escape.window="closeTransactionModal()"
        class="fixed inset-0 z-[100] overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="global-transaction-modal-title"
    >
        <div class="flex min-h-full items-start justify-center p-3 sm:p-5 sm:pt-10">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeTransactionModal()"></div>

            <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-2xl bg-white text-right shadow-2xl" @click.stop>
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-6">
                    <div>
                        <h3 id="global-transaction-modal-title" class="text-lg font-black text-slate-800">تفاصيل العملية</h3>
                        <p x-show="transactionDetails" class="mt-0.5 text-xs text-slate-400" x-text="'رقم العملية: #' + String(transactionDetails?.id || '').padStart(6, '0')"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a x-show="transactionDetails && transactionPrintUrl" :href="transactionPrintUrl" target="_blank" rel="noopener" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100">طباعة</a>
                        <button type="button" @click="closeTransactionModal()" aria-label="إغلاق تفاصيل العملية" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div x-show="transactionLoading" class="flex min-h-64 items-center justify-center py-16">
                    <div class="text-center">
                        <svg class="mx-auto h-9 w-9 animate-spin text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <p class="mt-3 text-sm font-bold text-slate-500">جارٍ تحميل العملية...</p>
                    </div>
                </div>

                <div x-show="!transactionLoading && transactionError" class="p-6">
                    <div class="rounded-xl border border-red-100 bg-red-50 p-5 text-center text-sm font-bold text-red-700" x-text="transactionError"></div>
                </div>

                <template x-if="!transactionLoading && transactionDetails">
                    <div class="max-h-[75vh] space-y-5 overflow-y-auto p-4 sm:p-6">
                        <x-modals.global-transaction-details-content />
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
