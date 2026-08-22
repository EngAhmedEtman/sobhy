@extends('errors.layout')

@section('title', '403 - غير مصرح بالوصول')

@section('icon')
<div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-amber-50 text-amber-600 border border-amber-100 shadow-inner mb-2">
    <span class="text-3xl font-black tracking-wider">403</span>
</div>
@endsection

@section('heading', 'غير مصرح لك بالوصول')

@section('message', 'ليس لديك الصلاحيات الكافية للوصول إلى هذه الصفحة أو تنفيذ هذه العملية. يرجى مراجعة مدير النظام.')
