<header class="h-14 lg:h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30">
    <div class="flex items-center gap-3">
        <!-- Mobile menu button -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -mr-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg focus:outline-none transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <div class="flex flex-col">
            <h2 class="text-base lg:text-xl font-bold text-slate-800 leading-tight">
                <?php echo e($title ?? 'لوحة التحكم'); ?>

            </h2>
            <?php if(isset($breadcrumb)): ?>
                <div class="text-xs text-slate-500 hidden sm:flex items-center mt-1 font-medium gap-1">
                    <?php echo str_replace('/', '<span class="text-slate-300 mx-1">/</span>', $breadcrumb); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex items-center gap-2 lg:gap-4">
        <!-- Search (desktop only) -->
        <div class="hidden lg:block relative">
            <input type="text" placeholder="ابحث عن عميل، فاتورة، منتج..." class="w-64 pl-4 pr-10 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 bg-slate-50">
            <div class="absolute right-3 top-2.5 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <!-- Profile Dropdown -->
        <div x-data="{ profileOpen: false }" class="relative z-50">
            <div @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-1.5 rounded-xl transition-colors">
                <div class="h-8 w-8 lg:h-9 lg:w-9 rounded-full bg-primary-100 text-primary-700 font-bold overflow-hidden border border-primary-200 flex items-center justify-center shrink-0 text-xs">
                    <?php echo e(substr(Auth::user()->name ?? 'م', 0, 2)); ?>

                </div>
                <div class="hidden md:flex flex-col items-start">
                    <p class="text-sm font-semibold text-slate-700 leading-tight"><?php echo e(Auth::user()->name ?? 'المدير'); ?></p>
                    <p class="text-[0.6rem] text-slate-500"><?php echo e(Auth::user()->role ? Auth::user()->role->name : 'مدير النظام'); ?></p>
                </div>
                <svg class="w-4 h-4 text-slate-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            
            <!-- Dropdown Menu -->
            <div x-show="profileOpen" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg shadow-slate-200/50 border border-slate-100 py-2">
                <div class="px-4 py-2 border-b border-slate-50 mb-1 md:hidden">
                    <p class="text-sm font-bold text-slate-800"><?php echo e(Auth::user()->name ?? 'المدير'); ?></p>
                    <p class="text-xs text-slate-500"><?php echo e(Auth::user()->role ? Auth::user()->role->name : 'مدير النظام'); ?></p>
                </div>
                <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    حسابي
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-danger-600 hover:bg-danger-50 transition-colors text-right">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
