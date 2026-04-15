<?php
declare(strict_types=1);
use App\Core\Session;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Shop - Cửa hàng điện thoại chính hãng</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="navbar-brand">📱 Phone Shop</a>
        <div class="navbar-menu">
            <?php if (Session::get('user_role') === 'admin'): ?>
                <a href="/admin/products">Quản lý</a>
            <?php endif; ?>
            <a href="/cart">Giỏ hàng</a>
        </div>
        <div class="navbar-user">
            <?php if (Session::has('user_name')): ?>
                <span class="user-greeting">Xin chào, <b><?= htmlspecialchars(Session::get('user_name')) ?></b></span>
                <a href="/logout" class="btn btn-sm btn-outline">Đăng xuất</a>
            <?php else: ?>
                <a href="/login" class="btn btn-sm btn-outline">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Nội dung chính -->
    <div class="container">
        <h2 class="page-title">Sản phẩm mới nhất</h2>
        <p class="page-subtitle">Khám phá những sản phẩm công nghệ mới nhất với giá tốt nhất</p>

        <?php if (empty($products)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h3>Chưa có sản phẩm nào</h3>
                <p>Chúng tôi đang cập nhật thêm sản phẩm mới. Hãy quay lại sau!</p>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $p): ?>
                    <div class="card">
                        <div class="card-image-wrapper">
                            <img src="<?= htmlspecialchars($p['image']) ?>"
                                 alt="<?= htmlspecialchars($p['name']) ?>"
                                 onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                        </div>
                        <div class="card-body">
                            <h3><?= htmlspecialchars($p['name']) ?></h3>
                            <p class="price"><?= number_format((float)$p['price'], 0, ',', '.') ?> ₫</p>
                            <form method="POST" action="/cart/add" class="ajax-cart-form">
                                <input type="hidden" name="csrf_token" value="<?= App\Core\Security::csrfToken() ?>">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($p['name']) ?>">
                                <input type="hidden" name="price" value="<?= $p['price'] ?>">
                                <input type="hidden" name="image" value="<?= htmlspecialchars($p['image']) ?>">
                                <button type="submit" class="btn">🛒 Thêm vào giỏ</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>"
                           class="pagination-item <?= $i === $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
