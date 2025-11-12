<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Login - Aplikasi Barang Habis Pakai Sekolah</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* reset */
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%}
    body{
      font-family: "Poppins", sans-serif;
      background:#f2f6fb;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:20px;
    }

    /* card wrapper that gives rounded outer corners like image */
    .auth-card{
      width:100%;
      max-width:1200px;
      height:80vh;
      min-height:560px;
      display:flex;
      border-radius:18px;
      overflow:hidden;
      box-shadow:0 10px 30px rgba(11,35,80,0.08);
      background:transparent;
    }

    /* left column */
    .left {
      width:50%;
      background: linear-gradient(135deg,#2ea0ff 0%, #0b5ed7 100%);
      color:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      flex-direction:column;
      padding:48px 36px;
      position:relative;
    }

    .left .logo {
      width:225px;               /* logo besar */
      height:auto;
      border-radius:50%;
      box-shadow: 0 8px 18px rgba(0,0,0,0.15);
      background:rgba(255,255,255,0.02);
      margin-bottom:18px;
    }

    .left h1{
      font-size:34px;
      font-weight:800;
      line-height:1.05;
      text-align:center;
      margin-bottom:12px;
      letter-spacing:1px;
      text-shadow:0 6px 18px rgba(0,0,0,0.12);
    }

    .left .subtitle{
      font-size:16px;
      opacity:0.95;
      margin-bottom:10px;
      font-weight:500;
    }

    .left .footer {
      position:absolute;
      bottom:22px;
      left:50%;
      transform:translateX(-50%);
      font-size:13px;
      opacity:0.9;
    }

    /* right column */
    .right {
      width:50%;
      background:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:48px 56px;
    }

    .form-wrap{
      width:100%;
      max-width:420px;
    }

    .form-wrap h2{
      font-size:30px;
      color:#1554b8;
      margin-bottom:6px;
      font-weight:700;
      text-align:center;
    }

    .form-wrap p.desc{
      text-align:center;
      color:#8b96a8;
      margin-bottom:28px;
      font-size:15px;
    }

    /* input group */
    .group{margin-bottom:22px; position:relative;}
    .group label{
      display:flex;
      align-items:center;
      gap:.5rem;
      color:#444;
      font-weight:600;
      margin-bottom:8px;
      font-size:14px;
    }

    /* input style to match photo: pill-ish with inset shadow */
    .input {
      width:100%;
      padding:14px 46px;
      border-radius:14px;
      border:2px solid rgba(6, 86, 171, 0.06);
      background:linear-gradient(180deg,#fff,#fbfdff);
      font-size:15px;
      color:#333;
      box-shadow: 0 10px 30px rgba(12,80,170,0.06), inset 0 1px 0 rgba(255,255,255,0.6);
      transition:box-shadow .18s ease, border-color .18s ease, transform .08s;
    }

    .input:focus{
      outline:none;
      border-color:#2ea0ff;
      box-shadow:0 10px 30px rgba(46,160,255,0.14), 0 0 0 6px rgba(46,160,255,0.06);
      background:#fff;
    }

    /* icon left inside input */
    .icon-left{
      position:absolute;
      left:12px;
      top:64%;
      transform:translateY(-50%);
      color:#1f86ff;
      font-size:16px;
    }

    /* eye icon */
    .icon-right{
      position:absolute;
      right:12px;
      top:66%;
      transform:translateY(-50%);
      color:#1f86ff;
      font-size:18px;
      cursor:pointer;
    }

    /* radio row (role) styled similar to image */
    .role-row{
      display:flex;
      gap:18px;
      align-items:center;
      margin-top:6px;
    }

    .role-row label{
      display:flex;
      align-items:center;
      gap:8px;
      font-weight:600;
      color:#333;
      cursor:pointer;
    }

    .role-row input[type="radio"]{
      width:16px;
      height:16px;
      accent-color:#1f86ff;
    }

    /* big CTA button */
    .btn{
      margin-top:6px;
      width:100%;
      border-radius:16px;
      padding:14px 18px;
      background:linear-gradient(90deg,#2ea0ff,#0b5ed7);
      color:#fff;
      font-weight:700;
      font-size:17px;
      border:none;
      cursor:pointer;
      box-shadow: 0 14px 30px rgba(11,94,210,0.18);
      transition:transform .12s ease, box-shadow .12s ease;
    }

    .btn:active{transform:translateY(1px)}
    .btn:hover{box-shadow:0 18px 36px rgba(11,94,210,0.22)}

    /* small text under button */
    .under{
      margin-top:14px;
      text-align:center;
      color:#7b87a2;
      font-size:14px;
    }

    .under a{color:#0b5ed7; text-decoration: none; font-weight:600}

    /* responsive */
    @media (max-width:900px){
      .auth-card{flex-direction:column; height:auto; max-width:760px;}
      .left, .right{width:100%}
      .left{padding:34px 24px}
      .right{padding:36px 24px}
      .left .logo{width:160px}
      .left h1{font-size:22px}
    }

    /* Hilangin icon mata default Edge/Chrome/Opera */
    input::-ms-reveal,
    input::-ms-clear {
      display: none !important;
    }

    input::-webkit-credentials-auto-fill-button,
    input::-webkit-inner-spin-button,
    input::-webkit-clear-button,
    input::-webkit-reveal {
      display: none !important;
      -webkit-appearance: none;
    }

    input[type="password"] {
    -webkit-text-security: disc !important;
    }

  </style>
</head>
<body>
  <div class="auth-card" role="main">
    <!-- LEFT -->
    <div class="left" aria-hidden="true">
      <img src="{{ asset('img/logo7.png') }}" alt="logo" class="logo">
      <h1>APLIKASI BARANG HABIS<br>PAKAI SEKOLAH</h1>
      <div class="subtitle">SMK MAHARDHIKA BATUAJAJAR</div>
      <div class="footer">© 2025 Sistem Inventaris Sekolah - Versi 2.1.0</div>
    </div>

    <!-- RIGHT -->
    <div class="right">
      <div class="form-wrap" aria-label="form login">
        <h2>Login</h2>
        <p class="desc">Masuk ke akun Anda</p>

        @if ($errors->any())
          <div style="margin-bottom:12px;color:#d9534f;font-weight:600;text-align:center;">{{ $errors->first() }}</div>
        @endif
        @if (session('success'))
          <div style="margin-bottom:12px;color:#28a745;font-weight:600;text-align:center;">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <!-- role -->
          <div class="group" style="margin-bottom:18px">
            <label>Role</label>
            <div class="role-row">
              <label><input type="radio" name="role" value="admin" {{ old('role','admin')=='admin'?'checked':'' }}> Admin</label>
              <label><input type="radio" name="role" value="guru" {{ old('role')=='guru'?'checked':'' }}> Guru</label>
            </div>
          </div>

          <!-- username -->
          <div class="group">
            <label for="nama">Username</label>
            <i class="fa-solid fa-user icon-left" aria-hidden="true"></i>
            <input id="nama" class="input" type="text" name="nama" placeholder="Masukkan username Anda" required autocomplete="username">
          </div>

          <!-- password -->
          <div class="group">
            <label for="password">Password</label>
            <i class="fa-solid fa-lock icon-left" aria-hidden="true"></i>
            <input id="password" class="input" type="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password">
            <i class="fa-solid fa-eye icon-right" id="togglePassword" title="Tampilkan / sembunyikan password"></i>
          </div>

          <button type="submit" class="btn">Masuk</button>

         
        </form>
      </div>
    </div>
  </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("togglePassword");
  const input = document.getElementById("password");

  if (toggle && input) {
    toggle.addEventListener("click", () => {
      const show = input.type === "password";
      input.type = show ? "text" : "password";
      
      // pastiin cuma satu icon aktif
      toggle.classList.remove("fa-eye", "fa-eye-slash");
      toggle.classList.add(show ? "fa-eye-slash" : "fa-eye");
    });
  }
});
</script>

</body>
</html>