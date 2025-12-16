<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Magazine;
use App\Models\Order;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $countUser = User::count();
        $countMagazines = Magazine::count();
        $countOrders = Order::count();
        $countCategories = Category::count();
        return view('admin.dashboard', compact('countUser', 'countMagazines', 'countOrders', 'countCategories'));
    }
    public function ordersChartData()
    {
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')->where('created_at', '>=', now()->subMonths(12))->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();
        $months = [];
        $orderCounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $count = $monthlyOrders->where('month', $date->month)->where('year', $date->year)->first()->count ?? 0;
            $orderCounts[] = $count;
        }
        return response()->json(['months' => $months, 'orderCounts' => $orderCounts]);
    }
    public function usersChartData()
    {
        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')->where('created_at', '>=', now()->subMonths(12))->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();
        $userCounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = $monthlyUsers->where('month', $date->month)->where('year', $date->year)->first()->count ?? 0;
            $userCounts[] = $count;
        }
        return response()->json(['userCounts' => $userCounts]);
    }
    public function categoriesChartData()
    {
        $categories = Category::withCount('magazines')->get();
        $labels = $categories->pluck('name');
        $data = $categories->pluck('magazines_count');
        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}
