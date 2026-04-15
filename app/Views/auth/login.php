<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Phone Shop</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>🔐 Đăng Nhập</h2>
            <p class="subtitle">Nhập thông tin để tiếp tục</p>
            
            <form method="POST" action="/login">
                <input type="hidden" name="csrf_token" value="<?= App\Core\Security::csrfToken() ?>">
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="example@email.com" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-success">Đăng nhập</button>
            </form>
        </div>
    </div>
    <script src="/js/app.js" defer></script>
</body>
</html>
