<?php

// 1. Mengamankan teks dari serangan XSS
function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// 2. Format angka menjadi mata uang rupiah (contoh: Rp 50.000)
function rupiah($value): string {
    return 'Rp ' . number_format((float) $value, 0, ',', '.');
}

// 3. Membuat link URL otomatis sesuai BASEURL di .env
function base_url(string $path = ''): string {
    return BASEURL . '/' . ltrim($path, '/');
}

// 4. Mengambil pesan notifikasi (flash message) sekali tampil lalu dihapus
function take_flash(): ?array {
    if (empty($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;

/**
 * Data bersama yang dibutuhkan setiap view (layout & sidebar).
 */
function view_shared_data(): array {
    return [
        'flash'    => take_flash(),
        'loggedIn' => Middleware::isLoggedIn(),
        'user'     => Middleware::user(),
        'current'  => strtolower(explode('/', trim($_GET['url'] ?? 'home', '/'))[0] ?? 'home'),
        'navItems' => [
            'home'         => ['label' => 'Dashboard', 'icon' => 'speedometer2', 'url' => ''],
            'categories'   => ['label' => 'Kategori', 'icon' => 'tags', 'url' => 'categories'],
            'items'        => ['label' => 'Barang', 'icon' => 'box', 'url' => 'items'],
            'transactions' => ['label' => 'Transaksi', 'icon' => 'arrow-left-right', 'url' => 'transactions'],
            'users'        => ['label' => 'Pengguna', 'icon' => 'people', 'url' => 'users'],
        ],
    ];
}
}