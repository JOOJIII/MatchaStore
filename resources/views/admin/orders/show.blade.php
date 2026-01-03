@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Order Details</h1>
        <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order #{{ $order->order_number }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <p><strong>{{ $order->user->name }}</strong><br>
                            {{ $order->user->email }}<br>
                            {{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}<br>
                            <strong>Status:</strong> 
                            <span class="badge 
                                @if($order->status == 'pending') badge-warning
                                @elseif($order->status == 'processing') badge-info
                                @elseif($order->status == 'completed') badge-success
                                @else badge-danger
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span><br>
                            <strong>Payment:</strong> {{ $order->payment_method }}</p>
                        </div>
                    </div>

                    <h5>Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                                    <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->notes)
                    <div class="mt-3">
                        <h5>Customer Notes</h5>
                        <p class="border p-3 rounded bg-light">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-inline">
                        @csrf
                        <div class="btn-group" role="group">
                            <button type="submit" name="status" value="pending" 
                                    class="btn btn-outline-warning {{ $order->status == 'pending' ? 'active' : '' }}">
                                Pending
                            </button>
                            <button type="submit" name="status" value="processing" 
                                    class="btn btn-outline-info {{ $order->status == 'processing' ? 'active' : '' }}">
                                Processing
                            </button>
                            <button type="submit" name="status" value="completed" 
                                    class="btn btn-outline-success {{ $order->status == 'completed' ? 'active' : '' }}">
                                Completed
                            </button>
                            <button type="submit" name="status" value="cancelled" 
                                    class="btn btn-outline-danger {{ $order->status == 'cancelled' ? 'active' : '' }}">
                                Cancelled
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection