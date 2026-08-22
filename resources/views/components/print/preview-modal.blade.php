<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'printPreviewModal',
    'title' => 'معاينة الطباعة (A4)',
    'printUrl' => '#',
    'maxWidth' => '3xl',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id' => 'printPreviewModal',
    'title' => 'معاينة الطباعة (A4)',
    'printUrl' => '#',
    'maxWidth' => '3xl',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<script>
    if (typeof window.openPrintPreviewModal === 'undefined') {
        window.openPrintPreviewModal = function(modalId, customUrl) {
            window.dispatchEvent(new CustomEvent('open-print-preview', { detail: { id: modalId, url: customUrl } }));
        };
    }
</script>

<div x-data="{ open: false, loaded: false, currentUrl: '<?php echo e($printUrl); ?>' }" 
     @open-print-preview.window="if ($event.detail.id === '<?php echo e($id); ?>') { open = true; loaded = true; if ($event.detail.url) { currentUrl = $event.detail.url; } }">
    <template x-teleport="body">
        <div x-show="open" 
             x-cloak
             style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 999999; display: flex; align-items: center; justify-content: center; padding: 6px; overflow: hidden;"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <!-- Backdrop -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="position: fixed; inset: 0; background-color: rgba(15, 23, 42, 0.78); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 1;"
                 class="cursor-pointer"
                 @click="open = false"></div>

            <!-- Modal Window -->
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 style="height: 94vh; max-height: 94vh; margin-left: auto; margin-right: auto; z-index: 2;"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-2xl transition-all w-full <?php echo e($maxWidthClass); ?> mx-auto border border-slate-200 flex flex-col font-[Cairo]">
                
                <!-- Modal Header -->
                <div class="px-3 sm:px-5 py-3 bg-slate-50 border-b border-slate-200 text-slate-800 flex items-center justify-between gap-2 shrink-0">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                        <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 border border-primary-200/60 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-xs sm:text-sm font-black text-slate-800 leading-tight truncate" id="modal-title"><?php echo e($title); ?></h3>
                            <p class="text-[0.65rem] sm:text-[0.7rem] text-slate-500 font-medium mt-0.5 hidden sm:block">يرجى الانتظار حتى يكتمل تحميل المستند</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="px-2.5 py-0.5 rounded-lg bg-primary-50 text-primary-700 text-[0.7rem] font-bold border border-primary-200/80 hidden sm:inline-flex">حجم A4</span>
                        <button @click="open = false" type="button" class="text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 p-1.5 rounded-lg transition-colors cursor-pointer">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body (Live Preview Iframe) -->
                <div style="flex: 1 1 0%; min-height: 0; display: flex; flex-direction: column;" class="relative bg-slate-100 p-1 sm:p-2 overflow-hidden">
                    <template x-if="loaded">
                        <iframe id="<?php echo e($id); ?>_iframe" 
                                :src="currentUrl" 
                                style="width: 100%; height: 100%; min-height: 100%; flex: 1 1 0%; border: 0;"
                                class="rounded-xl border border-slate-300 bg-white shadow-inner block"
                                frameborder="0"></iframe>
                    </template>
                </div>

                <!-- Modal Footer Controls -->
                <div class="px-3 sm:px-5 py-2 sm:py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 shrink-0">
                    <button @click="open = false" type="button" class="px-3.5 py-1.5 bg-white text-slate-700 border border-slate-300 font-bold rounded-xl text-xs hover:bg-slate-100 transition-colors cursor-pointer text-center">
                        إغلاق
                    </button>

                    <div class="flex items-center gap-2">
                        <a :href="currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'autoprint=1'" target="_blank" class="flex-1 sm:flex-none px-3.5 py-1.5 bg-slate-100 text-slate-800 border border-slate-300 font-bold rounded-xl text-xs hover:bg-slate-200 transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            <span>حفظ كـ PDF</span>
                        </a>
                        <button type="button" 
                                @click="document.getElementById('<?php echo e($id); ?>_iframe').contentWindow.print()" 
                                class="flex-1 sm:flex-none px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white font-extrabold rounded-xl text-xs transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>طباعة الآن</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </template>
</div>
