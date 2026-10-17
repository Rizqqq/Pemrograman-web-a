<?php

class Item extends Model {
    protected string $table = 'items';

    // 1. Generate kode barang otomatis (ITM0001, ITM0002, dst.)
    public function generateCode(): string {
        $max = $this->db->query(
            "SELECT MAX(CAST(SUBSTRING(code, 4) AS UNSIGNED)) FROM items WHERE code LIKE 'ITM%'"
        )->fetchColumn();
        return 'ITM' . str_pad((string)((($max ? (int)$max : 0) + 1)), 4, '0', STR_PAD_LEFT);
    }

    // 2. Ambil data barang dengan paginasi, pencarian, dan filter (sesuai format view v1.1)
    public function paginate(int $limit = 10, int $page = 1, string $search = '', string $categoryId = ''): array {
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];

        if ($search !== '') {
            $where[] = "(i.code LIKE :search OR i.name LIKE :search OR c.name LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        if ($categoryId !== '') {
            $where[] = "i.category_id = :category_id";
            $params['category_id'] = (int) $categoryId;
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Hitung total data
        $count = $this->db->prepare("SELECT COUNT(*) FROM items i LEFT JOIN categories c ON i.category_id = c.id {$whereSql}");
        $count->execute($params);
        $total = (int) $count->fetchColumn();

        // Ambil data barang
        $stmt = $this->db->prepare(
            "SELECT i.*, c.name AS category_name 
             FROM items i 
             LEFT JOIN categories c ON i.category_id = c.id 
             {$whereSql} 
             ORDER BY i.id DESC 
             LIMIT {$limit} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return [
            'data'         => $stmt->fetchAll(),
            'current_page' => $page,
            'total_pages'  => (int) ceil($total / max($limit, 1)),
            'total_data'   => $total,
        ];
    }

    // 3. Simpan barang baru
    public function create(array $data): bool {
        $sql = "INSERT INTO items (code, name, category_id, quantity, unit, price, entry_date, description) 
                VALUES (:code, :name, :category_id, :quantity, :unit, :price, :entry_date, :description)";
        return $this->db->prepare($sql)->execute($data);
    }

    // 4. Update barang
    public function update(int $id, array $data): bool {
        $data['id'] = $id;
        $sql = "UPDATE items SET 
                code = :code, 
                name = :name, 
                category_id = :category_id, 
                quantity = :quantity, 
                unit = :unit, 
                price = :price, 
                entry_date = :entry_date, 
                description = :description 
                WHERE id = :id";
        return $this->db->prepare($sql)->execute($data);
    }
}