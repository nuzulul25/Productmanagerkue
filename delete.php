<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';


if (
    $_SERVER['REQUEST_METHOD']
    !== 'POST'
) {

    header(
        'Location: index.php'
    );

    exit;
}


check_csrf();


$id =
    filter_input(
        INPUT_POST,
        'id',
        FILTER_VALIDATE_INT
    );


if ($id) {


    $stmt =
        $pdo->prepare(
            "DELETE FROM products
             WHERE id = :id"
        );


    $stmt->execute([
        'id' => $id
    ]);


    flash(
        'success',
        'Produk kue berhasil dihapus.'
    );


} else {


    flash(
        'error',
        'ID produk tidak valid.'
    );

}


header(
    'Location: index.php'
);

exit;