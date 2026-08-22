<x-layouts.app title="إعدادات النظام">
    <x-slot:breadcrumb>إعدادات النظام</x-slot:breadcrumb>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Header -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-800">إعدادات النظام والطباعة</h2>
                    <p class="text-xs text-slate-400">تعديل بيانات المؤسسة التي تظهر في الفواتير والتقارير المطبوعة.</p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <!-- Company Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم المؤسسة / الشركة (يظهر في الهيدر والفواتير)</label>
                        <input type="text" name="company_name" value="{{ \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا لتجارة الخردة') }}" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 bg-white font-semibold">
                    </div>

                    <!-- Company Phone -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهاتف للتواصل (يظهر في الفواتير)</label>
                        <input type="text" name="phone" value="{{ \App\Models\Setting::get('phone', \App\Models\Setting::get('company_phone', '01070191977')) }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 bg-white text-left font-semibold" dir="ltr" placeholder="01xxxxxxxxx">
                    </div>

                    <!-- Company Address -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">عنوان المؤسسة / المقر (يظهر في الفواتير)</label>
                        <input type="text" name="address" value="{{ \App\Models\Setting::get('address', \App\Models\Setting::get('company_address', '')) }}" placeholder="مثال: الشرقية - منيا القمح" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 bg-white font-semibold">
                    </div>

                    <!-- Invoice Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات وشروط أسفل الفاتورة (اختياري)</label>
                        <textarea name="invoice_notes" rows="3" placeholder="ملاحظات تظهر في أسفل الفاتورة المطبوعة..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 bg-white leading-relaxed">{{ \App\Models\Setting::get('invoice_notes', '') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-all shadow-sm shadow-primary-600/20 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span>حفظ الإعدادات</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
