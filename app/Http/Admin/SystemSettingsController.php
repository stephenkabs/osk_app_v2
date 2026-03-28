<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $latestBackup = null;

        try {
            $latestBackup = collect(Storage::disk('spaces')->files('Laravel'))
                ->filter(fn ($file) => str_ends_with($file, '.zip'))
                ->map(function ($file) {
                    return [
                        'name' => basename($file),
                        'path' => $file,
                        'size' => Storage::disk('spaces')->size($file),
                        'last_modified' => Storage::disk('spaces')->lastModified($file),
                        'url' => Storage::disk('spaces')->temporaryUrl($file, now()->addMinutes(10)),
                    ];
                })
                ->sortByDesc('last_modified')
                ->first();
        } catch (\Throwable $e) {
            Log::warning('Unable to load latest backup for system settings.', [
                'error' => $e->getMessage(),
            ]);
        }

        return view('admin.system-settings.index', [
            'settings' => SystemSetting::orderBy('key')->get(),
            'latestBackup' => $latestBackup,
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
