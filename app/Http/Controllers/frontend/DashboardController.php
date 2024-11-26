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
                'view' => 'pages.admin.request',
                'css' => 'resources/css/request.css',
            ],
            'users' => [
                'view' => 'pages.admin.user',
                'css' => 'resources/css/user.css',
            ],
            'project_requests' => [
                'view' => 'pages.professional.project_request',
                'css' => 'resources/css/project_request.css',
            ],
            'manage_projects' => [
                'view' => 'pages.professional.manage_projects',
                'css' => 'resources/css/projects.css',
            ],
            'projects' => [
                'view' => 'pages.customer.projects',
                'css' => 'resources/css/projects.css',
            ],
            'professional' => [
                'view' => 'pages.customer.professional',
                'css' => 'resources/css/professional.css',
            ],
            'profile' => [
                'view' => 'pages.common.profile',
                'css' => 'resources/css/profile.css',
            ],
            'notification' => [
                'view' => 'pages.common.notification',
                'css' => 'resources/css/notification.css',
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
