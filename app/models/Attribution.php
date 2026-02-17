<?php

class Attribution extends Model {
    protected $table = 'attributions';

    public function getAllWithDetails() {
        $stmt = $this->pdo->query("
            SELECT a.*, 
                   d.nom as don_nom, d.type_don, d.unite as don_unite,
                   b.nom as besoin_nom, b.quantite_requise, b.unite as besoin_unite,
                   v.nom as ville_nom, v.region
            FROM attributions a
            JOIN dons d ON a.don_id = d.id
            JOIN besoins b ON a.besoin_id = b.id
            JOIN villes v ON b.ville_id = v.id
            ORDER BY a.date_attribution DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function createAttribution($donId, $besoinId, $quantite, $observations = '') {
        
        $donModel = new Don();
        $don = $donModel->find($donId);
        
        if (!$don) {
            return ['success' => false, 'message' => 'Don non trouvé'];
        }
        
        $disponible = $donModel->getAvailableQuantity($donId);
        
        if ($quantite > $disponible) {
            return [
                'success' => false, 
                'message' => "Erreur: La quantité demandée ({$quantite} {$don['unite']}) est supérieure au don disponible ({$disponible} {$don['unite']})"
            ];
        }
        
        if ($quantite <= 0) {
            return ['success' => false, 'message' => 'La quantité doit être supérieure à 0'];
        }
        
        $besoinModel = new Besoin();
        $besoin = $besoinModel->find($besoinId);
        
        if (!$besoin) {
            return ['success' => false, 'message' => 'Besoin non trouvé'];
        }
        
        if ($don['type_don'] !== $besoin['type_besoin']) {
            return [
                'success' => false, 
                'message' => "Erreur: Le type du don ({$don['type_don']}) ne correspond pas au type du besoin ({$besoin['type_besoin']})"
            ];
        }
        
        $data = [
            'don_id' => $donId,
            'besoin_id' => $besoinId,
            'quantite_attribuee' => $quantite,
            'observations' => $observations
        ];
        
        $id = parent::create($data);
        
        return [
            'success' => true, 
            'message' => 'Attribution créée avec succès',
            'id' => $id
        ];
    }
    
    public function getByDon($donId) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, b.nom as besoin_nom, v.nom as ville_nom
            FROM attributions a
            JOIN besoins b ON a.besoin_id = b.id
            JOIN villes v ON b.ville_id = v.id
            WHERE a.don_id = ?
            ORDER BY a.date_attribution DESC
        ");
        $stmt->execute([$donId]);
        return $stmt->fetchAll();
    }
    
    public function getByBesoin($besoinId) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, d.nom as don_nom, d.donateur
            FROM attributions a
            JOIN dons d ON a.don_id = d.id
            WHERE a.besoin_id = ?
            ORDER BY a.date_attribution DESC
        ");
        $stmt->execute([$besoinId]);
        return $stmt->fetchAll();
    }
    
    public function getRecent($limit = 10) {
        $stmt = $this->pdo->query("
            SELECT a.*, 
                   d.nom as don_nom, d.type_don,
                   b.nom as besoin_nom,
                   v.nom as ville_nom
            FROM attributions a
            JOIN dons d ON a.don_id = d.id
            JOIN besoins b ON a.besoin_id = b.id
            JOIN villes v ON b.ville_id = v.id
            ORDER BY a.date_attribution DESC
            LIMIT {$limit}
        ");
        return $stmt->fetchAll();
    }
}
