<?php
class Employee extends Model {
    protected string $table = 'employees';
    public function all(): array {
        return $this->db->query(
        "SELECT c.*, (SELECT COUNT(*) FROM items i WHERE i.employee_id = c.id) AS item_count
        FROM employees c ORDER BY c.name"
        )->fetchAll();
    }
    public function create(array $data): bool {
        return $this->db->prepare(
        "INSERT INTO employees (nip, name, department, position, salary) VALUES (:nip, :name, :department, :position, :salary)"
        )->execute($data);
    }
    public function update(int $id, array $data): bool {
        $data['id'] = $id;
        return $this->db->prepare(
        "UPDATE employees SET nip = :nip, name = :name, department = :department, position = :position, salary = :salary WHERE id = :id"
        )->execute($data);
    }
}