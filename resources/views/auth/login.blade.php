<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Login - Aplikasi Barang Habis Pakai Sekolah</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* RESET */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { height: 100%; }

    body {
      font-family: "Poppins", sans-serif;
      background: #f2f6fb;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* WRAPPER CARD */
    .auth-card {
      width: 100%;
      max-width: 1200px;
      height: 80vh;
      min-height: 560px;
      display: flex;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(11,35,80,0.08);
      background: transparent;

      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp .8s ease forwards;
    }

    @keyframes fadeUp {
      to { opacity: 1; transform: translateY(0); }
    }

    /* LEFT PANEL */
    .left {
      width: 50%;
      background: linear-gradient(135deg, #2ea0ff 0%, #0b5ed7 100%);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 48px 36px;
      position: relative;

      opacity: 0;
      transform: translateX(-35px);
      animation: slideLeft .9s ease forwards .2s;
    }

   @keyframes slideLeft {
    from {
        opacity: 0;
        transform: translateX(-35px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
    }

    .left .logo {
      width: 225px;
      border-radius: 50%;
      background: rgba(255,255,255,0.03);
      box-shadow: 0 8px 18px rgba(0,0,0,0.15);
      margin-bottom: 18px;

      opacity: 0;
      animation:
        logoFadeIn 1.2s ease forwards .4s,
        logoFloat 4s ease-in-out infinite 1.4s;
    }

    @keyframes logoFadeIn {
      from { opacity:0; transform: translateY(20px) scale(.96); }
      to   { opacity:1; transform: translateY(0) scale(1); }
    }

    @keyframes logoFloat {
      0%,100% { transform: translateY(0); }
      50%     { transform: translateY(-14px); }
    }

    .left h1 {
      font-size: 34px;
      font-weight: 800;
      text-align: center;
      text-shadow: 0 6px 18px rgba(0,0,0,0.12);
      margin-bottom: 12px;

      opacity: 0;
      animation: fadeText .8s ease forwards .6s;
    }

    .left .subtitle {
      font-size: 16px;
      font-weight: 500;

      opacity: 0;
      animation: fadeText .8s ease forwards .75s;
    }

    .left .footer {
      position: absolute;
      bottom: 22px;
      transform: translateX(-50%);
      font-size: 13px;

      opacity: 0;
      animation: fadeText .8s ease forwards 1s;
    }

    @keyframes fadeText {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* RIGHT PANEL */
    .right {
      width: 50%;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px 56px;

      opacity: 0;
      transform: translateX(35px);
      animation: slideRight .9s ease forwards .25s;
    }

    @keyframes slideRight {
      to { opacity: 1; transform: translateX(0); }
    }

    .form-wrap {
      width: 100%;
      max-width: 420px;
      opacity: 0;
      animation: fadeText .9s ease forwards .55s;
    }

    /* FORM TITLE */
    .form-wrap h2 {
      text-align: center;
      font-size: 30px;
      font-weight: 700;
      color: #1554b8;
      margin-bottom: 6px;
    }

    .form-wrap .desc {
      text-align: center;
      color: #8b96a8;
      margin-bottom: 28px;
      font-size: 15px;
    }

    /* FORM GROUP */
    .group { margin-bottom: 22px; position: relative; }

    .group label {
      display: flex;
      align-items: center;
      gap: .5rem;
      font-weight: 600;
      color: #444;
      margin-bottom: 8px;
      opacity: 0;
      animation: labelFade .7s ease forwards;
    }

    @keyframes labelFade {
      to { opacity: 1; }
    }

    .input {
      width: 100%;
      padding: 14px 46px;
      border-radius: 14px;
      font-size: 15px;
      color: #333;
      background: linear-gradient(180deg,#fff,#fbfdff);
      border: 2px solid rgba(6,86,171,0.06);
      box-shadow:
        0 10px 30px rgba(12,80,170,0.06),
        inset 0 1px 0 rgba(255,255,255,.6);
      transition: .35s ease;
    }

    .input:focus {
      outline: none;
      border-color: #2ea0ff;
      background: #fff;
      box-shadow:
        0 10px 30px rgba(46,160,255,0.14),
        0 0 0 6px rgba(46,160,255,0.06);
    }

    /* ICON INPUT */
    .icon-left {
      position: absolute;
      left: 12px;
      top: 66%;
      transform: translateY(-50%);
      color: #1f86ff;
      font-size: 16px;
    }

    .icon-right {
      position: absolute;
      right: 12px;
      top: 66%;
      transform: translateY(-50%);
      font-size: 18px;
      color: #1f86ff;
      cursor: pointer;
    }

    /* HILANGIN ICON MATA BAWAAN BROWSER */
    input::-ms-reveal,
    input::-ms-clear { display:none !important; }

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

    /* RADIO (ROLE) */
    .role-row {
      display: flex;
      gap: 18px;
      align-items: center;
      margin-top: 6px;
    }

    .role-row label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
      color: #333;
      cursor: pointer;
    }

    /* BUTTON */
    .btn {
      width: 100%;
      margin-top: 6px;
      padding: 14px 18px;
      border: none;
      border-radius: 16px;
      font-size: 17px;
      font-weight: 700;
      color: #fff;
      cursor: pointer;

      background: linear-gradient(90deg,#2ea0ff,#0b5ed7);
      box-shadow: 0 14px 30px rgba(11,94,210,0.18);

      transition: .18s ease;
    }

    .btn:hover {
      transform: translateY(-2px);
      filter: brightness(1.05);
    }

    .btn:active {
      transform: translateY(1px);
      filter: brightness(.95);
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .auth-card { flex-direction: column; height: auto; max-width: 760px; }
      .left, .right { width: 100%; }
      .left { padding: 34px 24px; }
      .right { padding: 36px 24px; }
      .left .logo { width: 160px; }
      .left h1 { font-size: 22px; }
    }
  </style>
</head>

<body>

  <div class="auth-card" role="main">

    <!-- LEFT SIDE -->
    <div class="left">
      <img src="{{ asset('img/logo7.png') }}" class="logo" alt="logo">
      <h1>APLIKASI BARANG HABIS<br>PAKAI SEKOLAH</h1>
      <div class="subtitle">SMK MAHARDHIKA BATUJAJAR</div>
      <div class="footer">© 2025 Sistem Inventaris Sekolah</div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="right">
      <div class="form-wrap">

        <h2>Login</h2>
        <p class="desc">Masuk ke akun Anda</p>

        @if ($errors->any())
          <div style="color:#d9534f;font-weight:600;text-align:center;margin-bottom:12px;">
            {{ $errors->first() }}
          </div>
        @endif

        @if (session('success'))
          <div style="color:#28a745;font-weight:600;text-align:center;margin-bottom:12px;">
            {{ session('success') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <!-- ROLE -->
          <div class="group" style="margin-bottom:18px;">
            <label>Role</label>
            <div class="role-row">
              <label><input type="radio" name="role" value="admin" {{ old('role','admin')=='admin'?'checked':'' }}> Admin</label>
              <label><input type="radio" name="role" value="guru" {{ old('role')=='guru'?'checked':'' }}> Guru</label>
            </div>
          </div>

          <!-- USERNAME -->
          <div class="group">
            <label for="nama">Username</label>
            <i class="fa-solid fa-user icon-left"></i>
            <input id="nama" name="nama" class="input" type="text" placeholder="Masukkan username Anda" required>
          </div>

          <!-- PASSWORD -->
          <div class="group">
            <label for="password">Password</label>
            <i class="fa-solid fa-lock icon-left"></i>

            <input id="password" name="password" class="input" type="password"
                   placeholder="Masukkan password Anda" required autocomplete="current-password">

            <i class="fa-solid fa-eye icon-right" id="togglePassword"></i>
          </div>

          <button class="btn" type="submit">Masuk</button>

        </form>

      </div>
    </div>

  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const toggle = document.getElementById("togglePassword");
      const input  = document.getElementById("password");

      toggle.addEventListener("click", () => {
        const show = input.type === "password";
        input.type = show ? "text" : "password";

        toggle.classList.remove("fa-eye", "fa-eye-slash");
        toggle.classList.add(show ? "fa-eye-slash" : "fa-eye");
      });
    });
  </script>

</body>
</html>
