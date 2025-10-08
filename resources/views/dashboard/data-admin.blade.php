@extends('layouts.admin-shell')

@section('title', 'Data Admin')

@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-users-gear"></i> Data Admin</h2>
    <div class="header-actions">
        <a href="{{ route('admin.data-admin.pdf') }}" class="btn-download" target="_blank">
            <i class="fa-solid fa-file-pdf"></i> Download PDF
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h3>Daftar Semua Admin</h3>
        <p>Total: <strong>{{ $admins->count() }}</strong> admin</p>
    </div>

    <div class="table-responsive">
        <table class="table table-data-admin">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Admin</th>
                    <th>Password</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $index => $admin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $admin->nip }}</td>
                    <td>{{ $admin->nama_admin }}</td>
                    <td>
                        <div class="password-cell">
                            <span class="password-hidden" data-id="{{ $admin->id_admin }}">••••••••</span>
                            <span class="password-plain" data-id="{{ $admin->id_admin }}" style="display:none;">{{ $admin->password_plain ?? 'N/A' }}</span>
                            <button type="button" class="btn-toggle-pass" data-id="{{ $admin->id_admin }}">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <img src="{{ asset('img/' . ($admin->foto ?? 'icon.jpg')) }}" alt="Foto" class="admin-photo">
                    </td>
                    
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data admin</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.page-header h2 {
    margin: 0;
    color: var(--fg);
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.btn-download {
    background: linear-gradient(90deg, #e53935, #d32f2f);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(229, 57, 53, 0.3);
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(229, 57, 53, 0.4);
}

.panel-header {
    padding: 18px;
    border-bottom: 1px solid #eee;
}

.panel-header h3 {
    margin: 0 0 8px;
    color: var(--fg);
}

.panel-header p {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
}

.table-responsive {
    overflow-x: auto;
    padding: 18px;
}

.table-data-admin {
    width: 100%;
    border-collapse: collapse;
}

.table-data-admin thead {
    background: linear-gradient(90deg, var(--accent), var(--accent2));
}

.table-data-admin th {
    padding: 14px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    color: #ffff;
}

.table-data-admin tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s ease;
}

.table-data-admin tbody tr:hover {
    background: #f8f9fa;
}

.table-data-admin td {
    padding: 14px;
    font-size: 14px;
    vertical-align: middle;
}

.password-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-toggle-pass {
    background: none;
    border: none;
    color: var(--accent);
    cursor: pointer;
    padding: 5px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.btn-toggle-pass:hover {
    background: #e3f2fd;
    color: var(--accent2);
}

.admin-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
}

.text-center {
    text-align: center;
    color: var(--muted);
    padding: 30px !important;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
</style>

<script>
document.querySelectorAll('.btn-toggle-pass').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const hiddenSpan = document.querySelector(`.password-hidden[data-id="${id}"]`);
        const plainSpan = document.querySelector(`.password-plain[data-id="${id}"]`);
        const icon = this.querySelector('i');

        if (hiddenSpan.style.display !== 'none') {
            hiddenSpan.style.display = 'none';
            plainSpan.style.display = 'inline';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            hiddenSpan.style.display = 'inline';
            plainSpan.style.display = 'none';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});
</script>
@endsection