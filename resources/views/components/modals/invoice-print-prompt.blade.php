@php
    $savedInvoice = session('invoice_saved');
@endphp

<div x-data="{
        open: {{ $savedInvoice ? 'true' : 'false' }},
        invoice: {{ Js::from($savedInvoice, JSON_UNESCAPED_UNICODE) }},
        printThermal() {
            if (!this.invoice || !this.invoice.print_thermal_url) return;
            const url = this.invoice.print_thermal_url;
            const title = (this.invoice.type_name || 'الفاتورة') + ' (حراري 80mm)';
            this.closeModal();
            this.$nextTick(() => {
                if (typeof window.openPrintPreviewModal === 'function') {
                    window.openPrintPreviewModal('printPreviewModal', url, title);
                } else {
                    window.open(url, '_blank');
                }
            });
        },
        printRegular() {
            if (!this.invoice || !this.invoice.print_url) return;
            const url = this.invoice.print_url;
            const title = (this.invoice.type_name || 'الفاتورة') + ' (عادية A4)';
            this.closeModal();
            this.$nextTick(() => {
                if (typeof window.openPrintPreviewModal === 'function') {
                    window.openPrintPreviewModal('printPreviewModal', url, title);
                } else {
                    window.open(url, '_blank');
                }
            });
        },
        closeModal() {
            this.open = false;
        }
    }"
    @open-invoice-print-prompt.window="invoice = $event.detail; open = true;"
    @keydown.escape.window="if (open) closeModal()"
    x-cloak>

    <template x-teleport="body">
        <div x-show="open"
             class="fixed inset-0 z-[80] overflow-y-auto"
             style="display: none;"
             aria-labelledby="invoice-print-modal-title"
             role="dialog"
             aria-modal="true">

            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <!-- Backdrop -->
                <div x-show="open"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                     @click="closeModal()"></div>

                <!-- Modal Window -->
                <div x-show="open"
                     @click.outside="closeModal()"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-5 sm:p-6 border border-slate-100 z-10"
                     dir="rtl">

                    <!-- Header -->
                    <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 id="invoice-print-modal-title" class="text-base sm:text-lg font-black text-slate-800">
                                    تم حفظ الفاتورة بنجاح
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    اختر نوع الطباعة المطلوب للفاتورة أو أغلق النافذة
                                </p>
                            </div>
                        </div>

                        <button @click="closeModal()" 
                                type="button" 
                                class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-1.5 rounded-lg transition-colors cursor-pointer"
                                title="إغلاق">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Invoice Summary Card -->
                    <template x-if="invoice">
                        <div class="my-4 p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-600" x-text="invoice.type_name || (invoice.type === 'sale' ? 'فاتورة مبيعات' : 'فاتورة مشتريات')"></span>
                                    <span class="px-2 py-0.5 bg-white border border-slate-200 text-primary-700 font-extrabold rounded-md text-[0.75rem]" dir="ltr" x-text="invoice.invoice_number"></span>
                                </div>
                                <div class="text-slate-700 font-bold" x-show="invoice.total_amount">
                                    <span class="text-slate-400 font-normal">الإجمالي: </span>
                                    <span class="text-emerald-700 font-black text-sm" x-text="Number(invoice.total_amount).toLocaleString() + ' ج.م'"></span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-600 pt-1 border-t border-slate-200/60" x-show="invoice.party_name">
                                <span class="text-slate-500" x-text="(invoice.party_label || (invoice.type === 'sale' ? 'العميل' : 'المورد')) + ':'"></span>
                                <span class="font-bold text-slate-800" x-text="invoice.party_name"></span>
                            </div>
                        </div>
                    </template>

                    <!-- Print Action Choices -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <!-- Thermal Print Button -->
                        <button type="button"
                                @click="printThermal()"
                                class="group relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-amber-300 bg-amber-50/60 hover:bg-amber-100/80 hover:border-amber-400 transition-all text-center cursor-pointer shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-400/50">
                            <div class="w-11 h-11 rounded-xl bg-amber-200/80 text-amber-800 flex items-center justify-center mb-2.5 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-amber-950 block">طباعة الفاتورة حرارية</span>
                            <span class="text-[0.7rem] font-medium text-amber-800 mt-1 block">مقاس 80mm (إيصال كاشير)</span>
                        </button>

                        <!-- Regular (A4) Print Button -->
                        <button type="button"
                                @click="printRegular()"
                                class="group relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 bg-slate-50 hover:bg-blue-50/70 hover:border-blue-300 transition-all text-center cursor-pointer shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400/50">
                            <div class="w-11 h-11 rounded-xl bg-slate-200 text-slate-700 group-hover:bg-blue-100 group-hover:text-blue-700 flex items-center justify-center mb-2.5 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-slate-800 group-hover:text-blue-950 block">طباعة الفاتورة عادية</span>
                            <span class="text-[0.7rem] font-medium text-slate-500 group-hover:text-blue-700 mt-1 block">مقاس A4 (ورق قياسي)</span>
                        </button>
                    </div>

                    <!-- Close Button -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-end">
                        <button type="button"
                                @click="closeModal()"
                                class="w-full sm:w-auto px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold rounded-xl transition-colors cursor-pointer text-center">
                            إغلاق
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </template>
</div>
