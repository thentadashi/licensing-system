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
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Application Type</th>
                            <th>Application Date</th>
                            <th>Last Updated</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr class="clickable-row" data-app-id="{{ $app->id }}" style="cursor: pointer;">
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
                                    <span class="badge {{ $badgeClass }} text-uppercase" style="font-size: 0.85rem;">
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
                            <tr class="app-details-row d-none" id="details-{{ $app->id }}">
                                <td colspan="6" class="bg-light p-3">
                                    <form method="POST" action="{{ route('admin.applications.update', $app) }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="mb-3">
                                            <label for="status-{{ $app->id }}" class="form-label fw-semibold mb-1">Files Uploaded</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($app->displayFiles() as $file)
                                                    <a href="{{ asset('storage/'.$file['file_path']) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                        {{ $file['requirement_label'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-sm-6 col-md-3">
                                                <label for="status-{{ $app->id }}" class="form-label fw-semibold mb-1">Status</label>
                                                <select id="status-{{ $app->id }}" name="status" class="form-select form-select-sm" {{ $app->status === 'Revision Requested' ? 'disabled' : '' }}>
                                                    @foreach(['Pending', 'Under Review', 'Approved', 'Rejected', 'Revision Requested'] as $status)
                                                        <option value="{{ $status }}" {{ $app->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-sm-6 col-md-3">
                                                <label for="progress_stage-{{ $app->id }}" class="form-label fw-semibold mb-1">Progress Stage</label>
                                                <select id="progress_stage-{{ $app->id }}" name="progress_stage" class="form-select form-select-sm">
                                                    @foreach(['Submitted', 'Under Review', 'Processing License', 'Ready for Release', 'Completed', 'Revision request', 'Rejected'] as $stage)
                                                        <option value="{{ $stage }}" {{ $app->progress_stage === $stage ? 'selected' : '' }}>
                                                            {{ $stage }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label for="admin_notes-{{ $app->id }}" class="form-label fw-semibold mb-1">Admin Notes</label>
                                                <textarea id="admin_notes-{{ $app->id }}" name="admin_notes" class="form-control form-control-sm" rows="2"
                                                    placeholder="Add notes (optional)" {{ $app->status === 'Revision Requested' ? 'style=display:none' : '' }}>{{ old('admin_notes', $app->admin_notes) }}</textarea>
                                            </div>

                                            <div class="col-12 d-grid gap-2">
                                                <button class="btn btn-primary btn-sm update-btn" type="submit">Update</button>

                                                <a href="{{ route('admin.applications.show', $app->id) }}"
                                                    class="btn btn-info btn-sm revision-link {{ $app->progress_stage === 'Revision request' && $app->status === 'Revision Requested' ? '' : 'd-none' }}">
                                                    View / Request Revision
                                                </a>
                                            </div>
                                        </div>
                                    </form>

                                    @if($userRole === 'super_admin')
                                        <div class="mt-3">
                                            <a href="{{ route('admin.students.index') }}" class="btn btn-warning btn-sm">Manage Users</a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle details row on clicking main table row
    const rows = document.querySelectorAll('tr.clickable-row');

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const appId = row.getAttribute('data-app-id');
            const detailRow = document.getElementById('details-' + appId);
            if (!detailRow) return;

            // Close other detail rows
            document.querySelectorAll('tr.app-details-row').forEach(r => {
                if (r.id !== 'details-' + appId) {
                    r.classList.add('d-none');
                }
            });

            // Toggle current detail row
            detailRow.classList.toggle('d-none');

            // Scroll into view if opened
            if (!detailRow.classList.contains('d-none')) {
                detailRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });

    // Handle UI updates inside each detail row when status/progress changes
    document.querySelectorAll('tr.app-details-row').forEach(detailRow => {
        const appId = detailRow.id.replace('details-', '');
        const statusSelect = detailRow.querySelector(`select[name="status"]`);
        const progressStage = detailRow.querySelector(`select[name="progress_stage"]`);
        const adminNotes = detailRow.querySelector(`textarea[name="admin_notes"]`);
        const revisionLink = detailRow.querySelector('.revision-link');
        const updateBtn = detailRow.querySelector('.update-btn');

        if(!statusSelect || !progressStage || !adminNotes) return;

        function updateUI(status) {
            switch(status) {
                case 'Revision Requested':
                    statusSelect.disabled = false;
                    progressStage.value = 'Revision request';
                    adminNotes.value = 'Your application will not proceed until the requested files are uploaded. if the requested files are not uploaded, the application will be rejected.';
                    adminNotes.style.display = 'none';
                    progressStage.style.display = 'none';
                    break;

                case 'Approved':
                    revisionLink.classList.add('d-none');
                    progressStage.style.display = 'block';
                    progressStage.disabled = false;

                    // Show only allowed progress options
                    Array.from(progressStage.options).forEach(option => {
                        const allowed = ['Processing License', 'Ready for Release', 'Completed'];
                        option.style.display = allowed.includes(option.value) ? '' : 'none';
                    });

                    if(!['Processing License', 'Ready for Release', 'Completed'].includes(progressStage.value)){
                        progressStage.value = 'Processing License';
                    }

                    adminNotes.style.display = 'none';
                    adminNotes.disabled = true;

                    progressStage.onchange = () => {
                        switch(progressStage.value) {
                            case 'Processing License':
                                adminNotes.value = 'Your application is being processed for licensing.';
                                break;
                            case 'Ready for Release':
                                adminNotes.value = 'Your application is ready for release.';
                                break;
                            case 'Completed':
                                adminNotes.value = 'Congratulations! Your application has been completed.';
                                break;
                            default:
                                adminNotes.value = '';
                        }
                    };
                    break;

                case 'Under Review':
                    statusSelect.disabled = false;
                    revisionLink.classList.add('d-none');
                    progressStage.style.display = 'none';
                    adminNotes.value = 'Your application is currently under review.';
                    adminNotes.style.display = 'none';
                    break;

                case 'Pending':
                    revisionLink.classList.add('d-none');
                    progressStage.style.display = 'none';
                    progressStage.value = 'Submitted';
                    adminNotes.value = 'Your application has been submitted and is awaiting review.';
                    adminNotes.style.display = 'none';
                    break;

                default: // Rejected or others
                    statusSelect.disabled = true;
                    revisionLink.classList.add('d-none');
                    progressStage.style.display = 'none';
                    progressStage.value = 'Rejected';
                    adminNotes.value = 'Your application has been rejected due to missing information or documentation.';
                    adminNotes.style.display = 'none';
                    break;
            }
        }

        // Initialize UI on page load
        updateUI(statusSelect.value);

        // Listen for status changes
        statusSelect.addEventListener('change', () => {
            updateUI(statusSelect.value);
        });

        // On form submit, hide update button if status is Revision Requested
        const form = updateBtn.closest('form');
        if(form){
            form.addEventListener('submit', () => {
                if(statusSelect.value === 'Revision Requested'){
                    updateBtn.style.display = 'none';
                    adminNotes.style.display = 'none';
                    revisionLink.style.display = 'inline-block';
                    progressStage.style.display = 'none';
                }
            });
        }
    });
});
</script>
@endsection
