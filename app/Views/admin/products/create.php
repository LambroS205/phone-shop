<?php
declare(strict_types=1);
use App\Core\Security;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm mới - Admin</title>
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
            border-color: #4CAF50;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .error-message {
            color: #f44336;
            font-size: 0.875rem;
            margin-top: 0.25rem;
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
            background-color: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
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
            <h1 class="admin-title">➕ Thêm sản phẩm mới</h1>
        </div>

        <div class="form-container">
            <form method="POST" action="/admin/products/store">
                <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                
                <div class="form-group">
                    <label for="name">Tên sản phẩm *</label>
                    <input type="text" id="name" name="name" required minlength="3" placeholder="Nhập tên sản phẩm">
                </div>

                <div class="form-group">
                    <label for="price">Giá (₫) *</label>
                    <input type="number" id="price" name="price" required min="1" step="0.01" placeholder="Ví dụ: 10000000">
                </div>

                <div class="form-group">
                    <label for="category_id">Danh mục ID</label>
                    <input type="number" id="category_id" name="category_id" min="0" placeholder="Ví dụ: 1">
                </div>

                <div class="form-group">
                    <label for="stock">Số lượng tồn kho</label>
                    <input type="number" id="stock" name="stock" min="0" value="0" placeholder="Ví dụ: 100">
                </div>

                <div class="form-group">
                    <label for="image">URL hình ảnh</label>
                    <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg">
                    <small style="color: #666;">Để trống để sử dụng ảnh mặc định</small>
                </div>

                <div class="form-group">
                    <label for="description">Mô tả sản phẩm</label>
                    <textarea id="description" name="description" rows="4" placeholder="Mô tả chi tiết về sản phẩm..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Lưu sản phẩm</button>
                    <a href="/admin/products" class="btn btn-secondary">❌ Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
