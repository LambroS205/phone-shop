<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use App\Core\Session;
use App\Core\Security;

/**
 * Admin Product Controller
 * Handles admin product management operations
 */
class AdminProductController extends AdminController {
    
    private Product $model;
    
    public function __construct(Product $model) {
        $this->model = $model;
    }
    
    /**
     * Display admin product list with management options
     */
    public function index(): void {
        $this->requireAdmin();
        
        $limit = 10;
        $page = (int)($_GET['page'] ?? 1);
        $offset = ($page - 1) * $limit;
        
        $products = $this->model->getPaginated($limit, $offset);
        $total = $this->model->countAll();
        $totalPages = ceil($total / $limit);
        
        $flash = $this->getFlash();
        $adminUser = $this->getAdminUser();
        
        require BASE_PATH . '/app/Views/admin/products/index.php';
    }
    
    /**
     * Show create product form
     */
    public function create(): void {
        $this->requireAdmin();
        
        $adminUser = $this->getAdminUser();
        $errors = [];
        $oldInput = [];
        
        require BASE_PATH . '/app/Views/admin/products/create.php';
    }
    
    /**
     * Store a new product
     */
    public function store(): void {
        $this->requireAdmin();
        
        // Verify CSRF
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) {
            $this->setFlash('error', 'Invalid security token. Please try again.');
            header('Location: /admin/products/create');
            exit;
        }
        
        // Validate and sanitize input
        $errors = $this->validateProductData($_POST);
        
        if (!empty($errors)) {
            $this->setFlash('error', 'Please correct the errors below.');
            header('Location: /admin/products/create');
            exit;
        }
        
        $data = [
            'name' => htmlspecialchars(trim($_POST['name'])),
            'price' => (float)$_POST['price'],
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'stock' => (int)($_POST['stock'] ?? 0),
            'description' => htmlspecialchars(trim($_POST['description'] ?? '')),
            'image' => !empty($_POST['image']) ? htmlspecialchars(trim($_POST['image'])) : 'default-phone.jpg'
        ];
        
        try {
            $this->model->create($data);
            $this->setFlash('success', 'Product created successfully!');
            header('Location: /admin/products');
            exit;
        } catch (\Exception $e) {
            $this->setFlash('error', 'Failed to create product: ' . $e->getMessage());
            header('Location: /admin/products/create');
            exit;
        }
    }
    
    /**
     * Show edit product form
     */
    public function edit(): void {
        $this->requireAdmin();
        
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $this->setFlash('error', 'Invalid product ID.');
            header('Location: /admin/products');
            exit;
        }
        
        $product = $this->model->findById($id);
        
        if (!$product) {
            $this->setFlash('error', 'Product not found.');
            header('Location: /admin/products');
            exit;
        }
        
        $adminUser = $this->getAdminUser();
        $errors = [];
        
        require BASE_PATH . '/app/Views/admin/products/edit.php';
    }
    
    /**
     * Update an existing product
     */
    public function update(): void {
        $this->requireAdmin();
        
        // Verify CSRF
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) {
            $this->setFlash('error', 'Invalid security token. Please try again.');
            header('Location: /admin/products');
            exit;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            $this->setFlash('error', 'Invalid product ID.');
            header('Location: /admin/products');
            exit;
        }
        
        // Validate and sanitize input
        $errors = $this->validateProductData($_POST);
        
        if (!empty($errors)) {
            $this->setFlash('error', 'Please correct the errors below.');
            header('Location: /admin/products/edit?id=' . $id);
            exit;
        }
        
        $data = [
            'name' => htmlspecialchars(trim($_POST['name'])),
            'price' => (float)$_POST['price'],
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'stock' => (int)($_POST['stock'] ?? 0),
            'description' => htmlspecialchars(trim($_POST['description'] ?? '')),
            'image' => !empty($_POST['image']) ? htmlspecialchars(trim($_POST['image'])) : 'default-phone.jpg'
        ];
        
        try {
            $this->model->update($id, $data);
            $this->setFlash('success', 'Product updated successfully!');
            header('Location: /admin/products');
            exit;
        } catch (\Exception $e) {
            $this->setFlash('error', 'Failed to update product: ' . $e->getMessage());
            header('Location: /admin/products/edit?id=' . $id);
            exit;
        }
    }
    
    /**
     * Delete a product
     */
    public function destroy(): void {
        $this->requireAdmin();
        
        // Verify CSRF
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) {
            $this->setFlash('error', 'Invalid security token. Please try again.');
            header('Location: /admin/products');
            exit;
        }
        
        $id = (int)$_POST['id'];
        
        if ($id <= 0) {
            $this->setFlash('error', 'Invalid product ID.');
            header('Location: /admin/products');
            exit;
        }
        
        try {
            $this->model->delete($id);
            $this->setFlash('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Failed to delete product: ' . $e->getMessage());
        }
        
        header('Location: /admin/products');
        exit;
    }
    
    /**
     * Validate product data
     */
    private function validateProductData(array $data): array {
        $errors = [];
        
        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Product name is required.';
        } elseif (strlen(trim($data['name'])) < 3) {
            $errors['name'] = 'Product name must be at least 3 characters.';
        }
        
        if (!isset($data['price']) || (float)$data['price'] <= 0) {
            $errors['price'] = 'Price must be greater than 0.';
        }
        
        if (isset($data['stock']) && (int)$data['stock'] < 0) {
            $errors['stock'] = 'Stock cannot be negative.';
        }
        
        return $errors;
    }
}
