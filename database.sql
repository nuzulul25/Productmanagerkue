CREATE DATABASE IF NOT EXISTS product_manager_kue
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE product_manager_kue;


CREATE TABLE IF NOT EXISTS products (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nama VARCHAR(100) NOT NULL,

    kategori VARCHAR(50) NOT NULL,

    harga DECIMAL(12,2) NOT NULL DEFAULT 0,

    stok INT NOT NULL DEFAULT 0,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

);


INSERT INTO products
(nama, kategori, harga, stok)
VALUES

(
    'Red Velvet Cake',
    'Cake',
    85000,
    8
),

(
    'Chocolate Cupcake',
    'Cupcake',
    18000,
    15
),

(
    'Butter Cookies',
    'Cookies',
    45000,
    12
),

(
    'Croissant Butter',
    'Pastry',
    22000,
    10
),

(
    'Strawberry Donut',
    'Donat',
    16000,
    20
),

(
    'Brownies Fudge',
    'Brownies',
    55000,
    7
);