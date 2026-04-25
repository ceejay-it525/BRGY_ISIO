<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BMIS | Log in</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f2f5;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-wrapper {
      display: flex;
      width: 900px;
      min-height: 550px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .left-panel {
      flex: 1;
      background: linear-gradient(135deg, #1a3c6e 0%, #2d6a9f 50%, #f5a623 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px;
      color: white;
      text-align: center;
    }

    .left-panel img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      border: 4px solid rgba(255,255,255,0.5);
      margin-bottom: 20px;
      object-fit: cover;
    }

    .left-panel h2 {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 8px;
      letter-spacing: 1px;
    }

    .left-panel p {
      font-size: 13px;
      opacity: 0.85;
      line-height: 1.6;
    }

    .left-panel .divider {
      width: 50px;
      height: 3px;
      background: rgba(255,255,255,0.5);
      border-radius: 2px;
      margin: 16px auto;
    }

    .badge-row {
      display: flex;
      gap: 10px;
      margin-top: 20px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .badge-item {
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.3);
      border-radius: 20px;
      padding: 5px 14px;
      font-size: 11px;
      font-weight: 500;
    }

    .right-panel {
      flex: 1;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 50px 45px;
    }

    .right-panel h3 {
      font-size: 24px;
      font-weight: 700;
      color: #1a3c6e;
      margin-bottom: 6px;
    }

    .right-panel .subtitle {
      font-size: 13px;
      color: #888;
      margin-bottom: 30px;
    }

    .form-group-custom {
      width: 100%;
      margin-bottom: 18px;
    }

    .form-group-custom label {
      font-size: 12px;
      font-weight: 600;
      color: #555;
      margin-bottom: 6px;
      display: block;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap i.field-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 14px;
    }

    .input-wrap input {
      width: 100%;
      padding: 12px 42px 12px 40px;
      border: 1.5px solid #e0e0e0;
      border-radius: 10px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s;
      outline: none;
      color: #333;
    }

    .input-wrap input:focus {
      border-color: #2d6a9f;
      box-shadow: 0 0 0 3px rgba(45,106,159,0.1);
    }

    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #aaa;
      font-size: 14px;
      transition: color 0.2s;
    }

    .toggle-password:hover {
      color: #2d6a9f;
    }

    .remember-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      margin-bottom: 24px;
    }

    .remember-row label {
      font-size: 13px;
      color: #666;
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
    }

    .remember-row input[type="checkbox"] {
      accent-color: #2d6a9f;
      width: 15px;
      height: 15px;
    }

    .btn-signin {
      width: 100%;
      padding: 13px;
      background: linear-gradient(135deg, #1a3c6e, #2d6a9f);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      cursor: pointer;
      transition: all 0.3s;
      letter-spacing: 0.5px;
    }

    .btn-signin:hover:not(:disabled) {
      background: linear-gradient(135deg, #2d6a9f, #1a3c6e);
      box-shadow: 0 5px 20px rgba(45,106,159,0.4);
      transform: translateY(-1px);
    }

    .btn-signin:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .alert-custom {
      width: 100%;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 20px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }

    .alert-danger-custom {
      background: #fff5f5;
      border: 1px solid #f5c6cb;
      color: #c0392b;
    }

    .alert-warning-custom {
      background: #fffbf0;
      border: 1px solid #ffd700;
      color: #856404;
    }

    .alert-success-custom {
      background: #f0fff4;
      border: 1px solid #b2dfdb;
      color: #1e7e34;
    }

    @media (max-width: 768px) {
      .login-wrapper { flex-direction: column; width: 95%; }
      .left-panel { padding: 30px 20px; min-height: 200px; }
      .right-panel { padding: 30px 25px; }
    }
  </style>
</head>
<body>

<div class="login-wrapper">

  <!-- LEFT PANEL -->
  <div class="left-panel">
    <img src="<?= base_url('assets/img/pasig.jpeg') ?>" alt="BMIS Logo">
    <h2>BMIS</h2>
    <div class="divider"></div>
    <p>Barangay Management<br>Information System</p>
    <div class="badge-row">
      <span class="badge-item"><i class="fas fa-users"></i> Residents</span>
      <span class="badge-item"><i class="fas fa-file-alt"></i> Blotter</span>
      <span class="badge-item"><i class="fas fa-certificate"></i> Clearances</span>
      <span class="badge-item"><i class="fas fa-store"></i> Permits</span>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">
    <h3>Welcome Back</h3>
    <p class="subtitle">Sign in to your account to continue</p>

    <?php $lockoutTime = $lockout ?? 0; ?>

    <?php if ($lockoutTime > 0): ?>
      <div class="alert-custom alert-warning-custom" id="lockout-alert">
        <i class="fas fa-clock fa-lg"></i>
        <div>
          <strong>Too many login attempts.</strong><br>
          Please wait <span id="lockout-timer"></span> before trying again.
        </div>
      </div>
    <?php elseif (session()->getFlashdata('error')): ?>
      <div class="alert-custom alert-danger-custom">
        <i class="fas fa-exclamation-circle fa-lg"></i>
        <div><?= session()->getFlashdata('error') ?></div>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('/auth') ?>" method="post" style="width:100%">
      <?= csrf_field() ?>

      <!-- EMAIL -->
      <div class="form-group-custom">
        <label>Email Address</label>
        <div class="input-wrap">
          <i class="fas fa-envelope field-icon"></i>
          <input type="email" name="email" placeholder="Enter your email" required>
        </div>
      </div>

      <!-- PASSWORD WITH SHOW/HIDE -->
      <div class="form-group-custom">
        <label>Password</label>
        <div class="input-wrap">
          <i class="fas fa-lock field-icon"></i>
          <input type="password" name="password" id="passwordInput" placeholder="Enter your password" required>
          <span class="toggle-password" onclick="togglePassword()">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </span>
        </div>
      </div>

      <div class="remember-row">
        <label>
          <input type="checkbox" name="remember" id="remember">
          Remember Me
        </label>
      </div>

      <button type="submit" class="btn-signin" id="signInBtn"
        <?= ($lockoutTime > 0) ? 'disabled' : '' ?>>
        <i class="fas fa-sign-in-alt"></i> Sign In
      </button>
    </form>
  </div>

</div>

<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<script>
  function togglePassword() {
    const input   = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
      input.type = 'text';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    }
  }
</script>

<?php if ($lockoutTime > 0): ?>
<script>
  let secondsLeft = <?= $lockoutTime ?>;
  const timerDisplay = document.getElementById('lockout-timer');
  const signInBtn    = document.getElementById('signInBtn');
  const alertBox     = document.getElementById('lockout-alert');

  function updateTimer() {
    if (secondsLeft > 0) {
      let minutes = Math.floor(secondsLeft / 60);
      let seconds = secondsLeft % 60;
      timerDisplay.textContent = `${minutes}m ${seconds < 10 ? '0' : ''}${seconds}s`;
      secondsLeft--;
      setTimeout(updateTimer, 1000);
    } else {
      signInBtn.disabled = false;
      alertBox.className = 'alert-custom alert-success-custom';
      alertBox.innerHTML = `<i class="fas fa-check-circle fa-lg"></i><div><strong>You can now try again.</strong></div>`;
    }
  }

  updateTimer();
</script>
<?php endif; ?>

</body>
</html>