<?php

require_once __DIR__ . '/../models/Besoin.php';
require_once __DIR__ . '/../models/Ville.php';

class BesoinController {
    private $besoinModel;
    private $villeModel;
    
    public function __construct() {
        $this->besoinModel = new Besoin();
        $this->villeModel = new Ville();
    }

    public function index() {
        $type = $_GET['type'] ?? null;
        
        if ($type) {
            $besoins = $this->besoinModel->getByType($type);
        } else {
            $besoins = $this->besoinModel->getAllWithDetails();
        }
        
        Flight::render('besoins/index', [
            'besoins' => $besoins,
            'type_filter' => $type
        ]);
    }

    public function show($id) {
        $besoin = $this->besoinModel->getWithAttributions($id);
        
        if (!$besoin) {
            Flight::redirect('/besoins?error=Besoin non trouvé');
            return;
        }
        
        Flight::render('besoins/show', ['besoin' => $besoin]);
    }

    public function create() {
        $villes = $this->villeModel->all();
        Flight::render('besoins/create', ['villes' => $villes]);
    }

    public function store() {
        $data = [
            'ville_id' => (int)($_POST['ville_id'] ?? 0),
            'type_besoin' => $_POST['type_besoin'] ?? '',
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'quantite_requise' => (float)($_POST['quantite_requise'] ?? 0),
            'unite' => trim($_POST['unite'] ?? '')
        ];
        
        // Validation
        $errors = [];
        if (empty($data['ville_id'])) {
            $errors[] = 'La ville est requise';
        }
        if (empty($data['type_besoin'])) {
            $errors[] = 'Le type de besoin est requis';
        }
        if (empty($data['nom'])) {
            $errors[] = 'Le nom du besoin est requis';
        }
        if ($data['quantite_requise'] <= 0) {
            $errors[] = 'La quantité requise doit être supérieure à 0';
        }
        if (empty($data['unite'])) {
            $errors[] = 'L\'unité est requise';
        }
        
        if (!empty($errors)) {
            $villes = $this->villeModel->all();
            Flight::render('besoins/create', [
                'errors' => $errors,
                'data' => $data,
                'villes' => $villes
            ]);
            return;
        }
        
        $id = $this->besoinModel->create($data);
        Flight::redirect('/besoins?success=Besoin créé avec succès');
    }

    public function edit($id) {
        $besoin = $this->besoinModel->find($id);
        
        if (!$besoin) {
            Flight::redirect('/besoins?error=Besoin non trouvé');
            return;
        }
        
        $villes = $this->villeModel->all();
        Flight::render('besoins/edit', [
            'besoin' => $besoin,
            'villes' => $villes
        ]);
    }

    public function update($id) {
        $data = [
            'ville_id' => (int)($_POST['ville_id'] ?? 0),
            'type_besoin' => $_POST['type_besoin'] ?? '',
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'quantite_requise' => (float)($_POST['quantite_requise'] ?? 0),
            'unite' => trim($_POST['unite'] ?? '')
        ];
        
        // Validation
        $errors = [];
        if (empty($data['ville_id'])) {
            $errors[] = 'La ville est requise';
        }
        if (empty($data['type_besoin'])) {
            $errors[] = 'Le type de besoin est requis';
        }
        if (empty($data['nom'])) {
            $errors[] = 'Le nom du besoin est requis';
        }
        if ($data['quantite_requise'] <= 0) {
            $errors[] = 'La quantité requise doit être supérieure à 0';
        }
        if (empty($data['unite'])) {
            $errors[] = 'L\'unité est requise';
        }
        
        if (!empty($errors)) {
            $villes = $this->villeModel->all();
            $besoin = $this->besoinModel->find($id);
            Flight::render('besoins/edit', [
                'errors' => $errors,
                'besoin' => $besoin,
                'data' => $data,
                'villes' => $villes
            ]);
            return;
        }
        
        $this->besoinModel->update($id, $data);
        Flight::redirect('/besoins?success=Besoin mis à jour avec succès');
    }

    public function delete($id) {
        $this->besoinModel->delete($id);
        Flight::redirect('/besoins?success=Besoin supprimé avec succès');
    }

    public function byVille($villeId) {
        $besoins = $this->besoinModel->getByVille($villeId);
        Flight::json($besoins);
    }
}
