<?php

require_once __DIR__ . '/../models/Attribution.php';
require_once __DIR__ . '/../models/Don.php';
require_once __DIR__ . '/../models/Besoin.php';

class AttributionController {
    private $attributionModel;
    private $donModel;
    private $besoinModel;
    
    public function __construct() {
        $this->attributionModel = new Attribution();
        $this->donModel = new Don();
        $this->besoinModel = new Besoin();
    }

    public function index() {
        $attributions = $this->attributionModel->getAllWithDetails();
        Flight::render('attributions/index', ['attributions' => $attributions]);
    }

    public function show($id) {
        // Get attribution with details
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT a.*, 
                   d.nom as don_nom, d.type_don, d.unite as don_unite, d.quantite_disponible,
                   b.nom as besoin_nom, b.quantite_requise, b.unite as besoin_unite, b.type_besoin,
                   v.nom as ville_nom, v.region
            FROM attributions a
            JOIN dons d ON a.don_id = d.id
            JOIN besoins b ON a.besoin_id = b.id
            JOIN villes v ON b.ville_id = v.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        $attribution = $stmt->fetch();
        
        if (!$attribution) {
            Flight::redirect('/attributions?error=Attribution non trouvée');
            return;
        }
        
        Flight::render('attributions/show', ['attribution' => $attribution]);
    }

    public function create($preselectedDonId = null) {
        $dons = $this->donModel->getAvailableDonations();
        $besoins = $this->besoinModel->getUncoveredNeeds();
        
        Flight::render('attributions/create', [
            'dons' => $dons,
            'besoins' => $besoins,
            'preselected_don_id' => $preselectedDonId
        ]);
    }

    public function store() {
        $donId = (int)($_POST['don_id'] ?? 0);
        $besoinId = (int)($_POST['besoin_id'] ?? 0);
        $quantite = (float)($_POST['quantite_attribuee'] ?? 0);
        $observations = trim($_POST['observations'] ?? '');
        
        // Validation
        $errors = [];
        if (empty($donId)) {
            $errors[] = 'Le don est requis';
        }
        if (empty($besoinId)) {
            $errors[] = 'Le besoin est requis';
        }
        if ($quantite <= 0) {
            $errors[] = 'La quantité doit être supérieure à 0';
        }
        
        if (!empty($errors)) {
            $dons = $this->donModel->getAvailableDonations();
            $besoins = $this->besoinModel->getUncoveredNeeds();
            
            Flight::render('attributions/create', [
                'errors' => $errors,
                'dons' => $dons,
                'besoins' => $besoins,
                'data' => [
                    'don_id' => $donId,
                    'besoin_id' => $besoinId,
                    'quantite_attribuee' => $quantite,
                    'observations' => $observations
                ]
            ]);
            return;
        }
        
        // Create attribution with validation
        $result = $this->attributionModel->createAttribution($donId, $besoinId, $quantite, $observations);
        
        if (!$result['success']) {
            $dons = $this->donModel->getAvailableDonations();
            $besoins = $this->besoinModel->getUncoveredNeeds();
            
            Flight::render('attributions/create', [
                'errors' => [$result['message']],
                'dons' => $dons,
                'besoins' => $besoins,
                'data' => [
                    'don_id' => $donId,
                    'besoin_id' => $besoinId,
                    'quantite_attribuee' => $quantite,
                    'observations' => $observations
                ]
            ]);
            return;
        }
        
        Flight::redirect('/attributions?success=' . urlencode($result['message']));
    }

    public function delete($id) {
        $this->attributionModel->delete($id);
        Flight::redirect('/attributions?success=Attribution supprimée avec succès');
    }

    public function byType() {
        $type = $_GET['type'] ?? '';
        $dons = $this->donModel->getByType($type);
        
        // Filter to only show available donations
        $available = array_filter($dons, function($don) {
            return $don['disponible'] > 0;
        });
        
        Flight::json(array_values($available));
    }

    public function besoinsByType() {
        $type = $_GET['type'] ?? '';
        $besoins = $this->besoinModel->getByType($type);
        
        // Filter to only show uncovered needs
        $uncovered = array_filter($besoins, function($besoin) {
            return $besoin['reste'] > 0;
        });
        
        Flight::json(array_values($uncovered));
    }

    public function getDonDetails() {
        $id = (int)($_GET['id'] ?? 0);
        $don = $this->donModel->getWithAttributions($id);
        Flight::json($don);
    }

    public function getBesoinDetails() {
        $id = (int)($_GET['id'] ?? 0);
        $besoin = $this->besoinModel->getWithAttributions($id);
        Flight::json($besoin);
    }
}
