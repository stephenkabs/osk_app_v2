<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        // Folder used by Spatie in Spaces
        $backupPath = 'Laravel'; // default Spatie folder

        $files = collect(Storage::disk('spaces')->files($backupPath))
            ->filter(fn ($file) => str_ends_with($file, '.zip'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('spaces')->size($file),
                    'last_modified' => Storage::disk('spaces')->lastModified($file),
                    'url' => Storage::disk('spaces')->temporaryUrl(
                        $file,
                        now()->addMinutes(10) // secure download
                    ),
                ];
            })
            ->sortByDesc('last_modified');

        return view('admin.backups.index', [
            'title' => 'System Backup',
            'backups' => $files,
        ]);
    }

    public function run()
    {
        try {
            Artisan::call('backup:run --only-db');

            return back()->with('success', 'Database backup completed successfully.');
        } catch (\Throwable $e) {

            Log::error('Backup failed', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Backup failed. Check logs.');
        }
    }
}
