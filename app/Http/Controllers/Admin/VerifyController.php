<?php

namespace App\Http\Controllers\Admin;

use App\Models\VerifyRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VerifyController extends Controller
{
    public function showRequests()
    {
        $verifications = DB::table('verify_requests')->get(); // Fetch data from the view

        return view('pages.admin.request', compact('verifications'));
    }
}

