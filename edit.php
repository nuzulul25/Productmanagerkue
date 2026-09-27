<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';


$id =
    filter_input(
        INPUT_GET,
        'id',
        FILTER_VALIDATE_INT
    );


if (!$id) {

    header(
        'Location: index.php'
    );

    exit;
}


$stmt = $pdo->prepare(
    "SELECT *
     FROM products
     WHERE id = :id"
);


$stmt->execute([
    'id' => $id
]);


$product =
    $stmt->fetch();


if (!$product) {

    flash(
        'error',
        'Produk tidak ditemukan.'
    );


    header(
        'Location: index.php'
    );

    exit;
}


$page_title =
    'Edit Kue — Sweet Crumbs';


$errors = [];


$nama =
    $product['nama'];


$kategori =
    $product['kategori'];


$harga =
    $product['harga'];


$stok =
    $product['stok'];


if (
    $_SERVER['REQUEST_METHOD']
    === 'POST'
) {

    check_csrf();


    $nama =
        trim($_POST['nama'] ?? '');


    $kategori =
        trim($_POST['kategori'] ?? '');


    $harga =
        $_POST['harga'] ?? '';


    $stok =
        $_POST['stok'] ?? '';


    $errors =
        validate_product(
            $nama,
            $kategori,
            $harga,
            $stok
        );


    if (!$errors) {


        $update =
            $pdo->prepare(
                "UPDATE products
                 SET
                    nama = :nama,
                    kategori = :kategori,
                    harga = :harga,
                    stok = :stok
                 WHERE id = :id"
            );


        $update->execute([

            'nama' =>
                $nama,

            'kategori' =>
                $kategori,

            'harga' =>
                (float)$harga,

            'stok' =>
                (int)$stok,

            'id' =>
                $id

        ]);


        flash(
            'success',
            'Produk kue berhasil diperbarui.'
        );


        header(
            'Location: index.php'
        );

        exit;

    }

}


include __DIR__ .
    '/../includes/header.php';

?>


<div class="card form-card">


<h1 class="form-title">

Edit Kue ✨

</h1>


<p class="form-sub">

Perbarui informasi produk
<?= e($product['nama']) ?>.

</p>


<?php if ($errors): ?>


<div class="alert error">

<ul class="error-list">


<?php foreach (
    $errors as $error
): ?>

<li>
    <?= e($error) ?>
</li>

<?php endforeach; ?>


</ul>

</div>


<?php endif; ?>


<form method="post">


<input
    type="hidden"
    name="csrf_token"
    value="<?= e(csrf_token()) ?>"
>


<div class="form-group">

<label for="nama">
    Nama Kue
</label>


<input
    id="nama"
    name="nama"
    value="<?= e((string)$nama) ?>"
    required
    minlength="3"
>

</div>



<div class="form-group">

<label for="kategori">
    Kategori
</label>


<select
    id="kategori"
    name="kategori"
    required
>


<option value="">
    Pilih kategori
</option>


<?php

$categories = [
    'Cake',
    'Cupcake',
    'Cookies',
    'Pastry',
    'Donat',
    'Brownies'
];

foreach ($categories as $cat):

?>


<option
    value="<?= e($cat) ?>"
    <?= $kategori === $cat
        ? 'selected'
        : '' ?>
>

<?= e($cat) ?>

</option>


<?php endforeach; ?>


</select>

</div>



<div class="form-grid">


<div class="form-group">

<label for="harga">
    Harga (Rp)
</label>


<input
    id="harga"
    type="number"
    name="harga"
    value="<?= e((string)$harga) ?>"
    min="0"
    step="1000"
    required
>

</div>



<div class="form-group">

<label for="stok">
    Stok (pcs)
</label>


<input
    id="stok"
    type="number"
    name="stok"
    value="<?= e((string)$stok) ?>"
    min="0"
    step="1"
    required
>

</div>


</div>



<div class="form-actions">


<a
    class="btn btn-secondary"
    href="index.php"
>
    Batal
</a>


<button
    class="btn btn-primary"
    type="submit"
>
    Simpan Perubahan
</button>


</div>


</form>


</div>


<?php

include __DIR__ .
    '/../includes/footer.php';

?>