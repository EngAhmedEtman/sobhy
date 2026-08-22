@extends('errors.layout')

@section('title', '404 - الصفحة غير موجودة')

@section('icon')
<div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 border border-blue-100 shadow-inner mb-2">
    <span class="text-3xl font-black tracking-wider">404</span>
</div>
@endsection

@section('heading', 'الصفحة غير موجودة')

@section('message', 'عفواً، الرابط أو الصفحة التي تحاول الوصول إليها غير متوفرة أو قد تم نقلها. يرجى التحقق من الرابط أو العودة للرئيسية.')
