<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title') | Guru</title>
  <link rel="stylesheet" href="{{ asset('css/guru.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
</head>

<body class="dash">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">
      <i class="fa-solid fa-chalkboard-user"></i>
      <span>Guru Page</span>
    </div>
    
    <nav>
      <a href="{{ route('guru.home') }}" class="{{ request()->routeIs('guru.home') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i> Dashboard
      </a>
    </nav>
    
    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="button" class="logout" onclick="logoutConfirm()">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </aside>

  <!-- Content -->
  <main class="content">
    <div class="topbar">
      <div class="profile">
        <img src="{{ asset('img/' . (session('auth_photo') ?? 'icon.jpg')) }}" alt="Foto Profil" class="profile-img" id="btn-view-profile">
        <div class="info">
          <strong>{{ session('auth_name') }}</strong>
          <span>Guru</span>
        </div>
      </div>
    </div>
    
    <div class="main">
      @yield('content')
    </div>
  </main>

  <!-- Modal View Profile -->
  <div id="modal-view-profile" class="modal">
    <div class="modal-content">
      <button class="modal-close" onclick="closeViewProfileModal()">&times;</button>
      
      <div class="modal-header">
        <h3 class="modal-title"><i class="fa-solid fa-user-circle"></i> Profil Guru</h3>
      </div>
      
      <div class="profile-photo-display">
        <img src="{{ asset('img/' . (session('auth_photo') ?? 'icon.jpg')) }}" alt="Foto Profil">
      </div>
      
      <div class="profile-info-grid">
        <div class="profile-field">
          <i class="fa-solid fa-user"></i>
          <div class="profile-field-content">
            <div class="profile-field-label">Nama Guru</div>
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
              <button type="button" id="toggle-password-view" style="margin-left: 10px; background: none; border: none; color: #007bff; cursor: pointer;">
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
        <i class="fa-solid fa-edit"></i> Edit Profil
      </button>
    </div>
  </div>

  <!-- Modal Edit Profil -->
  <div id="modal-edit-profile" class="modal">
    <div class="modal-content">
      <button class="modal-close" onclick="closeEditProfileModal()">&times;</button>
      
      <div class="modal-header">
        <h3 class="modal-title"><i class="fa-solid fa-user-edit"></i> Edit Profil</h3>
      </div>
      
      <form action="{{ route('guru.profile.update') }}" method="POST" enctype="multipart/form-data">
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

        <label>Nama Guru</label>
        <input type="text" name="nama_guru" value="{{ session('auth_name') }}" required>

        <label>Password (Biarkan kosong jika tidak ingin mengubah)</label>
        <div class="password-wrapper">
          <input type="password" name="password" id="password" placeholder="Masukkan password baru">
          <button type="button" class="toggle-password"><i class="fa-solid fa-eye"></i></button>
        </div>

        <label>Konfirmasi Password</label>
        <div class="password-wrapper">
          <input type="password" name="password_confirmation" id="password-confirm" placeholder="Konfirmasi password baru">
          <button type="button" class="toggle-password-confirm"><i class="fa-solid fa-eye"></i></button>
        </div>

        <div class="form-buttons">
          <button type="button" class="btn btn-danger" onclick="closeEditProfileModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Toggle password visibility in View Modal
    document.getElementById('toggle-password-view').addEventListener('click', function () {
      const passwordDisplay = document.getElementById('password-display');
      const icon = this.querySelector('i');

      if (passwordDisplay.textContent === '••••••••') {
        const plainPassword = '{{ session("auth_password_plain") ?? "Tidak tersedia" }}';
        passwordDisplay.textContent = plainPassword;
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        passwordDisplay.textContent = '••••••••';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });

    // Buka Modal View Profil
    document.getElementById("btn-view-profile").addEventListener("click", function () {
      document.getElementById("modal-view-profile").style.display = "flex";
    });

    // Tutup Modal View
    function closeViewProfileModal() {
      document.getElementById("modal-view-profile").style.display = "none";
    }

    // Buka Modal Edit
    function openEditProfileModal() {
      closeViewProfileModal();
      document.getElementById("modal-edit-profile").style.display = "flex";
    }

    // Tutup Modal Edit
    function closeEditProfileModal() {
      document.getElementById("modal-edit-profile").style.display = "none";
    }

    // Toggle input password
    document.querySelector('.toggle-password').addEventListener('click', function () {
      const input = document.getElementById('password');
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });

    document.querySelector('.toggle-password-confirm').addEventListener('click', function () {
      const input = document.getElementById('password-confirm');
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });

    // Preview file & ganti nama file
    document.getElementById('foto-input').addEventListener('change', function (e) {
      const fileName = e.target.files[0]?.name || 'Belum ada file dipilih';
      document.getElementById('file-name').textContent = fileName;

      if (e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function (event) {
          document.getElementById('current-profile-photo').src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
      }
    });

    // SweetAlert Logout
    function logoutConfirm() {
      Swal.fire({
        title: 'Yakin ingin logout?',
        text: 'Anda akan keluar dari halaman guru!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('logoutForm').submit();
        }
      });
    }

    // Close modal jika klik di luar
    window.addEventListener('click', function (event) {
      if (event.target === document.getElementById('modal-view-profile')) {
        closeViewProfileModal();
      }
      if (event.target === document.getElementById('modal-edit-profile')) {
        closeEditProfileModal();
      }
    });

    // SweetAlert notifikasi sukses update profil
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 2000
      });
    @endif
  </script>

  @yield('scripts')
</body>
</html>