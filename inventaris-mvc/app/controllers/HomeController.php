<?php
class HomeController extends Controller {
    public function __construct() {
    $this->requireLogin();
}
    public function index(): void {
        // Panggil Model item 
        $itemModel = new item();
        $daftarBarang = $itemModel->all();

        $this->view('dashboard/index', [
            'title' => 'Testing Dashboard',
            'barang' => $daftarBarang
        ]);
    }
}