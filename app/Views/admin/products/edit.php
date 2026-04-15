<?php
declare(strict_types=1);
use App\Core\Security;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .admin-header {
            margin-bottom: 2rem;
        }
        .admin-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2196F3;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }
        .btn-primary {
            background-color: #2196F3;
            color: white;
        }
        .btn-primary:hover {
            background-color: #1976D2;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .current-image {
            margin-bottom: 1rem;
        }
        .current-image img {
            max-width: 200px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            <h1 class="admin-title">✏️ Sửa sản phẩm</h1>
        </div>

        <div class="form-container">
            <form method="POST" action="/admin/products/update">
                <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
                
                <div class="form-group">
                    <label for="name">Tên sản phẩm *</label>
                    <input type="text" id="name" name="name" required minlength="3" 
                           value="<?= htmlspecialchars($product['name']) ?>" placeholder="Nhập tên sản phẩm">
                </div>

                <div class="form-group">
                    <label for="price">Giá (₫) *</label>
                    <input type="number" id="price" name="price" required min="1" step="0.01" 
                           value="<?= (float)$product['price'] ?>" placeholder="Ví dụ: 10000000">
                </div>

                <div class="form-group">
                    <label for="category_id">Danh mục ID</label>
                    <input type="number" id="category_id" name="category_id" min="0" 
                           value="<?= (int)$product['category_id'] ?>" placeholder="Ví dụ: 1">
                </div>

                <div class="form-group">
                    <label for="stock">Số lượng tồn kho</label>
                    <input type="number" id="stock" name="stock" min="0" 
                           value="<?= (int)$product['stock'] ?>" placeholder="Ví dụ: 100">
                </div>

                <div class="form-group">
                    <label>Ảnh hiện tại</label>
                    <div class="current-image">
                        <img src="<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             onerror="this.src='https://via.placeholder.com/200?text=No+Image'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">URL hình ảnh mới</label>
                    <input type="url" id="image" name="image" 
                           placeholder="https://example.com/new-image.jpg">
                    <small style="color: #666;">Để trống để giữ nguyên ảnh hiện tại</small>
                </div>

                <div class="form-group">
                    <label for="description">Mô tả sản phẩm</label>
                    <textarea id="description" name="description" rows="4" 
                              placeholder="Mô tả chi tiết về sản phẩm..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Cập nhật</button>
                    <a href="/admin/products" class="btn btn-secondary">❌ Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
