<?php

class HomeController extends Controller {
    public function __construct() {
        // Wajib login untuk melihat dashboard
        $this->requireLogin();
    }

    public function index(): void {
        // Panggil model yang dibutuhkan
        $itemModel = $this->model('Item');
        $trxModel  = $this->model('Transaction');
        $catModel  = $this->model('Category');

        // Kirim semua statistik ke view dashboard
        $this->view('dashboard/index', [
            'title'              => 'Dashboard',
            'totalItems'         => $itemModel->totalItems(),
            'totalCategories'    => $catModel->count(),
            'totalStock'         => $itemModel->totalStock(),
            'totalAssetValue'    => $itemModel->totalAssetValue(),
            'lowStockCount'      => $itemModel->lowStockCount(5),
            'lowStockItems'      => $itemModel->lowStockItems(5),
            'recentTransactions' => $trxModel->recent(5),
            'todayTransactions'  => $trxModel->todayCount(),
        ]);
    }
}