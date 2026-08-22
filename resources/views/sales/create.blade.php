<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'إنشاء فاتورة مبيعات']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'إنشاء فاتورة مبيعات']); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> المبيعات > فاتورة جديدة <?php $__env->endSlot(); ?>

    <div class="mb-4 flex justify-end">
        <a href="<?php echo e(route('sales.index')); ?>" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
            العودة للفواتير
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden"
         x-data="salesInvoice({ 
             products: <?php echo e(Js::from($products)); ?>,
             customers: <?php echo e(Js::from($customers)); ?>

         })">
        
        <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base sm:text-xl font-black text-slate-800">فاتورة مبيعات جديدة</h2>
                <p class="text-xs text-slate-500 mt-0.5">إضافة مبيعات وخصمها من المخزن تلقائياً</p>
            </div>
        </div>

        <form action="<?php echo e(route('sales.store')); ?>" method="POST" class="p-4 sm:p-6">
            <?php echo csrf_field(); ?>
            
            <!-- Customer & Notes -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 pb-6 border-b border-slate-100">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">اسم العميل <span class="text-danger-500">*</span></label>
                    <select name="customer_id" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                        <option value="">-- اختر العميل --</option>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php if(request('customer_id') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">تاريخ الفاتورة <span class="text-danger-500">*</span></label>
                    <input type="date" name="date" required value="<?php echo e(date('Y-m-d')); ?>" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">المدفوع نقداً (ج.م)</label>
                    <input type="number" name="paid_amount" step="0.01" min="0" value="0" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">ملاحظات (اختياري)</label>
                    <input type="text" name="notes" placeholder="أي ملاحظات..." class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                </div>
            </div>

            <!-- Items Header -->
            <div class="mb-3 flex justify-between items-center">
                <h3 class="text-sm sm:text-lg font-bold text-slate-800">الأصناف المباعة</h3>
                <button type="button" @click="addItem" class="px-3 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 text-xs sm:text-sm font-bold flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    إضافة صنف
                </button>
            </div>

            <!-- Mobile Items (Card Layout) -->
            <div class="sm:hidden space-y-4 mb-6">
                <template x-for="(item, index) in items" :key="index">
                    <div class="bg-slate-50 rounded-xl border border-slate-100 p-4 relative">
                        <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="absolute top-2 left-2 p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-danger-600 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">الصنف</label>
                                <div class="relative" @click.outside="item.open = false">
                                    <input type="hidden" :name="`items[${index}][product_id]`" :value="item.product_id" required>
                                    
                                    <!-- Mobile Trigger -->
                                    <button type="button" 
                                            @click="item.open = !item.open" 
                                            class="w-full flex items-center justify-between px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm text-right focus:outline-none focus:border-primary-500">
                                        <span class="truncate font-medium" :class="item.product_id ? 'text-slate-800' : 'text-slate-400'" 
                                              x-text="item.product_id && getProduct(item.product_id) ? getProduct(item.product_id).name + ' (متوفر: ' + Number(getProduct(item.product_id).stock).toLocaleString('en-US') + ' ك)' : '-- اختر الصنف --'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0 mr-1 transition-transform" :class="item.open ? 'rotate-180 text-primary-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <!-- Mobile Dropdown Menu -->
                                    <div x-show="item.open" 
                                         class="absolute z-50 right-0 left-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" 
                                         style="display: none;">
                                        <div class="p-2 border-b border-slate-100 bg-slate-50">
                                            <div class="relative">
                                                <input type="text" 
                                                       x-model="item.search" 
                                                       placeholder="بحث عن صنف بالاسم..." 
                                                       class="w-full pl-2 pr-7 py-1.5 bg-white border border-slate-200 rounded-md text-xs focus:outline-none focus:border-primary-500"
                                                       @keydown.escape="item.open = false"
                                                       @click.stop>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 absolute right-2 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="max-h-52 overflow-y-auto divide-y divide-slate-50">
                                            <template x-for="product in getFilteredProducts(item.search)" :key="product.id">
                                                <button type="button" 
                                                        @click="selectProduct(index, product)" 
                                                        class="w-full px-3 py-2 text-right flex items-center justify-between hover:bg-primary-50 transition-colors group text-xs"
                                                        :class="item.product_id == product.id ? 'bg-primary-50 font-bold' : ''">
                                                    <div class="flex items-center gap-1.5 truncate">
                                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" :class="product.stock > 0 ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                                                        <span class="text-slate-800 group-hover:text-primary-700 truncate" x-text="product.name"></span>
                                                    </div>
                                                    <span class="px-1.5 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-600 group-hover:bg-primary-100 group-hover:text-primary-800 shrink-0 mr-2" dir="ltr" x-text="'متوفر: ' + Number(product.stock).toLocaleString('en-US') + ' ك'"></span>
                                                </button>
                                            </template>
                                            <div x-show="getFilteredProducts(item.search).length === 0" class="p-3 text-center text-xs text-slate-400">
                                                لا توجد أصناف مطابقة
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1">الكمية (كيلو)</label>
                                    <input type="number" x-model.number="item.quantity" :name="`items[${index}][quantity]`" step="0.01" min="0.01" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-white text-center text-base" dir="ltr">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1">سعر الكيلو (ج.م)</label>
                                    <input type="number" x-model.number="item.unit_price" :name="`items[${index}][unit_price]`" step="0.01" min="0" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-white text-center text-base" dir="ltr">
                                </div>
                            </div>
                            <div class="text-center pt-2 border-t border-slate-200">
                                <span class="text-xs text-slate-400">الإجمالي:</span>
                                <span class="text-lg font-black text-primary-700 mr-1" dir="ltr" x-text="formatCurrency(item.quantity * item.unit_price)"></span>
                                <span class="text-xs text-slate-400">ج.م</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Desktop Items Table -->
            <div class="hidden sm:block rounded-xl border border-slate-100 bg-white mb-6 min-h-[220px]">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-right">الصنف (المنتج)</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center w-36">الكمية (كيلو)</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center w-40">سعر الكيلو (ج.م)</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center w-44">الإجمالي (ج.م)</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide text-center w-16">حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-2.5 border-b border-slate-50 align-middle text-right">
                                    <div class="relative" @click.outside="item.open = false">
                                        <input type="hidden" :name="`items[${index}][product_id]`" :value="item.product_id" required>
                                        
                                        <!-- Trigger Button -->
                                        <button type="button" 
                                                @click="item.open = !item.open" 
                                                class="w-full flex items-center justify-between px-3 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg text-sm text-right focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-400 transition-all">
                                            <div class="flex items-center gap-2 truncate">
                                                <template x-if="item.product_id && getProduct(item.product_id)">
                                                    <span class="flex items-center gap-1.5 truncate">
                                                        <span class="w-2 h-2 rounded-full" :class="getProduct(item.product_id).stock > 0 ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                                                        <span class="font-bold text-slate-800 truncate" x-text="getProduct(item.product_id).name"></span>
                                                        <span class="text-xs text-slate-400 font-normal" x-text="'(متوفر: ' + Number(getProduct(item.product_id).stock).toLocaleString('en-US') + ' ك)'"></span>
                                                    </span>
                                                </template>
                                                <template x-if="!item.product_id">
                                                    <span class="text-slate-400 font-medium">-- اختر الصنف المطلوب --</span>
                                                </template>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 mr-2 transition-transform" :class="item.open ? 'rotate-180 text-primary-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="item.open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="absolute z-50 right-0 top-full mt-1 w-full min-w-[280px] bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" 
                                             style="display: none;">
                                            
                                            <!-- Search input -->
                                            <div class="p-2 border-b border-slate-100 bg-slate-50">
                                                <div class="relative">
                                                    <input type="text" 
                                                           x-model="item.search" 
                                                           placeholder="بحث عن صنف بالاسم..." 
                                                           class="w-full pl-2 pr-7 py-1.5 bg-white border border-slate-200 rounded-md text-xs focus:outline-none focus:border-primary-500"
                                                           @keydown.escape="item.open = false"
                                                           @click.stop>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 absolute right-2 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Options List -->
                                            <div class="max-h-56 overflow-y-auto divide-y divide-slate-50">
                                                <template x-for="product in getFilteredProducts(item.search)" :key="product.id">
                                                    <button type="button" 
                                                            @click="selectProduct(index, product)" 
                                                            class="w-full px-3.5 py-2 text-right flex items-center justify-between hover:bg-primary-50 transition-colors group text-xs"
                                                            :class="item.product_id == product.id ? 'bg-primary-50 font-bold' : ''">
                                                        <div class="flex items-center gap-2 truncate">
                                                            <span class="w-1.5 h-1.5 rounded-full shrink-0" :class="product.stock > 0 ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                                                            <span class="text-slate-800 group-hover:text-primary-700 truncate font-medium" x-text="product.name"></span>
                                                        </div>
                                                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-600 group-hover:bg-primary-100 group-hover:text-primary-800 shrink-0 mr-2" dir="ltr" x-text="'متوفر: ' + Number(product.stock).toLocaleString('en-US') + ' ك'"></span>
                                                    </button>
                                                </template>
                                                <div x-show="getFilteredProducts(item.search).length === 0" class="p-3 text-center text-xs text-slate-400">
                                                    لا توجد أصناف مطابقة للبحث
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 border-b border-slate-50 align-middle">
                                    <input type="number" x-model.number="item.quantity" :name="`items[${index}][quantity]`" step="0.01" min="0.01" required class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-white text-center text-sm" dir="ltr">
                                </td>
                                <td class="px-4 py-2.5 border-b border-slate-50 align-middle">
                                    <input type="number" x-model.number="item.unit_price" :name="`items[${index}][unit_price]`" step="0.01" min="0" required class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-white text-center text-sm" dir="ltr">
                                </td>
                                <td class="px-4 py-2.5 border-b border-slate-50 align-middle">
                                    <div class="px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg text-sm font-bold text-primary-700" dir="ltr" x-text="formatCurrency(item.quantity * item.unit_price)"></div>
                                </td>
                                <td class="px-4 py-2.5 border-b border-slate-50 align-middle">
                                    <button type="button" @click="removeItem(index)" class="p-1.5 rounded border border-slate-200 bg-white text-slate-400 hover:text-danger-600 hover:border-danger-600 shadow-sm transition-all" x-show="items.length > 1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="flex flex-col sm:flex-row justify-between items-center bg-slate-50 p-4 sm:p-6 rounded-xl border border-slate-100 gap-4">
                <div class="text-center sm:text-right w-full sm:w-auto">
                    <p class="text-xs text-slate-500 font-bold mb-1">إجمالي الفاتورة</p>
                    <p class="text-3xl sm:text-4xl font-black text-primary-700" dir="ltr">
                        <span x-text="formatCurrency(calculateTotal())"></span> <span class="text-sm text-slate-500 font-bold">ج.م</span>
                    </p>
                    <p class="text-[0.65rem] text-danger-600 mt-1 font-bold">سيتم إضافة هذا الإجمالي لمديونية العميل فور الحفظ.</p>
                </div>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-base font-black transition-colors shadow-lg shadow-primary-600/30 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                    حفظ الفاتورة
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('salesInvoice', ({ products, customers }) => ({
                products: products,
                customers: customers,
                items: [{ product_id: '', quantity: 1, unit_price: 0, open: false, search: '' }],
                addItem() { 
                    this.items.push({ product_id: '', quantity: 1, unit_price: 0, open: false, search: '' }); 
                },
                removeItem(index) { 
                    if (this.items.length > 1) this.items.splice(index, 1); 
                },
                getFilteredProducts(search) {
                    if (!search) return this.products;
                    const q = search.toLowerCase();
                    return this.products.filter(p => p.name.toLowerCase().includes(q));
                },
                getProduct(id) {
                    return this.products.find(p => p.id == id);
                },
                selectProduct(index, product) {
                    this.items[index].product_id = product.id;
                    this.items[index].open = false;
                    this.items[index].search = '';
                },
                calculateTotal() {
                    return this.items.reduce((total, item) => {
                        return total + ((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0));
                    }, 0);
                },
                formatCurrency(value) {
                    return (parseFloat(value) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }));
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
