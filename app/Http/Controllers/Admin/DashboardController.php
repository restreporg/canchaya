<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCourts       = Court::count();
        $totalReservations = Reservation::count();
        $totalPayments     = Payment::where('status', 'pagado')->sum('amount');
        $totalClients      = User::where('role', 'client')->count();

        $reservationsByStatus = Reservation::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentReservations = Reservation::with(['user', 'court'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCourts',
            'totalReservations',
            'totalPayments',
            'totalClients',
            'reservationsByStatus',
            'recentReservations'
        ));
    }
}