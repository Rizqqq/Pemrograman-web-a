<?php
/** @var ApiRouter $router */

$v = '/' . API_VERSION;

// Discovery & health (publik)
$router->get($v, [MetaController::class, 'index']);
$router->get($v . '/health', [MetaController::class, 'health']);

// Identitas & token
$router->get($v . '/me', [AuthController::class, 'me']);
$router->get($v . '/tokens', [AuthController::class, 'tokensIndex']);
$router->post($v . '/tokens', [AuthController::class, 'tokensStore']);
$router->delete($v . '/tokens/{id}', [AuthController::class, 'tokensDestroy']);

// Barang
$router->get($v . '/items', [ItemsController::class, 'index']);
$router->post($v . '/items', [ItemsController::class, 'store']);
$router->get($v . '/items/{id}', [ItemsController::class, 'show']);
$router->put($v . '/items/{id}', [ItemsController::class, 'update']);
$router->patch($v . '/items/{id}', [ItemsController::class, 'patch']);
$router->delete($v . '/items/{id}', [ItemsController::class, 'destroy']);
$router->post($v . '/items/{id}/restore', [ItemsController::class, 'restore']);
$router->get($v . '/items/{id}/transactions', [ItemsController::class, 'history']);

// Kategori
$router->get($v . '/categories', [CategoriesController::class, 'index']);
$router->post($v . '/categories', [CategoriesController::class, 'store']);
$router->get($v . '/categories/{id}', [CategoriesController::class, 'show']);
$router->put($v . '/categories/{id}', [CategoriesController::class, 'update']);
$router->patch($v . '/categories/{id}', [CategoriesController::class, 'patch']);
$router->delete($v . '/categories/{id}', [CategoriesController::class, 'destroy']);
$router->post($v . '/categories/{id}/restore', [CategoriesController::class, 'restore']);

// Transaksi (append-only)
$router->get($v . '/transactions', [TransactionsController::class, 'index']);
$router->post($v . '/transactions', [TransactionsController::class, 'store']);
$router->get($v . '/transactions/{id}', [TransactionsController::class, 'show']);

// Laporan
$router->get($v . '/dashboard', [ReportsController::class, 'dashboard']);
$router->get($v . '/reports/low-stock', [ReportsController::class, 'lowStock']);
$router->get($v . '/reports/stock', [ReportsController::class, 'stock']);
$router->get($v . '/reports/transactions', [ReportsController::class, 'transactions']);
$router->get($v . '/logs', [ReportsController::class, 'logs']);
$router->get($v . '/request-logs', [ReportsController::class, 'requestLogs']);

// Pengguna (read-only)
$router->get($v . '/users', [UsersController::class, 'index']);
$router->get($v . '/users/{id}', [UsersController::class, 'show']);