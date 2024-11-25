<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        $defaultTab = 'requests';
        $availableTabs = [
            'requests' => [
                'view' => 'partials.admin.request',
                'css' => 'resources/css/request.css',
            ],
            'users' => [
                'view' => 'partials.admin.user',
                'css' => 'resources/css/user.css',
            ],
            'project_requests' => [
                'view' => 'partials.professional.project_request',
                'css' => 'resources/css/project_request.css',
            ],
            'projects' => [
                'view' => 'partials.customer.projects',
                'css' => 'resources/css/projects.css',
            ],
            'professional' => [
                'view' => 'partials.customer.professional',
                'css' => 'resources/css/professional.css',
            ],
            'profile' => [
                'view' => 'partials.common.profile',
                'css' => 'resources/css/profile.css',
            ],
        ];
    
        $tab = $request->get('tab', $defaultTab);
        $tabData = $availableTabs[$tab] ?? $availableTabs[$defaultTab];
    
        return view('layouts.dashboard', [
            'tabView' => $tabData['view'],
            'tabCss' => asset($tabData['css']),
        ]);
    }
}
