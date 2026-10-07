<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        if (array_key_exists('company_phone', $data)) {
            Setting::set('phone', $data['company_phone']);
        }

        if (array_key_exists('company_address', $data)) {
            Setting::set('address', $data['company_address']);
        }

        \Illuminate\Support\Facades\Cache::flush();

        return back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
