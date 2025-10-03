<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title') | Guru</title>
  <link rel="stylesheet" href="{{ asset('css/guru.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: #fff;
      padding: 30px;
      border-radius: 8px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      position: relative;
    }
    .modal-header {
      border-bottom: 1px solid #eee;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }
    .modal-title {
      margin: 0;
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .profile-info-grid {
      display: grid;
      gap: 20px;
      margin: 20px 0;
    }
    .profile-field {
      display: flex;
      align-items: center;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 8px;
      border-left: 4px solid #007bff;
    }
    .profile-field i {
      margin-right: 12px;
      width: 20px;
      color: #007bff;
    }
    .profile-field-content {
      flex: 1;
    }
    .profile-field-label {
      font-size: 12px;
      color: #6c757d;
      margin-bottom: 4px;
    }
    .profile-field-value {
      font-weight: 600;
      color: #333;
    }
    .profile-photo-display {
      text-align: center;
      margin-bottom: 20px;
    }
    .profile-photo-display img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #007bff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-edit-profile {
      background: linear-gradient(45deg, #007bff, #0056b3);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 20px auto 0;
    }
    .btn-edit-profile:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0,123,255,0.3);
    }
  </style>
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

  {{-- Modal View Profile --}}
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
      </div>
      <button type="button" class="btn-edit-profile" onclick="openEditProfileModal()">
        <i class="fa-solid fa-edit"></i> Edit Profil
      </button>
    </div>
  </div>
  {{-- Modal Edit Profil --}}
  <div id="modal-edit-profile" class="modal">
    <div class="modal-content">
      <button class="modal-close" onclick="closeEditProfileModal()">&times;</button>
      <div class="modal-header">
        <h3 class="modal-title"><i class="fa-solid fa-user-edit"></i> Edit Profil</h3>
      </div>
      <form action="{{ route('guru.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="profile-photo-display">
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
