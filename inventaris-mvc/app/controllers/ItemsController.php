<?php

class ItemsController extends Controller {
    public function __construct() {
    $this->requireLogin();
}

    // Normalisasi input form
    private function payload(array $input): array {
        return [
            'code'        => $input['code'],
            'name'        => $input['name'],
            'category_id' => ($input['category_id'] ?? '') !== '' ? (int)$input['category_id'] : null,
            'quantity'    => (int)($input['quantity'] ?? 0),
            'unit'        => $input['unit'] ?: 'unit',
            'price'       => (float)($input['price'] ?? 0),
            'entry_date'  => ($input['entry_date'] ?? '') !== '' ? $input['entry_date'] : null,
            'description' => $input['description'] ?? '',
        ];
    }

    // 1. Tampilkan daftar barang (mendukung pencarian, filter, dan limit)
    public function index(): void {
        $search   = trim($_GET['search'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $page     = max(1, (int)($_GET['page'] ?? 1));
        
        $allowedLimits = [5, 10, 20, 50, 100];
        $limit = (int)($_GET['limit'] ?? 10);
        if (!in_array($limit, $allowedLimits)) $limit = 10;

        $items = $this->model('Item')->paginate($limit, $page, $search, $category);
        $categories = $this->model('Category')->all();

        $this->view('items/index', [
            'title'        => 'Data Barang',
            'items'        => $items,
            'categories'   => $categories,
            'search'       => $search,
            'category'     => $category,
            'currentLimit' => $limit,
        ]);
    }

    // 2. Form Tambah Barang
    public function create(): void {
        $this->view('items/create', [
            'title'      => 'Tambah Barang',
            'code'       => $this->model('Item')->generateCode(),
            'categories' => $this->model('Category')->all(),
        ]);
    }

    // 3. Simpan Barang Baru
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('items');

        $input = array_map('trim', $_POST);
        if (empty($input['name']) || empty($input['code'])) {
            $this->flash('danger', 'Kode dan nama barang wajib diisi.');
            $this->redirect('items/create');
        }

        $this->model('Item')->create($this->payload($input));
        $this->flash('success', 'Barang berhasil ditambahkan.');
        $this->redirect('items');
    }

    // 4. Form Edit Barang
    public function edit(string $id = ''): void {
        if (empty($id)) $this->redirect('items');

        $item = $this->model('Item')->find((int)$id);
        if (!$item) {
            $this->flash('danger', 'Barang tidak ditemukan.');
            $this->redirect('items');
        }

        $this->view('items/edit', [
            'title'      => 'Edit Barang',
            'item'       => $item,
            'categories' => $this->model('Category')->all(),
        ]);
    }

    // 5. Update Barang
    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('items');

        $input = array_map('trim', $_POST);
        $id = (int)($input['id'] ?? 0);

        if (empty($input['name']) || empty($input['code']) || $id <= 0) {
            $this->flash('danger', 'Kode dan nama barang wajib diisi.');
            $this->redirect('items/edit/' . $id);
        }

        $this->model('Item')->update($id, $this->payload($input));
        $this->flash('success', 'Barang berhasil diperbarui.');
        $this->redirect('items');
    }

    // 6. Hapus Barang
    public function delete(string $id = ''): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($id)) $this->redirect('items');

        $this->model('Item')->delete((int)$id);
        $this->flash('success', 'Barang berhasil dihapus.');
        $this->redirect('items');
    }
}