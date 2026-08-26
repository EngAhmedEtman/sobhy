<x-layouts.app title="المديونيات">
    <x-slot:breadcrumb>المديونيات</x-slot:breadcrumb>

    <div
        x-data="debtPage({
            endpoint: {{ Illuminate\Support\Js::from(route('debts.index')) }},
            initialTab: {{ Illuminate\Support\Js::from($tab) }},
            initialSearch: {{ Illuminate\Support\Js::from($search) }},
            labels: {{ Illuminate\Support\Js::from($tabLabels) }}
        })"
        @popstate.window="restoreFromUrl()"
        @click="if ($event.target.closest('.ajax-pagination a')) {
            $event.preventDefault();
            fetchData($event.target.closest('.ajax-pagination a').href);
        }"
    >
        <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 sm:h-12 sm:w-12">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-800 sm:text-2xl">المديونيات والأرصدة</h1>
                    <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">اعرف بسهولة المبالغ المطلوبة لنا والمستحقة علينا</p>
                </div>
            </div>

            <form action="{{ route('debts.index') }}" method="GET" class="w-full xl:mr-auto xl:w-80" @submit.prevent="fetchData()">
                <input type="hidden" name="tab" value="{{ $tab }}" :value="tab">
                <label for="debt-search" class="sr-only">البحث في التبويب الحالي</label>
                <div class="flex h-10 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm focus-within:border-primary-400 focus-within:ring-2 focus-within:ring-primary-100">
                    <input id="debt-search" name="search" x-model="search" type="search" maxlength="100" placeholder="ابحث بالاسم أو رقم الهاتف" class="h-full min-w-0 flex-1 border-0 bg-transparent px-3 text-sm text-slate-700 placeholder:text-slate-400 focus:ring-0">
                    <button type="submit" :disabled="loading" class="shrink-0 bg-primary-600 px-4 text-sm font-bold text-white transition-colors hover:bg-primary-700 disabled:cursor-wait disabled:opacity-60">بحث</button>
                </div>
                <p class="mt-1.5 text-[0.7rem] font-medium text-slate-400" x-text="'البحث داخل: ' + activeLabel"></p>
            </form>
        </div>

        <nav class="mb-5 grid grid-cols-2 gap-1 rounded-lg bg-slate-200/70 p-1 lg:grid-cols-4" aria-label="أنواع المديونيات">
            @foreach($tabs as $tabKey => $tabDetails)
                <a
                    href="{{ route('debts.index', ['tab' => $tabKey]) }}"
                    @click.prevent="selectTab('{{ $tabKey }}')"
                    :class="tab === '{{ $tabKey }}' ? 'bg-white font-bold text-primary-700 shadow-sm' : 'font-semibold text-slate-600 hover:bg-white/60 hover:text-slate-800'"
                    :aria-current="tab === '{{ $tabKey }}' ? 'page' : null"
                    class="flex min-h-10 items-center justify-center rounded-md px-2 py-2 text-center text-xs transition-colors sm:text-sm"
                >
                    {{ $tabDetails['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="relative" :aria-busy="loading">
            <div x-show="loading" x-cloak class="absolute inset-0 z-10 flex min-h-40 items-center justify-center bg-slate-50/70" aria-live="polite">
                <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 shadow-sm">
                    <svg class="h-4 w-4 animate-spin text-primary-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4Z"></path>
                    </svg>
                    جاري التحميل
                </div>
            </div>

            <div id="debtsContent" :class="{ 'pointer-events-none opacity-50': loading }">
                @include('debts._content')
            </div>
        </div>
    </div>
</x-layouts.app>
