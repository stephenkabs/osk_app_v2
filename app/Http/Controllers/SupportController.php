<?php

namespace App\Http\Controllers;

class SupportController extends Controller
{
    /**
     * Show system help & support page
     */
    public function index()
    {
        return view('support.index');
    }
}
