<!-- Mobile Cards (visible on small screens only) -->
<div class="sm:hidden space-y-3 mb-6">
    @forelse($customers as $customer)
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 hover:border-slate-200 transition-colors">
        <div class="flex justify-between items-start mb-3">
            <div>
                <a href="{{ route('customers.show', $customer->id) }}" class="text-base font-bold text-primary-700 hover:underline">{{ $customer->name }}</a>
                @if($customer->phone)
                    <p class="text-xs text-slate-500 font-mono mt-0.5" dir="ltr">{{ $customer->phone }}</p>
                @endif
            </div>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('customers.show', $customer->id) }}" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="كشف حساب العميل">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </a>
                <button type="button" @click="editData = { id: '{{ $customer->id }}', name: '{{ addslashes($customer->name) }}', phone: '{{ $customer->phone }}' }; showEditModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </button>
                <button type="button" @click="deleteId = '{{ $customer->id }}'; showDeleteModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
        </div>
        <div class="flex justify-between items-center text-sm border-t border-slate-50 pt-2">
            <span class="text-xs text-slate-500">حالة الرصيد:</span>
            @if($customer->balance > 0)
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-rose-50 text-rose-700 border border-rose-200/60" dir="ltr">
                    {{ format_amount($customer->balance) }} <span class="text-[0.65rem] mr-1 text-rose-800">ج.م (مطلوب منه)</span>
                </span>
            @elseif($customer->balance < 0)
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60" dir="ltr">
                    {{ format_amount(abs($customer->balance)) }} <span class="text-[0.65rem] mr-1 text-emerald-800">ج.م (رصيد للعميل)</span>
                </span>
            @else
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-slate-100 text-slate-600">خالص (لا يوجد مديونية)</span>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-slate-100 p-8 text-center text-sm text-slate-500">
        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
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
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">اسم العميل</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رقم الهاتف</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">مطلوب منه (لنا عنده)</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">رصيد له (دافع زيادة)</th>
                <th class="px-4 py-3 text-[0.75rem] font-bold text-slate-500 border-b border-slate-100 uppercase tracking-wide align-middle text-center">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr class="hover:bg-slate-50/60 transition-colors group">
                <td class="px-4 py-2.5 text-[0.8rem] text-slate-500 border-b border-slate-50 align-middle text-center font-medium">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                <td class="px-4 py-2.5 text-[0.8rem] font-bold text-primary-700 border-b border-slate-50 align-middle text-center">
                    <a href="{{ route('customers.show', $customer->id) }}" class="hover:underline">{{ $customer->name }}</a>
                </td>
                <td class="px-4 py-2.5 text-[0.8rem] text-slate-600 border-b border-slate-50 align-middle text-center font-mono" dir="ltr">{{ $customer->phone ?? '-' }}</td>
                <td class="px-4 py-2.5 text-[0.8rem] font-bold border-b border-slate-50 align-middle text-center" dir="ltr">
                    @if($customer->balance > 0)
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                            {{ format_amount($customer->balance) }} ج.م
                        </span>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </td>
                <td class="px-4 py-2.5 text-[0.8rem] font-bold border-b border-slate-50 align-middle text-center" dir="ltr">
                    @if($customer->balance < 0)
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            {{ format_amount(abs($customer->balance)) }} ج.م
                        </span>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </td>
                <td class="px-4 py-2.5 border-b border-slate-50 align-middle text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <a href="{{ route('customers.show', $customer->id) }}" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 shadow-sm transition-all inline-flex items-center justify-center" title="كشف حساب العميل">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </a>
                        <button type="button" @click="editData = { id: '{{ $customer->id }}', name: '{{ addslashes($customer->name) }}', phone: '{{ $customer->phone }}' }; showEditModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 shadow-sm transition-all inline-flex items-center justify-center" title="تعديل">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button type="button" @click="deleteId = '{{ $customer->id }}'; showDeleteModal = true" class="p-1 rounded border border-slate-200 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 shadow-sm transition-all inline-flex items-center justify-center" title="حذف">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    لا توجد نتائج مطابقة لمعايير البحث الحالية.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($customers->hasPages())
    <div class="mt-4 ajax-pagination">
        {{ $customers->links() }}
    </div>
@endif
