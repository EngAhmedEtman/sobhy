<header class="h-14 lg:h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30"
        x-data="globalSearchComponent()">
    
    <div class="flex items-center gap-3">
        <!-- Mobile menu button -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -mr-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg focus:outline-none transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <div class="flex flex-col">
            <h2 class="text-base lg:text-xl font-bold text-slate-800 leading-tight">
                {{ $title ?? 'لوحة التحكم' }}
            </h2>
            @if(isset($breadcrumb))
                <div class="text-xs text-slate-500 hidden sm:flex items-center mt-1 font-medium gap-1">
                    {!! str_replace('/', '<span class="text-slate-300 mx-1">/</span>', $breadcrumb) !!}
                </div>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
        
        <!-- Desktop Search Bar -->
        <div class="hidden md:block relative" @click.away="isOpen = false">
            <div class="relative flex items-center">
                <input type="text" 
                       x-ref="desktopSearchInput"
                       x-model="query" 
                       @input="handleInput()"
                       @focus="if(query.trim().length > 0) isOpen = true"
                       @keydown.escape="closeSearch()"
                       placeholder="بحث سريع في النظام..." 
                       class="w-64 lg:w-80 pl-14 pr-10 py-2 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 bg-slate-50 focus:bg-white transition-all text-slate-800">
                
                <!-- Search Icon -->
                <div class="absolute right-3 text-slate-400 pointer-events-none">
                    <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <!-- Clear Button / Shortcut badge -->
                <div class="absolute left-2.5 flex items-center">
                    <button type="button" x-show="query.length > 0" @click="clearSearch()" class="text-slate-400 hover:text-slate-600 p-0.5 rounded">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    <span x-show="query.length === 0" class="hidden lg:inline-block text-[0.65rem] font-bold text-slate-400 bg-slate-200/70 px-1.5 py-0.5 rounded border border-slate-200 select-none pointer-events-none">Ctrl+K</span>
                </div>
            </div>

            <!-- Desktop Search Dropdown -->
            <div x-show="isOpen && query.trim().length > 0" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-1"
                 x-cloak
                 class="absolute left-0 right-0 mt-2 w-96 lg:w-[28rem] bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50 max-h-[75vh] flex flex-col text-right">
                
                <!-- Results Header -->
                <div class="p-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>نتائج البحث عن: "<strong class="text-slate-800" x-text="query"></strong>"</span>
                    <span class="text-[0.7rem] font-bold bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full" x-text="results.total_count + ' نتيجة'"></span>
                </div>

                <!-- Results Scrollable Body -->
                <div class="overflow-y-auto p-2 space-y-3 divide-y divide-slate-100">
                    
                    <!-- Loading State -->
                    <div x-show="loading" class="py-6 text-center text-xs text-slate-400">
                        <svg class="w-5 h-5 animate-spin mx-auto text-primary-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>جاري البحث الفوري...</span>
                    </div>

                    <!-- No Results -->
                    <div x-show="!loading && results.total_count === 0" class="py-8 text-center text-xs text-slate-400">
                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="font-bold text-slate-600">لا توجد نتائج مطابقة</p>
                        <p class="text-[0.7rem] text-slate-400 mt-0.5">تأكد من كتابة الاسم أو رقم الهاتف أو رقم الفاتورة بشكل صحيح</p>
                    </div>

                    <!-- Customers Section -->
                    <template x-if="!loading && results.customers && results.customers.length > 0">
                        <div class="pt-2">
                            <h4 class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider px-2 mb-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                <span>العملاء</span>
                            </h4>
                            <div class="space-y-1">
                                <template x-for="item in results.customers" :key="item.id">
                                    <a :href="item.url" class="flex items-center justify-between p-2 rounded-xl hover:bg-blue-50/50 transition-colors group">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-xs shrink-0">
                                                <span x-text="item.title.substring(0, 1)"></span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 group-hover:text-blue-600 truncate" x-text="item.title"></p>
                                                <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                            </div>
                                        </div>
                                        <span class="text-[0.7rem] font-bold px-2 py-0.5 rounded-full shrink-0"
                                              :class="item.balance_type === 'debt' ? 'bg-danger-50 text-danger-700' : (item.balance_type === 'credit' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600')"
                                              x-text="item.balance_text"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Suppliers Section -->
                    <template x-if="!loading && results.suppliers && results.suppliers.length > 0">
                        <div class="pt-2">
                            <h4 class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider px-2 mb-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span>الموردين</span>
                            </h4>
                            <div class="space-y-1">
                                <template x-for="item in results.suppliers" :key="item.id">
                                    <a :href="item.url" class="flex items-center justify-between p-2 rounded-xl hover:bg-amber-50/50 transition-colors group">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-800 font-bold flex items-center justify-center text-xs shrink-0">
                                                <span x-text="item.title.substring(0, 1)"></span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 group-hover:text-amber-800 truncate" x-text="item.title"></p>
                                                <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                            </div>
                                        </div>
                                        <span class="text-[0.7rem] font-bold px-2 py-0.5 rounded-full shrink-0"
                                              :class="item.balance_type === 'supplier_liability' ? 'bg-amber-100 text-amber-800' : (item.balance_type === 'supplier_debit' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600')"
                                              x-text="item.balance_text"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Products Section -->
                    <template x-if="!loading && results.products && results.products.length > 0">
                        <div class="pt-2">
                            <h4 class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider px-2 mb-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                <span>المنتجات والمخزون</span>
                            </h4>
                            <div class="space-y-1">
                                <template x-for="item in results.products" :key="item.id">
                                    <a :href="item.url" class="flex items-center justify-between p-2 rounded-xl hover:bg-purple-50/50 transition-colors group">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 font-bold flex items-center justify-center text-xs shrink-0">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 group-hover:text-purple-600 truncate" x-text="item.title"></p>
                                                <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                            </div>
                                        </div>
                                        <span class="text-[0.7rem] font-bold text-primary-600 group-hover:underline">عرض المخزون</span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Sales Invoices Section -->
                    <template x-if="!loading && results.sales && results.sales.length > 0">
                        <div class="pt-2">
                            <h4 class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider px-2 mb-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span>فواتير المبيعات</span>
                            </h4>
                            <div class="space-y-1">
                                <template x-for="item in results.sales" :key="item.id">
                                    <a :href="item.url" target="_blank" class="flex items-center justify-between p-2 rounded-xl hover:bg-emerald-50/50 transition-colors group">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center text-xs shrink-0">
                                                #
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 truncate" x-text="item.title"></p>
                                                <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                            </div>
                                        </div>
                                        <span class="text-[0.7rem] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">طباعة / عرض</span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Purchases Invoices Section -->
                    <template x-if="!loading && results.purchases && results.purchases.length > 0">
                        <div class="pt-2">
                            <h4 class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider px-2 mb-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                <span>فواتير المشتريات</span>
                            </h4>
                            <div class="space-y-1">
                                <template x-for="item in results.purchases" :key="item.id">
                                    <a :href="item.url" target="_blank" class="flex items-center justify-between p-2 rounded-xl hover:bg-sky-50/50 transition-colors group">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 font-bold flex items-center justify-center text-xs shrink-0">
                                                #
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 group-hover:text-sky-700 truncate" x-text="item.title"></p>
                                                <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                            </div>
                                        </div>
                                        <span class="text-[0.7rem] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md">طباعة / عرض</span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Transactions Section -->
                    <template x-if="!loading && results.transactions && results.transactions.length > 0">
                        <div class="pt-2">
                            <h4 class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider px-2 mb-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                <span>الإيصالات والعمليات</span>
                            </h4>
                            <div class="space-y-1">
                                <template x-for="item in results.transactions" :key="item.id">
                                    <a :href="item.url" target="_blank" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-100 transition-colors group">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-xs shrink-0">
                                                🧾
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 group-hover:text-slate-900 truncate" x-text="item.title"></p>
                                                <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                            </div>
                                        </div>
                                        <span class="text-[0.7rem] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">عرض الإيصال</span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>
            </div>
        </div>

        <!-- Mobile Search Trigger Button (Mobile Only) -->
        <button type="button" 
                @click="openMobileSearch()" 
                class="md:hidden p-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 transition-colors"
                title="بحث سريع">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        <!-- Technical Support Modal Trigger Button in Header -->
        <button type="button" 
                @click="showSupportModal = true"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300 transition-all text-xs font-bold shadow-sm shadow-emerald-500/5">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="hidden sm:inline-block">الدعم الفني</span>
        </button>
        
        <!-- Profile Dropdown -->
        <div x-data="{ profileOpen: false }" class="relative z-50">
            <div @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-1.5 rounded-xl transition-colors">
                <div class="h-8 w-8 lg:h-9 lg:w-9 rounded-full bg-primary-100 text-primary-700 font-bold overflow-hidden border border-primary-200 flex items-center justify-center shrink-0 text-xs">
                    {{ mb_substr(Auth::user()->name ?? 'م', 0, 2) }}
                </div>
                <div class="hidden md:flex flex-col items-start">
                    <p class="text-sm font-semibold text-slate-700 leading-tight">{{ Auth::user()->name ?? 'المدير' }}</p>
                    <p class="text-[0.6rem] text-slate-500">{{ Auth::user()->role ? Auth::user()->role->name : 'مدير النظام' }}</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            
            <!-- Dropdown Menu -->
            <div x-show="profileOpen" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg shadow-slate-200/50 border border-slate-100 py-2">
                <div class="px-4 py-2 border-b border-slate-50 mb-1 md:hidden">
                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name ?? 'المدير' }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->role ? Auth::user()->role->name : 'مدير النظام' }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    حسابي
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-danger-600 hover:bg-danger-50 transition-colors text-right">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Technical Support Modal in Header -->
    <div x-show="showSupportModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-100 p-5 sm:p-6 overflow-hidden text-right"
             @click.away="showSupportModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">تواصل مع الدعم الفني</h3>
                        <p class="text-xs text-slate-400 font-medium">شركة كوديرا (Codera)</p>
                    </div>
                </div>
                <button type="button" @click="showSupportModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Modal Content & Direct Action Links -->
            <div class="py-5 space-y-3">
                <p class="text-xs text-slate-600 leading-relaxed">
                    يسعدنا تواصلكم دائماً، فريق الدعم الفني متواجد لمساعدتكم وتقديم الحلول السريعة لأي استفسار أو مشكلة تقنية.
                </p>

                <!-- WhatsApp Card -->
                <a href="https://wa.me/201070191977" target="_blank" class="flex items-center justify-between p-3.5 rounded-xl bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200 text-emerald-800 transition-all group shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">واتساب الدعم الفني</h4>
                            <p class="text-[0.7rem] font-black text-emerald-700" dir="ltr">01070191977</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-lg bg-emerald-600 text-white shadow-sm group-hover:bg-emerald-700 transition-colors">محادثة الآن</span>
                </a>

                <!-- Website Card -->
                <a href="https://coderaeg.com" target="_blank" class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-800 transition-all group shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">موقع الشركة</h4>
                            <p class="text-[0.7rem] font-bold text-primary-600" dir="ltr">coderaEg.com</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-lg bg-slate-200 text-slate-700 group-hover:bg-slate-300 transition-colors">زيارة الموقع</span>
                </a>

                <!-- Phone Call Card -->
                <a href="tel:01070191977" class="flex items-center justify-between p-3.5 rounded-xl bg-blue-50/60 hover:bg-blue-50 border border-blue-100 text-blue-900 transition-all group shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">اتصال مباشر</h4>
                            <p class="text-[0.7rem] font-bold text-blue-700" dir="ltr">01070191977</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-lg bg-blue-100 text-blue-700 group-hover:bg-blue-200 transition-colors">اتصال</span>
                </a>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="button" @click="showSupportModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    إغلاق
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Full-Screen Search Modal Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex flex-col p-3 sm:p-6 md:hidden">
        
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-full" @click.away="mobileOpen = false">
            
            <!-- Mobile Search Bar Header -->
            <div class="p-3 border-b border-slate-100 flex items-center gap-2 bg-slate-50">
                <div class="relative flex-1">
                    <input type="text" 
                           x-ref="mobileSearchInput"
                           x-model="query" 
                           @input="handleInput()"
                           placeholder="بحث بالاسم، الهاتف، الفاتورة..." 
                           class="w-full pl-8 pr-9 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <div class="absolute right-3 top-3 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <button type="button" x-show="query.length > 0" @click="clearSearch()" class="absolute left-2.5 top-2.5 text-slate-400 p-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <button type="button" @click="mobileOpen = false" class="px-3 py-2 text-xs font-bold text-slate-600 bg-slate-200/80 rounded-xl hover:bg-slate-300 transition-colors">
                    إلغاء
                </button>
            </div>

            <!-- Mobile Search Results -->
            <div class="overflow-y-auto p-3 flex-1 space-y-3 divide-y divide-slate-100">
                
                <!-- Loading -->
                <div x-show="loading" class="py-6 text-center text-xs text-slate-400">
                    <svg class="w-5 h-5 animate-spin mx-auto text-primary-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>جاري البحث الفوري...</span>
                </div>

                <!-- Empty state before typing -->
                <div x-show="!loading && query.trim().length === 0" class="py-8 text-center text-xs text-slate-400">
                    <p class="font-bold text-slate-600 mb-1">ابحث في كافة بيانات النظام</p>
                    <p class="text-[0.7rem] text-slate-400">يمكنك كتابة اسم العميل، هاتف المورد، رقم الفاتورة أو اسم الصنف</p>
                </div>

                <!-- No results -->
                <div x-show="!loading && query.trim().length > 0 && results.total_count === 0" class="py-8 text-center text-xs text-slate-400">
                    <p class="font-bold text-slate-600">لا توجد نتائج مطابقة</p>
                </div>

                <!-- Customers Section -->
                <template x-if="!loading && results.customers && results.customers.length > 0">
                    <div class="pt-2">
                        <h4 class="text-[0.7rem] font-bold text-slate-400 mb-1.5">العملاء</h4>
                        <div class="space-y-1">
                            <template x-for="item in results.customers" :key="item.id">
                                <a :href="item.url" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate" x-text="item.title"></p>
                                        <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                    </div>
                                    <span class="text-[0.7rem] font-bold px-2 py-0.5 rounded-full shrink-0"
                                          :class="item.balance_type === 'debt' ? 'bg-danger-50 text-danger-700' : (item.balance_type === 'credit' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600')"
                                          x-text="item.balance_text"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Suppliers Section -->
                <template x-if="!loading && results.suppliers && results.suppliers.length > 0">
                    <div class="pt-2">
                        <h4 class="text-[0.7rem] font-bold text-slate-400 mb-1.5">الموردين</h4>
                        <div class="space-y-1">
                            <template x-for="item in results.suppliers" :key="item.id">
                                <a :href="item.url" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate" x-text="item.title"></p>
                                        <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                    </div>
                                    <span class="text-[0.7rem] font-bold px-2 py-0.5 rounded-full shrink-0"
                                          :class="item.balance_type === 'supplier_liability' ? 'bg-amber-100 text-amber-800' : (item.balance_type === 'supplier_debit' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600')"
                                          x-text="item.balance_text"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Products Section -->
                <template x-if="!loading && results.products && results.products.length > 0">
                    <div class="pt-2">
                        <h4 class="text-[0.7rem] font-bold text-slate-400 mb-1.5">المنتجات والمخزون</h4>
                        <div class="space-y-1">
                            <template x-for="item in results.products" :key="item.id">
                                <a :href="item.url" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate" x-text="item.title"></p>
                                        <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                    </div>
                                    <span class="text-[0.7rem] font-bold text-primary-600">عرض</span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Sales Invoices Section -->
                <template x-if="!loading && results.sales && results.sales.length > 0">
                    <div class="pt-2">
                        <h4 class="text-[0.7rem] font-bold text-slate-400 mb-1.5">فواتير المبيعات</h4>
                        <div class="space-y-1">
                            <template x-for="item in results.sales" :key="item.id">
                                <a :href="item.url" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate" x-text="item.title"></p>
                                        <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                    </div>
                                    <span class="text-[0.7rem] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">عرض</span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Purchases Invoices Section -->
                <template x-if="!loading && results.purchases && results.purchases.length > 0">
                    <div class="pt-2">
                        <h4 class="text-[0.7rem] font-bold text-slate-400 mb-1.5">فواتير المشتريات</h4>
                        <div class="space-y-1">
                            <template x-for="item in results.purchases" :key="item.id">
                                <a :href="item.url" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate" x-text="item.title"></p>
                                        <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                    </div>
                                    <span class="text-[0.7rem] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md">عرض</span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Transactions Section -->
                <template x-if="!loading && results.transactions && results.transactions.length > 0">
                    <div class="pt-2">
                        <h4 class="text-[0.7rem] font-bold text-slate-400 mb-1.5">الإيصالات والعمليات</h4>
                        <div class="space-y-1">
                            <template x-for="item in results.transactions" :key="item.id">
                                <a :href="item.url" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate" x-text="item.title"></p>
                                        <p class="text-[0.65rem] text-slate-400" x-text="item.subtitle"></p>
                                    </div>
                                    <span class="text-[0.7rem] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">عرض</span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

