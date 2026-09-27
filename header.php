<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= e($page_title ?? 'Sweet Crumbs Bakery') ?>
    </title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>


<header class="topbar">

    <div class="brand">

        <div class="brand-icon">
            🍰
        </div>

        <div>

            <div class="brand-name">
                Sweet Crumbs
            </div>

            <div class="brand-sub">
                Bakery Product Manager
            </div>

        </div>

    </div>


    <nav>

        <a href="index.php">
            Daftar Kue
        </a>

        <a
            class="nav-button"
            href="create.php"
        >
            + Tambah Kue
        </a>

    </nav>

</header>


<main class="container">


<?php if ($flash = get_flash()): ?>

    <div class="alert <?= e($flash['type']) ?>">

        <?= e($flash['message']) ?>

    </div>

<?php endif; ?>