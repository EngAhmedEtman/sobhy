@extends('errors.layout')

@section('title', '419 - انتهت صلاحية الجلسة')

@section('icon')
<div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-inner mb-2">
    <span class="text-3xl font-black tracking-wider">419</span>
</div>
@endsection

@section('heading', 'انتهت صلاحية الجلسة')

@section('message', 'انتهت صلاحية الصفحة أو الجلسة بسبب عدم النشاط لفترة. يرجى إعادة تسجيل الدخول أو تحديث الصفحة لمتابعة العمل بأمان.')
