@props(['products' => [], 'suppliers' => [], 'fixedSupplier' => null])

<div x-data="purchaseFormComponent({
        products: {{ Js::from($products) }},
        suppliers: {{ Js::from($suppliers) }},
        fixedSupplier: {{ $fixedSupplier ? Js::from($fixedSupplier) : 'null' }}
    })" 
    x-show="showPurchaseModal" 
    @edit-purchase.window="editInvoice($event.detail)"
    @create-purchase.window="createInvoice()"
    x-cloak 
    class="fixed inset-0 z-[70] overflow-y-auto"
    style="display: none;">
    <div class="flex items-start justify-center min-h-screen pt-10 sm:pt-16 p-4 text-center" @click.self="showPurchaseModal = false">
        <div x-show="showPurchaseModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showPurchaseModal = false"></div>
        <div x-show="showPurchaseModal" @click.outside="showPurchaseModal = false" x-transition class="relative w-full max-w-6xl p-5 sm:p-6 text-right transition-all transform bg-white shadow-2xl rounded-2xl z-10">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800" x-text="isEdit ? 'تعديل فاتورة رقم ' + invoiceNumber : 'فاتورة مشتريات جديدة'"></h3>
                <button type="button" @click="showPurchaseModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>

            <div x-show="loading" class="py-12 flex justify-center">
                <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>

            <form x-show="!loading" :action="actionUrl" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT" :disabled="!isEdit">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <!-- Right Column: Invoice Header Info -->
                    <div class="lg:col-span-4 space-y-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-4">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <!-- Supplier Selection -->
                                <div class="relative">
                                    <template x-if="!fixedSupplier">
                                        <div x-data="{ open: false, search: '' }">
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">المورد <span class="text-danger-500">*</span></label>
                                            <input type="hidden" name="supplier_id" :value="selectedSupplierId" required>
                                            
                                            <button type="button" 
                                                    @click="open = !open" 
                                                    class="w-full flex items-center justify-between px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 transition-all text-right">
                                                <span class="text-xs font-bold truncate" :class="selectedSupplierId ? 'text-slate-800' : 'text-slate-400'" x-text="selectedSupplierId ? selectedName : 'اختر المورد...'"></span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 mr-1" :class="open ? 'rotate-180 text-primary-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div x-show="open" 
                                                 @click.outside="open = false" 
                                                 class="absolute z-[60] left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden" 
                                                 style="display: none;">
                                                <div class="p-1.5 border-b border-slate-100 bg-slate-50/70">
                                                    <input type="text" x-model="search" placeholder="ابحث..." class="w-full pl-2 pr-6 py-1 bg-white border border-slate-200 rounded text-xs" @keydown.escape="open = false">
                                                </div>
                                                <div class="max-h-48 overflow-y-auto divide-y divide-slate-50">
                                                    <template x-for="entity in filterSuppliers(search)" :key="entity.id">
                                                        <button type="button" @click="onSelectSupplier(entity); open = false; search = ''" class="w-full px-3 py-1.5 text-right hover:bg-primary-50/60" :class="selectedSupplierId == entity.id ? 'bg-primary-50 font-bold' : ''">
                                                            <span class="text-[0.75rem] text-slate-800" x-text="entity.name"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="fixedSupplier">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">المورد</label>
                                            <input type="hidden" name="supplier_id" :value="fixedSupplier.id">
                                            <div class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-right">
                                                <span class="text-xs font-bold text-slate-800 truncate block" x-text="fixedSupplier.name"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Date -->
                                <div x-data="{
                                    fp: null,
                                    initFp() {
                                        this.$nextTick(() => {
                                            if (window.flatpickr && this.$refs.dateInput) {
                                                this.fp = flatpickr(this.$refs.dateInput, {
                                                    locale: 'ar',
                                                    dateFormat: 'Y-m-d',
                                                    defaultDate: date || new Date().toISOString().split('T')[0],
                                                    maxDate: 'today',
                                                    disableMobile: true,
                                                    static: true,
                                                    appendTo: this.$refs.dateWrapper,
                                                    onChange: (dates, dateStr) => { date = dateStr; }
                                                });
                                                this.$watch('date', val => {
                                                    if (this.fp && val) this.fp.setDate(val, false);
                                                });
                                            }
                                        });
                                    }
                                }" x-init="initFp()">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">التاريخ <span class="text-danger-500">*</span></label>
                                    <div class="relative" x-ref="dateWrapper">
                                        <input type="text" 
                                               x-ref="dateInput"
                                               name="date" 
                                               required 
                                               x-model="date" 
                                               placeholder="YYYY-MM-DD"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-center font-bold text-xs cursor-pointer text-slate-800" 
                                               readonly>
                                        <div class="absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <!-- Paid Amount -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5" x-text="isEdit ? 'إجمالي المدفوع في الفاتورة' : 'المدفوع (اختياري)'"></label>
                                    <input type="number" step="0.01" min="0" x-model="paidAmount" name="paid_amount" placeholder="0.00" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-center text-xs font-bold" dir="ltr">
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات (اختياري)</label>
                                    <input type="text" name="notes" x-model="notes" placeholder="ملاحظات..." class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-xs">
                                </div>
                            </div>

                            <!-- Balance Indicator -->
                            <div class="mt-2 p-3 bg-slate-100/50 border border-slate-200 rounded-lg space-y-2 transition-colors duration-300"
                                 :class="selectedSupplier ? 'bg-blue-50/50 border-blue-100' : ''">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[0.7rem] font-bold whitespace-nowrap shrink-0" :class="selectedSupplier ? 'text-slate-500' : 'text-slate-400'">حساب المورد الحالي:</span>
                                    <span class="text-xs sm:text-sm font-bold whitespace-nowrap" :class="selectedSupplier ? 'text-slate-800' : 'text-slate-400'" dir="ltr" x-text="selectedSupplier ? formatBalance(selectedSupplier.balance) : '-'"></span>
                                </div>
                                <div class="flex items-center justify-between gap-2 border-t pt-2 transition-colors duration-300"
                                     :class="selectedSupplier ? 'border-blue-100' : 'border-slate-200'">
                                    <span class="text-[0.7rem] font-bold whitespace-nowrap shrink-0" :class="selectedSupplier ? 'text-slate-500' : 'text-slate-400'">الحساب بعد الفاتورة:</span>
                                    <span class="text-xs sm:text-sm font-black whitespace-nowrap" :class="!selectedSupplier ? 'text-slate-400' : (newSupplierBalance > 0 ? 'text-amber-800' : (newSupplierBalance < 0 ? 'text-emerald-600' : 'text-slate-600'))" dir="ltr" x-text="selectedSupplier ? formatBalance(newSupplierBalance) : '-'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Left Column: Items List -->
                    <div class="lg:col-span-8">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-sm font-bold text-slate-800">الأصناف المشتراة</h4>
                            <button type="button" @click="addItem()" class="px-3 py-1.5 bg-primary-50 text-primary-600 rounded-lg hover:bg-primary-100 text-xs font-bold flex items-center gap-1 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                إضافة صنف
                            </button>
                        </div>
                        <div class="border border-slate-200 rounded-xl bg-white shadow-sm">
                            <!-- Desktop Header -->
                            <div class="hidden sm:grid sm:grid-cols-12 gap-2 bg-slate-50 border-b border-slate-200 px-3 py-2 text-[0.7rem] font-bold text-slate-500 uppercase text-center rounded-t-xl">
                                <div class="sm:col-span-5 text-right px-1">المنتج</div>
                                <div class="sm:col-span-2 text-center">الكمية (ك)</div>
                                <div class="sm:col-span-2 text-center">سعر الكيلو</div>
                                <div class="sm:col-span-2 text-center">الإجمالي</div>
                                <div class="sm:col-span-1 text-center">حذف</div>
                            </div>
                            
                            <!-- Items Body -->
                            <div class="divide-y divide-slate-100">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <div class="p-2.5 sm:p-2 hover:bg-slate-50/50 transition-colors">
                                        
                                        <!-- Desktop Row (grid-cols-12) -->
                                        <div class="hidden sm:grid sm:grid-cols-12 gap-2 items-center">
                                            <!-- Product (5 cols) -->
                                            <div class="sm:col-span-5">
                                                <div x-data="{ open: false, search: '' }" class="relative w-full text-right" :class="open ? 'z-50' : 'z-10'">
                                                    <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id" required>
                                                    
                                                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 transition-all text-right">
                                                        <span class="text-xs font-medium truncate" :class="item.product_id ? 'text-slate-800' : 'text-slate-400'" x-text="getProduct(item.product_id) ? getProduct(item.product_id).name + ' (متوفر: ' + getProduct(item.product_id).stock + ')' : 'اختر المنتج...'"></span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-primary-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open" @click.outside="open = false" class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl overflow-hidden" style="display: none; min-width: 240px;">
                                                        <div class="p-1.5 border-b border-slate-100 bg-slate-50/90">
                                                            <input type="text" x-model="search" placeholder="ابحث عن المنتج..." class="w-full px-2 py-1 bg-white border border-slate-200 rounded-md text-xs focus:outline-none focus:border-primary-500" @click.stop>
                                                        </div>
                                                        <div class="max-h-48 overflow-y-auto divide-y divide-slate-50">
                                                            <template x-for="p in filterProducts(search)" :key="p.id">
                                                                <button type="button" 
                                                                        :disabled="items.some(i => i.product_id == p.id && i.id !== item.id)"
                                                                        @click="onSelectProduct(item, p); open = false; search = ''" 
                                                                        class="w-full px-3 py-2 text-right flex items-center justify-between transition-colors group" 
                                                                        :class="[
                                                                            item.product_id == p.id ? 'bg-primary-50 font-bold' : '',
                                                                            items.some(i => i.product_id == p.id && i.id !== item.id) ? 'opacity-50 cursor-not-allowed bg-slate-50' : 'hover:bg-primary-50/60'
                                                                        ]">
                                                                    <span class="text-xs font-medium" :class="items.some(i => i.product_id == p.id && i.id !== item.id) ? 'text-slate-400' : 'text-slate-800 group-hover:text-primary-700'" x-text="items.some(i => i.product_id == p.id && i.id !== item.id) ? p.name + ' (مضاف مسبقاً)' : p.name"></span>
                                                                    <span class="text-[0.7rem] font-bold" :class="items.some(i => i.product_id == p.id && i.id !== item.id) ? 'text-slate-400' : 'text-slate-500'" x-text="p.stock"></span>
                                                                </button>
                                                            </template>
                                                            <div x-show="filterProducts(search).length === 0" class="p-3 text-center text-xs text-slate-400">
                                                                لا توجد نتائج مطابقة
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quantity (2 cols) -->
                                            <div class="sm:col-span-2">
                                                <input type="number" step="0.01" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="0.01" required placeholder="0" class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs font-bold focus:outline-none focus:border-primary-500 focus:ring-1 text-center bg-white" dir="ltr">
                                            </div>

                                            <!-- Price (2 cols) -->
                                            <div class="sm:col-span-2">
                                                <input type="number" step="0.01" :name="'items['+index+'][price]'" x-model="item.price" min="0" required placeholder="0.00" class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs font-bold focus:outline-none focus:border-primary-500 focus:ring-1 text-center bg-white" dir="ltr">
                                            </div>

                                            <!-- Total (2 cols) -->
                                            <div class="sm:col-span-2 text-center">
                                                <div class="text-xs font-bold text-slate-800 py-1.5 rounded-lg text-center" dir="ltr">
                                                    <span x-text="Number((parseFloat(item.quantity || 0) * parseFloat(item.price || 0)).toFixed(2)).toLocaleString('en-US')"></span>
                                                    <span class="text-[0.65rem] text-slate-400 font-normal">ج.م</span>
                                                </div>
                                            </div>

                                            <!-- Delete Button (1 col) -->
                                            <div class="sm:col-span-1 flex justify-center">
                                                <button type="button" @click="removeItem(item.id)" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف الصنف">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Mobile Layout -->
                                        <div class="sm:hidden space-y-2">
                                            <div class="flex items-center gap-2">
                                                <div x-data="{ open: false, search: '' }" class="relative flex-1 text-right" :class="open ? 'z-50' : 'z-10'">
                                                    <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id" required>
                                                    
                                                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-2.5 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 text-right">
                                                        <span class="text-xs font-bold truncate" :class="item.product_id ? 'text-slate-800' : 'text-slate-400'" x-text="getProduct(item.product_id) ? getProduct(item.product_id).name + ' (متوفر: ' + getProduct(item.product_id).stock + ')' : 'اختر المنتج...'"></span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                                    </button>

                                                    <div x-show="open" @click.outside="open = false" class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl overflow-hidden" style="display: none; min-width: 220px;">
                                                        <div class="p-1.5 border-b border-slate-100 bg-slate-50/90">
                                                            <input type="text" x-model="search" placeholder="ابحث عن المنتج..." class="w-full px-2 py-1 bg-white border border-slate-200 rounded-md text-xs focus:outline-none focus:border-primary-500" @click.stop>
                                                        </div>
                                                        <div class="max-h-48 overflow-y-auto divide-y divide-slate-50">
                                                            <template x-for="p in filterProducts(search)" :key="p.id">
                                                                <button type="button" 
                                                                        :disabled="items.some(i => i.product_id == p.id && i.id !== item.id)"
                                                                        @click="onSelectProduct(item, p); open = false; search = ''" 
                                                                        class="w-full px-3 py-2 text-right flex items-center justify-between transition-colors" 
                                                                        :class="item.product_id == p.id ? 'bg-primary-50 font-bold' : ''">
                                                                    <span class="text-xs font-medium" :class="items.some(i => i.product_id == p.id && i.id !== item.id) ? 'text-slate-400' : 'text-slate-800'" x-text="items.some(i => i.product_id == p.id && i.id !== item.id) ? p.name + ' (مضاف مسبقاً)' : p.name"></span>
                                                                    <span class="text-[0.7rem] font-bold text-slate-500" x-text="p.stock"></span>
                                                                </button>
                                                            </template>
                                                            <div x-show="filterProducts(search).length === 0" class="p-3 text-center text-xs text-slate-400">
                                                                لا توجد نتائج مطابقة
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="button" @click="removeItem(item.id)" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-lg shrink-0 border border-rose-100" title="حذف الصنف">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-3 gap-2">
                                                <div>
                                                    <label class="block text-[0.65rem] font-bold text-slate-500 mb-1 text-center">الكمية (ك)</label>
                                                    <input type="number" step="0.01" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="0.01" required class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs font-bold text-center bg-white" dir="ltr">
                                                </div>
                                                <div>
                                                    <label class="block text-[0.65rem] font-bold text-slate-500 mb-1 text-center">سعر الكيلو</label>
                                                    <input type="number" step="0.01" :name="'items['+index+'][price]'" x-model="item.price" min="0" required class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs font-bold text-center bg-white" dir="ltr">
                                                </div>
                                                <div>
                                                    <label class="block text-[0.65rem] font-bold text-slate-500 mb-1 text-center">الإجمالي</label>
                                                    <div class="px-2 py-1.5 bg-slate-50 rounded-lg text-xs font-bold text-slate-800 text-center border border-slate-200" dir="ltr">
                                                        <span x-text="Number((parseFloat(item.quantity || 0) * parseFloat(item.price || 0)).toFixed(2)).toLocaleString('en-US')"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Price Info -->
                                        <div x-show="item.priceInfo" x-transition class="mt-2 flex items-center gap-3 px-3 py-1.5 bg-blue-50/50 rounded-lg border border-blue-100/60 text-[0.7rem] sm:w-2/3">
                                            <span class="text-blue-800 font-medium">سعر المورد السابق: <b class="text-primary-700 font-mono" dir="ltr" x-text="(item.priceInfo && item.priceInfo.entity !== 'لا يوجد') ? item.priceInfo.entity + ' ج.م' : '-'"></b></span>
                                            <span class="text-slate-400">|</span>
                                            <span class="text-slate-600">العام: <b class="text-slate-800 font-mono" dir="ltr" x-text="(item.priceInfo && item.priceInfo.overall !== 'لا يوجد') ? item.priceInfo.overall + ' ج.م' : '-'"></b></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer Total -->
                            <div class="bg-slate-50 border-t border-slate-200 px-4 py-3 flex justify-between items-center rounded-b-xl">
                                <span class="text-sm font-bold text-slate-600">إجمالي الفاتورة:</span>
                                <span class="text-lg font-black text-primary-600" dir="ltr" x-text="total.toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م'"></span>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of Grid -->
                    
                <div class="mt-6 flex gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-sm shadow-primary-600/20" x-text="isEdit ? 'حفظ التعديلات' : 'حفظ الفاتورة'"></button>
                    <button type="button" @click="showPurchaseModal = false" class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('purchaseFormComponent', (config) => ({
            products: config.products || [],
            suppliers: config.suppliers || [],
            fixedSupplier: config.fixedSupplier || null,
            
            showPurchaseModal: false,
            isEdit: false,
            loading: false,
            
            invoiceId: null,
            invoiceNumber: '',
            
            items: [{id: Date.now(), product_id: '', quantity: 1, price: 0, priceInfo: null, priceInfoLoading: false}],
            selectedSupplierId: config.fixedSupplier ? config.fixedSupplier.id : '',
            selectedName: config.fixedSupplier ? config.fixedSupplier.name : '',
            paidAmount: 0,
            originalSupplierId: null,
            originalTotal: 0,
            originalPaidAmount: 0,
            date: new Date().toISOString().split('T')[0],
            notes: '',
            
            get actionUrl() {
                return this.isEdit ? `/purchases/${this.invoiceId}` : '/purchases';
            },

            getProduct(id) {
                return this.products.find(p => p.id == id);
            },

            filterSuppliers(search) {
                if (!search) return this.suppliers;
                return this.suppliers.filter(e => e.name.toLowerCase().includes(search.toLowerCase()));
            },

            filterProducts(search) {
                if (!search) return this.products;
                return this.products.filter(p => p.name.toLowerCase().includes(search.toLowerCase()));
            },

            onSelectSupplier(entity) {
                this.selectedSupplierId = entity.id;
                this.selectedName = entity.name;
                
                this.items.forEach(item => {
                    if (item.product_id) {
                        this.fetchItemPriceInfo(item, entity.id);
                    }
                });
            },

            onSelectProduct(item, product) {
                item.product_id = product.id;
                this.fetchItemPriceInfo(item, this.selectedSupplierId);
            },

            fetchItemPriceInfo(item, supplierId) {
                item.priceInfoLoading = true;
                item.priceInfo = null;
                let url = `/api/products/${item.product_id}/price-info?type=purchase`;
                if (supplierId) {
                    url += `&entity_id=${supplierId}`;
                }
                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        item.priceInfo = data;
                        item.priceInfoLoading = false;
                    }).catch(() => {
                        item.priceInfoLoading = false;
                    });
            },
            
            createInvoice() {
                this.isEdit = false;
                this.invoiceId = null;
                this.invoiceNumber = '';
                this.items = [{id: Date.now(), product_id: '', quantity: 1, price: 0, priceInfo: null, priceInfoLoading: false}];
                
                if (this.fixedSupplier) {
                    this.selectedSupplierId = this.fixedSupplier.id;
                    this.selectedName = this.fixedSupplier.name;
                } else {
                    this.selectedSupplierId = '';
                    this.selectedName = '';
                }
                
                this.paidAmount = 0;
                this.originalSupplierId = null;
                this.originalTotal = 0;
                this.originalPaidAmount = 0;
                this.date = new Date().toISOString().split('T')[0];
                this.notes = '';
                
                this.showPurchaseModal = true;
            },
            
            async editInvoice(id) {
                this.isEdit = true;
                this.invoiceId = id;
                this.loading = true;
                this.showPurchaseModal = true;
                
                try {
                    const res = await fetch(`/purchases/${id}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const details = await res.json();
                    
                    this.invoiceNumber = details.invoice_number;
                    this.selectedSupplierId = details.supplier_id;
                    this.selectedName = details.supplier_name;
                    this.date = details.date;
                    this.notes = details.notes || '';
                    this.paidAmount = details.transaction ? details.transaction.paid_amount : 0;
                    this.originalSupplierId = details.supplier_id;
                    this.originalTotal = Number(details.total_amount) || 0;
                    this.originalPaidAmount = Number(this.paidAmount) || 0;
                    
                    this.items = details.items.map(item => ({
                        id: item.id || Date.now() + Math.random(),
                        product_id: item.product_id,
                        quantity: item.quantity,
                        price: item.unit_price,
                        priceInfo: null,
                        priceInfoLoading: false
                    }));
                } catch(e) {
                    console.error(e);
                    alert('حدث خطأ أثناء جلب الفاتورة للتعديل');
                    this.showPurchaseModal = false;
                } finally {
                    this.loading = false;
                }
            },
            
            addItem() { 
                this.items.push({id: Date.now(), product_id: '', quantity: 1, price: 0, priceInfo: null, priceInfoLoading: false});
            },
            
            removeItem(id) { 
                if(this.items.length > 1) this.items = this.items.filter(i => i.id !== id);
            },
            
            get total() { 
                return this.items.reduce((sum, item) => sum + (parseFloat(item.quantity || 0) * parseFloat(item.price || 0)), 0);
            },
            
            get selectedSupplier() { 
                if (this.fixedSupplier) return this.fixedSupplier;
                return this.suppliers.find(s => s.id == this.selectedSupplierId) || null; 
            },
            
            get newSupplierBalance() { 
                if (!this.selectedSupplier) return 0;
                let baseBalance = Number(this.selectedSupplier.balance);
                if (this.isEdit && this.selectedSupplierId == this.originalSupplierId) {
                    baseBalance -= this.originalTotal - this.originalPaidAmount;
                }
                return baseBalance + this.total - (parseFloat(this.paidAmount) || 0);
            },
            
            formatBalance(amount) {
                if (!amount || amount == 0) return '0 ج.م (خالص)';
                if (amount > 0) return Number(amount).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م (له علينا)';
                return Number(Math.abs(amount)).toLocaleString('en-US', {maximumFractionDigits: 2}) + ' ج.م (لنا عنده)';
            }
        }));
    });
</script>
