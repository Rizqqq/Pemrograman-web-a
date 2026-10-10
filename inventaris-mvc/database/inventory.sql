CREATE DATABASE IF NOT EXISTS db_inventory
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_inventory;

DROP TABLE IF EXISTS transactions, items, categories, users;

-- 1. Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin','staff','manager') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel Items
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    category_id INT NULL,
    quantity INT NOT NULL DEFAULT 0,
    unit VARCHAR(20) NOT NULL DEFAULT 'unit',
    price DECIMAL(15,2) NOT NULL DEFAULT 0,
    entry_date DATE NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_items_category FOREIGN KEY (category_id)
    REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabel Transactions
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('IN','OUT') NOT NULL,
    quantity INT NOT NULL,
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transactions_item FOREIGN KEY (item_id) 
    REFERENCES items(id) ON DELETE CASCADE,
    CONSTRAINT fk_transactions_user FOREIGN KEY (user_id) 
    REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED DATA (DATA AWAL CONTOH)
-- Password untuk semua user adalah: 123456
INSERT INTO users (username, password, full_name, role) VALUES
('admin', '123aaa123', 'Administrator', 'admin'),
('staff', '321aaa321', 'Staff Gudang', 'staff');

INSERT INTO categories (name, description) VALUES
('Elektronik', 'Peralatan dan perangkat elektronik kantor'),
('Alat Tulis Kantor', 'Kebutuhan ATK sehari-hari'),
('Perabotan', 'Meja, kursi, dan lemari');

INSERT INTO items (code, name, category_id, quantity, unit, price, entry_date) VALUES
('ITM0001', 'Laptop ThinkPad T480', 1, 15, 'unit', 7500000.00, '2026-01-10'),
('ITM0002', 'Monitor Dell 24 Inch', 1, 8, 'unit', 2100000.00, '2026-01-12'),
('ITM0003', 'Kertas HVS A4 80gr', 2, 50, 'rim', 48000.00, '2026-02-01'),
('ITM0004', 'Kursi Kerja Ergonomis', 3, 5, 'unit', 1250000.00, '2026-02-05');