<?php

class Don extends Model {
    protected $table = 'dons';
    
    public function getAllWithAvailable() {
        $stmt = $this->pdo->query("
            SELECT d.*,
                   COALESCE(SUM(a.quantite_attribuee), 0) as total_attribue,
                   (d.quantite_disponible - COALESCE(SUM(a.quantite_attribuee), 0)) as disponible
            FROM dons d
            LEFT JOIN attributions a ON d.id = a.don_id
            GROUP BY d.id
            ORDER BY d.nom
        ");
        return $stmt->fetchAll();
    }
    
    public function getWithAttributions($id) {
        $don = $this->find($id);
        if ($don) {
            $stmt = $this->pdo->prepare("
                SELECT a.*, b.nom as besoin_nom, v.nom as ville_nom
                FROM attributions a
                JOIN besoins b ON a.besoin_id = b.id
                JOIN villes v ON b.ville_id = v.id
                WHERE a.don_id = ?
                ORDER BY a.date_attribution DESC
            ");
            $stmt->execute([$id]);
            $don['attributions'] = $stmt->fetchAll();
            
            $don['total_attribue'] = array_sum(array_column($don['attributions'], 'quantite_attribuee'));
            $don['disponible'] = $don['quantite_disponible'] - $don['total_attribue'];
        }
        return $don;
    }

    public function getAvailableDonations() {
        $stmt = $this->pdo->query("
            SELECT d.*,
                   (d.quantite_disponible - COALESCE(SUM(a.quantite_attribuee), 0)) as disponible
            FROM dons d
            LEFT JOIN attributions a ON d.id = a.don_id
            GROUP BY d.id
            HAVING disponible > 0
            ORDER BY d.nom
        ");
        return $stmt->fetchAll();
    }
    
    public function getByType($type) {
        $stmt = $this->pdo->prepare("
            SELECT d.*,
                   (d.quantite_disponible - COALESCE(SUM(a.quantite_attribuee), 0)) as disponible
            FROM dons d
            LEFT JOIN attributions a ON d.id = a.don_id
            WHERE d.type_don = ?
            GROUP BY d.id
            ORDER BY d.nom
        ");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function getAvailableQuantity($id) {
        $don = $this->find($id);
        if (!$don) return 0;
        
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(quantite_attribuee), 0) as total
            FROM attributions
            WHERE don_id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        return (float)$don['quantite_disponible'] - (float)$result['total'];
    }
}
