@props(['type' => 'purchase'])

<div x-show="showDetailsModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div x-show="showDetailsModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showDetailsModal = false"></div>
        <div x-show="showDetailsModal" x-transition class="relative w-full max-w-5xl p-5 sm:p-6 text-right transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">تفاصيل الفاتورة</h3>
                <div class="flex items-center gap-2">
                    <template x-if="details && details.id">
                        <div class="flex items-center gap-2">
                            <!-- Print Button -->
                            <button @click="openPrintPreviewModal('printPreviewModal', `/{{ $type === 'purchase' ? 'purchases' : 'sales' }}/${details.id}/print`)" class="px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-200 text-xs font-bold flex items-center gap-1.5 transition-colors" title="طباعة الفاتورة">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span>طباعة</span>
                            </button>
                            <!-- Edit Button -->
                            <button @click="$dispatch('edit-{{ $type }}', details.id); showDetailsModal = false" class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-800 text-xs font-bold flex items-center gap-1.5 transition-colors" title="تعديل الفاتورة">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                <span>تعديل الفاتورة</span>
                            </button>
                        </div>
                    </template>
                    <button @click="showDetailsModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
            </div>

            <div x-show="loading" class="py-12 flex justify-center">
                <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>

            <div x-show="!loading && details">
                <template x-if="details">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        
                        <!-- Right Sidebar: Header & Financials -->
                        <div class="lg:col-span-4 space-y-4">
                            <!-- Invoice Header -->
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-4">
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">رقم الفاتورة</p>
                                    <p class="font-bold text-primary-700" x-text="details.invoice_number" dir="ltr"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">الطرف الثاني</p>
                                    <p class="font-bold text-slate-800" x-text="details.supplier_name || details.customer_name"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-slate-500 mb-1">التاريخ</p>
                                        <p class="font-bold text-slate-800" x-text="details.date" dir="ltr"></p>
                                    </div>
                                </div>
                                <div x-show="details.notes">
                                    <p class="text-xs text-slate-500 mb-1">ملاحظات</p>
                                    <p class="text-sm font-medium text-slate-700" x-text="details.notes"></p>
                                </div>
                            </div>
                            
                            <!-- Financial details -->
                            <div x-show="details.transaction" class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 space-y-3">
                                <h4 class="text-sm font-bold text-blue-800 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    الملخص المالي للفاتورة
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center pb-2 border-b border-blue-100">
                                        <span class="text-xs font-bold text-slate-500">إجمالي الفاتورة</span>
                                        <span class="text-sm font-bold text-slate-700" dir="ltr" x-text="Number(details.total_amount).toLocaleString() + ' ج.م'"></span>
                                    </div>
                                    <div class="flex justify-between items-center pb-2 border-b border-blue-100">
                                        <span class="text-xs font-bold text-slate-500">المدفوع نقداً</span>
                                        <span class="text-sm font-bold text-emerald-600" dir="ltr" x-text="Number(details.transaction?.paid_cash || 0).toLocaleString() + ' ج.م'"></span>
                                    </div>
                                    <div class="flex justify-between items-center pb-2 border-b border-blue-100">
                                        <span class="text-xs font-bold text-slate-500">مسدد من الرصيد</span>
                                        <span class="text-sm font-bold text-blue-600" dir="ltr" x-text="Number(details.transaction?.paid_from_balance || 0).toLocaleString() + ' ج.م'"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-slate-500">المتبقي (آجل)</span>
                                        <span class="text-sm font-bold text-danger-500" dir="ltr" x-text="Number(details.transaction?.remaining_from_this || 0).toLocaleString() + ' ج.م'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Left Column: Items List -->
                        <div class="lg:col-span-8">
                            <h4 class="text-sm font-bold text-slate-800 mb-2">الأصناف</h4>
                            <div class="border border-slate-200 rounded-xl bg-white">
                                <!-- Desktop Header -->
                                <div class="hidden sm:grid sm:grid-cols-12 gap-2 bg-slate-50 border-b border-slate-200 px-4 py-2 text-[0.7rem] font-bold text-slate-500 uppercase text-center rounded-t-xl">
                                    <div class="sm:col-span-5 text-right">المنتج</div>
                                    <div class="sm:col-span-2">الكمية</div>
                                    <div class="sm:col-span-2">السعر</div>
                                    <div class="sm:col-span-3 text-left">الإجمالي</div>
                                </div>
                                
                                <!-- Items Body -->
                                <div class="divide-y divide-slate-100">
                                    <template x-for="item in details.items">
                                        <div class="p-3 sm:p-4 hover:bg-slate-50/50 transition-colors">
                                            <!-- Row Container -->
                                            <div class="flex flex-col sm:grid sm:grid-cols-12 gap-2 items-start sm:items-center">
                                                
                                                <!-- Product (Row 1 on mobile) -->
                                                <div class="w-full sm:col-span-5 text-right font-medium text-slate-800 text-sm">
                                                    <span x-text="item.product_name"></span>
                                                </div>

                                                <!-- Details (Row 2 on mobile) -->
                                                <div class="w-full sm:col-span-7 grid grid-cols-3 gap-2 sm:gap-4 mt-2 sm:mt-0">
                                                    <!-- Quantity -->
                                                    <div class="text-center">
                                                        <span class="block sm:hidden text-[0.65rem] font-bold text-slate-500 mb-1">الكمية</span>
                                                        <span class="text-sm text-slate-600 bg-slate-50 sm:bg-transparent px-2 py-1 rounded-lg block" dir="ltr" x-text="item.quantity + ' ك'"></span>
                                                    </div>
                                                    <!-- Price -->
                                                    <div class="text-center">
                                                        <span class="block sm:hidden text-[0.65rem] font-bold text-slate-500 mb-1">السعر</span>
                                                        <span class="text-sm text-slate-600 bg-slate-50 sm:bg-transparent px-2 py-1 rounded-lg block" dir="ltr" x-text="Number(item.unit_price).toLocaleString()"></span>
                                                    </div>
                                                    <!-- Total -->
                                                    <div class="text-left">
                                                        <span class="block sm:hidden text-[0.65rem] font-bold text-slate-500 mb-1 text-center">الإجمالي</span>
                                                        <span class="text-sm font-bold text-slate-800 bg-slate-100 sm:bg-transparent px-2 py-1 rounded-lg block text-center sm:text-left" dir="ltr" x-text="Number(item.total).toLocaleString()"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Footer Total -->
                                <div class="bg-slate-50 border-t border-slate-200 px-4 py-3 flex justify-between items-center rounded-b-xl">
                                    <span class="text-sm font-bold text-slate-600">إجمالي الفاتورة:</span>
                                    <span class="text-lg font-black text-danger-600" dir="ltr" x-text="Number(details.total_amount).toLocaleString() + ' ج.م'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
