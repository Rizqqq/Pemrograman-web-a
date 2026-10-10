<?php
class HomeController extends Controller {
    public function index(): void {
        // Mengirim data teks ke file View
        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'pesan' => 'Hello World! MVC murni tanpa framework berhasil berjalan.'
        ]);
    }
}