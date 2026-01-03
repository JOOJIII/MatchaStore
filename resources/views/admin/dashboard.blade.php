{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard - Matcha Store')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="text-muted">
            <i class="fas fa-calendar me-1"></i> {{ now()->format('l, d F Y') }}
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row">
        <!-- Total Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-primary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_orders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_orders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-danger h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Low Stock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Stats Row -->
    <div class="row">
        <!-- Total Customers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-info h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_customers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Feedbacks Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-secondary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                New Feedbacks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['new_feedbacks'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-dark h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Featured Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Product::where('is_featured', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-1"></i> Recent Orders
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($order->status == 'pending') bg-warning
                                            @elseif($order->status == 'processing') bg-info
                                            @elseif($order->status == 'completed') bg-success
                                            @else bg-danger
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                        No orders yet
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('admin.orders') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> View All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Feedbacks -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-comments me-1"></i> Recent Feedbacks
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentFeedbacks as $feedback)
                                <tr>
                                    <td>{{ $feedback->name }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($feedback->subject, 25) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($feedback->status == 'new') bg-primary
                                            @elseif($feedback->status == 'read') bg-info
                                            @elseif($feedback->status == 'replied') bg-success
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($feedback->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $feedback->created_at->format('d M') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-comments fa-2x mb-2"></i><br>
                                        No feedbacks yet
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('admin.feedbacks') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> View All Feedbacks
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box me-1"></i> Recent Products
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded me-3"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold">
                                                    <a href="{{ route('admin.products.show', $product) }}" 
                                                       class="text-decoration-none text-dark">
                                                        {{ \Illuminate\Support\Str::limit($product->name, 30) }}
                                                    </a>
                                                </div>
                                                <small class="text-muted">ID: {{ $product->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst(str_replace('_', ' ', $product->category)) }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->stock > 10 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $product->stock }} units
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->is_featured)
                                            <span class="badge bg-success">
                                                <i class="fas fa-star me-1"></i> Featured
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Regular</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.products.show', $product) }}" 
                                               class="btn btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                        No products yet. <a href="{{ route('admin.products.create') }}">Add your first product</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-1"></i> View All Products
                        </a>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Add New Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-primary { color: #4e73df !important; }
    .text-warning { color: #f6c23e !important; }
    .text-success { color: #1cc88a !important; }
    .text-danger { color: #e74a3b !important; }
    .text-info { color: #36b9cc !important; }
    .text-secondary { color: #858796 !important; }
    .text-dark { color: #5a5c69 !important; }
    
    .bg-primary { background-color: #4e73df !important; }
    .bg-warning { background-color: #f6c23e !important; }
    .bg-success { background-color: #1cc88a !important; }
    .bg-danger { background-color: #e74a3b !important; }
    .bg-info { background-color: #36b9cc !important; }
    .bg-secondary { background-color: #858796 !important; }
</style>
@endpush