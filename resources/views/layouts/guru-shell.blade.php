<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title') | Guru</title>
  <link rel="stylesheet" href="{{ asset('css/guru.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      <!-- <a href="{{ route('guru.barang.index') }}" class="{{ request()->routeIs('guru.barang.*') ? 'active' : '' }}">
        <i class="fa-solid fa-boxes-stacked"></i> Daftar Barang
      </a>
    </nav> -->

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
        <img src="{{ asset('img/icon.jpg') }}" alt="Foto Profil">
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

  <script>
    function logoutConfirm(){
      Swal.fire({
        title: "Logout?",
        text: "Anda akan keluar dari halaman Guru.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Logout",
        cancelButtonText: "Batal"
      }).then((result) => {
        if(result.isConfirmed){
          document.getElementById("logoutForm").submit();
        }
      });
    }
  </script>
   @yield('scripts')
</body>
</html>
