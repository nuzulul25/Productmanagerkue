<?php

declare(strict_types=1);

$host = 'localhost';
$db   = 'product_manager_kue';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {

    $pdo = new PDO($dsn, $user, $pass, $options);

} catch (PDOException $e) {

    die(
        '<div style="
            font-family:Arial;
            padding:30px;
            max-width:700px;
            margin:50px auto;
            background:#fff5f5;
            border:1px solid #e7b6b6;
            border-radius:14px;
            color:#5b1720">

            <h2>Database belum terhubung</h2>

            <p>
                Pastikan MySQL/XAMPP aktif dan database
                <b>product_manager_kue</b>
                sudah dibuat.
            </p>

            <p>
                Error:
                ' . htmlspecialchars($e->getMessage()) . '
            </p>

        </div>'
    );
}