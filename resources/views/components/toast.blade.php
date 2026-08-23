<div 
    x-data="{ 
        show: {{ (session()->has('success') || session()->has('error') || $errors->any()) ? 'true' : 'false' }},
        message: {{ Js::from(session('success') ?? session('error') ?? ($errors->any() ? $errors->first() : '')) }},
        type: '{{ (session()->has('error') || $errors->any()) ? 'error' : 'success' }}',
        progress: 100 
    }" 
    x-init="
        if (show) {
            setTimeout(() => { progress = 0; }, 100);
            setTimeout(() => { show = false; }, 4500);
        }
    "
    @show-toast.window="
        message = $event.detail.message;
        type = $event.detail.type || 'success';
        show = true;
        progress = 100;
        setTimeout(() => { progress = 0; }, 100);
        setTimeout(() => { show = false; }, 4500);
    "
    x-show="show" 
    x-cloak
    :role="type === 'error' ? 'alert' : 'status'"
    aria-live="polite"
    aria-atomic="true"
    x-transition:enter="transform transition ease-out duration-300"
    x-transition:enter-start="translate-x-12 opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transform transition ease-in duration-300"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-12 opacity-0"
    style="display: none;"
    class="fixed top-5 right-5 z-[999] flex flex-col w-full max-w-xs overflow-hidden rounded-xl shadow-lg border bg-white"
    :class="{
        'border-primary-100': type === 'success',
        'border-danger-100': type === 'error'
    }"
>
    <div class="flex items-center p-4">
        <!-- Icon for Success -->
        <div x-show="type === 'success'" class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        
        <!-- Icon for Error -->
        <div x-show="type === 'error'" class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>

        <!-- Message -->
        <div class="mr-3 text-sm font-bold flex-1" :class="{ 'text-slate-800': type === 'success', 'text-rose-700': type === 'error' }" x-text="message"></div>

        <!-- Close button -->
        <button @click="show = false" type="button" class="mr-auto -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 text-slate-400 hover:text-slate-900 focus:ring-2 focus:ring-slate-300 hover:bg-slate-100 transition-colors">
            <span class="sr-only">إغلاق</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <!-- Progress bar -->
    <div class="w-full h-1 bg-slate-100 mt-auto">
        <div class="h-full" 
             :style="`transition: width 4.2s linear; width: ${progress}%; background-color: ${type === 'success' ? '#10b981' : '#ef4444'}`">
        </div>
    </div>
</div>
