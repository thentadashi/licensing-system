@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="text-center mb-4">
        <h1 class="fw-bold">Manage Applications</h1>
        <p class="text-secondary fs-6">Click a row to view and manage application details</p>
    </div>

    @if(session('success'))
        <div id="successAlert" class="alert alert-success d-inline-flex align-items-center py-2 px-3 shadow-sm fs-sm" role="alert" style="font-size: 0.9rem;">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('successAlert');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 2500);
        </script>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive rounded">
                <table class="table table-hover align-middle" id="applicationsTable">
                    <thead class="table-light text-muted">
                        <tr>
                            <th style="width:70px;">app. no.</th>
                            <th style="width:270px;">Name</th>
                            <th style="width:100px;">Program</th>
                            <th style="width:100px;">Type</th>
                            <th style="width:150px;">Date</th>
                            <th style="width:150px;">Last Updated</th>
                            <th style="width:100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr class="clickable-row" data-app-id="{{ $app->id }}" style="cursor: pointer;">
                                <td>{{ $app->id }}</td>
                                <td>{{ $app->user->name }}</td>
                                <td>{{ $app->user->program }}</td>
                                <td>{{ $app->application_type }}</td>
                                <td class="tosh_small">{{ $app->created_at->format('D | M d, Y | h:i A') }}</td>
                                <td class="tosh_small">{{ $app->updated_at->format('D | M d, Y | h:i A') }}</td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'Approved' => 'bg-success',
                                            'Rejected' => 'bg-danger',
                                            'Revision Requested' => 'bg-warning text-dark',
                                        ];

                                        $badgeClass = $statusClasses[$app->status->value] ?? 'bg-secondary';
                                    @endphp

                                    <span class="badge {{ $badgeClass }} text-uppercase" style="font-size: 0.75rem;">
                                        @switch($app->progress_stage)
                                            @case('Completed') Completed @break
                                            @case('Ready for Release') Ready for Release @break
                                            @case('Processing License') Processing License @break
                                            @default {{ $app->status->value }}
                                        @endswitch
                                    </span>
                                </td>
                            </tr>

                          {{-- Hidden detail row --}}
                            <tr class="app-details-row d-none small" id="details-{{ $app->id }}">
                                <td colspan="7" class="bg-light p-3">
                                    {{-- Update Form --}}
                                    <form method="POST" action="{{ route('admin.applications.update', $app) }}">
                                        @csrf
                                        @method('PATCH')
                                        {{-- Files Block --}}
                                        <div class="mb-3">
                                            <label for="files-{{ $app->id }}" class="form-label fw-semibold mb-1">Files Uploaded</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($app->displayFiles() as $file)
                                                    <a href="{{ asset('storage/'.$file['file_path']) }}" target="_blank" class="btn btn-primary btn-sm">
                                                        {{ $file['requirement_label'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Status & Stage Block --}}
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="status-{{ $app->id }}" class="form-label fw-semibold mb-1">Status</label>
                                                <select id="status-{{ $app->id }}" name="status" class="form-select form-select-sm">
                                                    <option value="Pending" {{ $app->status->value === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Under Review" {{ $app->status->value === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                                    <option value="Approved" {{ $app->status->value === 'Approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="Rejected" {{ $app->status->value === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                    <option value="Revision Requested" {{ $app->status->value === 'Revision Requested' ? 'selected' : '' }}>Revision Requested</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="progress_stage-{{ $app->id }}" class="form-label fw-semibold mb-1">Progress Stage</label>
                                                <select id="progress_stage-{{ $app->id }}" name="progress_stage" class="form-select form-select-sm">
                                                    @foreach(['Submitted', 'Under Review', 'Processing License', 'Ready for Release', 'Completed', 'Revision request', 'Rejected'] as $stage)
                                                        <option value="{{ $stage }}" {{ $app->progress_stage->value === $stage ? 'selected' : '' }}>
                                                            {{ $stage }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Extra Details Block --}}
                                        @if($app->extraFields->count())
                                            <div class="mb-3">
                                                <label for="extra-{{ $app->id }}" class="form-label fw-semibold mb-1">Extra Details</label>
                                                <ul class="mb-0 ps-3">
                                                    @foreach($app->extraFields as $ef)
                                                        <li><strong>{{ ucfirst(str_replace('_', ' ', $ef->field_name)) }}:</strong> {{ $ef->field_value }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        {{-- Admin Notes Block --}}
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-3" style="display: none;">
                                            <div class="flex-fill">
                                                <label for="admin_notes-{{ $app->id }}" class="form-label fw-semibold mb-1">Notes</label>
                                                <textarea id="admin_notes-{{ $app->id }}" name="admin_notes" class="form-control form-control-sm admin-notes" rows="1">{{ old('admin_notes', $app->admin_notes) }}</textarea>
                                            </div>
                                        </div>

                                        {{-- Action Buttons Block --}}
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <button class="btn btn-primary btn-sm update-btn" type="submit">Update</button>
                                            <a href="{{ route('admin.applications.show', $app->id) }}"
                                            class="btn btn-primary btn-sm revision-link {{$app->status === 'Revision Requested'}}">
                                                <i class="bi bi-eye"></i> View / Send Revision
                                            </a>
                                        </div>
                                    </form>

                                    {{-- Archive / Trash Block --}}
                                    @if(!$app->archive && !$app->trash)
                                        <div class="d-flex align-items-center gap-2">
                                            <form action="{{ route('admin.applications.archive', $app) }}" method="POST" class="d-inline">
                                                @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm archive-btn"
                                                        onclick="return confirm('Archive this application? You can unarchive later.');">
                                                        <i class="bi bi-archive"></i> Archive
                                                    </button>
                                            </form>

                                            <form action="{{ route('admin.applications.trash', $app) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm trash-btn"
                                                    onclick="return confirm('Move to Trash? You can restore from Trash, or delete permanently there.');">
                                                    <i class="bi bi-trash"></i> Trash
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>


                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $applications->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Application Details Modal -->
<div class="modal fade" id="applicationDetailsModal" tabindex="-1" aria-labelledby="applicationDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="applicationDetailsModalLabel">Application Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalContent">
        <!-- Content will be injected here -->
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script src="{{ asset('js/admin/applications.js') }}"></script>
@endsection
@endsection
