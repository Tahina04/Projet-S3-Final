<?php

class Ville extends Model {
    protected $table = 'villes';

    public function getAllWithNeedsCount() {
        $stmt = $this->pdo->query("
            SELECT v.*, 
                   COUNT(b.id) as besoins_count
            FROM villes v
            LEFT JOIN besoins b ON v.id = b.ville_id
            GROUP BY v.id
            ORDER BY v.nom
        ");
        return $stmt->fetchAll();
    }

    public function getWithBesoins($id) {
        $ville = $this->find($id);
        if ($ville) {
            $stmt = $this->pdo->prepare("SELECT * FROM besoins WHERE ville_id = ? ORDER BY nom");
            $stmt->execute([$id]);
            $ville['besoins'] = $stmt->fetchAll();
        }
        return $ville;
    }

    public function search($term) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM villes 
            WHERE nom LIKE ? OR region LIKE ?
            ORDER BY nom
        ");
        $term = "%{$term}%";
        $stmt->execute([$term, $term]);
        return $stmt->fetchAll();
    }
}