</header>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('globalSearchComponent', () => ({
            query: '',
            isOpen: false,
            mobileOpen: false,
            showSupportModal: false,
            loading: false,
            debounceTimer: null,
            results: {
                customers: [],
                suppliers: [],
                products: [],
                sales: [],
                purchases: [],
                transactions: [],
                total_count: 0
            },

            init() {
                window.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                        e.preventDefault();
                        if (window.innerWidth < 768) {
                            this.openMobileSearch();
                        } else {
                            this.focusDesktopSearch();
                        }
                    }
                });
            },

            focusDesktopSearch() {
                this.isOpen = true;
                this.$nextTick(() => {
                    if (this.$refs.desktopSearchInput) {
                        this.$refs.desktopSearchInput.focus();
                        this.$refs.desktopSearchInput.select();
                    }
                });
            },

            openMobileSearch() {
                this.mobileOpen = true;
                this.$nextTick(() => {
                    if (this.$refs.mobileSearchInput) {
                        this.$refs.mobileSearchInput.focus();
                    }
                });
            },

            handleInput() {
                clearTimeout(this.debounceTimer);
                const q = this.query.trim();

                if (q.length === 0) {
                    this.isOpen = false;
                    this.loading = false;
                    this.results = { customers: [], suppliers: [], products: [], sales: [], purchases: [], transactions: [], total_count: 0 };
                    return;
                }

                this.isOpen = true;
                this.loading = true;

                this.debounceTimer = setTimeout(() => {
                    this.fetchResults(q);
                }, 250);
            },

            fetchResults(q) {
                fetch(`/api/global-search?q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => {
                        this.results = data;
                        this.loading = false;
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },

            clearSearch() {
                this.query = '';
                this.results = { customers: [], suppliers: [], products: [], sales: [], purchases: [], transactions: [], total_count: 0 };
                this.isOpen = false;
                this.loading = false;
            },

            closeSearch() {
                this.isOpen = false;
                this.mobileOpen = false;
            }
        }));
    });
</script>
