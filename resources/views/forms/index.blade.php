@extends('layouts.app') {{-- or admin.layouts.app if for admin --}}

@section('content')
<div class="container my-4">
  <h2 class="mb-4 fw-bold">Downloadable Forms</h2>

  {{-- 🔍 Search Bar --}}
  <div class="d-flex justify-content-between mb-3">
      <input type="text" id="fileSearch" class="form-control w-50" placeholder="Search forms...">
  </div>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="fileList">
    @forelse($files as $file)
      @php
          $fileName    = $file->getFilename();
          $ext         = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          $displayName = pathinfo($fileName, PATHINFO_FILENAME);
          $displayName = str_replace(['_', '-'], ' ', $displayName);
          $displayName = preg_replace('/\s*form$/i', '', $displayName);
          $displayName = ucwords(strtolower($displayName));
          $icon = match ($ext) {
              'pdf' => 'bi-file-earmark-pdf text-danger',
              'doc', 'docx' => 'bi-file-earmark-word text-primary',
              default => 'bi-file-earmark'
          };
      @endphp

      <div class="col file-card">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <i class="bi {{ $icon }} fs-1"></i>
            <p class="mt-2 mb-2 fw-semibold small file-name">{{ $displayName }}</p>
            <a href="{{ asset('downloadable_forms/' . rawurlencode($fileName)) }}"
               target="_blank" rel="noopener"
               class="btn btn-sm btn-outline-primary">
               Download
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col">
        <div class="alert alert-info">No downloadable forms available.</div>
      </div>
    @endforelse
  </div>
</div>

{{-- 🔎 Client-side Search Script --}}
<script>
document.getElementById('fileSearch').addEventListener('keyup', function() {
    let search = this.value.toLowerCase();
    document.querySelectorAll('#fileList .file-card').forEach(card => {
        let name = card.querySelector('.file-name').textContent.toLowerCase();
        card.style.display = name.includes(search) ? '' : 'none';
    });
});
</script>
@endsection
