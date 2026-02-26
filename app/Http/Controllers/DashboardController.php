<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership();
        $colocation = $activeMembership?->colocation;

        $stats = [
            'to_pay' => 0,
            'to_receive' => 0,
        ];

        if ($colocation) {
            $stats['to_pay'] = $colocation->settlements()
                ->where('from_user_id', $user->id)
                ->where('is_paid', false)
                ->sum('amount');

            $stats['to_receive'] = $colocation->settlements()
                ->where('to_user_id', $user->id)
                ->where('is_paid', false)
                ->sum('amount');
        }

        return view('dashboard', compact('user', 'activeMembership', 'colocation', 'stats'));
    }
}
