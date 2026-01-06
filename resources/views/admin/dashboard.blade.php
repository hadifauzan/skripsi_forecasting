@extends('layouts.admin.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Dashboard Admin</h1>
            <p class="text-sm text-gray-600">Ringkasan data dan statistik sistem</p>
        </div>

        <!-- Summary Cards Row 1: Affiliates & Resellers -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Total Affiliates -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-4 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-purple-100 text-xs font-medium">Total Affiliator</p>
                        <h3 class="text-2xl font-bold mt-0.5">{{ number_format($totalAffiliates) }}</h3>
                    </div>
                    <div class="bg-white rounded-lg p-2">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center text-xs">
                    <span class="text-purple-100">Aktif: {{ number_format($activeAffiliates) }} | Pending: {{ number_format($pendingAffiliates) }}</span>
                </div>
            </div>

            <!-- Total Resellers -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-4 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-blue-100 text-xs font-medium">Total Reseller</p>
                        <h3 class="text-2xl font-bold mt-0.5">{{ number_format($totalResellers) }}</h3>
                    </div>
                    <div class="bg-white rounded-lg p-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center text-xs">
                    <span class="text-blue-100">Aktif: {{ number_format($activeResellers) }} | Pending: {{ number_format($pendingResellers) }}</span>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-4 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-green-100 text-xs font-medium">Total Customer</p>
                        <h3 class="text-2xl font-bold mt-0.5">{{ number_format($totalCustomers) }}</h3>
                    </div>
                    <div class="bg-white rounded-lg p-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center text-xs">
                    <span class="text-green-100">Aktif: {{ number_format($activeCustomers) }}</span>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-md p-4 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-orange-100 text-xs font-medium">Total Produk</p>
                        <h3 class="text-2xl font-bold mt-0.5">{{ number_format($totalProducts) }}</h3>
                    </div>
                    <div class="bg-white rounded-lg p-2">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center text-xs">
                    <span class="text-orange-100">Aktif: {{ number_format($activeProducts) }}</span>
                </div>
            </div>
        </div>

        <!-- Summary Cards Row 2: Orders & Revenue -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Total Orders -->
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 text-xs font-medium">Total Pesanan</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalOrders) }}</h3>
                    </div>
                    <div class="bg-indigo-100 rounded-lg p-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Pending: {{ number_format($pendingOrders) }} | Processing: {{ number_format($processingOrders) }}
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 text-xs font-medium">Total Pendapatan</p>
                        <h3 class="text-xl font-bold text-gray-900 mt-0.5">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-green-100 rounded-lg p-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Bulan ini: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                </div>
            </div>

            <!-- Total Sales Transactions -->
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 text-xs font-medium">Total Transaksi</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalSales) }}</h3>
                    </div>
                    <div class="bg-yellow-100 rounded-lg p-2">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Bulan ini: {{ number_format($monthlySales) }} transaksi
                </div>
            </div>

            <!-- Affiliate Submissions -->
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 text-xs font-medium">Pengajuan Affiliate</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalSubmissions) }}</h3>
                    </div>
                    <div class="bg-pink-100 rounded-lg p-2">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Pending: {{ number_format($pendingSubmissions) }} | Approved: {{ number_format($approvedSubmissions) }}
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <!-- Affiliates Status Chart -->
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Status Affiliator</h3>
                <div class="relative" style="height: 280px;">
                    <canvas id="affiliatesChart"></canvas>
                </div>
            </div>

            <!-- Resellers Status Chart -->
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Status Reseller</h3>
                <div class="relative" style="height: 280px;">
                    <canvas id="resellersChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <!-- Orders Status Chart -->
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Status Pesanan</h3>
                <div class="relative" style="height: 280px;">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

            <!-- Submissions Status Chart -->
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Status Pengajuan Affiliate</h3>
                <div class="relative" style="height: 280px;">
                    <canvas id="submissionsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Trends Chart -->
        <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200 mb-4">
            <h3 class="text-base font-semibold text-gray-900 mb-3">Tren Bulanan (6 Bulan Terakhir)</h3>
            <div class="relative" style="height: 260px;">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
            <h3 class="text-base font-semibold text-gray-900 mb-3">Tren Pendapatan (6 Bulan Terakhir)</h3>
            <div class="relative" style="height: 260px;">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    // Affiliates Status Pie Chart
    const affiliatesCtx = document.getElementById('affiliatesChart').getContext('2d');
    new Chart(affiliatesCtx, {
        type: 'pie',
        data: {
            labels: ['Aktif', 'Pending', 'Nonaktif'],
            datasets: [{
                data: [{{ $activeAffiliates }}, {{ $pendingAffiliates }}, {{ $inactiveAffiliates }}],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 8,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Resellers Status Pie Chart
    const resellersCtx = document.getElementById('resellersChart').getContext('2d');
    new Chart(resellersCtx, {
        type: 'pie',
        data: {
            labels: ['Aktif', 'Pending', 'Nonaktif'],
            datasets: [{
                data: [{{ $activeResellers }}, {{ $pendingResellers }}, {{ $inactiveResellers }}],
                backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 8,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Orders Status Bar Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
            datasets: [{
                label: 'Jumlah Pesanan',
                data: [{{ $pendingOrders }}, {{ $processingOrders }}, {{ $shippedOrders }}, {{ $deliveredOrders }}, {{ $cancelledOrders }}],
                backgroundColor: ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

    // Submissions Status Doughnut Chart
    const submissionsCtx = document.getElementById('submissionsChart').getContext('2d');
    new Chart(submissionsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Shipped', 'Received', 'Rejected'],
            datasets: [{
                data: [{{ $pendingSubmissions }}, {{ $approvedSubmissions }}, {{ $shippedSubmissions }}, {{ $receivedSubmissions }}, {{ $rejectedSubmissions }}],
                backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ef4444'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 8,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Monthly Trend Line Chart
    const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    new Chart(monthlyTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
            datasets: [
                {
                    label: 'Affiliator',
                    data: {!! json_encode(array_column($monthlyData, 'affiliates')) !!},
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'Reseller',
                    data: {!! json_encode(array_column($monthlyData, 'resellers')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'Pesanan',
                    data: {!! json_encode(array_column($monthlyData, 'orders')) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 8,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

    // Revenue Trend Chart
    const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
    new Chart(revenueTrendCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode(array_column($monthlyData, 'revenue')) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: '#10b981',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
