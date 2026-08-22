<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['products' => [], 'customers' => [], 'fixedCustomer' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['products' => [], 'customers' => [], 'fixedCustomer' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-data="saleFormComponent({
        products: <?php echo e(Js::from($products)); ?>,
        customers: <?php echo e(Js::from($customers)); ?>,
        fixedCustomer: <?php echo e($fixedCustomer ? Js::from($fixedCustomer) : 'null'); ?>

    })" 
    x-show="showSaleModal" 
    @edit-sale.window="editInvoice($event.detail)"
    @create-sale.window="createInvoice()"
    x-cloak 
    class="fixed inset-0 z-[70] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center sm:p-0">
        <div x-show="showSaleModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showSaleModal = false"></div>
        <div x-show="showSaleModal" x-transition class="relative w-full max-w-6xl p-5 sm:p-6 text-right transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800" x-text="isEdit ? 'تعديل فاتورة مبيعات رقم ' + invoiceNumber : 'فاتورة مبيعات جديدة'"></h3>
                <button @click="showSaleModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>

            <div x-show="loading" class="py-12 flex justify-center">
                <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>

            <form x-show="!loading" :action="actionUrl" method="POST">
                <?php echo csrf_field(); ?>
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <!-- Right Column: Invoice Header Info -->
                    <div class="lg:col-span-4 space-y-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-4">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Customer Selection -->
                                <div class="relative">
                                    <template x-if="!fixedCustomer">
                                        <div x-data="{
                                            open: false,
                                            search: '',
                                            get filteredEntities() {
                                                if (!this.search) return customers;
                                                return customers.filter(e => e.name.toLowerCase().includes(this.search.toLowerCase()));
                                            },
                                            selectEntity(entity) {
                                                selectedCustomerId = entity.id;
                                                selectedName = entity.name;
                                                this.open = false;
                                                this.search = '';
                                                
                                                // Update price info for all items with the new customer context
                                                items.forEach(item => {
                                                    if (item.product_id) {
                                                        item.priceInfoLoading = true;
                                                        fetch(`/api/products/${item.product_id}/price-info?type=sale&entity_id=${entity.id}`)
                                                            .then(res => res.json())
                                                            .then(data => {
                                                                item.priceInfo = data;
                                                                item.priceInfoLoading = false;
                                                            }).catch(() => {
                                                                item.priceInfoLoading = false;
                                                            });
                                                    }
                                                });
                                            }
                                        }">
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">العميل <span class="text-danger-500">*</span></label>
                                            <input type="hidden" name="customer_id" :value="selectedCustomerId" required>
                                            
                                            <button type="button" 
                                                    @click="open = !open" 
                                                    class="w-full flex items-center justify-between px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 transition-all text-right">
                                                <span class="text-xs font-bold truncate" :class="selectedCustomerId ? 'text-slate-800' : 'text-slate-400'" x-text="selectedCustomerId ? selectedName : 'اختر العميل...'"></span>
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
                                                    <template x-for="entity in filteredEntities" :key="entity.id">
                                                        <button type="button" @click="selectEntity(entity)" class="w-full px-3 py-1.5 text-right hover:bg-primary-50/60" :class="selectedCustomerId == entity.id ? 'bg-primary-50 font-bold' : ''">
                                                            <span class="text-[0.75rem] text-slate-800" x-text="entity.name"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="fixedCustomer">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">العميل</label>
                                            <input type="hidden" name="customer_id" :value="fixedCustomer.id">
                                            <div class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-right">
                                                <span class="text-xs font-bold text-slate-800 truncate block" x-text="fixedCustomer.name"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Date -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">التاريخ <span class="text-danger-500">*</span></label>
                                    <input type="date" name="date" required x-model="date" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 text-left text-xs font-bold" dir="ltr">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Paid Amount -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5" x-text="isEdit ? 'المدفوع نقداً' : 'المدفوع (اختياري)'"></label>
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
                                 :class="selectedCustomer ? 'bg-blue-50/50 border-blue-100' : ''">
                                <div class="flex items-center justify-between">
                                    <span class="text-[0.7rem] font-bold" :class="selectedCustomer ? 'text-slate-500' : 'text-slate-400'">حساب العميل الحالي:</span>
                                    <span class="text-sm font-bold" :class="selectedCustomer ? 'text-slate-800' : 'text-slate-400'" dir="ltr" x-text="selectedCustomer ? formatBalance(selectedCustomer.balance) : '-'"></span>
                                </div>
                                <div class="flex items-center justify-between border-t pt-2 transition-colors duration-300"
                                     :class="selectedCustomer ? 'border-blue-100' : 'border-slate-200'">
                                    <span class="text-[0.7rem] font-bold" :class="selectedCustomer ? 'text-slate-500' : 'text-slate-400'">الحساب بعد الفاتورة:</span>
                                    <span class="text-sm font-black" :class="!selectedCustomer ? 'text-slate-400' : (newCustomerBalance > 0 ? 'text-emerald-600' : (newCustomerBalance < 0 ? 'text-danger-600' : 'text-slate-600'))" dir="ltr" x-text="selectedCustomer ? formatBalance(newCustomerBalance) : '-'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Left Column: Items List -->
                    <div class="lg:col-span-8">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-sm font-bold text-slate-800">الأصناف المباعة</h4>
                            <button type="button" @click="addItem()" class="px-3 py-1.5 bg-primary-50 text-primary-600 rounded-lg hover:bg-primary-100 text-xs font-bold flex items-center gap-1 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                إضافة صنف
                            </button>
                        </div>
                        <div class="border border-slate-200 rounded-xl bg-white">
                            <!-- Desktop Header -->
                            <div class="hidden sm:grid sm:grid-cols-12 gap-2 bg-slate-50 border-b border-slate-200 px-2 py-2 text-[0.7rem] font-bold text-slate-500 uppercase text-center rounded-t-xl">
                                <div class="sm:col-span-5 text-right px-2">المنتج</div>
                                <div class="sm:col-span-2">الكمية</div>
                                <div class="sm:col-span-2">سعر الكيلو</div>
                                <div class="sm:col-span-2">الإجمالي</div>
                                <div class="sm:col-span-1"></div>
                            </div>
                            
                            <!-- Items Body -->
                            <div class="divide-y divide-slate-100">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <div class="p-3 sm:p-2 hover:bg-slate-50/50 transition-colors">
                                        <!-- Row Container -->
                                        <div class="flex flex-col sm:grid sm:grid-cols-12 gap-3 sm:gap-2 items-start sm:items-center">
                                            
                                            <!-- Product Col (Row 1 on mobile) -->
                                            <div class="w-full sm:col-span-5 flex items-start gap-2">
                                                <div x-data="{
                                                    open: false,
                                                    search: '',
                                                    get filteredProducts() {
                                                        if (!this.search) return products;
                                                        return products.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
                                                    },
                                                    get selectedProduct() {
                                                        return products.find(p => p.id == item.product_id);
                                                    },
                                                    selectProduct(p) {
                                                        item.product_id = p.id;
                                                        this.open = false;
                                                        this.search = '';
                                                        
                                                        item.priceInfoLoading = true;
                                                        item.priceInfo = null;
                                                        let url = `/api/products/${p.id}/price-info?type=sale`;
                                                        if (selectedCustomerId) {
                                                            url += `&entity_id=${selectedCustomerId}`;
                                                        }
                                                        fetch(url)
                                                            .then(res => res.json())
                                                            .then(data => {
                                                                item.priceInfo = data;
                                                                item.priceInfoLoading = false;
                                                            }).catch(() => {
                                                                item.priceInfoLoading = false;
                                                            });
                                                    }
                                                }" class="relative w-full text-right">
                                                    <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id" required>
                                                    
                                                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-2 py-1.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 transition-all text-right">
                                                        <span class="text-[0.75rem] font-medium truncate" :class="item.product_id ? 'text-slate-800' : 'text-slate-400'" x-text="selectedProduct ? selectedProduct.name + ' (متوفر: ' + selectedProduct.stock + ')' : 'اختر المنتج...'"></span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-primary-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open" @click.outside="open = false" class="absolute z-[100] left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" style="display: none; min-width: 220px;">
                                                        <div class="p-1.5 border-b border-slate-100 bg-slate-50/70">
                                                            <input type="text" x-model="search" placeholder="ابحث عن المنتج..." class="w-full px-2 py-1 bg-white border border-slate-200 rounded-md text-xs focus:outline-none focus:border-primary-500">
                                                        </div>
                                                        <div class="max-h-48 overflow-y-auto divide-y divide-slate-50">
                                                            <template x-for="p in filteredProducts" :key="p.id">
                                                                <button type="button" 
                                                                        :disabled="items.some(i => i.product_id == p.id && i.id !== item.id)"
                                                                        @click="selectProduct(p)" 
                                                                        class="w-full px-3 py-2 text-right flex items-center justify-between transition-colors group" 
                                                                        :class="[
                                                                            item.product_id == p.id ? 'bg-primary-50 font-bold' : '',
                                                                            items.some(i => i.product_id == p.id && i.id !== item.id) ? 'opacity-50 cursor-not-allowed bg-slate-50' : 'hover:bg-primary-50/60'
                                                                        ]">
                                                                    <span class="text-[0.75rem] font-medium" :class="items.some(i => i.product_id == p.id && i.id !== item.id) ? 'text-slate-400' : 'text-slate-800 group-hover:text-primary-700'" x-text="items.some(i => i.product_id == p.id && i.id !== item.id) ? p.name + ' (مضاف مسبقاً)' : p.name"></span>
                                                                    <span class="text-[0.7rem] font-bold" :class="items.some(i => i.product_id == p.id && i.id !== item.id) ? 'text-slate-400' : 'text-slate-500'" x-text="p.stock"></span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Mobile Delete Button -->
                                                <button type="button" @click="removeItem(item.id)" class="sm:hidden p-2 text-danger-400 hover:text-danger-600 bg-danger-50 hover:bg-danger-100 rounded-lg shrink-0 transition-colors border border-danger-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>

                                            <!-- Details (Row 2 on mobile) -->
                                            <div class="w-full sm:col-span-6 grid grid-cols-3 gap-2 sm:gap-2">
                                                <!-- Quantity -->
                                                <div>
                                                    <span class="block sm:hidden text-[0.65rem] font-bold text-slate-500 mb-1 text-center">الكمية</span>
                                                    <input type="number" step="0.01" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="0.01" required class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-primary-500 focus:ring-1 text-center" dir="ltr">
                                                </div>
                                                <!-- Price -->
                                                <div>
                                                    <span class="block sm:hidden text-[0.65rem] font-bold text-slate-500 mb-1 text-center">سعر الكيلو</span>
                                                    <input type="number" step="0.01" :name="'items['+index+'][price]'" x-model="item.price" min="0" required class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-primary-500 focus:ring-1 text-center" dir="ltr">
                                                </div>
                                                <!-- Total -->
                                                <div class="flex flex-col justify-end sm:justify-center h-full">
                                                    <span class="block sm:hidden text-[0.65rem] font-bold text-slate-500 mb-1 text-center">الإجمالي</span>
                                                    <div class="text-xs sm:text-sm font-bold text-slate-700 bg-slate-50 sm:bg-transparent py-1.5 rounded-lg text-center h-full flex items-center justify-center border border-slate-100 sm:border-none" dir="ltr">
                                                        <span x-text="(parseFloat(item.quantity || 0) * parseFloat(item.price || 0)).toFixed(2)"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Desktop Delete Button -->
                                            <div class="hidden sm:flex sm:col-span-1 justify-center">
                                                <button type="button" @click="removeItem(item.id)" class="p-1.5 text-danger-400 hover:text-danger-600 hover:bg-danger-50 rounded-lg transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Price Info -->
                                        <div x-show="item.priceInfo" x-transition class="mt-2 flex justify-between items-center px-3 py-1.5 bg-slate-50/50 rounded-lg border border-slate-100 sm:w-1/2">
                                            <span class="text-[0.6rem] text-slate-500">عميل: <span class="font-bold text-primary-600" dir="ltr" x-text="(item.priceInfo && item.priceInfo.entity !== 'لا يوجد') ? item.priceInfo.entity : '-'"></span></span>
                                            <span class="text-[0.6rem] text-slate-500">عام: <span class="font-bold text-slate-700" dir="ltr" x-text="(item.priceInfo && item.priceInfo.overall !== 'لا يوجد') ? item.priceInfo.overall : '-'"></span></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer Total -->
                            <div class="bg-slate-50 border-t border-slate-200 px-4 py-3 flex justify-between items-center rounded-b-xl">
                                <span class="text-sm font-bold text-slate-600">إجمالي الفاتورة:</span>
                                <span class="text-lg font-black text-primary-600" dir="ltr" x-text="total.toLocaleString('en-US', {minimumFractionDigits: 2}) + ' ج.م'"></span>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of Grid -->
                    
                <div class="mt-6 flex gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-sm shadow-primary-600/20" x-text="isEdit ? 'حفظ التعديلات' : 'حفظ الفاتورة'"></button>
                    <button type="button" @click="showSaleModal = false" class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('saleFormComponent', (config) => ({
            products: config.products || [],
            customers: config.customers || [],
            fixedCustomer: config.fixedCustomer || null,
            
            showSaleModal: false,
            isEdit: false,
            loading: false,
            
            invoiceId: null,
            invoiceNumber: '',
            
            items: [{id: Date.now(), product_id: '', quantity: 1, price: 0, priceInfo: null, priceInfoLoading: false}],
            selectedCustomerId: config.fixedCustomer ? config.fixedCustomer.id : '',
            selectedName: config.fixedCustomer ? config.fixedCustomer.name : '',
            paidAmount: 0,
            date: new Date().toISOString().split('T')[0],
            notes: '',
            
            get actionUrl() {
                return this.isEdit ? `/sales/${this.invoiceId}` : '/sales';
            },
            
            createInvoice() {
                this.isEdit = false;
                this.invoiceId = null;
                this.invoiceNumber = '';
                this.items = [{id: Date.now(), product_id: '', quantity: 1, price: 0, priceInfo: null, priceInfoLoading: false}];
                
                if (this.fixedCustomer) {
                    this.selectedCustomerId = this.fixedCustomer.id;
                    this.selectedName = this.fixedCustomer.name;
                } else {
                    this.selectedCustomerId = '';
                    this.selectedName = '';
                }
                
                this.paidAmount = 0;
                this.date = new Date().toISOString().split('T')[0];
                this.notes = '';
                
                this.showSaleModal = true;
            },
            
            async editInvoice(id) {
                this.isEdit = true;
                this.invoiceId = id;
                this.loading = true;
                this.showSaleModal = true;
                
                try {
                    const res = await fetch(`/sales/${id}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const details = await res.json();
                    
                    this.invoiceNumber = details.invoice_number;
                    this.selectedCustomerId = details.customer_id;
                    this.selectedName = details.customer_name;
                    this.date = details.date;
                    this.notes = details.notes || '';
                    this.paidAmount = details.transaction ? details.transaction.paid_cash : 0;
                    
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
                    this.showSaleModal = false;
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
            
            get selectedCustomer() { 
                if (this.fixedCustomer) return this.fixedCustomer;
                return this.customers.find(s => s.id == this.selectedCustomerId) || null; 
            },
            
            get newCustomerBalance() { 
                if (!this.selectedCustomer) return 0;
                // Positive balance for customers means they owe us (لك), so selling adds debt
                return Number(this.selectedCustomer.balance) + this.total - (parseFloat(this.paidAmount) || 0);
            },
            
            formatBalance(amount) {
                if (!amount || amount == 0) return '0 ج.م (خالص)';
                if (amount > 0) return Number(amount).toLocaleString() + ' ج.م (لك)';
                return Number(Math.abs(amount)).toLocaleString() + ' ج.م (عليك)';
            }
        }));
    });
</script>
