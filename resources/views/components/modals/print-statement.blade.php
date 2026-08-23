@props(['entityId', 'type' => 'customer'])

<!-- Print Statement Modal -->
<div x-data="{
        filter: 'all',
        startDate: '',
        endDate: '',
        generatePrint() {
            let baseUrl = '{{ $type === 'supplier' ? '/suppliers/' : '/customers/' }}' + '{{ $entityId }}' + '/print';
            let params = new URLSearchParams();
            params.set('filter', this.filter || 'all');
            
            if (this.filter === 'custom') {
                if (this.startDate) params.set('start_date', this.startDate);
                if (this.endDate) params.set('end_date', this.endDate);
            }

            let url = baseUrl + '?' + params.toString();
            if (typeof window.openPrintPreviewModal === 'function') {
                window.openPrintPreviewModal('printPreviewModal', url);
            } else {
                window.open(url, '_blank');
            }
            showPrintModal = false;
        }
    }"
    x-show="showPrintModal" 
    x-cloak 
    class="fixed inset-0 z-[60] overflow-y-auto" 
    style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4 text-center" @click.self="showPrintModal = false">
        <div x-show="showPrintModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showPrintModal = false"></div>
        <div x-show="showPrintModal" @click.outside="showPrintModal = false" x-transition class="relative w-full max-w-md p-5 sm:p-6 text-right transition-all transform bg-white shadow-2xl rounded-2xl z-10" dir="rtl">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">طباعة كشف حساب {{ $type === 'supplier' ? 'المورد' : 'العميل' }}</h3>
                </div>
                <button @click="showPrintModal = false" type="button" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>

            <div class="space-y-2.5 text-slate-700">
                <p class="text-xs sm:text-sm font-bold text-slate-700 mb-2">اختر فترة كشف الحساب:</p>
                
                <!-- Option 1: All -->
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors" :class="filter === 'all' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="all" class="mt-0.5 text-primary-600 focus:ring-primary-500">
                    <div>
                        <div class="text-xs sm:text-sm font-bold text-slate-800">كشف حساب شامل</div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5">جميع العمليات والحركات المالية منذ البداية وحتى اليوم.</div>
                    </div>
                </label>

                <!-- Option 2: Since Last Zero Balance Settlement -->
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors" :class="filter === 'last_zero' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="last_zero" class="mt-0.5 text-primary-600 focus:ring-primary-500">
                    <div>
                        <div class="text-xs sm:text-sm font-bold text-slate-800">منذ آخر تسوية للرصيد (رصيد خالص / صفر)</div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5">عرض العمليات المسجلة بعد آخر مرة تم فيها تسوية وتصفير الحساب بالكامل.</div>
                    </div>
                </label>

                <!-- Option 3: This Month -->
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors" :class="filter === 'this_month' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="this_month" class="mt-0.5 text-primary-600 focus:ring-primary-500">
                    <div>
                        <div class="text-xs sm:text-sm font-bold text-slate-800">كشف حساب الشهر الحالي</div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5">حركات شهر {{ now()->translatedFormat('F Y') }} فقط مع الرصيد السابق.</div>
                    </div>
                </label>

                <!-- Option 4: Custom Date Range -->
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors" :class="filter === 'custom' ? 'border-primary-500 bg-primary-50/40 text-primary-900' : 'border-slate-200 hover:bg-slate-50'">
                    <input type="radio" x-model="filter" value="custom" class="mt-0.5 text-primary-600 focus:ring-primary-500">
                    <div class="w-full">
                        <div class="text-xs sm:text-sm font-bold text-slate-800">فترة مخصصة</div>
                        <div class="text-[0.7rem] text-slate-500 mt-0.5 mb-2">حدد تاريخ البداية والنهاية لكشف الحساب.</div>
                        
                        <div x-show="filter === 'custom'" class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-100" style="display: none;">
                            <!-- Start Date -->
                            <div>
                                <label class="block text-[0.7rem] font-bold text-slate-600 mb-1">من تاريخ</label>
                                <input type="date" x-model="startDate" lang="en" max="{{ date('Y-m-d') }}" class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" dir="ltr">
                            </div>
                            
                            <!-- End Date -->
                            <div>
                                <label class="block text-[0.7rem] font-bold text-slate-600 mb-1">إلى تاريخ</label>
                                <input type="date" x-model="endDate" lang="en" max="{{ date('Y-m-d') }}" class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" dir="ltr">
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <div class="mt-6 flex gap-2 pt-3 border-t border-slate-100">
                <button type="button" @click="showPrintModal = false" class="w-1/3 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">إلغاء</button>
                <button type="button" @click="generatePrint()" class="w-2/3 px-4 py-2.5 text-xs sm:text-sm font-bold text-white bg-slate-800 rounded-xl hover:bg-slate-900 shadow-sm transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>عرض وطباعة التقرير</span>
                </button>
            </div>
        </div>
    </div>
</div>
