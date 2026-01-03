@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Order Management</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'pending') badge-warning
                                    @elseif($order->status == 'processing') badge-info
                                    @elseif($order->status == 'completed') badge-success
                                    @else badge-danger
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->payment_method }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.details', $order) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" 
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Status
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" name="status" value="pending" 
                                                    class="dropdown-item {{ $order->status == 'pending' ? 'active' : '' }}">
                                                Pending
                                            </button>
                                            <button type="submit" name="status" value="processing" 
                                                    class="dropdown-item {{ $order->status == 'processing' ? 'active' : '' }}">
                                                Processing
                                            </button>
                                            <button type="submit" name="status" value="completed" 
                                                    class="dropdown-item {{ $order->status == 'completed' ? 'active' : '' }}">
                                                Completed
                                            </button>
                                            <button type="submit" name="status" value="cancelled" 
                                                    class="dropdown-item {{ $order->status == 'cancelled' ? 'active' : '' }}">
                                                Cancelled
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection