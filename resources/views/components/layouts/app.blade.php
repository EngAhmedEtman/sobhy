<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    
    <title>{{ (isset($title) && !empty($title)) ? $title . ' - ' . \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا لتجارة الخردة') : \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا لتجارة الخردة والنظام المحاسبي') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">
    <a href="#main-content" class="skip-link">تخطَّ إلى المحتوى الرئيسي</a>
    
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" 
         x-transition.opacity 
         class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
         @click="sidebarOpen = false" aria-hidden="true"></div>

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
        <main id="main-content" class="flex-1 p-3 sm:p-4 lg:p-8" tabindex="-1">
            {{ $slot }}
        </main>
    </div>

    <!-- Global Alert / Toast -->
    <x-toast />
    
    <!-- Global Print Preview Modal -->
    <x-print.preview-modal />

</body>
</html>
