<?php

class Model {
    protected $pdo;
    protected $table;
    private static $db = null;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Static method to get database connection for static methods
    public static function getDB() {
        if (self::$db === null) {
            require_once CONFIG_PATH . '/database.php';
            global $pdo;
            self::$db = $pdo;
        }
        return self::$db;
    }
    
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));
        
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET {$set} WHERE id = ?");
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    protected function getAvailableQuantity($donId) {
        $stmt = $this->pdo->prepare("
            SELECT d.quantite_disponible - COALESCE(SUM(a.quantite_attribuee), 0) as disponible
            FROM dons d
            LEFT JOIN attributions a ON d.id = a.don_id
            WHERE d.id = ?
            GROUP BY d.id
        ");
        $stmt->execute([$donId]);
        $result = $stmt->fetch();
        return $result ? (float)$result['disponible'] : 0;
    }
}
