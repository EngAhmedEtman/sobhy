<?php
    $currentRoute = request()->route()->getName();
?>

<aside 
    class="fixed inset-y-0 right-0 z-50 w-64 bg-white border-l border-slate-200 transition-transform duration-300 ease-in-out lg:translate-x-0"
    :class="{'translate-x-0': sidebarOpen, 'translate-x-full': !sidebarOpen}">
    
    <div class="flex flex-col h-full">
        <!-- Logo Area -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="text-primary-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-base text-slate-800 leading-tight"><?php echo e(\App\Models\Setting::get('company_name', 'حديد مصر')); ?></h1>
                    <p class="text-[0.6rem] text-slate-400 leading-tight">لنظام إدارة الخردة</p>
                </div>
            </div>
            <!-- Close button (mobile only) -->
            <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-1">
            
            <!-- Dashboard -->
            <a href="<?php echo e(route('dashboard')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('dashboard') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span class="font-semibold text-sm">لوحة المعلومات</span>
            </a>

            <!-- Debts -->
            <a href="<?php echo e(route('debts.index')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('debts.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('debts.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <span class="font-semibold text-sm">المديونيات</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-bold text-slate-400 tracking-wider">المبيعات والعملاء</div>
            
            <!-- Customers -->
            <a href="<?php echo e(route('customers.index')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('customers.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('customers.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span class="font-semibold text-sm">العملاء</span>
            </a>

            <!-- Sales -->
            <a href="<?php echo e(route('sales.index')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('sales.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('sales.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                <span class="font-semibold text-sm">المبيعات</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-bold text-slate-400 tracking-wider">المشتريات والموردين</div>

            <!-- Suppliers -->
            <a href="<?php echo e(route('suppliers.index')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('suppliers.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('suppliers.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <span class="font-semibold text-sm">الموردين</span>
            </a>
            
            <!-- Purchases -->
            <a href="<?php echo e(route('purchases.index')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('purchases.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('purchases.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span class="font-semibold text-sm">المشتريات</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-bold text-slate-400 tracking-wider">المخزون والتقارير</div>

            <!-- Products -->
            <a href="<?php echo e(route('products.index')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('products.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('products.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <span class="font-semibold text-sm">المنتجات والمخزون</span>
            </a>

            <!-- Reports -->
            <a href="<?php echo e(route('reports.index')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('reports.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-semibold text-sm">التقارير والإحصائيات</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-bold text-slate-400 tracking-wider">النظام</div>

            <!-- Team (Users & Roles) -->
            <div x-data="{ openTeam: <?php echo e(request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'true' : 'false'); ?> }" class="space-y-1">
                <button @click="openTeam = !openTeam" class="w-full flex items-center justify-between p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'bg-slate-50 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span class="font-semibold text-sm">إدارة الفريق</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openTeam }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="openTeam" x-transition class="pr-10 space-y-1 pb-2">
                    <a href="<?php echo e(route('users.index')); ?>" @click="sidebarOpen = false" class="block p-2 rounded-lg text-sm transition-colors <?php echo e(request()->routeIs('users.*') ? 'text-primary-600 font-bold bg-primary-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'); ?>">المستخدمين</a>
                    <a href="<?php echo e(route('roles.index')); ?>" @click="sidebarOpen = false" class="block p-2 rounded-lg text-sm transition-colors <?php echo e(request()->routeIs('roles.*') ? 'text-primary-600 font-bold bg-primary-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'); ?>">الصلاحيات والأدوار</a>
                </div>
            </div>

            <!-- Settings -->
            <a href="{{ route('settings.index') }}" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('settings.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 ml-3 transition-colors {{ request()->routeIs('settings.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span class="font-semibold text-sm">إعدادات النظام</span>
            </a>
        </nav>

        <!-- Technical Support Box -->
        <div class="p-3 border-t border-slate-100 bg-slate-50/70">
            <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-slate-800 leading-tight truncate">تواصل مع الدعم الفني</h4>
                        <p class="text-[0.65rem] text-slate-400 truncate">شركة كوديرا (Codera)</p>
                    </div>
                </div>
                
                <div class="space-y-1 pt-1">
                    <a href="https://wa.me/201070191977" target="_blank" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors text-[0.7rem] font-bold">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>واتساب الدعم</span>
                        </span>
                        <span dir="ltr">01070191977</span>
                    </a>

                    <a href="https://coderaeg.com" target="_blank" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors text-[0.7rem] font-bold">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            <span>موقع الشركة</span>
                        </span>
                        <span dir="ltr">coderaEg.com</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</aside>
