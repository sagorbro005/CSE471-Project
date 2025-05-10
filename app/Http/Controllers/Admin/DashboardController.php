<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Orders (excluding prescription orders)
        $totalOrders = Order::whereDoesntHave('prescriptions')->count();
        // Total Users (all users)
        $totalUsers = User::count();
        // Pending Prescriptions
        $pendingPrescriptions = Prescription::where('status', 'pending')->count();
        // Total Revenue (sum of total for delivered orders only)
        $totalRevenue = Order::where('status', 'Delivered')->sum('total');
        // Recent Orders (latest 7, excluding prescription orders)
        $recentOrders = Order::with('user')
            ->whereDoesntHave('prescriptions') // Exclude prescription orders
            ->orderByDesc('created_at')
            ->take(7)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'user' => [
                        'name' => $order->user->name ?? '',
                        'email' => $order->user->email ?? '',
                    ],
                    'status' => $order->status,
                    'total' => $order->total,
                    'date' => $order->created_at->format('M d, Y'),
                ];
            });

        return response()->json([
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'pendingPrescriptions' => $pendingPrescriptions,
            'totalRevenue' => $totalRevenue,
            'recentOrders' => $recentOrders,
        ]);
    }
}
