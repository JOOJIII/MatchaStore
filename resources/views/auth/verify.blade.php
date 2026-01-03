@extends('layouts.app')

@section('title', 'Verify Email - Matcha Store')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto text-center">
        <i class="fas fa-envelope text-5xl matcha-text mb-4"></i>
        <h1 class="text-3xl font-bold mb-4">Verify Your Email Address</h1>
        
        @if (session('resent'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <p class="text-gray-600 mb-6">
                Before proceeding, please check your email for a verification link.
                If you did not receive the email,
            </p>
            
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn-matcha px-6 py-3 rounded-lg">
                    Click here to request another
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
