<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    /* Responsivitas untuk Mobile/Tablet */
    @media (max-width: 768px) {
      .auth-container {
        flex-direction: column;
        min-height: 100vh;
      }

      .welcome-side {
        padding: 20px;
        min-height: auto;
      }

      .welcome-side h1 {
        font-size: 1.8rem;
      }

      .welcome-side .welcome-logo {
        max-width: 150px;
        height: auto;
      }

      .welcome-side p {
        font-size: 0.9rem;
      }

      .form-side {
        padding: 30px 20px;
        border-radius: 30px 30px 0 0;
        margin-top: -20px;
      }

      .form-side h2 {
        font-size: 1.5rem;
      }

      .underline-form {
        max-width: 100%;
      }

      .input-group {
        margin-bottom: 20px;
      }

      .input-group input {
        font-size: 16px; /* Mencegah zoom otomatis di iOS */
      }

      .btn-submit {
        padding: 12px;
        font-size: 1rem;
      }

      .role-switch {
        gap: 15px;
      }
    }

    /* Responsivitas untuk Mobile Kecil */
    @media (max-width: 480px) {
      .welcome-side {
        padding: 15px;
      }

      .welcome-side h1 {
        font-size: 1.5rem;
      }

      .welcome-side .welcome-logo {
        max-width: 120px;
      }

      .form-side {
        padding: 25px 15px;
      }

      .form-side h2 {
        font-size: 1.3rem;
      }

      .input-group label {
        font-size: 0.9rem;
      }

      .role-switch label {
        font-size: 0.85rem;
      }

      .toggle-password {
        font-size: 14px;
      }
    }

    /* Responsivitas untuk Desktop Besar */
    @media (min-width: 1200px) {
      .auth-container {
        max-width: 1400px;
        margin: 0 auto;
      }
    }

    /* Pastikan gambar responsive */
    .welcome-logo {
      max-width: 200px;
      width: 100%;
      height: auto;
      display: block;
      margin: 20px auto;
    }

    /* Pastikan container fleksibel */
    .auth-container {
      display: flex;
      min-height: 100vh;
      box-sizing: border-box;
    }

    .welcome-side,
    .form-side {
      box-sizing: border-box;
      flex: 1;
    }

    .welcome-side {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 40px;
    }

    .form-side {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
    }

    .underline-form {
      width: 100%;
      max-width: 400px;
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
      <h2>SIGN IN</h2>
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