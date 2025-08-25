@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">License Application</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 mb-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Application Type</label>
            <select id="applicationType" name="application_type" class="form-select" required>
                <option value="" selected disabled>-- Select application type --</option>
                @foreach($requirements as $key => $cfg)
                    <option value="{{ $key }}">{{ $cfg['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div id="dynamicFields" style="display:none;">
            <div id="extraFieldsArea"></div>

            <h6 class="mt-3">Upload Required Files</h6>
            <div id="filesArea"></div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </div>
        </div>
    </form>

    <h4 class="mt-4">Your Applications</h4>

    @forelse($applications as $app)
        <div class="card mb-3">
<div class="card-body">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h5 class="card-title mb-1">{{ $app->application_type }}</h5>
            <div class="small text-muted">Submitted: {{ $app->created_at->format('M d, Y h:i') }} |  Date Reviewed: {{ $app->updated_at->format('M d, Y h:i') }}</div>

            <div class="mt-2 d-flex flex-wrap gap-2">
                @foreach($app->displayFiles() as $f)
                    <a href="{{ asset('storage/'.$f['file_path']) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        {{ $f['requirement_label'] }}
                    </a>
                @endforeach
            </div>

            @if($app->extraFields->count())
                <div class="mt-2 small text-muted">
                    @foreach($app->extraFields as $ef)
                        <div><strong>{{ ucfirst(str_replace('_', ' ', $ef->field_name)) }}:</strong> {{ $ef->field_value }}</div>
                    @endforeach
                </div>
            @endif
            @if ($app->status === 'Trashed')
            @else
                {{-- Show admin revision request details --}}
                @if($app->revision_files)
                    <div class="alert alert-warning mt-3 p-2">
                        <strong>Revision Required:</strong>
                        <ul class="mb-1">
                            @foreach(json_decode($app->revision_files, true) as $file)
                                <li>{{ ucfirst(str_replace('_', ' ', $file)) }}</li>
                            @endforeach
                        </ul>
                        <p class="mb-2 small">Please ensure to address the notes while uploading the files. <br>Please Review the files before re-uploading</p>
                        <p></p>
                        @if($app->revision_notes)
                            <div><strong>Notes:</strong> {{ $app->revision_notes }}</div>
                            
                        @endif
                    </div>

                    {{-- Reupload button --}}
                    <form action="{{ route('applications.reupload', $app->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        @foreach(json_decode($app->revision_files, true) as $file)
                            <div class="mb-2">
                                <label class="form-label">{{ ucfirst(str_replace('_', ' ', $file)) }}</label>
                                <input type="file" name="files[{{ $file }}]" class="form-control" required>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary btn-sm">Reupload Files</button>
                    </form>
                @endif
            @endif
        </div>



        <div class="text-end">
            <span class="badge 
                @if($app->status === 'Approved') bg-success
                @elseif($app->status === 'Rejected') bg-danger
                @else bg-secondary @endif">
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
        </div>
    </div>


    @php
        $map = [
            'Submitted' => 10,
            'Under Review' => 40,
            'Processing License' => 70,
            'Ready for Release' => 90,
            'Completed' => 100,
            'Revision request' => 20,
            'Rejected' => 100,
            'Trashed' => 100,
        ];

        $percent = $map[$app->progress_stage->value] ?? 0;

        $isRejected = $app->progress_stage->value === 'Rejected';
        $isTrashed = $app->progress_stage->value === 'Trashed';
    @endphp

    <div class="mt-3">
        <div class="d-flex justify-content-between small mb-1">
            <div>{{ $app->progress_stage->value }}</div>
            <div>{{ $percent }}%</div>
        </div>
        <div class="progress" style="height:10px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated 
                {{ $isRejected ? 'bg-danger' : ($isTrashed ? 'bg-danger' : 'bg-primary') }}" 
                role="progressbar" 
                style="width: {{ $percent }}%;"></div>
        </div>
    </div>

    @if($app->admin_notes)
        <div class="mt-3 small text-muted">
            <div class="alert 
                @if($app->status->value === 'Revision Requested') alert-warning 
                @elseif($app->status->value === 'Rejected') alert-danger 
                @elseif($app->status->value === 'Trashed') alert-danger 
                @else alert-success 
                @endif">
                <strong>Admin note:</strong> {{ $app->admin_notes }}
            </div>
        </div>
    @endif

        </div>
        </div>
    @empty
        <div class="alert alert-info">You have no applications yet.</div>
    @endforelse

<script>
    // requirements object passed from server
    const REQUIREMENTS = @json($requirements);

    const appTypeEl = document.getElementById('applicationType');
    const dynamicArea = document.getElementById('dynamicFields');
    const extraFieldsArea = document.getElementById('extraFieldsArea');
    const filesArea = document.getElementById('filesArea');

    function renderExtraFields(cfg) {
        extraFieldsArea.innerHTML = '';
        if (!cfg.extra_fields || Object.keys(cfg.extra_fields).length === 0) {
            return;
        }

        for (const [key, fcfg] of Object.entries(cfg.extra_fields)) {
            const label = fcfg.label ?? key;
            if (fcfg.type === 'array') {
                // For GI subjects we will render checkboxes
                if (key === 'gi_subjects') {
                    const subjects = [
                        'Air Law',
                        'Aircraft General Knowledge',
                        'Flight Performance and Planning',
                        'Human Performance',
                        'Meteorology',
                        'Navigation',
                        'Operational Procedures',
                        'Principles of Flight',
                        'Radiotelephony'
                    ];
                    const wrap = document.createElement('div');
                    wrap.className = 'mb-2';
                    const title = document.createElement('div');
                    title.className = 'form-label';
                    title.innerText = label;
                    wrap.appendChild(title);

                    subjects.forEach(sub => {
                        const id = `gi_${sub.replace(/\s+/g,'_')}`;
                        const div = document.createElement('div');
                        div.className = 'form-check form-check-inline';
                        div.innerHTML = `<input class="form-check-input" type="checkbox" name="extra[gi_subjects][]" id="${id}" value="${sub}">
                                         <label class="form-check-label small" for="${id}">${sub}</label>`;
                        wrap.appendChild(div);
                    });

                    extraFieldsArea.appendChild(wrap);
                } else {
                    // generic array input (not used elsewhere)
                    const input = document.createElement('input');
                    input.className = 'form-control mb-2';
                    input.name = `extra[${key}][]`;
                    input.placeholder = label;
                    extraFieldsArea.appendChild(input);
                }
            } else {
                const div = document.createElement('div');
                div.className = 'mb-3';
                let inner = '';
                if (key === 'issuance_type') {
                    inner = `<label class="form-label">${label}</label>
                             <select name="extra[${key}]" class="form-select" required>
                                <option value="Issuance">Issuance</option>
                                <option value="Reissuance">Reissuance</option>
                             </select>`;
                } else if (key === 'aircraft') {
                    inner = `<label class="form-label">${label}</label>
                             <select name="extra[${key}]" class="form-select" required>
                                <option value="C152">C152</option>
                                <option value="P2002JF">P2002JF</option>
                             </select>`;
                } else {
                    inner = `<label class="form-label">${label}</label>
                             <input name="extra[${key}]" class="form-control" ${fcfg.required ? 'required' : ''}>`;
                }
                div.innerHTML = inner;
                extraFieldsArea.appendChild(div);
            }
        }
    }

    function renderFileInputs(cfg) {
        filesArea.innerHTML = '';
        for (const [key, fcfg] of Object.entries(cfg.files)) {
            const div = document.createElement('div');
            div.className = 'mb-3';
            const label = document.createElement('label');
            label.className = 'form-label';
            label.innerText = `${fcfg.label} ${fcfg.required ? ' *' : ''}`;
            const input = document.createElement('input');
            input.type = 'file';
            input.name = `files[${key}]`;
            input.className = 'form-control';
            if (fcfg.required) input.required = true;
            div.appendChild(label);
            div.appendChild(input);
            filesArea.appendChild(div);
        }
    }

    appTypeEl.addEventListener('change', function() {
        const val = this.value;
        if (!val) {
            dynamicArea.style.display = 'none';
            return;
        }
        const cfg = REQUIREMENTS[val];
        if (!cfg) {
            dynamicArea.style.display = 'none';
            return;
        }
        renderExtraFields(cfg);
        renderFileInputs(cfg);
        dynamicArea.style.display = '';
    });

</script>
@endsection
