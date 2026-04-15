<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct() {
        // 1. Load cấu hình từ file .env trước
        $this->loadEnv();

        // 2. Lấy thông số kết nối (Ưu tiên .env, nếu không có thì dùng mặc định)
        $host = $_ENV['DB_HOST'] ?? 'db';
        $dbname = $_ENV['DB_NAME'] ?? 'phone_shop';
        $user = $_ENV['DB_USER'] ?? 'dev_user';
        $pass = $_ENV['DB_PASS'] ?? 'devpass';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '$charset' COLLATE utf8mb4_unicode_ci"
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    // Phương thức phụ trợ: Đọc file .env thủ công (không cần Composer)
    private function loadEnv(): void {
        // Xác định đường dẫn thư mục gốc (Fallback nếu BASE_PATH chưa được định nghĩa)
        $rootPath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
        $file = $rootPath . '/.env';

        if (!file_exists($file)) return; // Nếu không có file .env thì bỏ qua

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; // Bỏ qua dòng comment
            if (strpos($line, '=') === false) continue;   // Bỏ qua dòng không có dấu =

            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}
