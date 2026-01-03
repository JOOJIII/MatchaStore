@extends('layouts.app')

@section('title', 'Payment - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-matcha-green text-white flex items-center justify-center">
                        1
                    </div>
                    <div class="ml-2 text-sm font-medium text-matcha-green">Cart</div>
                </div>
                <div class="w-24 h-1 bg-matcha-green mx-4"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-matcha-green text-white flex items-center justify-center">
                        2
                    </div>
                    <div class="ml-2 text-sm font-medium text-matcha-green">Checkout</div>
                </div>
                <div class="w-24 h-1 bg-matcha-green mx-4"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-matcha-green text-white flex items-center justify-center">
                        3
                    </div>
                    <div class="ml-2 text-sm font-medium text-matcha-green">Payment</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fas fa-credit-card text-3xl text-matcha-green"></i>
            </div>
            
            <h1 class="text-2xl font-bold mb-4">Complete Your Payment</h1>
            <p class="text-gray-600 mb-6">
                Order #{{ $order->order_number }} • Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </p>

            <!-- Payment Instructions -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="font-semibold mb-3">Payment Instructions</h3>
                <p class="text-gray-600 mb-4">
                    You will be redirected to Midtrans secure payment page. Please complete your payment within 24 hours.
                </p>
                
                <div class="text-left max-w-md mx-auto">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Secure SSL encrypted connection</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Multiple payment methods available</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Instant payment confirmation</span>
                    </div>
                </div>
            </div>

            <!-- Payment Button -->
            <button onclick="processPayment()" 
                    class="w-full max-w-md mx-auto btn-matcha py-4 rounded-lg text-lg font-semibold mb-4">
                <i class="fas fa-lock mr-2"></i> Pay Now
            </button>

            <!-- Manual Bank Transfer Info (if selected) -->
            @if($order->payment_method == 'bank_transfer')
            <div class="mt-6 p-4 border rounded-lg bg-yellow-50">
                <h4 class="font-semibold mb-2">Bank Transfer Instructions</h4>
                <div class="text-left space-y-2 text-sm">
                    <p><strong>Bank:</strong> BCA (Bank Central Asia)</p>
                    <p><strong>Account Number:</strong> 1234567890</p>
                    <p><strong>Account Name:</strong> Matcha Store</p>
                    <p><strong>Amount:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    <p class="text-red-600"><strong>Note:</strong> Include Order #{{ $order->order_number }} in transfer description</p>
                </div>
            </div>
            @endif

            <!-- Cancel Order -->
            <div class="mt-8">
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to cancel this order?')"
                            class="text-gray-600 hover:text-red-600">
                        <i class="fas fa-times mr-1"></i> Cancel Order
                    </button>
                </form>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-xl p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center animate-spin">
                    <i class="fas fa-sync-alt text-matcha-green text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Processing Payment</h3>
                <p class="text-gray-600">Redirecting to secure payment page...</p>
            </div>
        </div>
    </div>
</div>

<script>
function processPayment() {
    // Show loading
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Redirect to Midtrans
    setTimeout(() => {
        window.location.href = "{{ $order->snap_redirect_url }}";
    }, 1500);
}

// Auto-redirect after 5 seconds
setTimeout(() => {
    if (!document.getElementById('loadingOverlay').classList.contains('hidden')) {
        processPayment();
    }
}, 5000);
</script>
@endsection