@props([
    'id' => 'printPreviewModal',
    'title' => 'معاينة الطباعة',
    'printUrl' => '#',
    'maxWidth' => '5xl',
])

@php
$maxWidthClass = match ($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    '6xl' => 'max-w-6xl',
    default => $maxWidth,
};
@endphp

<script>
    if (typeof window.openPrintPreviewModal === 'undefined') {
        window.openPrintPreviewModal = function(modalId, customUrl) {
            window.dispatchEvent(new CustomEvent('open-print-preview', { detail: { id: modalId, url: customUrl } }));
        };
    }
</script>

<div x-data="{ 
        open: false, 
        loaded: false, 
        currentUrl: '{{ $printUrl }}'
     }" 
     @open-print-preview.window="if ($event.detail.id === '{{ $id }}') { open = true; loaded = true; if ($event.detail.url) { currentUrl = $event.detail.url; } }">
    <template x-teleport="body">
        <div x-show="open" 
             x-cloak
             class="fixed inset-0 z-[999999] flex items-center justify-center p-2 sm:p-4 overflow-hidden"
             @click.self="open = false"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <!-- Backdrop -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm cursor-pointer"
                 @click="open = false"></div>

            <!-- Modal Window -->
            <div x-show="open"
                 @click.outside="open = false"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-2xl transition-all w-full {{ $maxWidthClass }} mx-auto border border-slate-200 flex flex-col h-[92vh] max-h-[92vh] z-10">
                
                <!-- Modal Header -->
                <div class="px-4 sm:px-6 py-3 bg-slate-50 border-b border-slate-200 text-slate-800 flex items-center justify-between gap-3 shrink-0">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 border border-primary-200/60 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-xs sm:text-sm font-bold text-slate-800 leading-tight truncate" id="modal-title">{{ $title }}</h3>
                            <p class="text-[0.65rem] text-slate-400 font-medium hidden sm:block">معاينة تفاصيل الطباعة</p>
                        </div>
                    </div>

                    <button @click="open = false" type="button" class="text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 p-1.5 rounded-lg transition-colors cursor-pointer shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body (Iframe) -->
                <div class="flex-1 w-full min-h-0 bg-slate-100 p-1 sm:p-3 overflow-hidden flex items-center justify-center">
                    <template x-if="loaded">
                        <iframe id="{{ $id }}_iframe" 
                                :src="currentUrl" 
                                class="w-full h-full bg-white rounded-xl shadow-sm border border-slate-200 block"
                                style="border: 0;"
                                frameborder="0"></iframe>
                    </template>
                </div>

                <!-- Modal Footer Controls -->
                <div class="px-3 sm:px-5 py-2.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-2 shrink-0">
                    <button @click="open = false" type="button" class="px-3.5 py-2 bg-white text-slate-700 border border-slate-200 font-bold rounded-lg text-xs hover:bg-slate-100 transition-colors cursor-pointer text-center shrink-0">
                        إغلاق
                    </button>

                    <div class="flex items-center gap-2">
                        <a :href="currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'autoprint=1'" target="_blank" class="px-3 py-2 bg-white text-slate-700 border border-slate-200 font-bold rounded-lg text-xs hover:bg-slate-100 transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            <span class="hidden sm:inline">فتح في نافذة مستقلة</span>
                            <span class="sm:hidden">نافذة جديدة</span>
                        </a>
                        <button type="button" 
                                @click="document.getElementById('{{ $id }}_iframe').contentWindow.print()" 
                                class="px-4 sm:px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-lg text-xs transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>طباعة الآن</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </template>
</div>
