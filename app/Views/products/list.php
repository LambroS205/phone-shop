<?php
declare(strict_types=1);
// ✅ BẮT BUỘC: Import class Session để sử dụng trong View
use App\Core\Session;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Shop</title>
    <!-- Đảm bảo đường dẫn CSS đúng -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<script src="/js/app.js" defer></script>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="/">Phone Shop</a>
        <div>
            <?php if (Session::get('user_role') === 'admin'): ?>
                <a href="/admin/products" style="margin-right: 10px;">Quản lý</a>
            <?php endif; ?>

            <?php if (Session::has('user_name')): ?>
                Xin chào, <b><?= htmlspecialchars(Session::get('user_name')) ?></b> |
                <a href="/logout">Đăng xuất</a>
            <?php else: ?>
                <a href="/login">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Nội dung chính -->
    <div class="container">
        <h2 style="margin-bottom: 1.5rem;">Sản phẩm mới nhất</h2>

        <?php if (empty($products)): ?>
            <p>Chưa có sản phẩm nào.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $p): ?>
                    <div class="card">
                        <!-- Xử lý ảnh placeholder nếu không có ảnh -->
                        <img src="<?= htmlspecialchars($p['image']) ?>"
                             alt="<?= htmlspecialchars($p['name']) ?>"
                             onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">

                             <div class="card-body">
    <h3><?= htmlspecialchars($p['name']) ?></h3>
    <p class="price"><?= number_format((float)$p['price'], 0, ',', '.') ?> ₫</p>
    <!-- ✅ Form Gửi dữ liệu ngầm đến CartController -->
    <form method="POST" action="/cart/add" style="margin-top: 10px;">
        <input type="hidden" name="csrf_token" value="<?= App\Core\Security::csrfToken() ?>">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <input type="hidden" name="name" value="<?= htmlspecialchars($p['name']) ?>">
        <input type="hidden" name="price" value="<?= $p['price'] ?>">
        <input type="hidden" name="image" value="<?= htmlspecialchars($p['image']) ?>">
        <button type="submit" class="btn" style="width: 100%;">🛒 Thêm vào giỏ</button>
    </form>
</div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div style="margin-top: 2rem; display: flex; gap: 10px; justify-content: center;">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>"
                           class="btn"
                           style="background: <?= $i === $page ? '#2563eb' : '#cbd5e1' ?>; color: <?= $i === $page ? '#fff' : '#333' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</form>
</body>
</html>
