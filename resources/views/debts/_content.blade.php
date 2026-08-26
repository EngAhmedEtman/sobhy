<div class="mb-3 flex flex-wrap items-end justify-between gap-2">
    <div>
        <h2 class="text-base font-black text-slate-800">{{ $activeTab['label'] }}</h2>
        <p class="mt-0.5 text-xs text-slate-500">{{ $activeTab['description'] }}</p>
    </div>
    <p class="text-xs font-bold text-slate-500">العدد: <span class="text-slate-800">{{ number_format($debts->total()) }}</span></p>
</div>

<div class="space-y-3 sm:hidden">
    @forelse($debts as $party)
        <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <a href="{{ route($activeTab['route'], $party->id) }}" class="block truncate text-sm font-bold text-primary-700 hover:underline">{{ $party->name }}</a>
                    <p class="mt-1 text-xs text-slate-500" dir="ltr">{{ $party->phone ?: 'لا يوجد رقم هاتف' }}</p>
                </div>
                <a href="{{ route($activeTab['route'], $party->id) }}" class="shrink-0 rounded-md border border-primary-200 bg-primary-50 px-3 py-1.5 text-xs font-bold text-primary-700 transition-colors hover:bg-primary-600 hover:text-white">{{ $activeTab['action_label'] }}</a>
            </div>
            <div class="mt-3 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                <span class="text-xs font-medium text-slate-500">{{ $activeTab['amount_label'] }}</span>
                <span class="shrink-0 text-base font-black text-danger-600" dir="ltr">{{ number_format(abs($party->balance), 0) }} <span class="text-xs">ج.م</span></span>
            </div>
        </article>
    @empty
        <div class="rounded-lg border border-slate-200 bg-white px-4 py-10 text-center text-sm text-slate-500">
            {{ $search !== '' ? 'لا توجد نتائج مطابقة للبحث في هذا التبويب.' : $activeTab['empty_message'] }}
        </div>
    @endforelse
</div>

<div class="mb-5 hidden overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm sm:block">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[680px] border-collapse text-center">
            <thead class="bg-slate-50">
                <tr>
                    <th class="border-b border-slate-200 px-4 py-3 text-xs font-bold text-slate-600">اسم {{ $activeTab['party_label'] }}</th>
                    <th class="border-b border-slate-200 px-4 py-3 text-xs font-bold text-slate-600">رقم الهاتف</th>
                    <th class="border-b border-slate-200 px-4 py-3 text-xs font-bold text-slate-600">{{ $activeTab['amount_label'] }}</th>
                    <th class="border-b border-slate-200 px-4 py-3 text-xs font-bold text-slate-600">الإجراء</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($debts as $party)
                    <tr class="transition-colors hover:bg-slate-50/70">
                        <td class="px-4 py-3 text-sm font-bold text-primary-700">
                            <a href="{{ route($activeTab['route'], $party->id) }}" class="hover:underline">{{ $party->name }}</a>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600" dir="ltr">{{ $party->phone ?: '-' }}</td>
                        <td class="px-4 py-3 text-sm font-black text-danger-600" dir="ltr">{{ number_format(abs($party->balance), 0) }} <span class="text-xs">ج.م</span></td>
                        <td class="px-4 py-3">
                            <a href="{{ route($activeTab['route'], $party->id) }}" class="inline-block rounded-md border border-primary-200 bg-primary-50 px-3 py-1.5 text-xs font-bold text-primary-700 transition-colors hover:bg-primary-600 hover:text-white">{{ $activeTab['action_label'] }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-sm text-slate-500">
                            {{ $search !== '' ? 'لا توجد نتائج مطابقة للبحث في هذا التبويب.' : $activeTab['empty_message'] }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($debts->hasPages())
    <div class="ajax-pagination mt-4">{{ $debts->links() }}</div>
@endif
