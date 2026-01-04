@auth
    @php
        $pendingPayments = auth()->user()->orders()
            ->where('payment_status', 'pending')
            ->where('status', 'pending')
            ->whereNotNull('snap_token')
            ->where('created_at', '>', now()->subHours(24))
            ->get();
    @endphp

    @if($pendingPayments->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3 mt-1"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-800 mb-2">
                    You have {{ $pendingPayments->count() }} pending payment(s)
                </h3>
                <div class="space-y-2">
                    @foreach($pendingPayments as $order)
                    <div class="flex items-center justify-between bg-white p-3 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Order #{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-600">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }} • 
                                {{ $order->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ $order->getPaymentUrl() }}" 
                               target="_blank"
                               class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-sm">
                                Pay Now
                            </a>
                            <a href="{{ route('orders.show', $order->id) }}" 
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                                Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
@endauth