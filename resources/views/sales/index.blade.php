<x-layouts.app title="إدارة المبيعات">
    <x-slot name="breadcrumb">المبيعات</x-slot>

    <div x-data="{ 
        showDetailsModal: false, 
        details: null, 
        loading: false, 
        viewInvoice(id) { 
            this.loading = true; 
            this.showDetailsModal = true; 
            fetch('/sales/' + id, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(d => { this.details = d; this.loading = false; }); 
        } 
    }">

        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 border border-primary-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-800">إدارة المبيعات</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">سجل فواتير المبيعات</p>
                </div>
            </div>
            <button @click="$dispatch('create-sale')" type="button" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 text-sm font-bold flex items-center transition-colors shadow-sm shadow-primary-600/20 w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                إضافة فاتورة مبيعات
            </button>
        </div>

        <!-- Desktop Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">رقم الفاتورة</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">التاريخ</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">اسم العميل</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">المنتجات</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الإجمالي</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">ملاحظات</th>
                            <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            <td class="px-4 py-3 text-[0.8rem] font-bold text-primary-700 border-b border-slate-100">{{ $sale->invoice_number }}</td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-100">{{ $sale->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100">
                                <a href="{{ route('customers.show', $sale->customer_id) }}" class="hover:underline hover:text-primary-600">{{ $sale->customer->name }}</a>
                            </td>
                            <td class="px-4 py-3 border-b border-slate-100 min-w-[150px] max-w-[250px] whitespace-normal">
                                <div class="flex flex-wrap justify-center gap-1">
                                    @foreach($sale->items as $item)
                                        <span class="bg-primary-50 text-primary-700 px-2 py-0.5 rounded text-[0.7rem] border border-primary-100">{{ $item->product->name }} ({{ number_format($item->quantity, 0) }})</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100" dir="ltr">{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-[0.8rem] text-slate-500 border-b border-slate-100 max-w-[150px] truncate">{{ $sale->notes ?? '-' }}</td>
                            <td class="px-4 py-3 border-b border-slate-100">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" @click="viewInvoice({{ $sale->id }})" class="p-1.5 rounded border border-slate-200 bg-white text-emerald-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 shadow-sm transition-all" title="عرض الفاتورة">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                    <!-- Edit Button -->
                                    <button type="button" @click="$dispatch('edit-sale', {{ $sale->id }})" class="p-1.5 rounded border border-slate-200 bg-white text-blue-600 hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all" title="تعديل الفاتورة">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <button type="button" x-data @click="$dispatch('open-modal', 'delete-sale-{{ $sale->id }}')" class="p-1.5 rounded border border-slate-200 bg-white text-danger-600 hover:text-danger-700 hover:border-danger-300 hover:bg-danger-50 shadow-sm transition-all" title="حذف الفاتورة">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                    <x-delete-modal name="delete-sale-{{ $sale->id }}" action="{{ route('sales.destroy', $sale->id) }}" title="حذف فاتورة مبيعات رقم {{ $sale->invoice_number }}" message="سيتم التراجع عن خصم الكميات من المخزن وإلغاء المديونية المتعلقة بها." />
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">لا توجد فواتير مسجلة حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
                <div class="mt-4 px-4 py-3">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
        
        <!-- Sale Modal Component -->
        <x-modals.sale-form :products="$products" :customers="$customers" />

        <!-- View Details Modal -->
        <x-modals.invoice-details type="sale" />

    </div>
</x-layouts.app>