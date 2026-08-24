<div class="grid grid-cols-1 gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4 sm:grid-cols-2">
    <div>
        <p class="mb-1 text-xs text-slate-500">نوع العملية</p>
        <p class="font-black text-primary-700" x-text="transactionDetails.type_label"></p>
    </div>
    <div>
        <p class="mb-1 text-xs text-slate-500">الطرف الثاني</p>
        <p class="font-bold text-slate-800" x-text="transactionDetails.party_name"></p>
    </div>
    <div>
        <p class="mb-1 text-xs text-slate-500">التاريخ</p>
        <p class="font-bold text-slate-800" dir="ltr" x-text="transactionDetails.transaction_date"></p>
    </div>
    <div x-show="transactionDetails.product_name">
        <p class="mb-1 text-xs text-slate-500">الصنف</p>
        <p class="font-bold text-slate-800" x-text="transactionDetails.product_name"></p>
    </div>
</div>

<div class="space-y-3 rounded-xl border border-emerald-100 bg-emerald-50/60 p-4">
    <h4 class="text-sm font-black text-emerald-800">الملخص المالي</h4>
    <div class="flex items-center justify-between border-b border-emerald-100 pb-3">
        <span class="text-xs font-bold text-slate-600">قيمة العملية</span>
        <span class="text-base font-black text-emerald-700" dir="ltr" x-text="Number(transactionDetails.amount || 0).toLocaleString() + ' ج.م'"></span>
    </div>
    <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-slate-600">الرصيد بعد العملية</span>
        <span class="text-sm font-black text-slate-800" dir="ltr" x-text="Number(transactionDetails.balance_after || 0).toLocaleString() + ' ج.م'"></span>
    </div>
</div>

<div x-show="transactionDetails.notes" class="rounded-xl border border-slate-100 bg-slate-50 p-4">
    <p class="mb-1 text-xs font-bold text-slate-500">ملاحظات</p>
    <p class="whitespace-pre-wrap text-sm text-slate-700" x-text="transactionDetails.notes"></p>
</div>
