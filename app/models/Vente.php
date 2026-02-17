<?php
// Vente Model - Gestion des ventes de dons
class Vente extends Model {
    protected $table = 'ventes';
    
    // Obtenir tous les besoins non satisfaits pour un type de don
    public static function getBesoinsNonSatisfaits($nomDon) {
        $db = self::getDB();
        $sql = "
            SELECT 
                b.id,
                b.nom,
                b.type_besoin,
                b.quantite_requise,
                COALESCE(SUM(a.quantite_attribuee), 0) as total_attribue,
                (b.quantite_requise - COALESCE(SUM(a.quantite_attribuee), 0)) as reste
            FROM besoins b
            LEFT JOIN attributions a ON b.id = a.besoin_id
            WHERE LOWER(b.nom) = LOWER(?)
            GROUP BY b.id
            HAVING reste > 0
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$nomDon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Vérifier si un don peut être vendu (aucun besoin non satisfait pour ce nom)
    public static function peutEtreVendu($donId) {
        $db = self::getDB();
        
        // Obtenir le nom du don
        $stmt = $db->prepare("SELECT nom FROM dons WHERE id = ?");
        $stmt->execute([$donId]);
        $don = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$don) {
            return false;
        }
        
        // Vérifier s'il y a des besoins non satisfaits pour ce nom
        $besoins = self::getBesoinsNonSatisfaits($don['nom']);
        
        // Si aucun besoin non satisfait, le don peut être vendu
        return count($besoins) === 0;
    }
    
    // Obtenir la raison pour laquelle un don ne peut pas être vendu
    public static function getRaisonNonVendable($donId) {
        $db = self::getDB();
        
        // Obtenir le nom du don
        $stmt = $db->prepare("SELECT nom FROM dons WHERE id = ?");
        $stmt->execute([$donId]);
        $don = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$don) {
            return "Don non trouvé";
        }
        
        // Vérifier s'il y a des besoins non satisfaits pour ce nom
        $besoins = self::getBesoinsNonSatisfaits($don['nom']);
        
        if (count($besoins) > 0) {
            $villes = [];
            foreach ($besoins as $besoin) {
                // Obtenir le nom de la ville
                $stmt = $db->prepare("
                    SELECT v.nom FROM villes v 
                    JOIN besoins b ON v.id = b.ville_id 
                    WHERE b.id = ?
                ");
                $stmt->execute([$besoin['id']]);
                $ville = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($ville) {
                    $villes[] = $ville['nom'] . ' (' . number_format($besoin['reste'], 0, ',', ' ') . ' ' . $besoin['nom'] . ' requis)';
                }
            }
            return "Encoreneeded dans: " . implode(', ', $villes);
        }
        
        return null;
    }
    
    // Obtenir tous les dons qui peuvent être vendus
    public static function getDonsVendables() {
        $db = self::getDB();
        $sql = "
            SELECT d.*, 
                (SELECT COUNT(*) FROM besoins b 
                 WHERE LOWER(b.nom) = LOWER(d.nom) 
                 AND (b.quantite_requise - COALESCE(
                     (SELECT SUM(a.quantite_attribuee) FROM attributions a WHERE a.besoin_id = b.id), 0
                 )) > 0) as besoin_existant
            FROM dons d
            WHERE d.quantite_disponible > 0 AND d.type_don != 'argent'
        ";
        $stmt = $db->query($sql);
        $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Filtrer les dons qui peuvent être vendus
        $vendables = [];
        foreach ($dons as $don) {
            $peutVendre = self::peutEtreVendu($don['id']);
            $don['peut_vendre'] = $peutVendre;
            $don['raison_non_vendable'] = $peutVendre ? null : self::getRaisonNonVendable($don['id']);
            $vendables[] = $don;
        }
        
        return $vendables;
    }
    
    // Créer une vente
    public static function createVente($donId, $quantite, $prixUnitaire, $observations = '') {
        $db = self::getDB();
        
        // Vérifier si le don existe et n'est pas de type argent
        $stmt = $db->prepare("SELECT nom, type_don FROM dons WHERE id = ?");
        $stmt->execute([$donId]);
        $don = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$don) {
            throw new Exception("Don non trouvé.");
        }
        
        if ($don['type_don'] === 'argent') {
            throw new Exception("Les dons financiers (argent) ne peuvent pas être vendus.");
        }
        
        // Vérifier si le don peut être vendu
        if (!self::peutEtreVendu($donId)) {
            throw new Exception("Ce don ne peut pas être vendu car il existe encore des besoins non satisfaits.");
        }
        
        // Obtenir le pourcentage de réduction
        $reductionPourcentage = Setting::getReductionPourcentage();
        
        // Calculer les prix
        $prixTotal = $quantite * $prixUnitaire;
        $prixApresReduction = $prixTotal * (1 - $reductionPourcentage / 100);
        
        // Insérer la vente
        $sql = "INSERT INTO ventes (don_id, quantite_vendue, prix_unitaire, prix_total, reduction_pourcentage, prix_apres_reduction, observations) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$donId, $quantite, $prixUnitaire, $prixTotal, $reductionPourcentage, $prixApresReduction, $observations]);
        
        // Mettre à jour la quantité disponible du don
        $updateSql = "UPDATE dons SET quantite_disponible = quantite_disponible - ? WHERE id = ?";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->execute([$quantite, $donId]);
        
        return $db->lastInsertId();
    }
    
    // Obtenir toutes les ventes avec les informations du don
    public static function getAllWithDon($type = null, $date = null) {
        $db = self::getDB();
        $sql = "
            SELECT v.*, d.nom as don_nom, d.type_don, d.unite as don_unite
            FROM ventes v
            JOIN dons d ON v.don_id = d.id
            WHERE 1=1
        ";
        $params = [];
        
        if ($type && $type !== 'all') {
            $sql .= " AND d.type_don = ?";
            $params[] = $type;
        }
        
        if ($date) {
            $sql .= " AND DATE(v.date_vente) = ?";
            $params[] = $date;
        }
        
        $sql .= " ORDER BY v.date_vente DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtenir toutes les ventes avec les informations du don (compatibilité)
    public static function getAllWithDonOld() {
        return self::getAllWithDon();
    }
}
