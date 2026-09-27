<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| CSRF TOKEN
|--------------------------------------------------------------------------
*/

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {

        $_SESSION['csrf_token'] =
            bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}


function check_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        !$token ||
        !hash_equals(
            $_SESSION['csrf_token'] ?? '',
            $token
        )
    ) {

        http_response_code(419);

        die(
            'Token keamanan tidak valid.
            Silakan kembali dan coba lagi.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| FLASH MESSAGE
|--------------------------------------------------------------------------
*/

function flash(
    string $type,
    string $message
): void {

    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}


function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;

    unset($_SESSION['flash']);

    return $flash;
}


/*
|--------------------------------------------------------------------------
| VALIDASI PRODUK
|--------------------------------------------------------------------------
*/

function validate_product(
    string $nama,
    string $kategori,
    $harga,
    $stok
): array {

    $errors = [];

    $nama = trim($nama);
    $kategori = trim($kategori);


    if (mb_strlen($nama) < 3) {

        $errors[] =
            'Nama kue minimal 3 karakter.';
    }


    if ($kategori === '') {

        $errors[] =
            'Kategori wajib dipilih.';
    }


    if (
        !is_numeric($harga) ||
        (float)$harga < 0
    ) {

        $errors[] =
            'Harga harus berupa angka dan tidak boleh negatif.';
    }


    if (
        filter_var(
            $stok,
            FILTER_VALIDATE_INT
        ) === false ||
        (int)$stok < 0
    ) {

        $errors[] =
            'Stok harus berupa bilangan bulat dan minimal 0.';
    }


    return $errors;
}