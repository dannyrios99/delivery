<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard');
    }
}
