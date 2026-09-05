<div 
    x-data="{
        initialShow: {{ (session()->has('success') || session()->has('error') || $errors->any()) ? 'true' : 'false' }},
        initialMessage: {{ Js::from(session('success') ?? session('error') ?? ($errors->any() ? $errors->first() : '')) }},
        initialType: '{{ (session()->has('error') || $errors->any()) ? 'error' : 'success' }}'
    }"
    x-init="
        if (initialShow) {
            $store.toast.display(initialMessage, initialType);
        }
    "
    @show-toast.window="
        $store.toast.display($event.detail.message, $event.detail.type || 'success');
    "
    x-show="$store.toast.visible"
    x-cloak
    :role="$store.toast.type === 'error' ? 'alert' : 'status'"
    aria-live="polite"
    aria-atomic="true"
    x-transition:enter="transform transition ease-out duration-300"
    x-transition:enter-start="translate-x-12 opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transform transition ease-in duration-300"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-12 opacity-0"
    class="fixed left-4 right-4 top-5 z-[1100] flex w-auto flex-col overflow-hidden rounded-xl border bg-white shadow-lg sm:left-auto sm:right-5 sm:w-full sm:max-w-sm"
    :class="{
        'border-primary-100': $store.toast.type === 'success',
        'border-danger-100': $store.toast.type === 'error'
    }"
>
    <div class="flex items-center p-4">
        <!-- Icon for Success -->
        <div x-show="$store.toast.type === 'success'" class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        
        <!-- Icon for Error -->
        <div x-show="$store.toast.type === 'error'" class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>

        <!-- Message -->
        <div class="mr-3 text-sm font-bold flex-1" :class="{ 'text-slate-800': $store.toast.type === 'success', 'text-rose-700': $store.toast.type === 'error' }" x-text="$store.toast.message"></div>

        <!-- Close button -->
        <button
            @click="$store.toast.hide()"
            type="button"
            aria-label="إغلاق التنبيه"
            title="إغلاق التنبيه"
            class="mr-auto inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border transition-colors focus:ring-2 focus:ring-slate-300"
            :class="$store.toast.type === 'error'
                ? 'border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-800'
                : 'border-slate-200 bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-800'"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <!-- Progress bar -->
    <div class="w-full h-1 bg-slate-100 mt-auto">
        <div class="h-full" 
             :style="`transition: width ${Math.max($store.toast.duration - 100, 0)}ms linear; width: ${$store.toast.progress}%; background-color: ${$store.toast.type === 'success' ? '#10b981' : '#ef4444'}`">
        </div>
    </div>
</div>
