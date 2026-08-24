<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['users' => function($q) {
            $q->where('email', '!=', 'admin@gmail.com');
        }])->get();
        return view('roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name', 'regex:/^(?!\d+$).+$/u'],
            'permissions' => 'nullable|array',
        ], [
            'name.required' => 'يرجى إدخال اسم الدور',
            'name.regex' => 'اسم الدور يجب ألا يتكون من أرقام فقط',
        ]);

        Role::create([
            'name' => $request->name,
            'permissions' => $request->permissions ?? [],
        ]);

        return back()->with('success', 'تم إضافة الدور بنجاح');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id, 'regex:/^(?!\d+$).+$/u'],
            'permissions' => 'nullable|array',
        ], [
            'name.required' => 'يرجى إدخال اسم الدور',
            'name.regex' => 'اسم الدور يجب ألا يتكون من أرقام فقط',
        ]);

        $role->update([
            'name' => $request->name,
            'permissions' => $request->permissions ?? [],
        ]);

        return back()->with('success', 'تم تعديل الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف هذا الدور لوجود مستخدمين مرتبطين به.');
        }

        $role->delete();
        return back()->with('success', 'تم حذف الدور بنجاح');
    }
}
