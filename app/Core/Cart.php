<?php
declare(strict_types=1);
namespace App\Core;

class Cart {
    private const SESSION_KEY = 'cart';
    
    public function __construct() {
        self::init();
    }

    public static function init(): void {
        if (!Session::has(self::SESSION_KEY)) {
            Session::put(self::SESSION_KEY, []);
        }
    }

    public function add(int $id, string $name, float $price, string $image, int $qty = 1): void {
        $cart = Session::get(self::SESSION_KEY);

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = ['id' => $id, 'name' => $name, 'price' => $price, 'image' => $image, 'qty' => $qty];
        }
        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $id): void {
        $cart = Session::get(self::SESSION_KEY);
        unset($cart[$id]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(int $id, int $qty): void {
        $cart = Session::get(self::SESSION_KEY);
        if ($qty <= 0) unset($cart[$id]);
        else $cart[$id]['qty'] = $qty;
        Session::put(self::SESSION_KEY, $cart);
    }

    public function getItems(): array { return Session::get(self::SESSION_KEY, []); }
    public function clear(): void { Session::remove(self::SESSION_KEY); }

    public function getTotal(): float {
        return array_reduce($this->getItems(), fn($carry, $item) => $carry + ($item['price'] * $item['qty']), 0);
    }

    public function count(): int {
        return array_reduce($this->getItems(), fn($carry, $item) => $carry + $item['qty'], 0);
    }
}
