@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Feedback Details</h1>
        <a href="{{ route('admin.feedbacks') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Feedbacks
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Feedback #{{ $feedback->id }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>From</h5>
                            <p><strong>{{ $feedback->name }}</strong><br>
                            {{ $feedback->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Date & Status</h5>
                            <p>{{ $feedback->created_at->format('d M Y, H:i') }}<br>
                            <span class="badge 
                                @if($feedback->status == 'new') badge-primary
                                @elseif($feedback->status == 'read') badge-info
                                @elseif($feedback->status == 'replied') badge-success
                                @else badge-secondary
                                @endif">
                                {{ ucfirst($feedback->status) }}
                            </span></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h5>Subject</h5>
                        <p class="h6">{{ $feedback->subject }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>Message</h5>
                        <div class="border p-3 rounded bg-light">
                            {{ $feedback->message }}
                        </div>
                    </div>

                    @if($feedback->user)
                    <div class="mb-3">
                        <h5>Customer Info</h5>
                        <p>Registered User: {{ $feedback->user->name }} (ID: {{ $feedback->user->id }})</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.feedbacks.status', $feedback) }}" method="POST" class="d-inline">
                        @csrf
                        <div class="btn-group" role="group">
                            <button type="submit" name="status" value="read" 
                                    class="btn btn-outline-info {{ $feedback->status == 'read' ? 'active' : '' }}">
                                Mark as Read
                            </button>
                            <button type="submit" name="status" value="replied" 
                                    class="btn btn-outline-success {{ $feedback->status == 'replied' ? 'active' : '' }}">
                                Mark as Replied
                            </button>
                            <button type="submit" name="status" value="closed" 
                                    class="btn btn-outline-secondary {{ $feedback->status == 'closed' ? 'active' : '' }}">
                                Close Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection