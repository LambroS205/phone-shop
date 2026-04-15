<?php
declare(strict_types=1);

namespace App\Core;

class Container {
    // Nơi lưu trữ các "công thức" (closures) để tạo object
    protected array $bindings = [];

    /**
     * Đăng ký một class với cách khởi tạo của nó
     *
     * @param string $abstract Tên class hoặc interface (VD: 'App\Models\Product')
     * @param callable $concrete Hàm closure trả về instance của class đó
     */
    public function bind(string $abstract, callable $concrete): void {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Lấy instance của một class đã đăng ký
     */
    public function make(string $abstract) {
        if (!isset($this->bindings[$abstract])) {
            throw new \Exception("Class {$abstract} chưa được đăng ký trong Container.");
        }

        // Gọi hàm closure và truyền chính container vào (để hỗ trợ nested dependencies)
        return ($this->bindings[$abstract])($this);
    }
}
