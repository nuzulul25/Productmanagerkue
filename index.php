<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';


$page_title = 'Daftar Kue — Sweet Crumbs';


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

$q = trim($_GET['q'] ?? '');

$kategori = trim(
    $_GET['kategori'] ?? ''
);


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

$page = max(
    1,
    (int)($_GET['page'] ?? 1)
);

$perPage = 6;


/*
|--------------------------------------------------------------------------
| QUERY FILTER
|--------------------------------------------------------------------------
*/

$where = [];

$params = [];
if ($q !== '') {

    $where[] =
        '(nama LIKE :nama OR kategori LIKE :kategoriCari)';

    $params['nama'] =
        "%{$q}%";

    $params['kategoriCari'] =
        "%{$q}%";
}

if ($kategori !== '') {

    $where[] =
        'kategori = :kategori';

    $params['kategori'] =
        $kategori;
}


$whereSql =
    $where
        ? 'WHERE ' . implode(' AND ', $where)
        : '';


/*
|--------------------------------------------------------------------------
| TOTAL DATA
|--------------------------------------------------------------------------
*/

$countStmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM products
     {$whereSql}"
);

$countStmt->execute($params);

$total = (int)$countStmt->fetchColumn();


$totalPages =
    max(
        1,
        (int)ceil(
            $total / $perPage
        )
    );


$page =
    min(
        $page,
        $totalPages
    );


$offset =
    ($page - 1) * $perPage;


/*
|--------------------------------------------------------------------------
| AMBIL PRODUK
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "SELECT *
     FROM products
     {$whereSql}
     ORDER BY id DESC
     LIMIT :limit
     OFFSET :offset"
);


foreach ($params as $key => $value) {

    $stmt->bindValue(
        ':' . $key,
        $value
    );
}


$stmt->bindValue(
    ':limit',
    $perPage,
    PDO::PARAM_INT
);


$stmt->bindValue(
    ':offset',
    $offset,
    PDO::PARAM_INT
);


$stmt->execute();


$products =
    $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| KATEGORI
|--------------------------------------------------------------------------
*/

$categories =
    $pdo->query(
        "SELECT DISTINCT kategori
         FROM products
         ORDER BY kategori ASC"
    )->fetchAll(PDO::FETCH_COLUMN);


/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/

$allCount =
    (int)$pdo->query(
        "SELECT COUNT(*)
         FROM products"
    )->fetchColumn();


$stockCount =
    (int)$pdo->query(
        "SELECT COALESCE(SUM(stok),0)
         FROM products"
    )->fetchColumn();


include __DIR__ . '/../includes/header.php';

?>


<section class="hero">

    <div>

        <h1>
            Kelola Kue Favoritmu 🍰
        </h1>

        <p>
            Kelola produk bakery dengan mudah.
            Tambahkan, cari, edit, dan hapus
            data kue dari satu halaman.
        </p>

    </div>




</section>



<div class="toolbar">

    <form
        class="search-box"
        method="get"
    >

        <input
            type="text"
            name="q"
            value="<?= e($q) ?>"
            placeholder="Cari nama atau kategori kue..."
        >


        <select
            class="filter"
            name="kategori"
        >

            <option value="">
                Semua kategori
            </option>


            <?php foreach ($categories as $cat): ?>

                <option
                    value="<?= e($cat) ?>"
                    <?= $kategori === $cat ? 'selected' : '' ?>
                >

                    <?= e($cat) ?>

                </option>

            <?php endforeach; ?>

        </select>


        <button
            class="btn btn-primary"
            type="submit"
        >
            Cari
        </button>


        <?php if ($q !== '' || $kategori !== ''): ?>

            <a
                class="btn btn-secondary"
                href="index.php"
            >
                Reset
            </a>

        <?php endif; ?>

    </form>

</div>



<div class="stats">

    <div class="stat">

        <b>
            <?= $allCount ?>
        </b>

        <span>
            Total produk
        </span>

    </div>


    <div class="stat">

        <b>
            <?= $stockCount ?>
        </b>

        <span>
            Total stok
        </span>

    </div>


    <div class="stat">

        <b>
            <?= $total ?>
        </b>

        <span>
            Hasil pencarian
        </span>

    </div>

</div>



<div
    class="card"
    style="margin-top:20px"
>


<?php if (!$products): ?>


    <div class="empty">

        <div class="emoji">
            🧁
        </div>

        <h3>
            Belum ada kue ditemukan
        </h3>

        <p>
            Coba ubah kata pencarian
            atau tambahkan produk baru.
        </p>


        <a
            class="btn btn-primary"
            href="create.php"
        >
            Tambah Kue
        </a>

    </div>


<?php else: ?>


<table>

<thead>

<tr>

<th>
    Produk
</th>

<th>
    Kategori
</th>

<th>
    Harga
</th>

<th>
    Stok
</th>

<th>
    Aksi
</th>

</tr>

</thead>


<tbody>


<?php

$icons = [
    '🍰',
    '🧁',
    '🍪',
    '🥐',
    '🎂',
    '🍩'
];


foreach ($products as $i => $product):

?>


<tr>


<td>

<span class="product-name">
    <?= e($product['nama']) ?>
</span>



</span>

</td>


<td>

<span class="badge">

<?= e($product['kategori']) ?>

</span>

</td>


<td class="price">

Rp
<?= number_format(
    (float)$product['harga'],
    0,
    ',',
    '.'
) ?>

</td>


<td
    class="stock
    <?= (int)$product['stok'] <= 5
        ? 'low'
        : '' ?>"
>

<?= (int)$product['stok'] ?>

pcs

</td>


<td>

<div class="actions">


<a
    class="btn btn-small btn-secondary"
    href="edit.php?id=<?= (int)$product['id'] ?>"
>
    Edit
</a>


<form
    method="post"
    action="delete.php"
    onsubmit="return confirm('Hapus produk ini?');"
    style="display:inline"
>

<input
    type="hidden"
    name="csrf_token"
    value="<?= e(csrf_token()) ?>"
>


<input
    type="hidden"
    name="id"
    value="<?= (int)$product['id'] ?>"
>


<button
    class="btn btn-small btn-danger"
    type="submit"
>
    Hapus
</button>

</form>


</div>

</td>


</tr>


<?php endforeach; ?>


</tbody>

</table>


<?php endif; ?>


</div>



<?php if ($totalPages > 1): ?>


<div class="pagination">


<?php for (
    $p = 1;
    $p <= $totalPages;
    $p++
): ?>


<?php

$paramsUrl = [
    'page' => $p
];


if ($q !== '') {

    $paramsUrl['q'] = $q;

}


if ($kategori !== '') {

    $paramsUrl['kategori'] =
        $kategori;

}

?>


<a
    class="<?= $p === $page
        ? 'active'
        : '' ?>"
    href="?<?= http_build_query($paramsUrl) ?>"
>

<?= $p ?>

</a>


<?php endfor; ?>


</div>


<?php endif; ?>


<?php

include __DIR__ .
    '/../includes/footer.php';

?>