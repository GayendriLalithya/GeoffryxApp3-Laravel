<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TabController extends Controller
{
    public function showRequests()
    {
        return view('partials.admin.request');
    }
}
