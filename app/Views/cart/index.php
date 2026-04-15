<?php use App\Core\Cart; use App\Core\Security; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Phone Shop</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="/" class="navbar-brand">📱 Phone Shop</a>
        <div class="navbar-menu">
            <a href="/">🏠 Tiếp tục mua sắm</a>
        </div>
    </nav>
    
    <div class="container">
        <h2 class="page-title">Giỏ hàng của bạn</h2>
        <p class="page-subtitle">Kiểm tra lại sản phẩm trước khi thanh toán</p>
        
        <?php $items = Cart::getItems(); if (empty($items)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🛒</div>
                <h3>Giỏ hàng trống</h3>
                <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                <a href="/" class="btn btn-success" style="max-width: 250px;">Mua sắm ngay</a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                            <td>
                                <form method="POST" action="/cart/update" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="form-input" style="width:70px;" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td><b><?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?>₫</b></td>
                            <td>
                                <form method="POST" action="/cart/remove">
                                    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="text-align:right; margin-top:2rem; padding:1.5rem; background:white; border-radius:16px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                <h3 style="font-size:1.5rem; margin-bottom:1rem;">Tổng thanh toán: <span style="color:var(--primary);"><?= number_format(Cart::getTotal(), 0, ',', '.') ?>₫</span></h3>
                <form method="POST" action="/checkout/process" style="display:inline-block;">
                    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                    <button type="submit" class="btn btn-success" style="max-width: 280px;">💳 Thanh toán ngay</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <script src="/js/app.js" defer></script>
</body>
</html>
