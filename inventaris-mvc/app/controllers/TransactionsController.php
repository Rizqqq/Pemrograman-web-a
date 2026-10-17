<?php

class TransactionsController extends Controller {
    public function __construct() {
    $this->requireLogin();
}

    // 1. Tampilkan halaman riwayat transaksi
    public function index(): void {
        $this->view('transactions/index', [
            'title'        => 'Riwayat Transaksi',
            'transactions' => $this->model('Transaction')->all(),
        ]);
    }

    // 2. Aksi barang masuk
    public function stockIn(): void {
        $this->store('IN', 'Barang masuk berhasil dicatat!');
    }

    // 3. Aksi barang keluar
    public function stockOut(): void {
        $this->store('OUT', 'Barang keluar berhasil dicatat!');
    }

    // 4. Logika penyimpanan transaksi
    private function store(string $type, string $msg): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('transactions');

        $itemId = (int)($_POST['item_id'] ?? 0);
        $qty    = (int)($_POST['quantity'] ?? 0);

        if ($itemId <= 0 || $qty <= 0) {
            $this->flash('danger', 'Jumlah stok harus lebih dari 0.');
            $this->redirect('items');
        }

        // Karena fitur Login baru ada di Langkah 10, kita beri fallback ID 1 (Admin) jika belum login
        $userId = $_SESSION['user']['id'] ?? 1;

        $ok = $this->model('Transaction')->create([
            'item_id'  => $itemId,
            'user_id'  => $userId,
            'type'     => $type,
            'quantity' => $qty,
            'notes'    => trim($_POST['notes'] ?? '') ?: null,
        ]);

        $this->flash(
            $ok ? 'success' : 'danger',
            $ok ? $msg : 'Gagal! Stok barang tidak mencukupi.'
        );

        $this->redirect('items');
    }
}