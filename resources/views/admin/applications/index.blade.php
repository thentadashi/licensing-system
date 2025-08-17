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

    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="applicationsTable">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th>txn no.</th>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Application Type</th>
                            <th>Application Date</th>
                            <th>Last Updated</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="tosh_small">
                        @foreach($applications as $app)
                            <tr class="clickable-row" data-app-id="{{ $app->id }}" style="cursor: pointer;">
                                <td>{{ $app->id }}</td>
                                <td>{{ $app->user->name }}</td>
                                <td>{{ $app->user->program }}</td>
                                <td>{{ $app->application_type }}</td>
                                <td>{{ $app->created_at->format('D | M d, Y | h:i A') }}</td>
                                <td>{{ $app->updated_at->format('D | M d, Y | h:i A') }}</td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'Approved' => 'bg-success',
                                            'Rejected' => 'bg-danger',
                                            'Revision Requested' => 'bg-warning text-dark',
                                        ];
                                        $badgeClass = $statusClasses[$app->status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-uppercase" style="font-size: 0.75rem;">
                                        @if ($app->progress_stage === 'Completed')
                                            Completed
                                        @elseif ($app->progress_stage === 'Ready for Release')
                                            Ready for Release
                                        @elseif ($app->progress_stage === 'Processing License')
                                            Processing License
                                        @else
                                            {{ $app->status }}
                                        @endif
                                    </span>
                                </td>
                            </tr>

                                                    {{-- Hidden detail row --}}
                            <tr class="app-details-row d-none small" id="details-{{ $app->id }}">
                                <td colspan="7" class="bg-light p-3">
                                    <form method="POST" action="{{ route('admin.applications.update', $app) }}">
                                        @csrf
                                        @method('PATCH')

                                        {{-- Fields in one line --}}
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                            <!-- Files Uploaded -->
                                            <div>
                                                <label for="files-{{ $app->id }}" class="form-label fw-semibold mb-1">Files Uploaded</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($app->displayFiles() as $file)
                                                        <a href="{{ asset('storage/'.$file['file_path']) }}" target="_blank" class="btn btn-primary btn-sm">
                                                            {{ $file['requirement_label'] }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Status -->
                                            <div>
                                                <label for="status-{{ $app->id }}" class="form-label fw-semibold mb-1">Status</label>
                                                <select id="status-{{ $app->id }}" name="status" class="form-select form-select-sm" {{ $app->status === 'Revision Requested' ? 'disabled' : '' }}>
                                                    @foreach(['Pending', 'Under Review', 'Approved', 'Rejected', 'Revision Requested'] as $status)
                                                        <option value="{{ $status }}" {{ $app->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Stage -->
                                            <div>
                                                <label for="progress_stage-{{ $app->id }}" class="form-label fw-semibold mb-1">Stage</label>
                                                <select id="progress_stage-{{ $app->id }}" name="progress_stage" class="form-select form-select-sm">
                                                    @foreach(['Submitted', 'Under Review', 'Processing License', 'Ready for Release', 'Completed', 'Revision request', 'Rejected'] as $stage)
                                                        <option value="{{ $stage }}" {{ $app->progress_stage === $stage ? 'selected' : '' }}>
                                                            {{ $stage }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($app->extraFields->count())
                                                <div>
                                                <label for="extra-{{ $app->id }}" class="form-label fw-semibold mb-1">Extra Details</label>
                                                    @foreach($app->extraFields as $ef)
                                                        <div><strong>{{ ucfirst(str_replace('_', ' ', $ef->field_name)) }}:</strong> {{ $ef->field_value }}</div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Action Buttons -->
                                            <div class="d-flex align-items-center gap-2 mt-2">
                                                <button class="btn btn-primary btn-sm update-btn" type="submit">Update</button>
                                                <a href="{{ route('admin.applications.show', $app->id) }}"
                                                    class="btn btn-primary btn-sm revision-link {{$app->status === 'Revision Requested'}}">
                                                    <i class="bi bi-eye"></i> View / Send Revision
                                                </a>
                                                @if($userRole === 'super_admin')
                                                    <a href="{{ route('admin.students.index') }}" class="btn btn-warning btn-sm">Manage Users</a>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Notes and Extra Fields in one line --}}
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-3" style="display: none">
                                            <!-- Notes -->
                                            <div class="flex-fill" style="display: none">
                                                <label for="admin_notes-{{ $app->id }}" class="form-label fw-semibold mb-1">Notes</label>
                                                <textarea id="admin_notes-{{ $app->id }}" name="admin_notes" class="form-control form-control-sm" rows="1">{{ old('admin_notes', $app->admin_notes) }}</textarea>
                                            </div>
                                        </div>
                                    </form>
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
