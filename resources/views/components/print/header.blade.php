@props([
    'title' => 'تقرير النظام',
    'subtitle' => null,
    'user' => null,
    'referenceCode' => null,
])

<div class="report-header-wrapper" style="padding-bottom: 8px; margin-bottom: 12px; border-bottom: 2px solid #0f172a; font-family: 'Cairo', sans-serif;">
    <div class="report-header-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; gap: 10px;">
        
        <!-- Right: Company Branding & Details -->
        <div class="header-brand-section" style="display: flex; align-items: center; gap: 8px; border-left: 1px solid #e2e8f0; padding-left: 8px;">
            <div class="brand-text-info">
                <div class="company-name" style="font-size: 14px; font-weight: 800; color: #0f172a;">{{ \App\Models\Setting::get('company_name', 'مؤسسة صبحي رضا') }}</div>
                
                @php
                    $commercialRegister = \App\Models\Setting::get('commercial_register');
                    $taxNumber = \App\Models\Setting::get('tax_number');
                    $phone = \App\Models\Setting::get('phone');
                @endphp

                @if($commercialRegister && $commercialRegister !== '---' && trim($commercialRegister) !== '')
                    <div class="company-sub" style="font-size: 10px; color: #475569; margin-top: 1px;">سجل تجاري: {{ $commercialRegister }}</div>
                @endif

                @if($taxNumber && $taxNumber !== '---' && trim($taxNumber) !== '')
                    <div class="company-sub" style="font-size: 10px; color: #475569; margin-top: 1px;">رقم ضريبي: {{ $taxNumber }}</div>
                @endif

                @if($phone && $phone !== '---' && trim($phone) !== '')
                    <div class="company-sub" style="font-size: 10px; color: #475569; margin-top: 1px;" dir="ltr">هاتف: {{ $phone }}</div>
                @endif
            </div>
        </div>

        <!-- Center: Report Title & Scope -->
        <div class="header-title-section" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
            <h1 class="report-main-title" style="font-size: 18px; font-weight: 900; color: #0f172a; margin: 0 auto 3px auto;">{{ $title }}</h1>
            @if($subtitle)
                <div class="report-subtitle-badge" style="display: inline-block; padding: 2px 8px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 11px; font-weight: 700; color: #334155; margin: 0 auto;">{{ $subtitle }}</div>
            @endif
        </div>

        <!-- Left: Extraction Metadata -->
        <div class="header-meta-section" style="text-align: left; direction: rtl; font-size: 10px; border-right: 1px solid #e2e8f0; padding-right: 8px;">
            <div class="meta-item" style="display: flex; justify-content: space-between; gap: 6px; margin-bottom: 2px;">
                <span class="meta-label" style="color: #64748b; font-weight: 600;">تاريخ الاستخراج:</span>
                <span class="meta-value font-mono dir-ltr" style="color: #0f172a; direction: ltr; display: inline-block;">{{ now()->format('Y-m-d H:i') }}</span>
            </div>
            <div class="meta-item" style="display: flex; justify-content: space-between; gap: 6px; margin-bottom: 2px;">
                <span class="meta-label" style="color: #64748b; font-weight: 600;">مستخرج التقرير:</span>
                <span class="meta-value font-bold" style="color: #0f172a;">{{ auth()->user()?->name ?? 'مدير النظام' }}</span>
            </div>
            <div class="meta-item" style="display: flex; justify-content: space-between; gap: 6px; margin-bottom: 2px;">
                <span class="meta-label" style="color: #64748b; font-weight: 600;">رقم المرجع:</span>
                <span class="meta-value font-mono dir-ltr" style="color: #0f172a; direction: ltr; display: inline-block;">{{ $referenceCode ?? ('REP-' . strtoupper(substr(md5(now()->timestamp), 0, 8))) }}</span>
            </div>
        </div>
    </div>
</div>
