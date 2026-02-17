<?php

require_once __DIR__ . '/../models/Achat.php';
require_once __DIR__ . '/../models/Besoin.php';
require_once __DIR__ . '/../models/Don.php';
require_once __DIR__ . '/../models/Ville.php';

class AchatController {
    
    public function index() {
        $achatModel = new Achat();
        $villeModel = new Ville();
        
        $villeId = isset($_GET['ville_id']) ? $_GET['ville_id'] : null;
        
        if ($villeId) {
            $achats = $achatModel->getByVille($villeId);
        } else {
            $achats = $achatModel->getAllWithDetails();
        }
        
        $villes = $villeModel->all();
        $totalAchats = $achatModel->getTotalAchats();
        
        Flight::render('achats/index', [
            'achats' => $achats,
            'villes' => $villes,
            'villeId' => $villeId,
            'totalAchats' => $totalAchats
        ]);
    }
    
    public function create() {
        $besoinModel = new Besoin();
        $donModel = new Don();
        
        // Get only nature and material needs (not argent)
        $besoins = $besoinModel->getAllWithDetails();
        $besoinsAchat = array_filter($besoins, function($b) {
            return in_array($b['type_besoin'], ['nature', 'materiaux']);
        });
        
        // Get only argent donations
        $dons = $donModel->getByType('argent');
        
        Flight::render('achats/create', [
            'besoins' => $besoinsAchat,
            'dons' => $dons
        ]);
    }
    
    public function store() {
        $besoinId = isset($_POST['besoin_id']) ? intval($_POST['besoin_id']) : 0;
        $donArgentId = isset($_POST['don_argent_id']) ? intval($_POST['don_argent_id']) : 0;
        $quantite = isset($_POST['quantite']) ? floatval($_POST['quantite']) : 0;
        $observations = isset($_POST['observations']) ? $_POST['observations'] : '';
        
        if ($besoinId <= 0 || $donArgentId <= 0 || $quantite <= 0) {
            Flight::redirect('/achats/create?error=Données invalides');
            return;
        }
        
        $achatModel = new Achat();
        $result = $achatModel->createAchat($besoinId, $donArgentId, $quantite, $observations);
        
        if ($result['success']) {
            Flight::redirect('/achats?success=' . urlencode($result['message']));
        } else {
            Flight::redirect('/achats/create?error=' . urlencode($result['message']));
        }
    }
    
    public function recap() {
        $achatModel = new Achat();
        
        $recap = $achatModel->getRecapData();
        
        Flight::render('achats/recap', [
            'recap' => $recap
        ]);
    }
    
    public function recapData() {
        $achatModel = new Achat();
        
        $recap = $achatModel->getRecapData();
        
        Flight::json($recap);
    }
    
    public function totalByVille() {
        $achatModel = new Achat();
        
        $totals = $achatModel->getTotalByVille();
        
        Flight::json($totals);
    }
}
