@extends('layouts.app')

@section('title', 'Checkout - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Address</h2>

                    <!-- 🔥 FORM BIASA -->
                    <form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Full Address *</label>
                            <textarea name="shipping_address" required
                                class="w-full px-3 py-2 border rounded-lg">{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Order Notes</label>
                            <textarea name="notes"
                                class="w-full px-3 py-2 border rounded-lg"></textarea>
                        </div>

                        <!-- PAYMENT METHOD (UI ONLY) -->
                        <input type="hidden" name="payment_method" value="midtrans">

                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Order Items</h2>

                    @foreach($cartItems as $item)
                        <div class="flex justify-between border-b py-3">
                            <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                            <strong>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- RIGHT -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">

                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-green-600">FREE</span>
                        </div>
                        <div class="border-t pt-2 flex justify-between font-bold">
                            <span>Total</span>
                            <span class="matcha-text">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- TERMS -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" id="terms" required class="mr-2">
                            <span class="text-sm">I agree to Terms & Conditions</span>
                        </label>
                    </div>

                    <!-- ✅ SUBMIT BIASA -->
                    <button type="submit"
                        onclick="return validateTerms()"
                        class="w-full btn-matcha py-3 rounded-lg text-lg font-semibold">
                        <i class="fas fa-lock mr-2"></i> Proceed to Payment
                    </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function validateTerms() {
    if (!document.getElementById('terms').checked) {
        alert('Please agree to the Terms & Conditions');
        return false;
    }
    return true;
}
</script>
@endsection
