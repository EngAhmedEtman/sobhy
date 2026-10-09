@php
    $savedInvoice = session('invoice_saved');
@endphp

<div x-data="{
        open: false,
        invoice: {{ Js::from($savedInvoice, JSON_UNESCAPED_UNICODE) }},
        init() {
            @if($savedInvoice)
                this.$nextTick(() => {
                    this.open = true;
                });
            @endif
        },
        printThermal() {
            if (!this.invoice || !this.invoice.print_thermal_url) return;
            const url = this.invoice.print_thermal_url;
            const title = (this.invoice.type_name || 'الفاتورة') + ' (حراري 80mm)';
            this.closeModal();
            setTimeout(() => {
                if (typeof window.openPrintPreviewModal === 'function') {
                    window.openPrintPreviewModal('printPreviewModal', url, title);
                } else {
                    window.open(url, '_blank');
                }
            }, 100);
        },
        printRegular() {
            if (!this.invoice || !this.invoice.print_url) return;
            const url = this.invoice.print_url;
            const title = (this.invoice.type_name || 'الفاتورة') + ' (عادية A4)';
            this.closeModal();
            setTimeout(() => {
                if (typeof window.openPrintPreviewModal === 'function') {
                    window.openPrintPreviewModal('printPreviewModal', url, title);
                } else {
                    window.open(url, '_blank');
                }
            }, 100);
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
             x-cloak
             class="fixed inset-0 flex items-center justify-center p-3 sm:p-4 overflow-y-auto"
             style="z-index: 999999;"
             aria-labelledby="invoice-print-modal-title"
             role="dialog"
             aria-modal="true">

            <!-- Backdrop -->
            <div x-show="open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                 style="z-index: 999998;"
                 @click="closeModal()"></div>

            <!-- Modal Window -->
            <div x-show="open"
                 @click.outside="closeModal()"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full bg-white rounded-2xl shadow-2xl border border-slate-100 p-4 sm:p-5 text-right my-auto"
                 style="z-index: 999999; max-width: 460px;"
                 dir="rtl">

                <!-- Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 id="invoice-print-modal-title" class="text-sm sm:text-base font-black text-slate-800 leading-tight">
                                تم حفظ الفاتورة بنجاح
                            </h3>
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">
                                اختر نوع الطباعة المطلوب أو أغلق النافذة
                            </p>
                        </div>
                    </div>

                    <button @click="closeModal()" 
                            type="button" 
                            class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-1.5 rounded-lg transition-colors cursor-pointer"
                            title="إغلاق">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Invoice Summary Card -->
                <template x-if="invoice">
                    <div class="my-3 p-3 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="font-bold text-slate-700" x-text="invoice.type_name || (invoice.type === 'sale' ? 'فاتورة مبيعات' : 'فاتورة مشتريات')"></span>
                                <span class="px-2 py-0.5 bg-white border border-slate-200 text-primary-700 font-extrabold rounded-md text-[0.75rem]" dir="ltr" x-text="invoice.invoice_number"></span>
                            </div>
                            <div class="text-slate-700 font-bold" x-show="invoice.total_amount">
                                <span class="text-slate-400 font-normal">الإجمالي: </span>
                                <span class="text-emerald-700 font-black text-sm" x-text="Number(invoice.total_amount).toLocaleString() + ' ج.م'"></span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-1.5 border-t border-slate-200/50 text-slate-600" x-show="invoice.party_name">
                            <span class="text-slate-400" x-text="(invoice.party_label || (invoice.type === 'sale' ? 'العميل' : 'المورد')) + ':'"></span>
                            <span class="font-bold text-slate-800 truncate max-w-[200px]" x-text="invoice.party_name"></span>
                        </div>
                    </div>
                </template>

                <!-- Print Action Choices (2 Clean Stacked Options) -->
                <div class="space-y-2.5 my-3">
                    <!-- Thermal Print Option -->
                    <button type="button"
                            @click="printThermal()"
                            class="w-full flex items-center justify-between p-3 rounded-xl border border-amber-300 bg-amber-50/70 hover:bg-amber-100 hover:border-amber-400 transition-all cursor-pointer group text-right shadow-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-amber-200/90 text-amber-900 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs sm:text-sm font-bold text-amber-950 block">طباعة الفاتورة حرارية</span>
                                <span class="text-[0.65rem] font-medium text-amber-800 block">مقاس 80mm لطابعات الإيصالات</span>
                            </div>
                        </div>
                        <span class="text-[0.7rem] font-bold text-amber-800 bg-amber-200/60 px-2 py-0.5 rounded-md">80mm</span>
                    </button>

                    <!-- Regular (A4) Print Option -->
                    <button type="button"
                            @click="printRegular()"
                            class="w-full flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-slate-50/80 hover:bg-blue-50/70 hover:border-blue-300 transition-all cursor-pointer group text-right shadow-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-slate-200 text-slate-700 group-hover:bg-blue-100 group-hover:text-blue-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-blue-950 block">طباعة الفاتورة عادية</span>
                                <span class="text-[0.65rem] font-medium text-slate-500 group-hover:text-blue-700 block">مقاس A4 ورق قياسي</span>
                            </div>
                        </div>
                        <span class="text-[0.7rem] font-bold text-slate-600 bg-slate-200/60 px-2 py-0.5 rounded-md">A4</span>
                    </button>
                </div>

                <!-- Close Button -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-end">
                    <button type="button"
                            @click="closeModal()"
                            class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors cursor-pointer text-center">
                        إغلاق
                    </button>
                </div>

            </div>
        </div>
    </template>
</div>
