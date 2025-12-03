<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Magazine;
use App\Models\Order;
use App\Models\Category;
use App\Models\Promo;

class DashboardController extends Controller
{
    public function index()
    {
        // Data for charts
        $userCount = User::count();
        $magazineCount = Magazine::count();
        $orderCount = Order::count();
        $categoryCount = Category::count();
        $promoCount = Promo::count();

        // Monthly orders data for chart (last 12 months)
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Prepare data for Chart.js
        $months = [];
        $orderCounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            $count = $monthlyOrders->where('month', $date->month)->where('year', $date->year)->first()->count ?? 0;
            $months[] = $month;
            $orderCounts[] = $count;
        }

        // User registration data (last 12 months)
        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $userCounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = $monthlyUsers->where('month', $date->month)->where('year', $date->year)->first()->count ?? 0;
            $userCounts[] = $count;
        }

        // Top categories by magazine count
        $topCategories = Category::withCount('magazines')->orderBy('magazines_count', 'desc')->take(5)->get();

        $categoryLabels = $topCategories->pluck('name');
        $categoryData = $topCategories->pluck('magazines_count');

        return view('admin.dashboard', compact(
            'userCount',
            'magazineCount',
            'orderCount',
            'categoryCount',
            'promoCount',
            'months',
            'orderCounts',
            'userCounts',
            'categoryLabels',
            'categoryData'
        ));
    }
}
