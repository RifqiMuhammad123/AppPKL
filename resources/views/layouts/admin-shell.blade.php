<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Dashboard Admin')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="dash">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fa-solid fa-cubes"></i> <span>Admin Page</span>
        </div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }} dashboard-link">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>

            <div class="Permintaan {{ request()->is('admin/guru') ? 'open' : '' }}">
                <a href="{{ route('admin.permintaan.index') }}" class="{{ request()->routeIs('admin.permintaan.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope"></i> Permintaan
                </a>
            </div>

            <!-- ✅ TAMBAHKAN INI - Menu History -->
            <div class="History {{ request()->is('admin/permintaan/history') ? 'open' : '' }}">
                <a href="{{ route('admin.permintaan.history') }}" class="{{ request()->routeIs('admin.permintaan.history') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Permintaan
                </a>
            </div>

            <div class="menu-parent {{ request()->is('admin/barang*') ? 'open' : '' }}">
                <button class="menu-toggle">
                    <span><i class="fa-solid fa-box"></i> Barang</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </button>
                <div class="submenu">
                    <a href="{{ route('admin.barang.create') }}" class="{{ request()->routeIs('admin.barang.create') ? 'active' : '' }}">
                        <i class="fa-solid fa-plus"></i> Tambah Barang
                    </a>
                    <a href="{{ route('admin.barang.index') }}" class="{{ request()->routeIs('admin.barang.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-list"></i> Daftar Barang
                    </a>
                </div>
            </div>

            <div class="menu-parent {{ request()->is('admin/guru*') ? 'open' : '' }}">
                <button class="menu-toggle">
                    <span><i class="fa-solid fa-users"></i> Guru</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </button>
                <div class="submenu">
                    <a href="{{ route('admin.guru.create') }}" class="{{ request()->routeIs('admin.guru.create') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-plus"></i> Tambah Guru
                    </a>
                    <a href="{{ route('admin.guru.index') }}" class="{{ request()->routeIs('admin.guru.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-list"></i> Daftar Guru
                    </a>
                </div>
            </div>

                    <a href="{{ route('admin.data-admin') }}" class="{{ request()->routeIs('admin.data-admin') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear"></i> Data Admin
                    </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="button" id="btn-logout" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="topbar">
            <div class="profile">
                <img src="{{ asset('img/' . (session('auth_photo') ?? 'icon.jpg')) }}" alt="Foto Profil" class="profile-img" id="btn-view-profile">
                <div class="info">
                    <strong>{{ session('auth_name') }}</strong>
                    <span>{{ ucfirst(session('auth_role')) }}</span>
                </div>
            </div>
        </div>

        <div class="main">
            @yield('content')
        </div>
    </div>

    <!-- Modal Lihat Profil -->
    <div id="modal-view-profile" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeViewProfileModal()">&times;</button>
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fa-solid fa-user-circle"></i>
                    Profil Admin
                </h3>
            </div>

            <div class="profile-photo-display">
                <img src="{{ asset('img/' . (session('auth_photo') ?? 'icon.jpg')) }}" alt="Foto Profil" id="view-profile-photo">
            </div>

            <div class="profile-info-grid">
                <div class="profile-field">
                    <i class="fa-solid fa-user"></i>
                    <div class="profile-field-content">
                        <div class="profile-field-label">Nama Admin</div>
                        <div class="profile-field-value">{{ session('auth_name') }}</div>
                    </div>
                </div>

                <div class="profile-field">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div class="profile-field-content">
                        <div class="profile-field-label">Role</div>
                        <div class="profile-field-value">{{ ucfirst(session('auth_role')) }}</div>
                    </div>
                </div>

                <div class="profile-field">
                    <i class="fa-solid fa-lock"></i>
                    <div class="profile-field-content">
                        <div class="profile-field-label">Password</div>
                        <div class="profile-field-value">
                            <span id="password-display">••••••••</span>
                            <button type="button" id="toggle-password-view" class="toggle-password" data-target="password-display" style="margin-top: 95px; left: 330px; background: none; border: none; color: #007bff; cursor: pointer;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="profile-field">
                    <i class="fa-solid fa-calendar-days"></i>
                    <div class="profile-field-content">
                        <div class="profile-field-label">Terakhir Login</div>
                        <div class="profile-field-value">{{ now()->format('d F Y, H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-edit-profile" onclick="openEditProfileModal()">
                <i class="fa-solid fa-edit"></i>
                Edit Profil
            </button>
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div id="modal-edit-profile" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeEditProfileModal()">&times;</button>
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fa-solid fa-user-edit"></i>
                    Edit Profil
                </h3>
            </div>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="current-photo">
                    <img src="{{ asset('img/' . (session('auth_photo') ?? 'icon.jpg')) }}" alt="Foto Profil Saat Ini" id="current-profile-photo">
                    <p>Foto saat ini</p>
                </div>

                <label>Ubah Foto Profil</label>
                <div class="file-input-wrapper">
                    <div class="file-input-button">
                        <i class="fa-solid fa-upload"></i> Pilih File
                    </div>
                    <input type="file" name="foto" id="foto-input" accept="image/*">
                </div>
                <small id="file-name">Belum ada file dipilih</small>

                <label>Nama Admin</label>
                <input type="text" name="nama_admin" value="{{ session('auth_name') }}" required>

                <label>Password (Biarkan kosong jika tidak ingin mengubah)</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Masukkan password baru">
                    <button type="button" class="toggle-password" data-target="password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <label>Konfirmasi Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="password-confirm" placeholder="Konfirmasi password baru">
                    <button type="button" class="toggle-password" data-target="password-confirm">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <div class="form-buttons">
                    <button type="button" class="btn btn-danger" onclick="closeEditProfileModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password (untuk view profile & edit profile)
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const targetEl = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (targetEl.tagName === 'INPUT') {
                    if (targetEl.type === 'password') {
                        targetEl.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        targetEl.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                } else {
                    if (targetEl.textContent === '••••••••') {
                        const plainPassword = '{{ session("auth_password_plain") ?? "Password lama tidak tersedia" }}';
                        targetEl.textContent = plainPassword;
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        targetEl.textContent = '••••••••';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                }
            });
        });

        // Modal View Profile
        document.getElementById("btn-view-profile").addEventListener("click", function() {
            document.getElementById("modal-view-profile").style.display = "flex";
        });

        function closeViewProfileModal() {
            document.getElementById("modal-view-profile").style.display = "none";
        }

        function openEditProfileModal() {
            closeViewProfileModal();
            document.getElementById("modal-edit-profile").style.display = "flex";
        }

        // Modal Edit Profile
        function closeEditProfileModal() {
            document.getElementById("modal-edit-profile").style.display = "none";
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('modal-view-profile')) {
                closeViewProfileModal();
            }
            if (event.target === document.getElementById('modal-edit-profile')) {
                closeEditProfileModal();
            }
        });

        // File input preview
        document.getElementById('foto-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Belum ada file dipilih';
            document.getElementById('file-name').textContent = fileName;

            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('current-profile-photo').src = event.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // SweetAlert logout
        document.getElementById("btn-logout").addEventListener("click", function(e){
            e.preventDefault();
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda akan keluar dari halaman Admin!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Logout",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("logout-form").submit();
                }
            });
        });

        // Menu toggle
        document.querySelectorAll('.menu-toggle').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const parent = toggle.closest('.menu-parent');
                document.querySelectorAll('.menu-parent').forEach(m => {
                    if (m !== parent) m.classList.remove('open');
                });
                parent.classList.toggle('open');
            });
        });

        document.querySelector('.dashboard-link')?.addEventListener('click', () => {
            document.querySelectorAll('.menu-parent').forEach(m => {
                m.classList.remove('open');
            });
        });

        // SweetAlert success alert setelah edit profil
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#1e88e5'
        });
        @endif
    </script>
    @yield('scripts')
</body>
</html>
