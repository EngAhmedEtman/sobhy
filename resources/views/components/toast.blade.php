<div 
    x-data="{ 
        show: false, 
        message: '', 
        type: 'success', 
        progress: 100 
    }" 
    x-init="
        <?php if(session()->has('success') || session()->has('error')): ?>
            <?php if(session()->has('success')): ?>
                message = '<?php echo e(session('success')); ?>';
                type = 'success';
            <?php else: ?>
                message = '<?php echo e(session('error')); ?>';
                type = 'error';
            <?php endif; ?>
            
            setTimeout(() => { show = true; }, 100);
            setTimeout(() => { progress = 0; }, 200);
            setTimeout(() => { show = false; }, 4200);
        <?php endif; ?>
    "
    x-show="show" 
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
        <div x-show="type === 'success'" class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-primary-50 text-primary-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        
        <!-- Icon for Error -->
        <div x-show="type === 'error'" class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-danger-50 text-danger-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>

        <!-- Message -->
        <div class="mr-3 text-sm font-bold" :class="{ 'text-primary-700': type === 'success', 'text-danger-700': type === 'error' }" x-text="message"></div>

        <!-- Close button -->
        <button @click="show = false" type="button" class="mr-auto -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 text-slate-400 hover:text-slate-900 focus:ring-2 focus:ring-slate-300 hover:bg-slate-100 transition-colors">
            <span class="sr-only">إغلاق</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <!-- Progress bar -->
    <div class="w-full h-1 bg-slate-100 mt-auto">
        <div class="h-full" 
             :style="`transition: width 4.2s linear; width: ${progress}%; background-color: ${type === 'success' ? '#22c55e' : '#ef4444'}`">
        </div>
    </div>
</div>
