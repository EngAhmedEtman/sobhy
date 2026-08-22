<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ (isset($title) && !empty($title)) ? $title . ' - ' . \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا لتجارة الخردة') : \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا لتجارة الخردة والنظام المحاسبي') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    <!-- Alpine.js Plugins & Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (CDN to avoid disk space issues) -->
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
    <!-- Flatpickr (Reliable Cross-Platform Date Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_green.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <style>
      body { font-family: 'Cairo', sans-serif; }
      [x-cloak] { display: none !important; }
      .flatpickr-calendar { font-family: 'Cairo', sans-serif !important; border-radius: 1rem !important; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" 
         x-transition.opacity 
         class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content wrapper -->
    <div class="lg:mr-64 flex flex-col min-h-screen">
        
        <!-- Header -->
        <x-header>
            <x-slot:title>{{ $title ?? 'لوحة التحكم' }}</x-slot:title>
            @if(isset($breadcrumb))
                <x-slot:breadcrumb>الرئيسية / {{ str_replace('>', '/', $breadcrumb) }}</x-slot:breadcrumb>
            @endif
        </x-header>

        <!-- Main Content -->
        <main class="flex-1 p-3 sm:p-4 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    <!-- Global Alert / Toast -->
    <x-toast />
    
    <!-- Global Print Preview Modal -->
    <x-print.preview-modal />

</body>
</html>
