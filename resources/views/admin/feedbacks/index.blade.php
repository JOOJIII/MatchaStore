@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Customer Feedbacks</h1>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedbacks as $feedback)
                        <tr>
                            <td>{{ $feedback->id }}</td>
                            <td>{{ $feedback->name }}</td>
                            <td>{{ $feedback->email }}</td>
                            <td>{{ $feedback->subject }}</td>
                            <td>{{ $feedback->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge 
                                    @if($feedback->status == 'new') badge-primary
                                    @elseif($feedback->status == 'read') badge-info
                                    @elseif($feedback->status == 'replied') badge-success
                                    @else badge-secondary
                                    @endif">
                                    {{ ucfirst($feedback->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.feedbacks.details', $feedback) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" 
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Status
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{ route('admin.feedbacks.status', $feedback) }}" method="POST">
                                            @csrf
                                            <button type="submit" name="status" value="new" 
                                                    class="dropdown-item {{ $feedback->status == 'new' ? 'active' : '' }}">
                                                New
                                            </button>
                                            <button type="submit" name="status" value="read" 
                                                    class="dropdown-item {{ $feedback->status == 'read' ? 'active' : '' }}">
                                                Read
                                            </button>
                                            <button type="submit" name="status" value="replied" 
                                                    class="dropdown-item {{ $feedback->status == 'replied' ? 'active' : '' }}">
                                                Replied
                                            </button>
                                            <button type="submit" name="status" value="closed" 
                                                    class="dropdown-item {{ $feedback->status == 'closed' ? 'active' : '' }}">
                                                Closed
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
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection