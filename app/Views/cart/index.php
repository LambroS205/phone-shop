<?php use App\Core\Cart; use App\Core\Security; ?>
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Giỏ hàng</title><link rel="stylesheet" href="/css/style.css"></head>
<script src="/js/app.js" defer></script>
<body>
    <nav class="navbar"><a href="/">🏠 Quay lại Shop</a></nav>
    <div class="container">
        <h2>Giỏ hàng của bạn</h2>
        <?php $items = Cart::getItems(); if (empty($items)): ?>
            <p>Giỏ hàng trống.</p>
        <?php else: ?>
            <table style="width:100%; border-collapse: collapse; margin-bottom: 2rem;">
                <tr style="background:#f1f5f9; text-align:left;"><th style="padding:10px;">SP</th><th>Giá</th><th>SL</th><th>Tổng</th><th></th></tr>
                <?php foreach ($items as $item): ?>
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:10px;"><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                    <td>
                        <form method="POST" action="/cart/update" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" style="width:50px;" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td><?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?>₫</td>
                    <td>
                        <form method="POST" action="/cart/remove">
                            <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-danger" style="padding:5px 10px;">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div style="text-align:right;">
                <h3>Tổng thanh toán: <?= number_format(Cart::getTotal(), 0, ',', '.') ?>₫</h3>
                <form method="POST" action="/checkout/process" style="margin-top:1rem; display:inline-block;">
                    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                    <button type="submit" class="btn">💳 Thanh toán ngay</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
