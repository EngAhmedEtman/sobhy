<!-- Print Report Modal -->
<div x-data="{ 
        showPrintReportModal: false, 
        startDate: '', 
        endDate: '',
        filterType: 'date_range',
        initModal() {
            let startInput = document.querySelector('input[name=start_date]');
            let endInput = document.querySelector('input[name=end_date]');
            let filterInput = document.querySelector('select[name=filter_type]');
            
            if(startInput) this.startDate = startInput.value;
            if(endInput) this.endDate = endInput.value;
            if(filterInput) this.filterType = filterInput.value;
            
            this.showPrintReportModal = true;
        },
        executePrint() {
            this.showPrintReportModal = false;
            
            // Build URL based on current form inputs
            let form = document.querySelector('#filterForm');
            let params = new URLSearchParams();
            if (form) {
                let formData = new FormData(form);
                for (let pair of formData.entries()) {
                    params.append(pair[0], pair[1]);
                }
            }
            
            // Override dates with modal values
            if(this.startDate) params.set('start_date', this.startDate);
            else params.delete('start_date');
            
            if(this.endDate) params.set('end_date', this.endDate);
            else params.delete('end_date');
            
            params.set('print_mode', '1');
            
            let url = window.location.pathname + '?' + params.toString();
            openPrintPreviewModal('printPreviewModal', url);
        }
    }" 
    @open-print-report.window="initModal()"
    x-show="showPrintReportModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
    
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center sm:p-0">
        <div x-show="showPrintReportModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50" @click="showPrintReportModal = false"></div>
        <div x-show="showPrintReportModal" x-transition class="relative w-full max-w-md p-5 sm:p-6 text-right transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">طباعة التقرير</h3>
                <button @click="showPrintReportModal = false" class="text-slate-400 hover:text-slate-600 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>

            <div class="space-y-4 text-slate-700" x-show="filterType !== 'since_last_zero'">
                <p class="text-sm font-medium mb-3 text-slate-500">حدد الفترة التي ترغب في طباعة التقرير لها:</p>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">من تاريخ</label>
                    <input type="date" x-model="startDate" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">إلى تاريخ</label>
                    <input type="date" x-model="endDate" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all text-sm">
                </div>
            </div>
            
            <div class="space-y-4 text-slate-700" x-show="filterType === 'since_last_zero'" style="display: none;">
                <div class="p-4 bg-primary-50 border border-primary-100 rounded-xl text-primary-700 text-sm">
                    <svg class="w-5 h-5 mb-2 text-primary-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    سيتم طباعة كشف الحساب وعرض العمليات بدءاً من آخر مرة كان فيها رصيد الحساب صفراً وحتى اليوم.
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button @click="showPrintReportModal = false" type="button" class="px-4 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">إلغاء</button>
                <button @click="executePrint()" type="button" class="px-4 py-2 text-sm font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    عرض ومعاينة الطباعة
                </button>
            </div>
        </div>
    </div>
</div>
