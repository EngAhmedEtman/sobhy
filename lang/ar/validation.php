<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => 'يجب قبول :attribute.',
    'active_url' => ':attribute ليس عنوان URL صالح.',
    'after' => 'يجب أن يكون :attribute تاريخاً بعد :date.',
    'alpha' => 'يجب أن يحتوي :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي :attribute على أحرف وأرقام وشرطات فقط.',
    'alpha_num' => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',
    'before' => 'يجب أن يكون :attribute تاريخاً قبل :date.',
    'between' => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم ملف :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون طول نص :attribute بين :min و :max حرف.',
        'array' => 'يجب أن يحتوي :attribute على عدد عناصر بين :min و :max.',
    ],
    'boolean' => 'يجب أن تكون قيمة :attribute إما true أو false.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'date' => ':attribute ليس تاريخاً صحيحاً.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح.',
    'exists' => 'القيمة المحددة لـ :attribute غير صالحة.',
    'max' => [
        'numeric' => 'يجب ألا تزيد قيمة :attribute عن :max.',
        'file' => 'يجب ألا يزيد حجم ملف :attribute عن :max كيلوبايت.',
        'string' => 'يجب ألا يزيد طول نص :attribute عن :max حرف.',
        'array' => 'يجب ألا يحتوي :attribute على أكثر من :max عنصر.',
    ],
    'min' => [
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
        'file' => 'يجب أن يكون حجم ملف :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يحتوي نص :attribute على الأقل على :min حروف.',
        'array' => 'يجب أن يحتوي :attribute على الأقل على :min عناصر.',
    ],
    'numeric' => 'يجب أن يكون :attribute رقماً.',
    'required' => 'حقل :attribute مطلوب.',
    'unique' => 'قيمة :attribute مستخدمة من قبل.',

    'attributes' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'phone' => 'رقم الهاتف',
        'amount' => 'المبلغ',
        'quantity' => 'الكمية',
        'unit_price' => 'سعر الوحدة',
        'notes' => 'الملاحظات',
        'date' => 'التاريخ',
        'role_id' => 'الدور والصلاحية',
    ],

];
