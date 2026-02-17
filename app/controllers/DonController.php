<?php

require_once __DIR__ . '/../models/Don.php';

class DonController {
    private $donModel;
    
    public function __construct() {
        $this->donModel = new Don();
    }

    public function index() {
        $type = $_GET['type'] ?? null;
        
        if ($type) {
            $dons = $this->donModel->getByType($type);
        } else {
            $dons = $this->donModel->getAllWithAvailable();
        }
        
        Flight::render('dons/index', [
            'dons' => $dons,
            'type_filter' => $type
        ]);
    }

    public function show($id) {
        $don = $this->donModel->getWithAttributions($id);
        
        if (!$don) {
            Flight::redirect('/dons?error=Don non trouvé');
            return;
        }
        
        Flight::render('dons/show', ['don' => $don]);
    }

    public function create() {
        Flight::render('dons/create');
    }

    public function store() {
        $data = [
            'type_don' => $_POST['type_don'] ?? '',
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'quantite_disponible' => (float)($_POST['quantite_disponible'] ?? 0),
            'unite' => trim($_POST['unite'] ?? ''),
            'date_expiration' => !empty($_POST['date_expiration']) ? $_POST['date_expiration'] : null,
            'donateur' => trim($_POST['donateur'] ?? '')
        ];
        
        // Validation
        $errors = [];
        if (empty($data['type_don'])) {
            $errors[] = 'Le type de don est requis';
        }
        if (empty($data['nom'])) {
            $errors[] = 'Le nom du don est requis';
        }
        if ($data['quantite_disponible'] <= 0) {
            $errors[] = 'La quantité disponible doit être supérieure à 0';
        }
        if (empty($data['unite'])) {
            $errors[] = 'L\'unité est requise';
        }
        
        if (!empty($errors)) {
            Flight::render('dons/create', [
                'errors' => $errors,
                'data' => $data
            ]);
            return;
        }
        
        $id = $this->donModel->create($data);
        Flight::redirect('/dons?success=Don créé avec succès');
    }

    public function edit($id) {
        $don = $this->donModel->find($id);
        
        if (!$don) {
            Flight::redirect('/dons?error=Don non trouvé');
            return;
        }
        
        Flight::render('dons/edit', ['don' => $don]);
    }

    public function update($id) {
        $data = [
            'type_don' => $_POST['type_don'] ?? '',
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'quantite_disponible' => (float)($_POST['quantite_disponible'] ?? 0),
            'unite' => trim($_POST['unite'] ?? ''),
            'date_expiration' => !empty($_POST['date_expiration']) ? $_POST['date_expiration'] : null,
            'donateur' => trim($_POST['donateur'] ?? '')
        ];
        
        // Validation
        $errors = [];
        if (empty($data['type_don'])) {
            $errors[] = 'Le type de don est requis';
        }
        if (empty($data['nom'])) {
            $errors[] = 'Le nom du don est requis';
        }
        if ($data['quantite_disponible'] <= 0) {
            $errors[] = 'La quantité disponible doit être supérieure à 0';
        }
        if (empty($data['unite'])) {
            $errors[] = 'L\'unité est requise';
        }
        
        if (!empty($errors)) {
            $don = $this->donModel->find($id);
            Flight::render('dons/edit', [
                'errors' => $errors,
                'don' => $don,
                'data' => $data
            ]);
            return;
        }
        
        $this->donModel->update($id, $data);
        Flight::redirect('/dons?success=Don mis à jour avec succès');
    }

    public function delete($id) {
        $this->donModel->delete($id);
        Flight::redirect('/dons?success=Don supprimé avec succès');
    }

    public function available() {
        $dons = $this->donModel->getAvailableDonations();
        Flight::json($dons);
    }
}
