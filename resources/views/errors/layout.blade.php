<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'حدث خطأ') - {{ \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا لتجارة الخردة') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: { sans: ['Cairo', 'sans-serif'] },
            colors: {
                primary: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d' },
                danger: { 50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5', 400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c', 800: '#991b1b', 900: '#7f1d1d' }
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
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200 text-slate-800 flex flex-col justify-between p-4 sm:p-6">
    
    <!-- Top Header Navigation Bar -->
    <header class="max-w-4xl w-full mx-auto flex items-center justify-between py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center font-bold shadow-md shadow-primary-500/20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
            <div>
                <span class="text-sm font-black text-slate-800">{{ \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا لتجارة الخردة') }}</span>
                <span class="text-[0.65rem] text-slate-400 block font-medium">النظام المحاسبي وإدارة الخردة</span>
            </div>
        </div>

        <a href="{{ url('/') }}" class="text-xs font-bold text-slate-600 hover:text-primary-600 transition-colors flex items-center gap-1">
            <span>الرئيسية</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        </a>
    </header>

    <!-- Center Card -->
    <main class="max-w-xl w-full mx-auto my-auto text-center py-8">
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-2xl shadow-slate-300/60 border border-slate-100 relative overflow-hidden">
            
            <!-- Background Graphic / Circle -->
            <div class="absolute -top-16 -right-16 w-36 h-36 bg-slate-50 rounded-full pointer-events-none -z-0"></div>
            <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-slate-50 rounded-full pointer-events-none -z-0"></div>

            <div class="relative z-10 space-y-5">
                <!-- Big Code Badge / Icon -->
                <div>
                    @yield('icon')
                </div>

                <div class="space-y-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-800">
                        @yield('heading', 'حدث خطأ غير متوقع')
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed max-w-md mx-auto">
                        @yield('message', 'نعتذر، حدث خطأ أثناء معالجة طلبكم. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.')
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-primary-500/20 transition-all inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        العودة للوحة التحكم
                    </a>

                    <button type="button" onclick="history.back()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition-all inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        الصفحة السابقة
                    </button>
                </div>

                <!-- Support Box -->
                <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 bg-slate-50/80 -mx-6 -mb-6 sm:-mx-10 sm:-mb-10 p-4 sm:p-5 rounded-b-3xl">
                    <div class="flex items-center gap-2 text-right">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="font-bold text-slate-700">الدعم الفني متاح دائماً:</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="https://wa.me/201070191977" target="_blank" class="inline-flex items-center gap-1 text-emerald-700 font-black hover:underline" dir="ltr">
                            <span>01070191977</span>
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                        <span class="text-slate-300">|</span>
                        <a href="https://coderaeg.com" target="_blank" class="font-bold text-primary-600 hover:underline">coderaEg.com</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-[0.7rem] text-slate-400 py-3">
        جميع الحقوق محفوظة &copy; {{ date('Y') }} - تم التطوير بواسطة <a href="https://coderaeg.com" target="_blank" class="font-bold text-primary-600 hover:underline">شركة كوديرا (Codera)</a>
    </footer>

</body>
</html>
