<?php

class Besoin extends Model {
    protected $table = 'besoins';

    public function getAllWithDetails() {
        $stmt = $this->pdo->query("
            SELECT b.*, v.nom as ville_nom, v.region,
                   COALESCE(SUM(a.quantite_attribuee), 0) as total_attribue,
                   (b.quantite_requise - COALESCE(SUM(a.quantite_attribuee), 0)) as reste
            FROM besoins b
            JOIN villes v ON b.ville_id = v.id
            LEFT JOIN attributions a ON b.id = a.besoin_id
            GROUP BY b.id
            ORDER BY v.nom, b.nom
        ");
        return $stmt->fetchAll();
    }

    public function getByVille($villeId) {
        $stmt = $this->pdo->prepare("
            SELECT b.*,
                   COALESCE(SUM(a.quantite_attribuee), 0) as total_attribue,
                   (b.quantite_requise - COALESCE(SUM(a.quantite_attribuee), 0)) as reste
            FROM besoins b
            LEFT JOIN attributions a ON b.id = a.besoin_id
            WHERE b.ville_id = ?
            GROUP BY b.id
            ORDER BY b.nom
        ");
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }

    public function getWithAttributions($id) {
        $stmt = $this->pdo->prepare("
            SELECT b.*, v.nom as ville_nom, v.region
            FROM besoins b
            JOIN villes v ON b.ville_id = v.id
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        $besoin = $stmt->fetch();
        
        if ($besoin) {
            $stmt = $this->pdo->prepare("
                SELECT a.*, d.nom as don_nom, d.type_don
                FROM attributions a
                JOIN dons d ON a.don_id = d.id
                WHERE a.besoin_id = ?
                ORDER BY a.date_attribution DESC
            ");
            $stmt->execute([$id]);
            $besoin['attributions'] = $stmt->fetchAll();
            
            // Calcul total
            $besoin['total_attribue'] = array_sum(array_column($besoin['attributions'], 'quantite_attribuee'));
            $besoin['reste'] = $besoin['quantite_requise'] - $besoin['total_attribue'];
        }
        return $besoin;
    }

    public function getByType($type) {
        $stmt = $this->pdo->prepare("
            SELECT b.*, v.nom as ville_nom, v.region,
                   COALESCE(SUM(a.quantite_attribuee), 0) as total_attribue,
                   (b.quantite_requise - COALESCE(SUM(a.quantite_attribuee), 0)) as reste
            FROM besoins b
            JOIN villes v ON b.ville_id = v.id
            LEFT JOIN attributions a ON b.id = a.besoin_id
            WHERE b.type_besoin = ?
            GROUP BY b.id
            ORDER BY v.nom
        ");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function getUncoveredNeeds($limit = null, $offset = 0) {
        $sql = "
            SELECT b.*, v.nom as ville_nom, v.region,
                   COALESCE(SUM(a.quantite_attribuee), 0) as total_attribue,
                   (b.quantite_requise - COALESCE(SUM(a.quantite_attribuee), 0)) as reste
            FROM besoins b
            JOIN villes v ON b.ville_id = v.id
            LEFT JOIN attributions a ON b.id = a.besoin_id
            GROUP BY b.id
            HAVING reste > 0
            ORDER BY reste DESC
        ";
        
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    public function countUncoveredNeeds() {
        $stmt = $this->pdo->query("
            SELECT COUNT(*) as total
            FROM (
                SELECT b.id,
                       COALESCE(SUM(a.quantite_attribuee), 0) as total_attribue,
                       (b.quantite_requise - COALESCE(SUM(a.quantite_attribuee), 0)) as reste
                FROM besoins b
                LEFT JOIN attributions a ON b.id = a.besoin_id
                GROUP BY b.id
                HAVING reste > 0
            ) as uncovered
        ");
        return $stmt->fetch()['total'];
    }
}
