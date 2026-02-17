<?php

require_once __DIR__ . '/../models/Vente.php';
require_once __DIR__ . '/../models/Setting.php';

class VenteController {
    private $venteModel;
    
    public function __construct() {
        $this->venteModel = new Vente();
    }
    
    // Liste des ventes
    public function index() {
        $type = $_GET['type'] ?? null;
        $date = $_GET['date'] ?? null;
        
        $ventes = Vente::getAllWithDon($type, $date);
        
        Flight::render('ventes/index', [
            'ventes' => $ventes,
            'filter_type' => $type,
            'filter_date' => $date
        ]);
    }
    
    // Formulaire de création de vente
    public function create() {
        $dons = Vente::getDonsVendables();
        $reduction = Setting::getReductionPourcentage();
        
        Flight::render('ventes/create', [
            'dons' => $dons,
            'reduction_pourcentage' => $reduction
        ]);
    }
    
    // Enregistrer une vente
    public function store() {
        $donId = (int)($_POST['don_id'] ?? 0);
        $quantite = (float)($_POST['quantite'] ?? 0);
        $prixUnitaire = (float)($_POST['prix_unitaire'] ?? 0);
        $observations = trim($_POST['observations'] ?? '');
        
        // Validation
        $errors = [];
        
        if ($donId <= 0) {
            $errors[] = 'Veuillez sélectionner un don';
        }
        
        if ($quantite <= 0) {
            $errors[] = 'La quantité doit être supérieure à 0';
        }
        
        if ($prixUnitaire <= 0) {
            $errors[] = 'Le prix unitaire doit être supérieur à 0';
        }
        
        // Vérifier si le don peut être vendu
        if ($donId > 0 && !Vente::peutEtreVendu($donId)) {
            $errors[] = Vente::getRaisonNonVendable($donId);
        }
        
        // Vérifier la quantité disponible
        if ($donId > 0) {
            $db = Model::getDB();
            $stmt = $db->prepare("SELECT quantite_disponible FROM dons WHERE id = ?");
            $stmt->execute([$donId]);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($don && $quantite > $don['quantite_disponible']) {
                $errors[] = 'La quantité demandée dépasse la quantité disponible (' . number_format($don['quantite_disponible'], 0, ',', ' ') . ')';
            }
        }
        
        if (!empty($errors)) {
            $dons = Vente::getDonsVendables();
            $reduction = Setting::getReductionPourcentage();
            
            Flight::render('ventes/create', [
                'dons' => $dons,
                'reduction_pourcentage' => $reduction,
                'errors' => $errors,
                'data' => [
                    'don_id' => $donId,
                    'quantite' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'observations' => $observations
                ]
            ]);
            return;
        }
        
        try {
            Vente::createVente($donId, $quantite, $prixUnitaire, $observations);
            Flight::redirect('/ventes?success=Vente enregistrée avec succès');
        } catch (Exception $e) {
            Flight::redirect('/ventes/create?error=' . urlencode($e->getMessage()));
        }
    }
    
    // Supprimer une vente
    public function delete($id) {
        $db = Model::getDB();
        
        // Obtenir les détails de la vente
        $stmt = $db->prepare("SELECT * FROM ventes WHERE id = ?");
        $stmt->execute([$id]);
        $vente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($vente) {
            // Restaurer la quantité dans le don
            $updateStmt = $db->prepare("UPDATE dons SET quantite_disponible = quantite_disponible + ? WHERE id = ?");
            $updateStmt->execute([$vente['quantite_vendue'], $vente['don_id']]);
            
            // Supprimer la vente
            $deleteStmt = $db->prepare("DELETE FROM ventes WHERE id = ?");
            $deleteStmt->execute([$id]);
            
            Flight::redirect('/ventes?success=Vente supprimée et quantité restaurée');
        } else {
            Flight::redirect('/ventes?error=Vente non trouvée');
        }
    }
    
    // Paramètres de vente
    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reduction = (float)($_POST['reduction_pourcentage'] ?? 20);
            
            if ($reduction < 0 || $reduction > 100) {
                Flight::redirect('/ventes/settings?error=Le pourcentage doit être entre 0 et 100');
                return;
            }
            
