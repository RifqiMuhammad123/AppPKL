<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sign In</title>
  @include('auth._head')
</head>
<body class="auth-gradient">
  <div class="auth-container">
    <div class="welcome-side">
      <div class="brand">
        <i class="fa-solid fa-notes-medical"></i>
        <span>GoDoc+</span>
      </div>
      <h1>Welcome!</h1>
      <p>Masuk untuk mengelola inventaris dan pelayanan.</p>
      <a class="btn-learn" href="{{ route('register') }}">Buat Akun</a>
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

        <div class="input-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="••••••••">
          <span class="underline"></span>
        </div>

        <button class="btn-submit" type="submit">
          <span>Submit</span>
          <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </button>
        <div class="socials">
          <i class="fa-brands fa-facebook"></i>
          <i class="fa-brands fa-instagram"></i>
          <i class="fa-brands fa-pinterest"></i>
        </div>
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
</body>
</html>
