@extends('errors.layout')

@section('title', '429 - عدد طلبات زائد')

@section('icon')
<div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-rose-50 text-rose-600 border border-rose-100 shadow-inner mb-2">
    <span class="text-3xl font-black tracking-wider">429</span>
</div>
@endsection

@section('heading', 'طلبات كثيرة جداً')

@section('message', 'لقد قمت بإرسال عدد كبير من الطلبات في وقت قصير. يرجى الانتظار بضع لحظات ثم إعادة المحاولة.')
