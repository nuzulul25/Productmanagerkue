<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';


$page_title =
    'Tambah Kue — Sweet Crumbs';


$errors = [];


$nama = '';
$kategori = '';
$harga = '';
$stok = '';


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


        $stmt = $pdo->prepare(
            "INSERT INTO products
            (nama, kategori, harga, stok)
            VALUES
            (:nama, :kategori, :harga, :stok)"
        );


        $stmt->execute([

            'nama' =>
                $nama,

            'kategori' =>
                $kategori,

            'harga' =>
                (float)$harga,

            'stok' =>
                (int)$stok

        ]);


        flash(
            'success',
            'Produk kue berhasil ditambahkan.'
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

Tambah Kue Baru 🍰

</h1>


<p class="form-sub">

Masukkan informasi kue
yang akan dijual.

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
    value="<?= e($nama) ?>"
    required
    minlength="3"
    placeholder="Contoh: Brownies Cokelat"
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
    placeholder="50000"
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
    placeholder="10"
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
    Simpan Kue
</button>


</div>


</form>


</div>


<?php

include __DIR__ .
    '/../includes/footer.php';

?>