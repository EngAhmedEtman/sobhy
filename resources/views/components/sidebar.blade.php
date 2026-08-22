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
            <a href="<?php echo e(route('settings.index')); ?>" @click="sidebarOpen = false" class="flex items-center p-3 rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('settings.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <svg class="w-5 h-5 ml-3 transition-colors <?php echo e(request()->routeIs('settings.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span class="font-semibold text-sm">إعدادات النظام</span>
            </a>
        </nav>
    </div>
</aside>
