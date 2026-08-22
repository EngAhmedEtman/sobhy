@extends('errors.layout')

@section('title', '500 - خطأ في الخادم')

@section('icon')
<div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-danger-50 text-danger-600 border border-danger-100 shadow-inner mb-2">
    <span class="text-3xl font-black tracking-wider">500</span>
</div>
@endsection

@section('heading', 'خطأ في الخادم الداخلي')

@section('message', 'حدث خطأ غير متوقع أثناء معالجة الطلب. تم تسجيل الخطأ وسيقوم فريق الدعم الفني بفحصه وحله في أقرب وقت.')
