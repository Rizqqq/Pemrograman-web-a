<?php

class Transaction extends Model {
    protected string $table = 'transactions';

    // 1. Ambil semua riwayat transaksi beserta nama barang dan user
    public function all(): array {
        $sql = "SELECT t.*, i.name AS item_name, i.code AS item_code, u.full_name AS user_name 
                FROM transactions t 
                JOIN items i ON t.item_id = i.id 
                LEFT JOIN users u ON t.user_id = u.id 
                ORDER BY t.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // 2. Simpan transaksi stok (Menggunakan Database Transaction)
    public function create(array $data): bool {
        try {
            $this->db->beginTransaction();

            // Simpan riwayat transaksi
            $this->db->prepare(
                "INSERT INTO transactions (item_id, user_id, type, quantity, notes)
                 VALUES (:item_id, :user_id, :type, :quantity, :notes)"
            )->execute([
                ':item_id'  => $data['item_id'],
                ':user_id'  => $data['user_id'],
                ':type'     => $data['type'], // 'IN' atau 'OUT'
                ':quantity' => $data['quantity'],
                ':notes'    => $data['notes'] ?? null,
            ]);

            // Update stok barang (+ jika IN, - jika OUT)
            $op = ($data['type'] === 'IN') ? '+' : '-';
            $this->db->prepare("UPDATE items SET quantity = quantity {$op} :quantity WHERE id = :item_id")
                ->execute([':quantity' => $data['quantity'], ':item_id' => $data['item_id']]);

            // Cek apakah stok menjadi negatif
            $check = $this->db->prepare("SELECT quantity FROM items WHERE id = :item_id");
            $check->execute([':item_id' => $data['item_id']]);
            
            // JIKA STOK < 0 -> BATALKAN SEMUA PERUBAHAN (ROLLBACK)
            if ((int) $check->fetchColumn() < 0) {
                $this->db->rollBack();
                return false;
            }

            // Jika aman, simpan permanen
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }
}