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
                        <h2>{{ $countUser }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Magazines</h5>
                        <h2>{{ $countMagazines }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <h2>{{ $countOrders }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Categories</h5>
                        <h2>{{ $countCategories }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Orders Over Time(Pesanan)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>User Registrations(Terdaftar)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="row mb-4">
            <div class="col-md-6" style="width: 30






            rem;">
                <div class="card">
                    <div class="card-header">
                        <h5>Magazines Category(Kategori Item)</h5>
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
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info">Manage Users</a>
                            <a href="{{ route('admin.magazines.index') }}" class="btn btn-outline-info">Manage
                                Magazines</a>
                            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-info">Manage Books</a>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-info">Manage
                                Categories</a>
                            <a href="{{ route('admin.promos.index') }}" class="btn btn-outline-info">Manage Promos</a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info">View Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let labelMonths = null;
        let dataOrders = null;
        let dataUsers = null;
        let labelCategories = null;
        let dataCategories = null;

        $(function() {

            // ===== AJAX Orders =====
            $.ajax({
                url: "{{ route('admin.charts.orders') }}",
                method: "GET",
                success: function(response) {
                    labelMonths = response.months;
                    dataOrders = response.orderCounts;
                    showOrdersChart();
                },
                error: function() {
                    alert("Gagal mengambil data orders!");
                }
            });

            // ===== AJAX Users =====
            $.ajax({
                url: "{{ route('admin.charts.users') }}",
                method: "GET",
                success: function(response) {
                    dataUsers = response.userCounts;
                    showUsersChart();
                },
                error: function() {
                    alert("Gagal mengambil data users!");
                }
            });

            // ===== AJAX Categories =====
            $.ajax({
                url: "{{ route('admin.charts.categories') }}",
                method: "GET",
                success: function(response) {
                    labelCategories = response.labels;
                    dataCategories = response.data;
                    showCategoriesChart();
                },
                error: function() {
                    alert("Gagal mengambil data categories!");
                }
            });

        });
        // ====== ORDERS CHART =======
        function showOrdersChart() {
            const ctx = document.getElementById('ordersChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelMonths,
                    datasets: [{
                        label: 'Orders',
                        data: dataOrders,
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
        }
        // ======= USERS CHART =======
        function showUsersChart() {
            const ctx = document.getElementById('usersChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelMonths,
                    datasets: [{
                        label: 'New Users',
                        data: dataUsers,
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
        }
        //  ======= CATEGORIES CHART ======
        function showCategoriesChart() {
            const ctx = document.getElementById('categoriesChart');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labelCategories,
                    datasets: [{
                        data: dataCategories,
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
        }
    </script>
@endpush
