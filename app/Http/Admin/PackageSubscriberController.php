<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class PackageSubscriberController extends Controller
{
    public function index()
    {
        $users = User::with(['package'])
            ->whereNotNull('package_id')
            ->latest()
            ->paginate(15);

        return view('admin.packages.subscribers', compact('users'));
    }
}
