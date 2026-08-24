<!-- Mobile Cards (visible on small screens only) -->
<div class="sm:hidden space-y-3 mb-6">
    @forelse($suppliers as $supplier)
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 hover:border-slate-200 transition-colors">
        <div class="flex justify-between items-start mb-3">
            <div>
                <a href="{{ route('suppliers.show', $supplier->id) }}" class="text-base font-bold text-primary-700 hover:underline">{{ $supplier->name }}</a>
                @if($supplier->phone)
                    <p class="text-xs text-slate-500 font-mono mt-0.5" dir="ltr">{{ $supplier->phone }}</p>
                @endif
            </div>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('suppliers.show', $supplier->id) }}" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="كشف حساب المورد">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </a>
                @if(auth()->user()?->hasPermission('suppliers.update'))
                <button type="button" @click="editData = { id: '{{ $supplier->id }}', name: '{{ addslashes($supplier->name) }}', phone: '{{ $supplier->phone }}' }; showEditModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </button>
                @endif
                @if(auth()->user()?->hasPermission('suppliers.delete'))
                <button type="button" @click="deleteId = '{{ $supplier->id }}'; showDeleteModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
                @endif
            </div>
        </div>
        <div class="flex justify-between items-center text-sm border-t border-slate-50 pt-2">
            <span class="text-xs text-slate-500">حالة الحساب:</span>
            @if($supplier->balance > 0)
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-amber-50 text-amber-800 border border-amber-200/60" dir="ltr">
                    {{ format_amount($supplier->balance) }} <span class="text-[0.65rem] mr-1 text-amber-900">ج.م (مستحق للمورد)</span>
                </span>
            @elseif($supplier->balance < 0)
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60" dir="ltr">
                    {{ format_amount(abs($supplier->balance)) }} <span class="text-[0.65rem] mr-1 text-emerald-800">ج.م (لنا عند المورد)</span>
                </span>
            @else
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-slate-100 text-slate-600">خالص (لا يوجد مستحقات)</span>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">
        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        لا توجد نتائج مطابقة لمعايير البحث الحالية.
    </div>
    @endforelse
</div>

<!-- Desktop Table (hidden on small screens) -->
<div class="hidden sm:block overflow-x-auto relative rounded-xl border border-slate-100 bg-white mb-6">
    <table class="w-full text-center border-collapse whitespace-nowrap">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">#</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم المورد</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رقم الهاتف</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">مستحق له (علينا للمورد)</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">لنا عنده (دافعين زيادة)</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr class="hover:bg-slate-50/60 transition-colors group">
                <td class="px-4 py-2.5 text-[0.8rem] text-slate-500 border-b border-slate-50 align-middle text-center font-medium">{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                <td class="px-4 py-2.5 text-[0.8rem] font-bold text-primary-700 border-b border-slate-50 align-middle text-center">
                    <a href="{{ route('suppliers.show', $supplier->id) }}" class="hover:underline">{{ $supplier->name }}</a>
                </td>
                <td class="px-4 py-2.5 text-[0.8rem] text-slate-600 border-b border-slate-50 align-middle text-center font-mono" dir="ltr">{{ $supplier->phone ?? '-' }}</td>
                <td class="px-4 py-2.5 text-[0.8rem] font-bold border-b border-slate-50 align-middle text-center" dir="ltr">
                    @if($supplier->balance > 0)
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-amber-50 text-amber-800 border border-amber-200/60">
                            {{ format_amount($supplier->balance) }} ج.م
                        </span>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </td>
                <td class="px-4 py-2.5 text-[0.8rem] font-bold border-b border-slate-50 align-middle text-center" dir="ltr">
                    @if($supplier->balance < 0)
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            {{ format_amount(abs($supplier->balance)) }} ج.م
                        </span>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </td>
                <td class="px-4 py-2.5 border-b border-slate-50 align-middle text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="كشف حساب المورد">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </a>
                        @if(auth()->user()?->hasPermission('suppliers.update'))
                        <button type="button" @click="editData = { id: '{{ $supplier->id }}', name: '{{ addslashes($supplier->name) }}', phone: '{{ $supplier->phone }}' }; showEditModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        @endif
                        @if(auth()->user()?->hasPermission('suppliers.delete'))
                        <button type="button" @click="deleteId = '{{ $supplier->id }}'; showDeleteModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    لا توجد نتائج مطابقة لمعايير البحث الحالية.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($suppliers->hasPages())
    <div class="mt-4 ajax-pagination">
        {{ $suppliers->links() }}
    </div>
@endif
