<?php

class Achat extends Model {
    protected $table = 'achats';
    
    public function getAllWithDetails() {
        $stmt = $this->pdo->query("
            SELECT a.*, 
                   b.nom as besoin_nom, b.type_besoin, b.unite as besoin_unite,
                   b.quantite_requise, b.prix_unitaire as prix_besoin,
                   v.nom as ville_nom, v.region,
                   d.nom as don_nom, d.quantite_disponible as don_quantite
            FROM achats a
            JOIN besoins b ON a.besoin_id = b.id
            JOIN villes v ON b.ville_id = v.id
            JOIN dons d ON a.don_argent_id = d.id
            ORDER BY a.date_achat DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getByVille($villeId) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, 
                   b.nom as besoin_nom, b.type_besoin, b.unite as besoin_unite,
                   d.nom as don_nom
            FROM achats a
            JOIN besoins b ON a.besoin_id = b.id
            JOIN dons d ON a.don_argent_id = d.id
            WHERE b.ville_id = ?
            ORDER BY a.date_achat DESC
        ");
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }
    
    public function createAchat($besoinId, $donArgentId, $quantite, $observations = '') {
        // Vérifier que le don est de type argent
        $donModel = new Don();
        $don = $donModel->find($donArgentId);
        
        if (!$don) {
            return ['success' => false, 'message' => 'Don non trouvé'];
        }
        
        if ($don['type_don'] !== 'argent') {
            return [
                'success' => false, 
                'message' => 'Erreur: Seul les dons en argent peuvent être utilisés pour effectuer des achats'
            ];
        }
        
        // Vérifier le montant disponible
        $disponible = $donModel->getAvailableQuantity($donArgentId);
        
        // Obtenir le prix unitaire du besoin
        $besoinModel = new Besoin();
        $besoin = $besoinModel->find($besoinId);
        
        if (!$besoin) {
            return ['success' => false, 'message' => 'Besoin non trouvé'];
        }
        
        $prixUnitaire = floatval($besoin['prix_unitaire']);
        $montantTotal = $quantite * $prixUnitaire;
        
        if ($montantTotal > $disponible) {
            return [
                'success' => false, 
                'message' => "Erreur: Le montant total ({$montantTotal} Ar) est supérieur au don disponible ({$disponible} Ar)"
            ];
        }
        
        if ($quantite <= 0) {
            return ['success' => false, 'message' => 'La quantité doit être supérieure à 0'];
        }
        
        $data = [
            'besoin_id' => $besoinId,
            'don_argent_id' => $donArgentId,
            'quantite_achetee' => $quantite,
            'prix_unitaire' => $prixUnitaire,
            'montant_total' => $montantTotal,
            'observations' => $observations
        ];
        
        $id = parent::create($data);
        
        return [
            'success' => true, 
            'message' => 'Achat créé avec succès',
            'id' => $id,
            'montant' => $montantTotal
        ];
    }
    
    public function getTotalByVille() {
        $stmt = $this->pdo->query("
            SELECT v.id, v.nom as ville_nom, v.region,
                   COALESCE(SUM(a.montant_total), 0) as total_achats
            FROM villes v
            LEFT JOIN besoins b ON v.id = b.ville_id
            LEFT JOIN achats a ON b.id = a.besoin_id
            GROUP BY v.id
            ORDER BY total_achats DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getTotalAchats() {
        $stmt = $this->pdo->query("
            SELECT COALESCE(SUM(montant_total), 0) as total
            FROM achats
        ");
        $result = $stmt->fetch();
        return floatval($result['total']);
    }
    
    public function getRecapData() {
        // Besoins totaux et satisfaits en montant
        $stmt = $this->pdo->query("
            SELECT 
                COALESCE(SUM(b.quantite_requise * b.prix_unitaire), 0) as besoins_totaux_montant,
                COALESCE(SUM(b.prix_unitaire * COALESCE(at.total_attribue, 0)), 0) as besoins_satisfaits_montant
            FROM besoins b
            LEFT JOIN (
                SELECT besoin_id, SUM(quantite_attribuee) as total_attribue
                FROM attributions
                GROUP BY besoin_id
            ) at ON b.id = at.besoin_id
        ");
        $besoins = $stmt->fetch();
        
        // Dons reçus (argent)
        $stmt = $this->pdo->query("
            SELECT COALESCE(SUM(quantite_disponible), 0) as total_argent
            FROM dons
            WHERE type_don = 'argent'
        ");
        $donsRecus = $stmt->fetch();
        
        // Dons dispatchés (distributions via attributions + achats)
        $stmt = $this->pdo->query("
            SELECT COALESCE(SUM(a.montant_total), 0) as total_dispatche
            FROM achats a
        ");
        $donsDispatche = $stmt->fetch();
        
        return [
            'besoins_totaux_montant' => floatval($besoins['besoins_totaux_montant']),
            'besoins_satisfaits_montant' => floatval($besoins['besoins_satisfaits_montant']),
            'dons_recus_montant' => floatval($donsRecus['total_argent']),
            'dons_dispatche_montant' => floatval($donsDispatche['total_dispatche'])
        ];
    }
}
