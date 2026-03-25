<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $showGoogleCalendarModal = $user && !$user->hasGoogleCalendarConnected();

        return view('dashboard.dashboard', compact('showGoogleCalendarModal'));
    }

}
