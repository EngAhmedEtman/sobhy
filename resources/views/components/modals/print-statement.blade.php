@props(['entityId', 'type' => 'customer'])

<!-- Print Statement Modal -->
<div x-show="showPrintModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div x-show="showPrintModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showPrintModal = false"></div>
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
                        <div class="text-sm font-bold text-slate-800">كشف حساب شامل</div>
                        <div class="text-xs text-slate-500 mt-0.5">جميع العمليات والحركات المالية منذ البداية وحتى اليوم.</div>
                    </div>
                </label>

                <!-- Option 2: This Month -->
                <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" :class="printFilter === 'this_month' ? 'border-primary-500 bg-primary-50/50' : ''">
                    <input type="radio" x-model="printFilter" value="this_month" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div>
                        <div class="text-sm font-bold text-slate-800">كشف حساب الشهر الحالي</div>
                        <div class="text-xs text-slate-500 mt-0.5">حركات شهر {{ now()->translatedFormat('F Y') }} فقط مع الرصيد السابق.</div>
                    </div>
                </label>

                <!-- Option 3: Custom Date Range -->
                <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" :class="printFilter === 'custom' ? 'border-primary-500 bg-primary-50/50' : ''">
                    <input type="radio" x-model="printFilter" value="custom" class="mt-1 text-primary-600 focus:ring-primary-500">
                    <div class="w-full">
                        <div class="text-sm font-bold text-slate-800">فترة مخصصة</div>
                        <div class="text-xs text-slate-500 mt-0.5 mb-2">حدد تاريخ البداية والنهاية لكشف الحساب.</div>
                        
                        <div x-show="printFilter === 'custom'" class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-100">
                            <div>
                                <label class="block text-[0.7rem] font-bold text-slate-500 mb-1">من تاريخ</label>
                                <input type="date" x-model="printStartDate" class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[0.7rem] font-bold text-slate-500 mb-1">إلى تاريخ</label>
                                <input type="date" x-model="printEndDate" class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs">
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="generatePrint()" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-sm transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>عرض وطباعة التقرير</span>
                </button>
                <button type="button" @click="showPrintModal = false" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">إلغاء</button>
            </div>
        </div>
    </div>
</div>
