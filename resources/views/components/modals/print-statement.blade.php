@props(['entityId', 'type' => 'customer', 'invoices' => [], 'operations' => []])

<!-- Print Statement Modal -->
<div x-data="{
        filter: 'all',
        startDate: '',
        endDate: '',
        operations: {{ Js::from($operations ?? []) }},
        selectedOperationIds: [],
        searchOperation: '',
        operationTypeFilter: 'all',
        get filteredOperations() {
            let list = this.operations;
            if (this.operationTypeFilter === 'invoices') {
                list = list.filter(o => o.type === 'sale' || o.type === 'purchase');
            } else if (this.operationTypeFilter === 'payments') {
                list = list.filter(o => ['payment_received', 'payment_made', 'cash_withdrawal'].includes(o.type));
            } else if (this.operationTypeFilter === 'returns') {
                list = list.filter(o => o.type === 'return_sale' || o.type === 'return_purchase');
            }

            if (!this.searchOperation) return list;
            const q = this.searchOperation.toLowerCase();
            return list.filter(o => 
                (o.type_label && o.type_label.toLowerCase().includes(q)) ||
                (o.notes && o.notes.toLowerCase().includes(q)) ||
                (o.date && o.date.includes(q)) ||
                (o.amount && String(o.amount).includes(q))
            );
        },
        toggleSelectAll() {
            const currentFilteredIds = this.filteredOperations.map(o => o.id);
            const allSelected = currentFilteredIds.length > 0 && currentFilteredIds.every(id => this.selectedOperationIds.includes(id));
            if (allSelected) {
                this.selectedOperationIds = this.selectedOperationIds.filter(id => !currentFilteredIds.includes(id));
            } else {
                this.selectedOperationIds = [...new Set([...this.selectedOperationIds, ...currentFilteredIds])];
            }
        },
        generatePrint() {
            let baseUrl = '{{ $type === 'supplier' ? '/suppliers/' : '/customers/' }}' + '{{ $entityId }}' + '/print';
            let params = new URLSearchParams();
            params.set('filter', this.filter || 'all');
            
            if (this.filter === 'custom') {
                if (this.startDate) params.set('start_date', this.startDate);
                if (this.endDate) params.set('end_date', this.endDate);
            } else if (this.filter === 'selected_operations') {
                if (this.selectedOperationIds.length === 0) {
                    alert('يرجى تحديد عملية أو فاتورة واحدة على الأقل للطباعة');
                    return;
                }
                params.set('transaction_ids', this.selectedOperationIds.join(','));
            }

            let url = baseUrl + '?' + params.toString();
            if (typeof window.openPrintPreviewModal === 'function') {
                window.openPrintPreviewModal('printPreviewModal', url);
            } else {
                window.open(url, '_blank');
            }
            showPrintModal = false;
        }
    }"
    x-show="showPrintModal" 
    x-cloak 
    class="fixed inset-0 z-[60] overflow-y-auto" 
    style="display: none;">
    <div class="flex items-start justify-center min-h-screen pt-6 sm:pt-10 p-4 text-center" @click.self="showPrintModal = false">
        <div x-show="showPrintModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showPrintModal = false"></div>
        <div x-show="showPrintModal" @click.outside="showPrintModal = false" x-transition class="relative w-full max-w-lg p-5 sm:p-6 text-right transition-all transform bg-white shadow-2xl rounded-2xl z-10" dir="rtl">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">طباعة كشف حساب {{ $type === 'supplier' ? 'المورد' : 'العميل' }}</h3>
                </div>
                <button @click="showPrintModal = false" type="button" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>

            <div class="space-y-2 text-slate-700 max-h-[70vh] overflow-y-auto pl-1 pr-1 custom-scrollbar">
                <p class="text-xs font-bold text-slate-700 mb-1">اختر نوع وفترة كشف الحساب:</p>
                
                <!-- Option 1: All -->
                <label class="flex items-start gap-3 p-2.5 border rounded-xl cursor-pointer transition-colors" :class="filter === 'all' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="all" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div>
                        <div class="text-xs font-bold text-slate-800">كشف حساب شامل</div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5">جميع العمليات والحركات المالية منذ البداية وحتى اليوم.</div>
                    </div>
                </label>

                <!-- Option 2: Since Last Zero Balance Settlement -->
                <label class="flex items-start gap-3 p-2.5 border rounded-xl cursor-pointer transition-colors" :class="filter === 'last_zero' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="last_zero" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div>
                        <div class="text-xs font-bold text-slate-800">منذ آخر تسوية للرصيد (رصيد خالص / صفر)</div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5">عرض العمليات المسجلة بعد آخر مرة تم فيها تصفير الحساب بالكامل.</div>
                    </div>
                </label>

                <!-- Option 3: This Month -->
                <label class="flex items-start gap-3 p-2.5 border rounded-xl cursor-pointer transition-colors" :class="filter === 'this_month' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="this_month" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div>
                        <div class="text-xs font-bold text-slate-800">كشف حساب الشهر الحالي</div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5">حركات شهر {{ now()->translatedFormat('F Y') }} فقط مع الرصيد السابق.</div>
                    </div>
                </label>

                <!-- Option 4: Custom Date Range -->
                <label class="flex items-start gap-3 p-2.5 border rounded-xl cursor-pointer transition-colors" :class="filter === 'custom' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="custom" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div class="w-full">
                        <div class="text-xs font-bold text-slate-800">فترة مخصصة حسب التاريخ</div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5 mb-1.5">حدد تاريخ البداية والنهاية لكشف الحساب.</div>
                        
                        <div x-show="filter === 'custom'" class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-100">
                            <!-- Start Date -->
                            <div>
                                <label class="block text-[0.7rem] font-bold text-slate-600 mb-1">من تاريخ</label>
                                <input type="date" x-model="startDate" lang="en" max="{{ date('Y-m-d') }}" class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" dir="ltr">
                            </div>
                            
                            <!-- End Date -->
                            <div>
                                <label class="block text-[0.7rem] font-bold text-slate-600 mb-1">إلى تاريخ</label>
                                <input type="date" x-model="endDate" lang="en" max="{{ date('Y-m-d') }}" class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" dir="ltr">
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Option 5: Selected Specific Operations & Invoices with Full Item Details -->
                <label class="flex items-start gap-3 p-2.5 border rounded-xl cursor-pointer transition-colors" :class="filter === 'selected_operations' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="selected_operations" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div class="w-full">
                        <div class="flex items-center justify-between">
                            <div class="text-xs font-bold text-slate-800">كشف عمليات وفواتير محددة (مع تفاصيل الأصناف) ⭐</div>
                            <span class="text-[0.65rem] px-1.5 py-0.5 rounded font-bold bg-primary-100 text-primary-700">تفصيلي مجمع</span>
                        </div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5">تحديد أي عدد من الفواتير والعمليات (فواتير، سحب، إيداع/تحصيل، مرتجعات) لطباعتها بتفاصيل بنودها.</div>

                        <!-- Operations Selection Section -->
                        <div x-show="filter === 'selected_operations'" class="mt-2.5 pt-2.5 border-t border-slate-200/80 space-y-2" @click.stop>
                            
                            <!-- Type Filter Pills -->
                            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-[0.7rem] font-bold">
                                <button type="button" @click="operationTypeFilter = 'all'"
                                        class="px-2 py-1 rounded-lg transition-all border"
                                        :class="operationTypeFilter === 'all' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                                    الكل
                                </button>
                                <button type="button" @click="operationTypeFilter = 'invoices'"
                                        class="px-2 py-1 rounded-lg transition-all border"
                                        :class="operationTypeFilter === 'invoices' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                                    الفواتير فقط
                                </button>
                                <button type="button" @click="operationTypeFilter = 'payments'"
                                        class="px-2 py-1 rounded-lg transition-all border"
                                        :class="operationTypeFilter === 'payments' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                                    سداد / تحصيل / سحب
                                </button>
                                <button type="button" @click="operationTypeFilter = 'returns'"
                                        class="px-2 py-1 rounded-lg transition-all border"
                                        :class="operationTypeFilter === 'returns' ? 'bg-amber-600 text-white border-amber-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                                    مرتجعات
                                </button>
                            </div>

                            <!-- Search & Action header -->
                            <div class="flex items-center justify-between gap-2">
                                <div class="relative flex-1">
                                    <input type="text" x-model="searchOperation" placeholder="بحث بالنوع، البيان، المبلغ أو التاريخ..." class="w-full pl-2 pr-7 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                                    <svg class="w-3.5 h-3.5 text-slate-400 absolute right-2 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                                <button type="button" @click="toggleSelectAll()" class="px-2.5 py-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-xs font-bold shrink-0 transition-colors shadow-sm">
                                    تحديد / إلغاء
                                </button>
                            </div>

                            <!-- Count Status -->
                            <div class="flex items-center justify-between text-[0.7rem] text-slate-500 font-bold px-1">
                                <span>تم تحديد <strong class="text-primary-700 font-black" x-text="selectedOperationIds.length"></strong> من أصل <span x-text="operations.length"></span> عملية</span>
                            </div>

                            <!-- Scrollable Operations Checkbox List -->
                            <div class="max-h-48 overflow-y-auto space-y-1.5 border border-slate-200 rounded-xl p-2 bg-slate-50/70 custom-scrollbar">
                                <template x-for="op in filteredOperations" :key="op.id">
                                    <label class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-100 hover:border-primary-200 transition-colors cursor-pointer text-xs"
                                           :class="selectedOperationIds.includes(op.id) ? 'bg-primary-50/60 border-primary-300 font-semibold' : ''">
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" :value="op.id" x-model="selectedOperationIds" class="rounded text-primary-600 focus:ring-primary-500">
                                            <div>
                                                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                                    <!-- Operation Badge -->
                                                    <span class="text-[0.65rem] px-1.5 py-0.5 rounded font-bold"
                                                          :class="{
                                                              'bg-blue-100 text-blue-700': op.type === 'sale' || op.type === 'purchase',
                                                              'bg-emerald-100 text-emerald-700': op.type === 'payment_received' || op.type === 'payment_made',
                                                              'bg-purple-100 text-purple-700': op.type === 'cash_withdrawal',
                                                              'bg-amber-100 text-amber-800': op.type === 'return_sale' || op.type === 'return_purchase',
                                                              'bg-slate-100 text-slate-700': !['sale','purchase','payment_received','payment_made','cash_withdrawal','return_sale','return_purchase'].includes(op.type)
                                                          }"
                                                          x-text="op.type_label">
                                                    </span>
                                                    <span class="text-[0.65rem] text-slate-400 font-normal" x-text="op.date"></span>
                                                </div>
                                                <div x-show="op.notes" class="text-[0.65rem] text-slate-500 truncate max-w-[180px]" x-text="op.notes"></div>
                                            </div>
                                        </div>
                                        <div class="text-left font-mono">
                                            <div class="font-black text-slate-800" dir="ltr">
                                                <span x-text="Number(op.amount).toLocaleString('en-US', {maximumFractionDigits: 2})"></span>
                                                <span class="text-[0.65rem] text-slate-400 font-normal">ج.م</span>
                                            </div>
                                            <div x-show="op.type === 'sale' || op.type === 'purchase'" class="text-[0.65rem] font-bold" :class="op.balance_after > 0 ? 'text-rose-600' : 'text-emerald-600'">
                                                <span x-text="op.balance_after > 0 ? 'متبقي: ' + Number(op.balance_after).toLocaleString('en-US', {maximumFractionDigits: 2}) : 'خالص'"></span>
                                            </div>
                                        </div>
                                    </label>
                                </template>

                                <div x-show="filteredOperations.length === 0" class="py-4 text-center text-xs text-slate-400 font-bold">
                                    لا توجد عمليات مطابقة
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <div class="mt-4 flex gap-2 pt-3 border-t border-slate-100">
                <button type="button" @click="showPrintModal = false" class="w-1/3 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">إلغاء</button>
                <button type="button" @click="generatePrint()" class="w-2/3 px-4 py-2.5 text-xs sm:text-sm font-bold text-white bg-slate-800 rounded-xl hover:bg-slate-900 shadow-sm transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>عرض وطباعة التقرير</span>
                </button>
            </div>
        </div>
    </div>
</div>
