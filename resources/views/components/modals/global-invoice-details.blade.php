<template x-teleport="body">
<div
    x-show="invoiceModalOpen"
    x-cloak
    @keydown.escape.window="closeInvoiceModal()"
    class="fixed inset-0 z-[100] overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="global-invoice-modal-title"
>
    <div class="flex min-h-full items-center justify-center p-3 sm:p-5">
        <div x-show="invoiceModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeInvoiceModal()"></div>

        <div x-show="invoiceModalOpen" x-transition @click.stop class="relative z-10 w-full max-w-5xl overflow-hidden rounded-2xl bg-white text-right shadow-2xl">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-6">
                <div>
                    <h3 id="global-invoice-modal-title" class="text-base font-black text-slate-800 sm:text-lg" x-text="invoiceType === 'sale' ? 'تفاصيل فاتورة المبيعات' : 'تفاصيل فاتورة المشتريات'"></h3>
                    <p x-show="invoiceDetails" class="mt-0.5 text-xs text-slate-400" x-text="`رقم الفاتورة: ${invoiceDetails?.invoice_number || ''}`"></p>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        x-show="invoiceDetails && invoicePrintUrl"
                        :href="invoicePrintUrl"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 transition-colors hover:bg-slate-100"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        <span>طباعة</span>
                    </a>
                    <button type="button" @click="closeInvoiceModal()" aria-label="إغلاق تفاصيل الفاتورة" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <div x-show="invoiceLoading" class="flex min-h-64 items-center justify-center py-16">
                <div class="text-center">
                    <svg class="mx-auto h-9 w-9 animate-spin text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <p class="mt-3 text-sm font-bold text-slate-500">جارٍ تحميل الفاتورة...</p>
                </div>
            </div>

            <div x-show="!invoiceLoading && invoiceError" class="p-6 sm:p-10">
                <div class="rounded-xl border border-red-100 bg-red-50 p-5 text-center text-sm font-bold text-red-700" x-text="invoiceError"></div>
            </div>

            <template x-if="!invoiceLoading && invoiceDetails">
                <div class="grid max-h-[75vh] grid-cols-1 gap-5 overflow-y-auto p-4 sm:p-6 lg:grid-cols-12">
                    <aside class="space-y-4 lg:col-span-4">
                        <div class="space-y-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <div>
                                <p class="mb-1 text-xs font-medium text-slate-500">رقم الفاتورة</p>
                                <p class="font-black text-primary-700" dir="ltr" x-text="invoiceDetails.invoice_number"></p>
                            </div>
                            <div>
                                <p class="mb-1 text-xs font-medium text-slate-500" x-text="invoiceType === 'sale' ? 'العميل' : 'المورد'"></p>
                                <p class="font-bold text-slate-800" x-text="invoiceDetails.customer_name || invoiceDetails.supplier_name"></p>
                            </div>
                            <div>
                                <p class="mb-1 text-xs font-medium text-slate-500">التاريخ</p>
                                <p class="font-bold text-slate-800" dir="ltr" x-text="invoiceDetails.date"></p>
                            </div>
                            <div x-show="invoiceDetails.notes">
                                <p class="mb-1 text-xs font-medium text-slate-500">ملاحظات</p>
                                <p class="whitespace-pre-wrap text-sm font-medium text-slate-700" x-text="invoiceDetails.notes"></p>
                            </div>
                        </div>

                        <div class="space-y-3 rounded-xl border border-blue-100 bg-blue-50/60 p-4">
                            <h4 class="text-sm font-black text-blue-800">الملخص المالي</h4>
                            <div class="flex items-center justify-between border-b border-blue-100 pb-2">
                                <span class="text-xs font-bold text-slate-500">إجمالي الفاتورة</span>
                                <span class="text-sm font-black text-slate-800" dir="ltr" x-text="`${Number(invoiceDetails.total_amount || 0).toLocaleString()} ج.م`"></span>
                            </div>
                            <div class="flex items-center justify-between border-b border-blue-100 pb-2">
                                <span class="text-xs font-bold text-slate-500">المدفوع نقداً</span>
                                <span class="text-sm font-black text-emerald-600" dir="ltr" x-text="`${Number(invoiceDetails.transaction?.paid_cash || 0).toLocaleString()} ج.م`"></span>
                            </div>
                            <div class="flex items-center justify-between border-b border-blue-100 pb-2">
                                <span class="text-xs font-bold text-slate-500">المسدد من الرصيد</span>
                                <span class="text-sm font-black text-blue-600" dir="ltr" x-text="`${Number(invoiceDetails.transaction?.paid_from_balance || 0).toLocaleString()} ج.م`"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">المتبقي</span>
                                <span class="text-sm font-black text-red-600" dir="ltr" x-text="`${Number(invoiceDetails.transaction?.remaining_from_this ?? invoiceDetails.total_amount ?? 0).toLocaleString()} ج.م`"></span>
                            </div>
                        </div>
                    </aside>

                    <section class="lg:col-span-8">
                        <h4 class="mb-2 text-sm font-black text-slate-800">الأصناف</h4>
                        <div class="overflow-hidden rounded-xl border border-slate-200">
                            <div class="hidden grid-cols-12 gap-2 border-b border-slate-200 bg-slate-50 px-4 py-2 text-center text-xs font-bold text-slate-500 sm:grid">
                                <div class="col-span-5 text-right">المنتج</div>
                                <div class="col-span-2">الكمية</div>
                                <div class="col-span-2">السعر</div>
                                <div class="col-span-3 text-left">الإجمالي</div>
                            </div>
                            <div class="divide-y divide-slate-100">
                                <template x-for="invoiceItem in invoiceDetails.items" :key="invoiceItem.id">
                                    <div class="grid grid-cols-1 gap-3 p-3 sm:grid-cols-12 sm:items-center sm:gap-2 sm:p-4">
                                        <div class="font-bold text-slate-800 sm:col-span-5" x-text="invoiceItem.product_name"></div>
                                        <div class="grid grid-cols-3 gap-2 text-center sm:col-span-7">
                                            <div>
                                                <span class="mb-1 block text-[0.65rem] font-bold text-slate-400 sm:hidden">الكمية</span>
                                                <span class="text-sm text-slate-600" dir="ltr" x-text="invoiceItem.quantity"></span>
                                            </div>
                                            <div>
                                                <span class="mb-1 block text-[0.65rem] font-bold text-slate-400 sm:hidden">السعر</span>
                                                <span class="text-sm text-slate-600" dir="ltr" x-text="Number(invoiceItem.unit_price || 0).toLocaleString()"></span>
                                            </div>
                                            <div class="sm:text-left">
                                                <span class="mb-1 block text-[0.65rem] font-bold text-slate-400 sm:hidden">الإجمالي</span>
                                                <span class="text-sm font-black text-slate-800" dir="ltr" x-text="Number(invoiceItem.total || 0).toLocaleString()"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="text-sm font-black text-slate-600">إجمالي الفاتورة</span>
                                <span class="text-lg font-black text-red-600" dir="ltr" x-text="`${Number(invoiceDetails.total_amount || 0).toLocaleString()} ج.م`"></span>
                            </div>
                        </div>
                    </section>
                </div>
            </template>
        </div>
    </div>
</div>
</template>
