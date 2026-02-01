<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index()
    {

        return view('admin.system-settings.index', [
            'settings' => SystemSetting::orderBy('key')->get()
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $id => $value) {
            $setting = SystemSetting::find($id);
            if (!$setting) continue;

            $setting->update([
                'value' => $setting->type === 'boolean'
                    ? ($value ? 'true' : 'false')
                    : $value
            ]);

            SystemSetting::clearCache($setting->key);
        }

        return back()->with('success', 'System settings updated successfully.');
    }
}
