<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Dashboard Admin')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }} dashboard-link">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>

            <!-- Menu Barang -->
            <div class="menu-parent {{ request()->is('admin/barang*') ? 'open' : '' }}">
                <button class="menu-toggle">
                    <span><i class="fa-solid fa-box"></i> Barang</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </button>
                <div class="submenu">
                    <a href="{{ route('admin.barang.create') }}" 
                       class="{{ request()->routeIs('admin.barang.create') ? 'active' : '' }}">
                        <i class="fa-solid fa-plus"></i> Tambah Barang
                    </a>
                    <a href="{{ route('admin.barang.index') }}" 
                       class="{{ request()->routeIs('admin.barang.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-list"></i> Daftar Barang
                    </a>
                </div>
            </div>

            <!-- Menu Guru -->
            <div class="menu-parent {{ request()->is('admin/guru*') ? 'open' : '' }}">
                <button class="menu-toggle">
                    <span><i class="fa-solid fa-users"></i> Guru</span>
                    <i class="fa-solid fa-chevron-down arrow"></i>
                </button>
                <div class="submenu">
                    <a href="{{ route('admin.guru.create') }}" 
                       class="{{ request()->routeIs('admin.guru.create') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-plus"></i> Tambah Guru
                    </a>
                    <a href="{{ route('admin.guru.index') }}" 
                       class="{{ request()->routeIs('admin.guru.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-list"></i> Daftar Guru
                    </a>
                </div>
            </div>

            <!-- Logout -->
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
        <!-- Topbar -->
        <div class="topbar">
            <div class="profile">
                
                 <img src="{{ asset('img/icon.jpg') }}" alt="Foto Profil" class="profile-img">

                <div class="info">
                    <strong>{{ session('auth_name') }}</strong>
                    <span>{{ ucfirst(session('auth_role')) }}</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main">
            @yield('content')
        </div>
    </div>

    <script>
        // Toggle submenu dengan animasi
        document.querySelectorAll('.menu-toggle').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const parent = toggle.closest('.menu-parent');

                // Tutup semua menu lain
                document.querySelectorAll('.menu-parent').forEach(m => {
                    if (m !== parent) m.classList.remove('open');
                });

                // Toggle menu yang diklik
                parent.classList.toggle('open');
            });
        });

        // Tutup semua submenu kalau klik Dashboard
        document.querySelector('.dashboard-link')?.addEventListener('click', () => {
            document.querySelectorAll('.menu-parent').forEach(m => {
                m.classList.remove('open');
            });
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
    </script>
    @yield('scripts')
</body>
</html>
