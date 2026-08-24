<x-layouts.app title="إدارة الموردين">
    <x-slot name="breadcrumb">الموردين</x-slot>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        showExportModal: false,
        showFilters: false,
        search: '{{ request('search') }}',
        balance_status: '{{ request('balance_status', '') }}',
        min_balance: '{{ request('min_balance', '') }}',
        max_balance: '{{ request('max_balance', '') }}',
        min_volume: '{{ request('min_volume', '') }}',
        max_volume: '{{ request('max_volume', '') }}',
        sort_by: '{{ request('sort_by', 'latest') }}',
        editData: { id: '', name: '', phone: '' },
        deleteId: '',
        loading: false,

        get activeFiltersCount() {
            let count = 0;
            if (this.balance_status !== '') count++;
            if (this.min_balance !== '') count++;
            if (this.max_balance !== '') count++;
            if (this.min_volume !== '') count++;
            if (this.max_volume !== '') count++;
            if (this.sort_by !== 'latest') count++;
            return count;
        },

        fetchData(url = null) {
            this.loading = true;
            let endpoint = url || '{{ route('suppliers.index') }}';
            let urlObj = new URL(endpoint, window.location.origin);
            
            if (!url) {
                if (this.search) urlObj.searchParams.set('search', this.search);
                else urlObj.searchParams.delete('search');
                
                if (this.balance_status) urlObj.searchParams.set('balance_status', this.balance_status);
                else urlObj.searchParams.delete('balance_status');

                if (this.min_balance) urlObj.searchParams.set('min_balance', this.min_balance);
                else urlObj.searchParams.delete('min_balance');

                if (this.max_balance) urlObj.searchParams.set('max_balance', this.max_balance);
                else urlObj.searchParams.delete('max_balance');

                if (this.min_volume) urlObj.searchParams.set('min_volume', this.min_volume);
                else urlObj.searchParams.delete('min_volume');

                if (this.max_volume) urlObj.searchParams.set('max_volume', this.max_volume);
                else urlObj.searchParams.delete('max_volume');
                
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
                let wrapper = document.getElementById('suppliersTableWrapper');
                if (wrapper) wrapper.innerHTML = html;
                this.loading = false;
            })
            .catch(() => {
                this.loading = false;
            });
        },

        resetFilters() {
            this.search = '';
            this.balance_status = '';
            this.min_balance = '';
            this.max_balance = '';
            this.min_volume = '';
            this.max_volume = '';
            this.sort_by = 'latest';
            this.fetchData();
        },

        exportPdf() {
            let urlObj = new URL('{{ route('print.suppliers-report') }}', window.location.origin);
            if (this.search) urlObj.searchParams.set('search', this.search);
            if (this.balance_status) urlObj.searchParams.set('balance_status', this.balance_status);
            if (this.min_balance) urlObj.searchParams.set('min_balance', this.min_balance);
            if (this.max_balance) urlObj.searchParams.set('max_balance', this.max_balance);
            if (this.min_volume) urlObj.searchParams.set('min_volume', this.min_volume);
            if (this.max_volume) urlObj.searchParams.set('max_volume', this.max_volume);
            if (this.sort_by && this.sort_by !== 'latest') urlObj.searchParams.set('sort_by', this.sort_by);
            window.open(urlObj.toString(), '_blank');
        }
    }"
    @click="if ($event.target.closest('.ajax-pagination a')) {
        $event.preventDefault();
        fetchData($event.target.closest('.ajax-pagination a').href);
    }">
        
        <!-- Header & Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 border border-primary-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة الموردين</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">الموردين والشركات المسجلة في النظام</p>
                </div>
            </div>
            <!-- Top Action Buttons (Add Supplier + Export PDF) -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button @click="showExportModal = true" 
                        type="button" 
                        title="تصدير / طباعة التقرير بالـ PDF"
                        class="flex-1 sm:flex-initial px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs sm:text-sm font-bold flex items-center justify-center gap-1.5 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>تصدير PDF</span>
                </button>
                @if(auth()->user()?->hasPermission('suppliers.create'))
                <button @click="showAddModal = true" class="flex-1 sm:flex-initial px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-xs sm:text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>إضافة مورد</span>
                </button>
                @endif
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-xl p-3 sm:p-4 border border-slate-100 shadow-sm mb-4">
            <div class="flex flex-col sm:flex-row items-center gap-2.5">
                <!-- Search Bar -->
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           x-model="search" 
                           @input.debounce.300ms="fetchData()" 
                           placeholder="بحث بالاسم أو رقم الهاتف..." 
                           class="w-full pr-9 pl-9 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-400 text-xs sm:text-sm font-medium text-slate-800 transition-all placeholder:text-slate-400">
                    <button x-show="search.length > 0" 
                            @click="search = ''; fetchData()" 
                            type="button" 
                            class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Advanced Filters Toggle Button -->
                <button @click="showFilters = !showFilters" 
                        type="button" 
                        class="w-full sm:w-auto px-3.5 py-2 border rounded-lg font-bold text-xs flex items-center justify-center gap-2 transition-all shrink-0"
                        :class="showFilters || activeFiltersCount > 0 ? 'bg-primary-50 border-primary-300 text-primary-700 shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>تصفية متقدمة</span>
                    <span x-show="activeFiltersCount > 0" x-text="activeFiltersCount" class="w-4 h-4 rounded-full bg-primary-600 text-white text-[0.6rem] flex items-center justify-center font-black"></span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-300 ease-in-out" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>

            <!-- Collapsible Advanced Filters Drawer with Smooth Animation -->
            <div x-show="showFilters" 
                 x-collapse.duration.300ms
                 x-cloak 
                 class="pt-3 mt-3 border-t border-slate-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5">
                    <!-- Balance Status Filter -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">حالة الحساب</label>
                        <select x-model="balance_status" @change="fetchData()" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 box-border">
                            <option value="">جميع الحسابات</option>
                            <option value="debt">مستحق للمورد (له علينا)</option>
                            <option value="credit">لنا عنده (دافعين زيادة)</option>
                            <option value="zero">خالص (صفر)</option>
                        </select>
                    </div>

                    <!-- Min Balance -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">من رصيد (مستحق)</label>
                        <input type="number" step="any" x-model="min_balance" @input.debounce.400ms="fetchData()" placeholder="0" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 font-mono box-border" dir="ltr">
                    </div>

                    <!-- Max Balance -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">إلى رصيد (مستحق)</label>
                        <input type="number" step="any" x-model="max_balance" @input.debounce.400ms="fetchData()" placeholder="مثال: 50000" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 font-mono box-border" dir="ltr">
                    </div>

                    <!-- Min Volume (Purchases Total) -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">من حجم تعاملات</label>
                        <input type="number" min="0" step="any" x-model="min_volume" @input.debounce.400ms="fetchData()" placeholder="0" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 font-mono box-border" dir="ltr">
                    </div>

                    <!-- Max Volume (Purchases Total) -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">إلى حجم تعاملات</label>
                        <input type="number" min="0" step="any" x-model="max_volume" @input.debounce.400ms="fetchData()" placeholder="مثال: 100000" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 font-mono box-border" dir="ltr">
                    </div>

                    <!-- Sorting Filter -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">الترتيب حسب</label>
                        <select x-model="sort_by" @change="fetchData()" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 box-border">
                            <option value="latest">الأحدث تسجيلاً</option>
                            <option value="oldest">الأقدم تسجيلاً</option>
                            <option value="name_asc">الاسم (أ - ي)</option>
                            <option value="balance_desc">الأعلى رصيداً</option>
                            <option value="balance_asc">الأقل رصيداً</option>
                            <option value="volume_desc">الأكثر تعاملاً</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Actions Row -->
                <div class="flex flex-wrap items-center justify-between gap-2 mt-3 pt-2.5 border-t border-slate-100">
                    <div class="text-[0.72rem] text-slate-500">
                        <span x-show="activeFiltersCount > 0" class="font-bold text-primary-700">تم تطبيق <span x-text="activeFiltersCount"></span> فلتر</span>
                        <span x-show="activeFiltersCount === 0">يتم عرض كافة السجلات المسجلة</span>
                    </div>
                    <div>
                        <button @click="resetFilters()" type="button" class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors inline-flex items-center gap-1.5">
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
            <div id="suppliersTableWrapper">
                @include('suppliers._table', ['suppliers' => $suppliers])
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4 text-center" @click.self="showAddModal = false">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showAddModal = false" aria-hidden="true"></div>
                <div x-show="showAddModal" @click.outside="showAddModal = false" x-transition class="relative bg-white rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all max-w-lg w-full z-10">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-lg font-bold text-slate-800">إضافة مورد جديد</h3>
                                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">اسم المورد <span class="text-danger-500">*</span></label>
                                    <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">رقم الهاتف</label>
                                    <input type="tel" name="phone" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9+]/g, '')" placeholder="مثال: 01012345678" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm" dir="ltr">
                                </div>
                                <div x-data="{ initialAmount: 0, balanceType: 'credit' }">
                                    <input type="hidden" name="balance" :value="balanceType === 'debit' ? -Math.abs(parseFloat(initialAmount) || 0) : Math.abs(parseFloat(initialAmount) || 0)">
                                    
                                    <label class="block text-sm font-bold text-slate-700 mb-1">الرصيد الافتتاحي (اختياري)</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                                        <div class="sm:col-span-7">
                                            <input type="number" step="0.01" min="0" x-model="initialAmount" placeholder="0.00" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm font-bold" dir="ltr">
                                        </div>
                                        <div class="sm:col-span-5">
                                            <select x-model="balanceType" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-xs font-bold text-slate-700">
                                                <option value="credit">له (مستحق للمورد)</option>
                                                <option value="debit">عليه (مستحق لنا عنده)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-[0.7rem] text-slate-500 mt-1 flex items-center gap-1 font-medium">
                                        <span>الحالة:</span>
                                        <span class="font-bold" :class="initialAmount > 0 ? (balanceType === 'credit' ? 'text-amber-700' : 'text-emerald-700') : 'text-slate-500'" x-text="initialAmount > 0 ? (balanceType === 'credit' ? `المورد له عندنا ${Number(initialAmount).toLocaleString()} ج.م` : `لنا عند المورد ${Number(initialAmount).toLocaleString()} ج.م`) : 'الحساب خالص (صفر)'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 flex justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 text-sm font-bold transition-colors">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-bold transition-colors shadow-sm">إضافة المورد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4 text-center" @click.self="showEditModal = false">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false" aria-hidden="true"></div>
                <div x-show="showEditModal" @click.outside="showEditModal = false" x-transition class="relative bg-white rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all max-w-lg w-full z-10">
                    <form :action="`/suppliers/${editData.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-lg font-bold text-slate-800">تعديل بيانات المورد</h3>
                                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">اسم المورد <span class="text-danger-500">*</span></label>
                                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">رقم الهاتف</label>
                                    <input type="tel" name="phone" x-model="editData.phone" inputmode="numeric" @input="editData.phone = editData.phone.replace(/[^0-9+]/g, '')" placeholder="مثال: 01012345678" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm" dir="ltr">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 flex justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 text-sm font-bold transition-colors">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-bold transition-colors shadow-sm">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 min-h-screen">
            
            <div x-show="showDeleteModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.away="showDeleteModal = false"
                 class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-100 p-6 text-center overflow-hidden whitespace-normal break-words">
                
                <form :action="`/suppliers/${deleteId}`" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <!-- Centered Danger Icon -->
                    <div class="mx-auto w-12 h-12 rounded-2xl bg-danger-50 text-danger-600 flex items-center justify-center mb-4 border border-danger-100 shadow-sm">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>

                    <!-- Centered Title & Message -->
                    <h3 class="text-base sm:text-lg font-black text-slate-800 mb-1.5 text-center">تأكيد الحذف</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 text-center px-1">
                        هل أنت متأكد من رغبتك في حذف هذا المورد؟ لن يمكنك التراجع عن هذا الإجراء.
                    </p>

                    <!-- Centered Buttons Grid -->
                    <div class="grid grid-cols-2 gap-2.5">
                        <button type="button" @click="showDeleteModal = false" class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" class="w-full px-4 py-2.5 bg-danger-600 hover:bg-danger-700 text-white rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-danger-500/20">
                            تأكيد الحذف
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export PDF Modal -->
        <div x-show="showExportModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 min-h-screen">
            
            <div x-show="showExportModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.away="showExportModal = false"
                 class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-100 p-5 sm:p-6 text-right overflow-hidden">
                
                <!-- Modal Header -->
                <div class="flex items-center gap-3.5 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-100 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800">تصدير تقرير الموردين (PDF)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">تجهيز التقرير للطباعة أو التنزيل الفوري</p>
                    </div>
                </div>

                <!-- Report Details Box -->
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200/80 mb-5 space-y-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">نوع التقرير:</span>
                        <span class="font-bold text-slate-800">تقرير أرصدة ومستحقات الموردين</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">نطاق البيانات:</span>
                        <template x-if="activeFiltersCount > 0 || search.length > 0">
                            <span class="font-bold text-primary-700 bg-primary-50 px-2 py-0.5 rounded border border-primary-200 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-600 inline-block"></span>
                                مفلتر (بناءً على التصفية الحالية)
                            </span>
                        </template>
                        <template x-if="activeFiltersCount === 0 && search.length === 0">
                            <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 inline-block"></span>
                                شامل (كافة الموردين المسجلين)
                            </span>
                        </template>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">تاريخ الاستخراج:</span>
                        <span class="font-bold text-slate-700 font-mono">{{ now()->format('Y-m-d') }}</span>
                    </div>
                </div>

                <!-- Modal Action Buttons -->
                <div class="flex items-center gap-2.5">
                    <button @click="exportPdf(); showExportModal = false" 
                            type="button" 
                            class="flex-1 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-primary-600/25 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>تنزيل / طباعة التقرير</span>
                    </button>
                    <button @click="showExportModal = false" 
                            type="button" 
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>