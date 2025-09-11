<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sign In</title>
  @include('auth._head')
  <style>
    .password-group {
      position: relative;
    }

    .password-group input {
      width: 100%;
      padding-right: 35px; /* ruang buat ikon */
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 60%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 16px;
      color: #444;
    }
  </style>
</head>
<body class="auth-gradient">
  <div class="auth-container">
    <div class="welcome-side">
      <div class="brand">
        <!-- <i class="fa-solid fa-notes-medical"></i> -->
        <!-- <span>GoDoc+</span> -->
      </div>
  
      <h1>Welcome!</h1>
      <img src="{{ asset('img/logo7.png') }}" class="welcome-logo img">
      <p>Masuk untuk mengelola inventaris dan pelayanan.</p>
    </div>

    <div class="form-side glass">
      <h2>Sign in</h2>
      @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
      @endif
      @if (session('success'))
        <div class="success">{{ session('success') }}</div>
      @endif
      <form method="POST" action="{{ route('login.post') }}" class="underline-form">
        @csrf
        <div class="input-group">
          <label>Role</label>
          <div class="role-switch">
            <label><input type="radio" name="role" value="admin" {{ old('role','admin')=='admin'?'checked':'' }}> Admin</label>
            <label><input type="radio" name="role" value="guru"  {{ old('role')=='guru'?'checked':'' }}> Guru</label>
          </div>
        </div>

        <div class="input-group">
          <label>Nama</label>
          <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama">
          <span class="underline"></span>
        </div>

        <div class="input-group password-group">
          <label>Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••">
          <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
          <span class="underline"></span>
        </div>

        <button class="btn-submit" type="submit">
          <span>Submit</span>
          <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </button>
      </form>
    </div>
  </div>

  @if(session('welcome') && session('auth_name'))
  <script>
    Swal.fire({
      title: 'Selamat datang!',
      text: '{{ session('auth_name') }}',
      icon: 'success',
      timer: 2000,
      showConfirmButton: false
    });
  </script>
  @endif

  <script>
    const toggle = document.getElementById("togglePassword");
    const input = document.getElementById("password");

    toggle.addEventListener("click", function () {
      const type = input.type === "password" ? "text" : "password";
      input.type = type;
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  </script>
</body>
</html>
