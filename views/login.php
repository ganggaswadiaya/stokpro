<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login — StokPro</title>
  <link rel="stylesheet" href="/stokpro/public/css/style.css"/>
</head>
<body>
<div class="login-wrap">
  <div class="login-box">
    <h1>StokPro</h1>
    <p>Sistem Manajemen Stok</p>

    <?php if (!empty($error)): ?>
      <div class="flash error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/stokpro/index.php?action=login">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" value="admin" required/>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" value="password" required/>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%">Masuk</button>
    </form>

    <p style="font-size:11px;color:var(--muted);margin-top:12px">
      Default: admin / password &nbsp;·&nbsp; kasir / password
    </p>
  </div>
</div>
</body>
</html>
