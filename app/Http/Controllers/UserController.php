<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Hide developer email from normal display in users management
        $users = User::where('email', '!=', 'admin@gmail.com')->with('role')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return back()->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function update(Request $request, User $user)
    {
        if ($user->email === 'admin@gmail.com' && auth()->user()->email !== 'admin@gmail.com') {
            return back()->with('error', 'غير مصرح بتعديل هذا الحساب.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'تم تعديل المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->email === 'admin@gmail.com') {
            return back()->with('error', 'لا يمكن حذف حساب التطوير الرئيسي.');
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'لا يمكن حذف الحساب الحالي الذي تستخدمه.');
        }

        $user->delete();
        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }
}
