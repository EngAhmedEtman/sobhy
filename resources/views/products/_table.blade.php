<div class="overflow-x-auto">
    <table class="w-full text-center border-collapse whitespace-nowrap">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">#</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم المنتج</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الرصيد المتبقي</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">ملاحظات</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="hover:bg-slate-50/60 transition-colors group">
                <td class="px-4 py-3 text-[0.8rem] text-slate-700 border-b border-slate-100 align-middle text-center font-medium">{{ $products->firstItem() + $loop->index }}</td>
                <td class="px-4 py-3 text-[0.85rem] font-bold text-primary-700 border-b border-slate-100 align-middle text-center">
                    <a href="{{ route('products.show', $product->id) }}" class="hover:underline">{{ $product->name }}</a>
                </td>
                <td class="px-4 py-3 text-[0.85rem] font-bold text-slate-700 border-b border-slate-100 align-middle text-center" dir="ltr">
                    {{ format_quantity($product->stock) }} <span class="text-xs text-slate-500 font-normal">كيلو</span>
                </td>
                <td class="px-4 py-3 text-[0.8rem] text-slate-600 border-b border-slate-100 align-middle text-center">{{ $product->notes ?? '-' }}</td>
                <td class="px-4 py-3 border-b border-slate-100 align-middle text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('products.show', $product->id) }}" class="p-1.5 rounded border border-slate-200 bg-white text-emerald-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 shadow-sm transition-all" title="سجل حركات المنتج">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </a>
                        @if(auth()->user()?->hasPermission('products.update'))
                        <button @click="editData = { id: '{{ $product->id }}', name: '{{ addslashes($product->name) }}', notes: '{{ addslashes($product->notes ?? '') }}', hasOpening: {{ $product->transactions_count > 0 ? 'true' : 'false' }} }; showEditModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-blue-600 hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all" title="تعديل">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        @endif
                        @if(auth()->user()?->hasPermission('products.delete'))
                        <button @click="deleteId = '{{ $product->id }}'; showDeleteModal = true" class="p-1.5 rounded border border-slate-200 bg-white text-danger-600 hover:text-danger-700 hover:border-danger-300 hover:bg-danger-50 shadow-sm transition-all" title="حذف">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-sm text-slate-500 text-center">
                    لا يوجد منتجات مضافة حتى الآن.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
    <div class="mt-4 ajax-pagination">
        {{ $products->links() }}
    </div>
@endif
