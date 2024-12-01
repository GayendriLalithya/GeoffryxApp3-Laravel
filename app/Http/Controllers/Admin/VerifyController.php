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
        // Fetch pending verification requests
        $verifications = Verify::with('user')->where('status', 'pending')->where('deleted', false)->get();

        // Fetch all certificate records
        $certificates = Certificate::whereIn('verify_id', $pendingRequests->pluck('verify_id'))->get();

        return view('pages.admin.request', compact('verifications', 'certificates'));
    }
}

