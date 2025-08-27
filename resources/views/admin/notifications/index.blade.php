{{-- resources/views/notifications/index.blade.php --}}

@extends(auth()->user()->role === 'student' ? 'layouts.app' : 'admin.layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Notifications</h3>

    <div class="d-flex justify-content-between mb-3">
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-primary">
                <i class="bi bi-envelope-open"></i> Mark all as read
            </button>
        </form>
    </div>

    <ul class="list-group shadow-sm">
        @forelse($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-start 
                {{ is_null($notification->read_at) ? 'list-group-item-info' : '' }}">
                
                <div class="me-auto">
                    <div class="fw-semibold text-truncate" style="max-width: 500px;">
                        {{ $notification->data['message'] ?? 'Notification' }}
                    </div>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>

                @if(is_null($notification->read_at))
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="ms-2">
                        @csrf
                        <button class="btn btn-sm btn-outline-primary" title="Mark as read">
                            <i class="bi bi-envelope-open"></i>
                        </button>
                    </form>
                @endif
            </li>
        @empty
            <li class="list-group-item text-muted">No notifications found.</li>
        @endforelse
    </ul>

    <div class="mt-3 d-flex justify-content-center">
        {{ $notifications->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