            Setting::setReductionPourcentage($reduction);
            Flight::redirect('/ventes/settings?success=Paramètres enregistrés avec succès');
            return;
        }
        
        $reduction = Setting::getReductionPourcentage();
        Flight::render('ventes/settings', [
            'reduction_pourcentage' => $reduction
        ]);
    }
    
    // API: Vérifier si un don peut être vendu
    public function checkCanSell() {
        $donId = (int)($_GET['don_id'] ?? 0);
        
        $result = [
            'can_sell' => Vente::peutEtreVendu($donId),
            'reason' => Vente::getRaisonNonVendable($donId)
        ];
        
        Flight::json($result);
    }
    
    // Réinitialiser la base de données à l'état initial
    public function reset() {
        try {
            $db = Model::getDB();
            
            // Désactiver les contraintes de clé étrangère
            $db->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            // Vider toutes les tables
            $db->exec("TRUNCATE TABLE ventes");
            $db->exec("TRUNCATE TABLE achats");
            $db->exec("TRUNCATE TABLE attributions");
            $db->exec("TRUNCATE TABLE besoins");
            $db->exec("TRUNCATE TABLE dons");
            $db->exec("TRUNCATE TABLE villes");
            $db->exec("TRUNCATE TABLE settings");
            
            // Réactiver les contraintes
            $db->exec("SET FOREIGN_KEY_CHECKS = 1");
            
            // Réinsérer les données initiales - Villes avec régions
            $db->exec("INSERT INTO villes (nom, region, description) VALUES 
                ('Toamasina', 'Atsinanana', 'Port principal de Madagascar'),
                ('Mananjary', 'Fitovinany', 'Ville côtière du sud-est'),
                ('Farafangana', 'Fitovinany', 'Ville du sud-est'),
                ('Nosy Be', 'Diana', 'Île touristique du nord'),
                ('Morondava', 'Menabe', 'Ville côtière de l''ouest')");
            
            // Réinsérer les données initiales - Besoins pour Toamasina
            $db->exec("INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
                (1, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 800, 'kg', 3000),
                (1, 'nature', 'Eau (L)', 'Eau potable', 1500, 'L', 1000),
                (1, 'materiaux', 'Tôle', 'Tôles pour reconstruction', 120, 'unités', 25000),
                (1, 'materiaux', 'Bâche', 'Bâches de protection', 200, 'unités', 15000),
                (1, 'argent', 'Argent', 'Aide financière', 12000000, 'Ar', 1),
                (1, 'materiaux', 'Groupe', 'Groupe électrogène', 3, 'unités', 6750000)");
            
            // Besoins pour Mananjary
            $db->exec("INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
                (2, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 500, 'kg', 3000),
                (2, 'nature', 'Huile (L)', 'Huile de cuisine', 120, 'L', 6000),
                (2, 'materiaux', 'Tôle', 'Tôles pour reconstruction', 80, 'unités', 25000),
                (2, 'materiaux', 'Clous (kg)', 'Clous de construction', 60, 'kg', 8000),
                (2, 'argent', 'Argent', 'Aide financière', 6000000, 'Ar', 1)");
            
            // Besoins pour Farafangana
            $db->exec("INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
                (3, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 600, 'kg', 3000),
                (3, 'nature', 'Eau (L)', 'Eau potable', 1000, 'L', 1000),
                (3, 'materiaux', 'Bâche', 'Bâches de protection', 150, 'unités', 15000),
                (3, 'materiaux', 'Bois', 'Bois de construction', 100, 'unités', 10000),
                (3, 'argent', 'Argent', 'Aide financière', 8000000, 'Ar', 1)");
            
            // Besoins pour Nosy Be
            $db->exec("INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
                (4, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 300, 'kg', 3000),
                (4, 'nature', 'Haricots', 'Haricots secs', 200, 'kg', 4000),
                (4, 'materiaux', 'Tôle', 'Tôles pour reconstruction', 40, 'unités', 25000),
                (4, 'materiaux', 'Clous (kg)', 'Clous de construction', 30, 'kg', 8000),
                (4, 'argent', 'Argent', 'Aide financière', 4000000, 'Ar', 1)");
            
            // Besoins pour Morondava
            $db->exec("INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
                (5, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 700, 'kg', 3000),
                (5, 'nature', 'Eau (L)', 'Eau potable', 1200, 'L', 1000),
                (5, 'materiaux', 'Bâche', 'Bâches de protection', 180, 'unités', 15000),
                (5, 'materiaux', 'Bois', 'Bois de construction', 150, 'unités', 10000),
                (5, 'argent', 'Argent', 'Aide financière', 10000000, 'Ar', 1)");
            
            // Réinsérer les données initiales - Dons
            $db->exec("INSERT INTO dons (type_don, nom, description, quantite_disponible, unite, donateur) VALUES
                ('nature', 'Riz', 'Riz blanc de qualité', 10000, 'kg', 'ONG Internationale'),
                ('nature', 'Eau', 'Eau minérale', 20000, 'L', 'Caritas'),
                ('nature', 'Huile', 'Huile végétale', 2000, 'L', 'Programme Alimentaire'),
                ('materiaux', 'Tôle', 'Tôles galvanisées', 1000, 'unités', 'ONG Reconstruction'),
                ('materiaux', 'Bâche', 'Bâches plastiques', 1500, 'unités', 'Protection Civile'),
                ('materiaux', 'Bois', 'Bois de construction', 800, 'unités', 'Entreprise BTP'),
                ('materiaux', 'Clous', 'Clous de construction', 500, 'kg', 'Magasin Bâtiment'),
                ('materiaux', 'Groupe', 'Groupe électrogène', 10, 'unités', 'ONG Energie'),
                ('argent', 'Don financier', 'Contribution financière', 50000000, 'Ar', 'Donateurs divers')");
            
            // Réinsérer les paramètres par défaut
            $db->exec("INSERT INTO settings (setting_key, setting_value) VALUES ('reduction_pourcentage', '10')");
            
            Flight::redirect('/?success=Base de données réinitialisée avec succès');
        } catch (Exception $e) {
            Flight::redirect('/?error=' . urlencode('Erreur lors de la réinitialisation: ' . $e->getMessage()));
        }
    }
}
