<?php
declare(strict_types=1);
/**
 * auth.php — redirect helper, login/logout route handling, login page view.
 * Depends on: $_base, $uri, $USER, $PASS, $APP_NAME from config.php (already loaded).
 */

function redirect(string $path): void {
    global $_base;
    header('Location: ' . $_base . '/' . ltrim($path, '/'));
    exit;
}

function loginPage(string $appName, string $err): void { ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — <?= htmlspecialchars($appName) ?></title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{min-height:100vh;background:#0b0e1a;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',system-ui,sans-serif;background-image:radial-gradient(ellipse at 20% 50%,rgba(0,180,255,.07),transparent 60%),radial-gradient(ellipse at 80% 20%,rgba(80,0,255,.07),transparent 60%)}
.card{background:#131728;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:48px 40px;width:100%;max-width:420px;box-shadow:0 32px 80px rgba(0,0,0,.6)}
.logo{display:flex;align-items:center;gap:12px;margin-bottom:32px}
.logo-icon{width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#00c6ff,#0072ff);display:flex;align-items:center;justify-content:center;font-size:22px}
.logo-text{font-size:1.2rem;font-weight:700;color:#fff}
h2{font-size:1.5rem;color:#fff;margin-bottom:6px}
.sub{font-size:.875rem;color:#6b7280;margin-bottom:28px}
label{display:block;font-size:.8rem;color:#9ca3af;margin-bottom:6px;font-weight:500;letter-spacing:.5px;text-transform:uppercase}
input{width:100%;padding:12px 16px;background:#1e2235;border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#fff;font-size:.95rem;outline:none;margin-bottom:18px;transition:border-color .2s}
input:focus{border-color:#0072ff}
.btn{width:100%;padding:13px;background:linear-gradient(135deg,#0072ff,#00c6ff);border:none;border-radius:10px;color:#fff;font-size:1rem;font-weight:600;cursor:pointer}
.err{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);color:#f87171;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:.875rem}
.hint{margin-top:20px;text-align:center;font-size:.78rem;color:#4b5563}
.free-badge{margin-top:12px;text-align:center;font-size:.72rem;color:#10b981;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);padding:6px 12px;border-radius:20px}
</style>
</head>
<body>
<div class="card">
  <div class="logo"><div class="logo-icon">📈</div><div><div class="logo-text"><?= htmlspecialchars($appName) ?></div></div></div>
  <h2>Welcome back</h2>
  <p class="sub">Sign in to your trading dashboard</p>
  <?php if ($err): ?><div class="err">⚠ <?= htmlspecialchars($err) ?></div><?php endif; ?>
  <form method="POST">
    <label>Username</label><input type="text" name="u" required autocomplete="username">
    <label>Password</label><input type="password" name="p" required autocomplete="current-password">
    <button class="btn">Sign In →</button>
  </form>
  <p class="hint">Educational purposes only. Not financial advice.</p>
</div>
</body>
</html>
<?php }

// ─── Login / logout routes ───────────────────────────────────
if ($uri === '/login' || $uri === '/login/') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (trim($_POST['u'] ?? '') === $USER && trim($_POST['p'] ?? '') === $PASS) {
            $_SESSION['auth'] = true;
            $_SESSION['user'] = $USER;
            redirect('/');
        }
        $err = 'Invalid credentials.';
    }
    loginPage($APP_NAME, $err ?? ''); exit;
}
if ($uri === '/logout' || $uri === '/logout/') {
    session_destroy(); redirect('login');
}

if (empty($_SESSION['auth'])) { redirect('login'); }

header_remove('X-Powered-By');
