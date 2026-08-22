<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا لتجارة الخردة') }} - تسجيل الدخول</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: { sans: ['Cairo', 'sans-serif'] },
            colors: {
                primary: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d' },
                danger: { 50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5', 400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c', 800: '#991b1b', 900: '#7f1d1d' },
            }
          }
        }
      }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
      body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex text-slate-800 selection:bg-primary-500 selection:text-white">

    <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl shadow-primary-900/5 p-8 sm:p-12 border border-slate-100 relative overflow-hidden">
            
            <!-- Decorative Accent -->
            <div class="absolute top-0 right-0 w-full h-1.5 bg-gradient-to-l from-primary-600 to-sky-400"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-50 text-primary-600 mb-5 border border-primary-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ \App\Models\Setting::get('company_name', 'حديد مصر') }}</h1>
                <p class="text-xs text-slate-500 mt-2 font-medium">مرحباً بك، يرجى إدخال بيانات حسابك للدخول</p>
            </div>

            <!-- Session Status / Alert -->
            @if(session('status'))
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3.5 bg-danger-50 border border-danger-200 text-danger-700 text-xs font-bold rounded-xl text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ isSubmitting: false }" @submit="if(isSubmitting) { $event.preventDefault(); return; } isSubmitting = true;">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full pl-4 pr-9 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all text-xs font-medium" placeholder="name@company.com">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-[0.7rem] font-bold text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700">كلمة المرور</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[0.7rem] font-bold text-primary-600 hover:text-primary-700 transition-colors">نسيت كلمة المرور؟</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full pl-4 pr-9 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all text-xs font-medium" placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-[0.7rem] font-bold text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                        <input id="remember_me" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500 cursor-pointer">
                        <span class="mr-2 text-xs font-bold text-slate-600 group-hover:text-slate-900 transition-colors">تذكرني في المرات القادمة</span>
                    </label>
                </div>

                <div class="pt-3">
                    <button type="submit" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs sm:text-sm py-3 rounded-xl transition-all shadow-lg shadow-primary-600/20 flex items-center justify-center gap-2">
                        <span x-show="!isSubmitting">تسجيل الدخول للنظام</span>
                        <span x-show="isSubmitting" x-cloak>جاري تسجيل الدخول...</span>
                        <svg x-show="!isSubmitting" class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        <svg x-show="isSubmitting" x-cloak class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </form>

            <!-- Technical Support Box -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-[0.7rem] text-slate-500 font-semibold mb-2">تواجه مشكلة أو تحتاج مساعدة تقنية؟</p>
                <div class="flex items-center justify-center gap-3">
                    <a href="https://wa.me/201070191977" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors text-xs font-bold border border-emerald-200/60">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>واتساب: 01070191977</span>
                    </a>
                    <a href="https://coderaeg.com" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors text-xs font-bold border border-slate-200">
                        <svg class="w-3.5 h-3.5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                        <span>coderaEg.com</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side Graphic (Hidden on mobile) -->
    <div class="hidden lg:flex lg:flex-1 bg-slate-900 relative items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900 to-slate-900 opacity-90 z-10"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 z-10"></div>
        
        <!-- Decorative Shapes -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob z-0"></div>
        <div class="absolute top-48 -left-24 w-96 h-96 bg-sky-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000 z-0"></div>

        <div class="relative z-20 text-center px-12">
            <h2 class="text-4xl font-black text-white mb-6 leading-tight">نظام متطور<br><span class="text-primary-400">لإدارة الخردة والمخازن</span></h2>
            <p class="text-slate-300 text-lg font-medium max-w-md mx-auto leading-relaxed">أداة احترافية تمنحك تحكماً كاملاً في المبيعات، المشتريات، الموردين والعملاء بكل سهولة وسرعة.</p>
            
            <div class="mt-12 flex justify-center gap-4">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                    <svg class="w-8 h-8 text-primary-400 mb-2 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H4a2 2 0 00-2 2v6a2 2 0 002 2h3a2 2 0 002-2zm0 0V9a2 2 0 012-2h3a2 2 0 012 2v10m-6 0a2 2 0 002 2h3a2 2 0 002-2V11a2 2 0 012-2h3a2 2 0 012 2v8a2 2 0 01-2 2h-3l-1-1z"></path></svg>
                    <span class="block text-xs font-bold text-white">إحصائيات دقيقة</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                    <svg class="w-8 h-8 text-sky-400 mb-2 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span class="block text-xs font-bold text-white">حماية البيانات</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                    <svg class="w-8 h-8 text-amber-400 mb-2 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="block text-xs font-bold text-white">أداء فائق</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
