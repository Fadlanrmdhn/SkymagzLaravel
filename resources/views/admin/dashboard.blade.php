@extends('admin.templateAdmin.app')

@section('content')
    <!-- Dashboard Content -->
    <div class="p-2">
        <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0">Dashboard</span>
                <div class="d-flex align-items-center">
                    <span>Admin SkyMagz</span>
                </div>
            </div>
        </nav>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2>{{ $userCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Magazines</h5>
                        <h2>{{ $magazineCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <h2>{{ $orderCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Categories</h5>
                        <h2>{{ $categoryCount }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Orders Over Time</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>User Registrations</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Magazines by Category</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoriesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">Manage Users</a>
                            <a href="{{ route('admin.magazines.index') }}" class="btn btn-outline-success">Manage Magazines</a>
                            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-info">Manage Books</a>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-warning">Manage Categories</a>
                            <a href="{{ route('admin.promos.index') }}" class="btn btn-outline-secondary">Manage Promos</a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">View Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Orders',
                    data: @json($orderCounts),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Users Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'New Users',
                    data: @json($userCounts),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Categories Chart
        const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
        new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryData),
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });
    });
</script>
@endpush

