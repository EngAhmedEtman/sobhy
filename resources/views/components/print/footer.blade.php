<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'preparedBy' => null,
    'approvedBy' => null,
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
    'preparedBy' => null,
    'approvedBy' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="report-footer-wrapper" style="margin-top: 20px; padding-top: 10px; border-top: 1.5px solid #0f172a; page-break-inside: avoid; font-family: 'Cairo', sans-serif;">
    <!-- Signatures Section -->
    <div class="signatures-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; text-align: center; margin-bottom: 12px;">
        <div class="signature-box" style="padding: 6px; border: 1px dashed #cbd5e1; border-radius: 4px;">
            <div class="sig-title" style="font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 18px;">إعداد وتجهيز:</div>
            <div class="sig-line" style="border-bottom: 1px solid #94a3b8; width: 80%; margin: 0 auto 4px auto;"></div>
            <div class="sig-name" style="font-size: 10px; color: #64748b;"><?php echo e($preparedBy ?? auth()->user()?->name ?? '.......................'); ?></div>
        </div>

        <div class="signature-box" style="padding: 6px; border: 1px dashed #cbd5e1; border-radius: 4px;">
            <div class="sig-title" style="font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 18px;">مراجعة وحسابات:</div>
            <div class="sig-line" style="border-bottom: 1px solid #94a3b8; width: 80%; margin: 0 auto 4px auto;"></div>
            <div class="sig-name" style="font-size: 10px; color: #64748b;">.......................</div>
        </div>

        <div class="signature-box" style="padding: 6px; border: 1px dashed #cbd5e1; border-radius: 4px;">
            <div class="sig-title" style="font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 18px;">اعتماد وتوقيع:</div>
            <div class="sig-line" style="border-bottom: 1px solid #94a3b8; width: 80%; margin: 0 auto 4px auto;"></div>
            <div class="sig-name" style="font-size: 10px; color: #64748b;"><?php echo e($approvedBy ?? '.......................'); ?></div>
        </div>
    </div>

    <!-- Legal Disclaimer & Footer Line -->
    <div class="footer-legal-bar" style="display: flex; align-items: center; justify-content: space-between; font-size: 10px; color: #64748b; padding-top: 6px; border-top: 1.5px solid #e2e8f0;">
        <div class="legal-text">
            * هذا المستند يمثل وثيقة رسمية صادرة من النظام وتعتبر لاغية في حال وجود أي كشط أو تعديل يدوي عليها.
        </div>
        <div class="footer-brand font-bold" style="font-weight: bold;">
            <?php echo e(\App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا')); ?> | ERP System
        </div>
    </div>
</div>
