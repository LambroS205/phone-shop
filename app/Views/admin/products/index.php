<?php
declare(strict_types=1);
use App\Core\Session;
use App\Core\Security;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .admin-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .btn-add {
            background-color: #4CAF50;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-add:hover {
            background-color: #45a049;
        }
        .flash-message {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 6px;
        }
        .flash-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .flash-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .product-table th,
        .product-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .product-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .product-table tr:hover {
            background-color: #f8f9fa;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-edit {
            background-color: #2196F3;
            color: white;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        .pagination-item {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination-item.active {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="navbar-brand">📱 Phone Shop Admin</a>
        <div class="navbar-menu">
            <a href="/admin/products">Sản phẩm</a>
            <a href="/">Xem trang chủ</a>
        </div>
        <div class="navbar-user">
            <span class="user-greeting">Xin chào, <b><?= htmlspecialchars($adminUser['name']) ?></b></span>
            <a href="/logout" class="btn btn-sm btn-outline">Đăng xuất</a>
        </div>
    </nav>

    <div class="container">
        <div class="admin-header">
            <h1 class="admin-title">Quản lý sản phẩm</h1>
            <a href="/admin/products/create" class="btn-add">➕ Thêm sản phẩm mới</a>
        </div>

        <?php if ($flash): ?>
            <div class="flash-message flash-<?= htmlspecialchars($flash['type']) ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($products)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h3>Chưa có sản phẩm nào</h3>
                <p>Thêm sản phẩm đầu tiên của bạn để bắt đầu.</p>
                <a href="/admin/products/create" class="btn-add" style="display: inline-block; margin-top: 1rem;">Thêm sản phẩm</a>
            </div>
        <?php else: ?>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= (int)$p['id'] ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($p['image']) ?>" 
                                     alt="<?= htmlspecialchars($p['name']) ?>"
                                     class="product-image"
                                     onerror="this.src='https://via.placeholder.com/60?text=No+Image'">
                            </td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= number_format((float)$p['price'], 0, ',', '.') ?> ₫</td>
                            <td><?= (int)$p['stock'] ?></td>
                            <td><?= (int)$p['category_id'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/products/edit?id=<?= $p['id'] ?>" class="btn-sm btn-edit">✏️ Sửa</a>
                                    <form method="POST" action="/admin/products/delete" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                        <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn-sm btn-delete">🗑️ Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="pagination-item <?= $i === $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
