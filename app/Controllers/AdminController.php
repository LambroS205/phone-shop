<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;

/**
 * Base controller for admin controllers
 * Provides common admin functionality and security checks
 */
abstract class AdminController {
    
    /**
     * Check if the current user is an admin
     * Redirects to login if not authenticated or authorized
     */
    protected function requireAdmin(): void {
        if (!Session::has('user_id')) {
            header('Location: /login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
        
        if (Session::get('user_role') !== 'admin') {
            http_response_code(403);
            die("Access Denied: Admin privileges required.");
        }
    }
    
    /**
     * Get current admin user data
     */
    protected function getAdminUser(): array {
        return [
            'id' => Session::get('user_id'),
            'name' => Session::get('user_name'),
            'role' => Session::get('user_role')
        ];
    }
    
    /**
     * Set flash message for admin dashboard
     */
    protected function setFlash(string $type, string $message): void {
        Session::put('admin_flash', [
            'type' => $type,
            'message' => $message
        ]);
    }
    
    /**
     * Get and clear flash message
     */
    protected function getFlash(): ?array {
        $flash = Session::get('admin_flash');
        Session::forget('admin_flash');
        return $flash;
    }
}
