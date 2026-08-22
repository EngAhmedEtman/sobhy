<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'customer']));

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

foreach (array_filter((['type' => 'customer']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!-- Print Statement Modal -->
<div x-show="showPrintModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center sm:p-0">
        <div x-show="showPrintModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showPrintModal = false"></div>
        <div x-show="showPrintModal" x-transition class="relative w-full max-w-md p-5 sm:p-6 text-right transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">طباعة كشف الحساب</h3>
                <button @click="showPrintModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>

            <div class="space-y-4 text-slate-700">
                <p class="text-sm font-bold mb-3">اختر فترة كشف الحساب:</p>
                
                <!-- Option 1: All -->
                <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" :class="printFilter === 'all' ? 'border-primary-500 bg-primary-50/50' : ''">
                    <input type="radio" x-model="printFilter" value="all" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div>
                        <span class="block text-sm font-bold text-slate-800">كامل الحساب</span>
                        <span class="block text-xs text-slate-500 mt-0.5">طباعة جميع العمليات المسجلة منذ بداية التعامل</span>
                    </div>
                </label>

                <!-- Option 2: Since Last Zero -->
                <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" :class="printFilter === 'last_zero' ? 'border-primary-500 bg-primary-50/50' : ''">
                    <input type="radio" x-model="printFilter" value="last_zero" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div>
                        <span class="block text-sm font-bold text-slate-800">منذ آخر تصفية حساب</span>
                        <span class="block text-xs text-slate-500 mt-0.5">طباعة العمليات ابتداءً من آخر مرة كان الرصيد فيها (0)</span>
                    </div>
                </label>

                <!-- Option 3: Last N -->
                <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" :class="printFilter === 'last_n' ? 'border-primary-500 bg-primary-50/50' : ''">
                    <input type="radio" x-model="printFilter" value="last_n" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div class="w-full">
                        <span class="block text-sm font-bold text-slate-800">آخر مجموعة عمليات</span>
                        <span class="block text-xs text-slate-500 mt-0.5 mb-2">تحديد عدد معين من أحدث العمليات لطباعتها</span>
                        
                        <div x-show="printFilter === 'last_n'" x-transition class="mt-2 flex items-center gap-2">
                            <span class="text-sm font-bold">عدد العمليات:</span>
                            <input type="number" x-model="printLimit" min="1" max="100" class="w-20 px-2 py-1 text-sm border border-slate-300 rounded focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                        </div>
                    </div>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button @click="showPrintModal = false" type="button" class="px-4 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">إلغاء</button>
                <button @click="executePrint()" type="button" class="px-4 py-2 text-sm font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    عرض ومعاينة الطباعة
                </button>
            </div>
        </div>
    </div>
</div>
