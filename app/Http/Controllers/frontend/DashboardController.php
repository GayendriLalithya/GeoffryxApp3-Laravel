<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        $defaultTab = 'profile';
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
            'work_history' => [
                'view' => 'pages.professional.work_history',
                'css' => 'resources/css/work_history.css',
            ],
            'projects' => [
                'view' => 'pages.customer.projects',
                'css' => 'resources/css/projects.css',
            ],
            'professional' => [
                'view' => 'pages.customer.professional',
                'css' => 'resources/css/professional.css',
            ],
            'finance' => [
                'view' => 'pages.customer.finance',
                'css' => 'resources/css/finance.css',
            ],
            'profile' => [
                'view' => 'pages.common.profile',
                'css' => 'resources/css/profile.css',
            ],
            'notification' => [
                'view' => 'pages.common.notification',
                'css' => 'resources/css/notification.css',
            ],
            'home' => [
                'view' => 'pages.common.home',
                'css' => 'resources/css/home.css',
            ],
        ];
    
        $tab = $request->get('tab', $defaultTab);
        $tabData = $availableTabs[$tab] ?? $availableTabs[$defaultTab];
    
        // return view('layouts.dashboard', [
        //     'tabView' => $tabData['view'],
        //     'tabCss' => asset($tabData['css']),
        // ]);

        // Project data for 'professional' tab
        $projectData = null;
        if ($tab === 'professional') {
            $projectData = [
                'name' => $request->get('name'),
                'location' => $request->get('location'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'budget' => $request->get('budget'),
                'requirements' => $request->get('requirements'),
            ];
        }

        // Load all professionals using a stored procedure
        $professionals = [];
        if ($tab === 'professional') {
            $professionals = DB::select('CALL LoadAllProfessionals()');
        }

        // Return the dashboard view
        return view('layouts.dashboard', [
            'tab' => $tab,
            'tabView' => $tabData['view'],
            'tabCss' => asset($tabData['css']),
            'projectData' => $projectData,
            'professionals' => $professionals,
        ]);
    }
}
