<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
{
    // Default tab view
    $tabView = 'partials.admin.request'; // Default to "Account Requests"

    // Dynamically set the tabView if a `tab` query parameter is provided
    if ($request->has('tab')) {
        $availableTabs = [
            'requests' => 'partials.admin.request',
            'users' => 'partials.admin.user', // Add the "Manage Users" tab
        ];

        $tabView = $availableTabs[$request->tab] ?? $tabView;
    }

    return view('layouts.dashboard', compact('tabView'));
}

}
