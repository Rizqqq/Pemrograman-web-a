<?php

class AuthController extends Controller {

    // Halaman Form Login
    public function login(): void {
        if (Middleware::isLoggedIn()) {
            $this->redirect('dashboard');
        }
        $this->view('auth/login', ['title' => 'Login']);
    }

    // Proses Autentikasi
    public function authenticate(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('auth/login');

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->model('User')->login($username, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $this->flash('success', 'Selamat datang, ' . $user['full_name'] . '!');
            $this->redirect('dashboard');
        }

        $this->flash('danger', 'Username atau password salah.');
        $this->redirect('auth/login');
    }

    // Proses Logout
    public function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
        session_start();
        $this->flash('success', 'Anda telah berhasil keluar.');
        $this->redirect('auth/login');
    }
}