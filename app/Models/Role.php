<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * All system permissions grouped by module.
     */
    public static function allPermissions(): array
    {
        return [
            'الرئيسية والتقارير' => [
                'dashboard.view' => 'عرض لوحة المعلومات (الرئيسية)',
                'reports.view' => 'عرض التقارير والإحصائيات',
                'debts.view' => 'عرض تقارير المديونيات',
            ],
            'المبيعات' => [
                'sales.view' => 'عرض فواتير المبيعات',
                'sales.create' => 'إنشاء فاتورة مبيعات',
                'sales.update' => 'تعديل فواتير المبيعات',
                'sales.delete' => 'حذف فواتير المبيعات',
            ],
            'المشتريات' => [
                'purchases.view' => 'عرض فواتير المشتريات',
                'purchases.create' => 'إنشاء فاتورة مشتريات',
                'purchases.update' => 'تعديل فواتير المشتريات',
                'purchases.delete' => 'حذف فواتير المشتريات',
            ],
            'الأصناف والمخزون' => [
                'products.view' => 'عرض الأصناف والمخزون',
                'products.create' => 'إضافة صنف جديد',
                'products.update' => 'تعديل بيانات الصنف',
                'products.delete' => 'حذف صنف من المخزن',
            ],
            'العملاء' => [
                'customers.view' => 'عرض العملاء وكشف الحساب',
                'customers.create' => 'إضافة عميل جديد',
                'customers.update' => 'تعديل بيانات العميل',
                'customers.delete' => 'حذف عميل',
            ],
            'الموردين' => [
                'suppliers.view' => 'عرض الموردين وكشف الحساب',
                'suppliers.create' => 'إضافة مورد جديد',
                'suppliers.update' => 'تعديل بيانات المورد',
                'suppliers.delete' => 'حذف مورد',
            ],
            'الإدارة وفريق العمل' => [
                'settings.manage' => 'إدارة إعدادات النظام',
                'users.view' => 'عرض المستخدمين وفريق العمل',
                'users.create' => 'إضافة مستخدم جديد',
                'users.update' => 'تعديل بيانات المستخدم',
                'users.delete' => 'حذف مستخدم',
                'roles.view' => 'عرض الأدوار والصلاحيات',
                'roles.create' => 'إضافة دور جديد',
                'roles.update' => 'تعديل الأدوار والصلاحيات',
                'roles.delete' => 'حذف دور',
            ],
        ];
    }

    /**
     * Get a flat key-value list of all permissions with Arabic translation labels.
     */
    public static function flatPermissions(): array
    {
        $flat = [];
        foreach (static::allPermissions() as $perms) {
            foreach ($perms as $key => $label) {
                $flat[$key] = $label;
            }
        }
        return $flat;
    }

    /**
     * Get translated permissions for this role instance.
     */
    public function getTranslatedPermissionsAttribute(): array
    {
        $flat = static::flatPermissions();
        $translated = [];
        foreach ($this->permissions ?? [] as $perm) {
            $translated[$perm] = $flat[$perm] ?? $perm;
        }
        return $translated;
    }
}
