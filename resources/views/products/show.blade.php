<x-layouts.app :title="$product->name">
    <x-slot name="breadcrumb">المنتجات / {{ $product->name }}</x-slot>

    <div class="flex flex-col-reverse sm:flex-row items-start sm:items-center gap-3 mb-5">
        <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg sm:text-xl font-black text-slate-800 truncate">{{ $product->name }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">سجل الأسعار والحركات الكامل للمنتج</p>
                </div>
            </div>
            <div class="bg-slate-50 rounded-lg p-3 border border-slate-100 flex items-center justify-between gap-3 shrink-0">
                <span class="text-xs sm:text-sm text-slate-500 font-bold">الرصيد المتاح:</span>
                <span class="text-lg sm:text-xl font-black {{ $product->stock < 0 ? 'text-danger-600' : 'text-primary-600' }}" dir="ltr">
                    {{ format_quantity($product->stock) }} <span class="text-xs text-slate-400">{{ $product->unit ?? 'كيلو' }}</span>
                </span>
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 hover:text-primary-600 text-sm font-bold flex items-center justify-center gap-2 shrink-0 transition-all shadow-sm w-full sm:w-auto">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            العودة للقائمة
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-[0.7rem] font-bold text-slate-500 mb-1">متوسط سعر البيع / كيلو</p>
            <p class="text-lg font-black text-primary-700" dir="ltr">{{ $salesSummary['average_price'] !== null ? format_amount($salesSummary['average_price']) : '-' }} <span class="text-[0.65rem] text-slate-400">ج.م</span></p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-[0.7rem] font-bold text-slate-500 mb-1">أقل سعر بيع / كيلو</p>
            <p class="text-lg font-black text-amber-700" dir="ltr">{{ $salesSummary['lowest_price'] !== null ? format_amount($salesSummary['lowest_price']) : '-' }} <span class="text-[0.65rem] text-slate-400">ج.م</span></p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-[0.7rem] font-bold text-slate-500 mb-1">أعلى سعر بيع / كيلو</p>
            <p class="text-lg font-black text-emerald-700" dir="ltr">{{ $salesSummary['highest_price'] !== null ? format_amount($salesSummary['highest_price']) : '-' }} <span class="text-[0.65rem] text-slate-400">ج.م</span></p>
        </div>
        <div class="bg-primary-50 rounded-xl border border-primary-100 shadow-sm p-4">
            <p class="text-[0.7rem] font-bold text-primary-600 mb-1">إجمالي مبيعات المنتج</p>
            <p class="text-lg font-black text-primary-800" dir="ltr">{{ format_amount($salesSummary['total_sales']) }} <span class="text-[0.65rem] text-primary-500">ج.م</span></p>
            <p class="text-[0.65rem] text-primary-500 mt-1" dir="ltr">{{ format_quantity($salesSummary['total_quantity']) }} {{ $product->unit ?? 'كيلو' }}</p>
        </div>
    </div>

    <div class="sm:hidden space-y-3">
        <h3 class="font-bold text-slate-800 text-base">كل حركات المنتج</h3>
        @forelse($product->transactions as $transaction)
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <div class="flex justify-between items-center gap-2 mb-3">
                    <span class="px-2 py-0.5 rounded text-[0.7rem] font-bold {{ $transaction->is_incoming ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'bg-danger-50 text-danger-700 border border-danger-200' }}">{{ $transaction->type_name }}</span>
                    <span class="text-[0.7rem] text-slate-400 font-bold" dir="ltr">{{ ($transaction->transaction_date ?? $transaction->created_at)->format('Y-m-d') }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div><span class="text-slate-400">الفاتورة / العملية:</span><p class="font-bold text-slate-700 mt-0.5">{{ $transaction->reference_number }}</p></div>
                    <div><span class="text-slate-400">العميل / المورد:</span><p class="font-bold text-slate-700 mt-0.5">{{ $transaction->party_name }}</p></div>
                    <div><span class="text-slate-400">الكمية:</span><p class="font-black {{ $transaction->is_incoming ? 'text-primary-600' : 'text-danger-600' }} mt-0.5" dir="ltr">{{ format_quantity(abs($transaction->quantity)) }} {{ $product->unit }}</p></div>
                    <div><span class="text-slate-400">سعر الوحدة:</span><p class="font-black text-emerald-700 mt-0.5" dir="ltr">{{ $transaction->unit_price !== null ? format_amount($transaction->unit_price).' ج.م' : '-' }}</p></div>
                    <div><span class="text-slate-400">إجمالي الحركة:</span><p class="font-black text-slate-700 mt-0.5" dir="ltr">{{ $transaction->operation_total !== null ? format_amount($transaction->operation_total).' ج.م' : '-' }}</p></div>
                    <div><span class="text-slate-400">الرصيد بعدها:</span><p class="font-black text-slate-700 mt-0.5" dir="ltr">{{ format_quantity($transaction->balance_after) }}</p></div>
                </div>
                @if($transaction->notes)<p class="text-xs text-slate-400 mt-3 border-t border-slate-50 pt-2">{{ $transaction->notes }}</p>@endif
            </div>
        @empty
            <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">لم يتم تسجيل أي حركات بعد.</div>
        @endforelse
    </div>

    <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">سجل حركات وأسعار الصنف</h3>
            <p class="text-xs text-slate-500 mt-0.5">سعر الكيلو وإجمالي كل حركة طبقًا للفاتورة المسجلة وقتها</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse whitespace-nowrap">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">التاريخ</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">رقم الفاتورة / العملية</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">العميل / المورد</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">نوع الحركة</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">وارد</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">منصرف</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">سعر / كيلو</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">إجمالي الحركة</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">الرصيد بعد</th>
                        <th class="px-3 py-3 text-xs font-bold text-slate-500 border-b">البيان</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->transactions as $transaction)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-3 py-3 text-xs text-slate-500 border-b" dir="ltr">{{ ($transaction->transaction_date ?? $transaction->created_at)->format('Y-m-d') }}</td>
                            <td class="px-3 py-3 text-xs font-black text-slate-700 border-b">{{ $transaction->reference_number }}</td>
                            <td class="px-3 py-3 text-xs font-bold text-slate-700 border-b">{{ $transaction->party_name }}</td>
                            <td class="px-3 py-3 text-xs font-bold border-b"><span class="px-2 py-1 rounded {{ $transaction->is_incoming ? 'bg-primary-50 text-primary-700' : 'bg-danger-50 text-danger-700' }}">{{ $transaction->type_name }}</span></td>
                            <td class="px-3 py-3 text-sm font-bold text-primary-600 border-b" dir="ltr">{{ $transaction->is_incoming ? format_quantity(abs($transaction->quantity)) : '-' }}</td>
                            <td class="px-3 py-3 text-sm font-bold text-danger-600 border-b" dir="ltr">{{ !$transaction->is_incoming ? format_quantity(abs($transaction->quantity)) : '-' }}</td>
                            <td class="px-3 py-3 text-sm font-black text-emerald-700 border-b" dir="ltr">{{ $transaction->unit_price !== null ? format_amount($transaction->unit_price).' ج.م' : '-' }}</td>
                            <td class="px-3 py-3 text-sm font-bold text-slate-700 border-b" dir="ltr">{{ $transaction->operation_total !== null ? format_amount($transaction->operation_total).' ج.م' : '-' }}</td>
                            <td class="px-3 py-3 text-sm font-bold text-slate-800 border-b" dir="ltr">{{ format_quantity($transaction->balance_after) }}</td>
                            <td class="px-3 py-3 text-xs text-slate-500 border-b max-w-xs truncate">{{ $transaction->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="px-4 py-10 text-sm text-slate-500">لم يتم تسجيل أي حركات لهذا المنتج بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
