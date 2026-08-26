<x-layouts.app title="إدارة المنتجات">
    <x-slot name="breadcrumb">المنتجات</x-slot>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        search: '{{ request('search') }}',
        stock_status: '{{ request('stock_status', '') }}',
        min_stock: '{{ request('min_stock', '') }}',
        max_stock: '{{ request('max_stock', '') }}',
        sort_by: '{{ request('sort_by', 'latest') }}',
        showFilters: false,
        loading: false,
        editData: { id: '', name: '', notes: '', hasOpening: false },
        deleteId: '',
        
        get activeFiltersCount() {
            let count = 0;
            if (this.stock_status !== '') count++;
            if (this.min_stock !== '') count++;
            if (this.max_stock !== '') count++;
            if (this.sort_by !== 'latest') count++;
            return count;
        },

        fetchData(url = null) {
            this.loading = true;
            let endpoint = url || '{{ route('products.index') }}';
            let urlObj = new URL(endpoint, window.location.origin);
            
            if (!url) {
                if (this.search) urlObj.searchParams.set('search', this.search);
                else urlObj.searchParams.delete('search');
                
                if (this.stock_status) urlObj.searchParams.set('stock_status', this.stock_status);
                else urlObj.searchParams.delete('stock_status');

                if (this.min_stock) urlObj.searchParams.set('min_stock', this.min_stock);
                else urlObj.searchParams.delete('min_stock');

                if (this.max_stock) urlObj.searchParams.set('max_stock', this.max_stock);
                else urlObj.searchParams.delete('max_stock');
                
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
                let wrapper = document.getElementById('productsTableWrapper');
                if (wrapper) wrapper.innerHTML = html;
                this.loading = false;
            })
            .catch(() => {
                this.loading = false;
            });
        },

        resetFilters() {
            this.search = '';
            this.stock_status = '';
            this.min_stock = '';
            this.max_stock = '';
            this.sort_by = 'latest';
            this.fetchData();
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة المنتجات</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">الأصناف والمنتجات المسجلة في المخزن</p>
                </div>
            </div>
            @if(auth()->user()?->hasPermission('products.create'))
            <button @click="showAddModal = true" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                إضافة منتج
            </button>
            @endif
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
                           placeholder="بحث باسم المنتج أو الملاحظات..." 
                           class="w-full pr-9 pl-9 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-400 text-xs sm:text-sm font-medium text-slate-800 transition-all placeholder:text-slate-400">
                    <button x-show="search.length > 0" 
                            @click="search = ''; fetchData()" 
                            type="button" 
                            class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400 hover:text-slate-600"
                            style="display: none;">
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
                    <span x-show="activeFiltersCount > 0" x-text="activeFiltersCount" class="w-4 h-4 rounded-full bg-primary-600 text-white text-[0.6rem] flex items-center justify-center font-black" style="display: none;"></span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-300 ease-in-out" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>

            <!-- Collapsible Advanced Filters Drawer -->
            <div x-show="showFilters" 
                 x-collapse.duration.300ms
                 x-cloak 
                 class="pt-3 mt-3 border-t border-slate-100"
                 style="display: none;">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2.5">
                    <!-- Stock Status Filter -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">حالة الرصيد</label>
                        <select x-model="stock_status" @change="fetchData()" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 box-border">
                            <option value="">جميع المنتجات</option>
                            <option value="in_stock">متوفر في المخزن</option>
                            <option value="out_of_stock">رصيد نفذ</option>
                        </select>
                    </div>

                    <!-- Min Stock -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">من كمية</label>
                        <input type="number" min="0" step="any" x-model="min_stock" @input.debounce.400ms="fetchData()" placeholder="0" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 font-mono box-border" dir="ltr">
                    </div>

                    <!-- Max Stock -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">إلى كمية</label>
                        <input type="number" min="0" step="any" x-model="max_stock" @input.debounce.400ms="fetchData()" placeholder="" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 font-mono box-border" dir="ltr">
                    </div>

                    <!-- Sorting Filter -->
                    <div class="flex flex-col">
                        <label class="block text-[0.72rem] font-bold text-slate-600 mb-1 truncate">الترتيب حسب</label>
                        <select x-model="sort_by" @change="fetchData()" class="w-full h-9 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-primary-500 box-border">
                            <option value="latest">الأحدث إضافة</option>
                            <option value="oldest">الأقدم إضافة</option>
                            <option value="stock_desc">الأعلى رصيداً</option>
                            <option value="stock_asc">الأقل رصيداً</option>
                            <option value="name_asc">الاسم (أ-ي)</option>
                            <option value="name_desc">الاسم (ي-أ)</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Actions Row -->
                <div class="flex justify-end items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                    <button x-show="activeFiltersCount > 0" 
                            @click="resetFilters()" 
                            type="button" 
                            class="px-3 py-1.5 text-[0.75rem] font-bold text-slate-500 hover:text-danger-600 hover:bg-danger-50 rounded-lg transition-colors flex items-center gap-1.5"
                            style="display: none;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        مسح الفلاتر
                    </button>
                </div>
            </div>
        </div>

        <div class="relative min-h-[400px]">
            <!-- Loading Overlay -->
            <div x-show="loading" 
                 x-transition.opacity 
                 class="absolute inset-0 z-20 bg-white/60 backdrop-blur-sm rounded-2xl flex items-center justify-center"
                 style="display: none;">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-sm font-bold text-primary-800">جاري التحديث...</span>
                </div>
            </div>

            <div id="productsTableWrapper" class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden transition-opacity duration-300" :class="loading ? 'opacity-40' : 'opacity-100'">
                @include('products._table', ['products' => $products])
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-start justify-center min-h-screen pt-10 sm:pt-16 p-4 text-center" @click.self="showAddModal = false">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div x-show="showAddModal" @click.outside="showAddModal = false" x-transition class="relative w-full max-w-md p-5 sm:p-6 overflow-hidden text-right transition-all transform bg-white shadow-2xl rounded-2xl z-10">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">إضافة منتج جديد</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">اسم المنتج <span class="text-danger-500">*</span></label>
                                <input type="text" name="name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">الرصيد الافتتاحي (كيلو)</label>
                                <input type="number" step="0.01" min="0" name="stock" value="0" placeholder="0" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">ملاحظات (اختياري)</label>
                                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-sm"></textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700">إضافة المنتج</button>
                            <button type="button" @click="showAddModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-start justify-center min-h-screen pt-10 sm:pt-16 p-4 text-center" @click.self="showEditModal = false">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>
                <div x-show="showEditModal" @click.outside="showEditModal = false" x-transition class="relative w-full max-w-md p-5 sm:p-6 overflow-hidden text-right transition-all transform bg-white shadow-2xl rounded-2xl z-10">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">تعديل المنتج</h3>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    <form :action="`/products/${editData.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">اسم المنتج <span class="text-danger-500">*</span></label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base">
                            </div>
                            <div x-show="!editData.hasOpening">
                                <label class="block text-sm font-medium text-slate-700 mb-1">إضافة رصيد افتتاحي (كيلو)</label>
                                <input type="number" step="0.01" min="0" name="stock" placeholder="0" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-base" dir="ltr">
                                <p class="text-xs text-slate-500 mt-1">بما أن المنتج ليس له رصيد افتتاحي، يمكنك إضافته الآن ولن تتمكن من تعديله لاحقاً.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">ملاحظات (اختياري)</label>
                                <textarea name="notes" x-model="editData.notes" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-1 bg-slate-50 text-sm"></textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700">حفظ التعديلات</button>
                            <button type="button" @click="showEditModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">إلغاء</button>
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
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-start justify-center p-4 pt-10 sm:pt-16 min-h-screen">
            
            <div x-show="showDeleteModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.away="showDeleteModal = false"
                 class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-100 p-6 text-center overflow-hidden whitespace-normal break-words">
                
                <form :action="`/products/${deleteId}`" method="POST">
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
                        هل أنت متأكد من رغبتك في حذف هذا الصنف؟ لن يمكنك التراجع عن هذا الإجراء.
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

    </div>
</x-layouts.app>
