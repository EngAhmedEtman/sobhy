<x-layouts.app title="إدارة المبيعات">
    <x-slot name="breadcrumb">المبيعات</x-slot>

    <div x-data="{ 
        showDetailsModal: false, 
        details: null, 
        loadingDetails: false,
        showFilters: false,
        search: '{{ request('search') }}',
        customer_id: '{{ request('customer_id', '') }}',
        product_id: '{{ request('product_id', '') }}',
        start_date: '{{ request('start_date', '') }}',
        end_date: '{{ request('end_date', '') }}',
        min_amount: '{{ request('min_amount', '') }}',
        max_amount: '{{ request('max_amount', '') }}',
        sort_by: '{{ request('sort_by', 'latest') }}',
        loading: false,

        get activeFiltersCount() {
            let count = 0;
            if (this.customer_id !== '') count++;
            if (this.product_id !== '') count++;
            if (this.start_date !== '') count++;
            if (this.end_date !== '') count++;
            if (this.min_amount !== '') count++;
            if (this.max_amount !== '') count++;
            if (this.sort_by !== 'latest') count++;
            return count;
        },

        fetchData(url = null) {
            this.loading = true;
            let endpoint = url || '{{ route('sales.index') }}';
            let urlObj = new URL(endpoint, window.location.origin);
            
            if (!url) {
                if (this.search) urlObj.searchParams.set('search', this.search);
                else urlObj.searchParams.delete('search');
                
                if (this.customer_id) urlObj.searchParams.set('customer_id', this.customer_id);
                else urlObj.searchParams.delete('customer_id');

                if (this.product_id) urlObj.searchParams.set('product_id', this.product_id);
                else urlObj.searchParams.delete('product_id');

                if (this.start_date) urlObj.searchParams.set('start_date', this.start_date);
                else urlObj.searchParams.delete('start_date');

                if (this.end_date) urlObj.searchParams.set('end_date', this.end_date);
                else urlObj.searchParams.delete('end_date');

                if (this.min_amount) urlObj.searchParams.set('min_amount', this.min_amount);
                else urlObj.searchParams.delete('min_amount');

                if (this.max_amount) urlObj.searchParams.set('max_amount', this.max_amount);
                else urlObj.searchParams.delete('max_amount');
                
                if (this.sort_by && this.sort_by !== 'latest') urlObj.searchParams.set('sort_by', this.sort_by);
                else urlObj.searchParams.delete('sort_by');
            }
            urlObj.searchParams.set('ajax', '1');

            fetch(urlObj.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                let wrapper = document.getElementById('salesTableWrapper');
                if (wrapper) wrapper.innerHTML = html;
                this.loading = false;
            })
            .catch(() => {
                this.loading = false;
            });
        },

        resetFilters() {
            this.search = '';
            this.customer_id = '';
            this.product_id = '';
            this.start_date = '';
            this.end_date = '';
            this.min_amount = '';
            this.max_amount = '';
            this.sort_by = 'latest';
            this.fetchData();
        },

        viewInvoice(id) { 
            this.loadingDetails = true; 
            this.showDetailsModal = true; 
            fetch('/sales/' + id, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(d => { this.details = d; this.loadingDetails = false; }); 
        } 
    }"
    @click="if ($event.target.closest('.ajax-pagination a')) {
        $event.preventDefault();
        fetchData($event.target.closest('.ajax-pagination a').href);
    }">

        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 border border-primary-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة المبيعات</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">سجل فواتير المبيعات للعملاء</p>
                </div>
            </div>
            <button @click="$dispatch('create-sale')" type="button" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                إضافة فاتورة مبيعات
            </button>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm mb-5">
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Search Bar -->
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           x-model="search" 
                           @input.debounce.300ms="fetchData()" 
                           placeholder="بحث برقم الفاتورة، اسم العميل، الملاحظات..." 
                           class="w-full pr-10 pl-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-sm font-medium text-slate-800 transition-all placeholder:text-slate-400">
                    <button x-show="search.length > 0" 
                            @click="search = ''; fetchData()" 
                            type="button" 
                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Advanced Filters Toggle Button -->
                <button @click="showFilters = !showFilters" 
                        type="button" 
                        class="w-full sm:w-auto px-4 py-2.5 border rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all shrink-0"
                        :class="showFilters || activeFiltersCount > 0 ? 'bg-primary-50 border-primary-300 text-primary-700 shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>تصفية متقدمة</span>
                    <span x-show="activeFiltersCount > 0" x-text="activeFiltersCount" class="w-5 h-5 rounded-full bg-primary-600 text-white text-[0.65rem] flex items-center justify-center font-black"></span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>

            <!-- Collapsible Advanced Filters Drawer (Accordion) -->
            <div x-show="showFilters" 
                 x-collapse 
                 x-cloak 
                 class="pt-4 mt-4 border-t border-slate-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <!-- Customer Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">العميل</label>
                        <select x-model="customer_id" @change="fetchData()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500">
                            <option value="">جميع العملاء</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">الصنف المباع</label>
                        <select x-model="product_id" @change="fetchData()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500">
                            <option value="">جميع الأصناف</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range: From Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">من تاريخ</label>
                        <input type="date" x-model="start_date" @change="fetchData()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500">
                    </div>

                    <!-- Date Range: To Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">إلى تاريخ</label>
                        <input type="date" x-model="end_date" @change="fetchData()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500">
                    </div>

                    <!-- Min Amount -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">الحد الأدنى للمبلغ</label>
                        <input type="number" min="0" step="any" x-model="min_amount" @input.debounce.400ms="fetchData()" placeholder="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 font-mono" dir="ltr">
                    </div>

                    <!-- Max Amount -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">الحد الأقصى للمبلغ</label>
                        <input type="number" min="0" step="any" x-model="max_amount" @input.debounce.400ms="fetchData()" placeholder="مثال: 50000" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 font-mono" dir="ltr">
                    </div>

                    <!-- Sorting Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">الترتيب حسب</label>
                        <select x-model="sort_by" @change="fetchData()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500">
                            <option value="latest">التاريخ (الأحدث أولاً)</option>
                            <option value="oldest">التاريخ (الأقدم أولاً)</option>
                            <option value="amount_desc">الأعلى قيمة</option>
                            <option value="amount_asc">الأقل قيمة</option>
                        </select>
                    </div>

                    <!-- Actions (Reset) -->
                    <div class="flex items-end">
                        <button @click="resetFilters()" type="button" class="w-full px-3 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            إعادة ضبط الفلاتر
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container with Skeleton Loading -->
        <div class="relative min-h-[200px]">
            <!-- Skeleton Loader Overlay -->
            <div x-show="loading" 
                 x-transition.opacity 
                 class="absolute inset-0 bg-white/70 backdrop-blur-[1px] z-20 flex flex-col items-center justify-center rounded-2xl">
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-lg">
                    <svg class="animate-spin h-4 w-4 text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>جاري التحديث...</span>
                </div>
            </div>

            <!-- Dynamic Table Content -->
            <div id="salesTableWrapper">
                @include('sales._table', ['sales' => $sales])
            </div>
        </div>
        
        <!-- Sale Modal Component -->
        <x-modals.sale-form :products="$products" :customers="$customers" />

        <!-- View Details Modal -->
        <x-modals.invoice-details type="sale" />

    </div>
</x-layouts.app>