<!-- Mobile Cards (visible on small screens) -->
<div class="sm:hidden space-y-3 mb-6">
    @forelse($sales as $sale)
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 hover:border-slate-200 transition-colors">
        <div class="flex justify-between items-start mb-2">
            <div>
                <span class="text-xs font-bold text-primary-700 font-mono">{{ $sale->invoice_number }}</span>
                <h4 class="text-sm font-bold text-slate-800 mt-0.5">
                    <a href="{{ route('customers.show', $sale->customer_id) }}" class="hover:underline">{{ $sale->customer->name }}</a>
                </h4>
            </div>
            <span class="text-[0.7rem] font-bold text-slate-400 font-mono" dir="ltr">{{ ($sale->invoice_date ?? $sale->created_at)->format('Y-m-d') }}</span>
        </div>

        <!-- Products -->
        <div class="flex flex-wrap gap-1 my-2">
            @foreach($sale->items as $item)
                <span class="bg-primary-50 text-primary-700 px-2 py-0.5 rounded text-[0.65rem] border border-primary-100 font-medium">
                    {{ $item->product->name }} ({{ format_quantity($item->quantity) }})
                </span>
            @endforeach
        </div>

        <div class="flex justify-between items-center pt-2 border-t border-slate-50 mt-2">
            <div>
                <span class="text-xs text-slate-500">الإجمالي:</span>
                <span class="text-sm font-black text-slate-800" dir="ltr">{{ format_amount($sale->total_amount) }} <span class="text-[0.65rem] text-slate-400 font-normal">ج.م</span></span>
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" @click="viewInvoice({{ $sale->id }})" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض الفاتورة">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </button>
                <button type="button" @click="$dispatch('edit-sale', {{ $sale->id }})" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل الفاتورة">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </button>
                <button type="button" @click="deleteSaleId = {{ $sale->id }}; deleteInvoiceNumber = '{{ $sale->invoice_number }}'; showDeleteModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف الفاتورة">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">
        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        لا توجد فواتير مبيعات مطابقة لخيارات البحث.
    </div>
    @endforelse
</div>

<!-- Desktop Table (hidden on small screens) -->
<div class="hidden sm:block overflow-x-auto relative rounded-xl border border-slate-100 bg-white mb-6">
    <table class="w-full text-center border-collapse whitespace-nowrap">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رقم الفاتورة</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">التاريخ</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم العميل</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">المنتجات المباعة</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجمالي</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">ملاحظات</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr class="hover:bg-slate-50/60 transition-colors group">
                <td class="px-4 py-2.5 text-[0.8rem] font-bold text-primary-700 border-b border-slate-50 align-middle text-center font-mono">{{ $sale->invoice_number }}</td>
                <td class="px-4 py-2.5 text-[0.8rem] text-slate-600 border-b border-slate-50 align-middle text-center font-mono" dir="ltr">{{ ($sale->invoice_date ?? $sale->created_at)->format('Y-m-d') }}</td>
                <td class="px-4 py-2.5 text-[0.8rem] font-bold text-slate-700 border-b border-slate-50 align-middle text-center">
                    <a href="{{ route('customers.show', $sale->customer_id) }}" class="hover:underline hover:text-primary-600">{{ $sale->customer->name }}</a>
                </td>
                <td class="px-4 py-2.5 border-b border-slate-50 min-w-[150px] max-w-[250px] whitespace-normal align-middle text-center">
                    <div class="flex flex-wrap justify-center gap-1">
                        @foreach($sale->items as $item)
                            <span class="bg-primary-50 text-primary-700 px-2 py-0.5 rounded text-[0.7rem] border border-primary-100 font-medium">
                                {{ $item->product->name }} ({{ format_quantity($item->quantity) }})
                            </span>
                        @endforeach
                    </div>
                </td>
                <td class="px-4 py-2.5 text-[0.8rem] font-bold text-slate-800 border-b border-slate-50 align-middle text-center" dir="ltr">
                    {{ format_amount($sale->total_amount) }} <span class="text-[0.65rem] text-slate-400 font-normal">ج.م</span>
                </td>
                <td class="px-4 py-2.5 text-[0.8rem] text-slate-500 border-b border-slate-50 max-w-[150px] truncate align-middle text-center">{{ $sale->notes ?? '-' }}</td>
                <td class="px-4 py-2.5 border-b border-slate-50 align-middle text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <button type="button" @click="viewInvoice({{ $sale->id }})" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="عرض الفاتورة">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                        <button type="button" @click="$dispatch('edit-sale', {{ $sale->id }})" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل الفاتورة">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </button>
                        <button type="button" @click="deleteSaleId = {{ $sale->id }}; deleteInvoiceNumber = '{{ $sale->invoice_number }}'; showDeleteModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف الفاتورة">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    لا توجد فواتير مبيعات مطابقة لخيارات البحث.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($sales->hasPages())
    <div class="mt-4 ajax-pagination">
        {{ $sales->links() }}
    </div>
@endif
