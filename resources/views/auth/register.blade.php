<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  @include('auth._head')
</head>
<body class="auth-gradient">
  <div class="auth-container">
    <div class="welcome-side">
      <div class="brand">
        <i class="fa-solid fa-notes-medical"></i>
        <span>GoDoc+</span>
      </div>
      <h1>Join Us</h1>
      <p>Buat akunmu dan mulai kelola data.</p>
      <a class="btn-learn" href="{{ route('login') }}">Sudah punya akun?</a>
    </div>

    <div class="form-side glass">
      <h2>Create account</h2>
      @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('register.post') }}" class="underline-form">
        @csrf
        <div class="input-group">
          <label>Role</label>
          <div class="role-switch">
            <label><input type="radio" name="role" value="admin" {{ old('role','admin')=='admin'?'checked':'' }}> Admin</label>
            <label><input type="radio" name="role" value="guru"  {{ old('role')=='guru'?'checked':'' }}> Guru</label>
          </div>
        </div>

        <div class="input-group">
          <label>NIP</label>
          <input type="text" name="nip" value="{{ old('nip') }}" placeholder="NIP">
          <span class="underline"></span>
        </div>

        <div class="input-group">
          <label>Nama</label>
          <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap">
          <span class="underline"></span>
        </div>

        <div class="input-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Minimal 6 karakter">
          <span class="underline"></span>
        </div>

        <button class="btn-submit" type="submit">
          <span>Daftar</span>
          <i class="fa-solid fa-user-plus"></i>
        </button>
      </form>
    </div>
  </div>
</body>
</html>
