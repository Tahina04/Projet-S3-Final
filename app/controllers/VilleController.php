<?php

require_once __DIR__ . '/../models/Ville.php';

class VilleController {
    private $villeModel;
    
    public function __construct() {
        $this->villeModel = new Ville();
    }

    public function index() {
        $villes = $this->villeModel->getAllWithNeedsCount();
        Flight::render('villes/index', ['villes' => $villes]);
    }

    public function show($id) {
        $ville = $this->villeModel->getWithBesoins($id);
        
        if (!$ville) {
            Flight::redirect('/villes?error=Ville non trouvée');
            return;
        }
        
        Flight::render('villes/show', ['ville' => $ville]);
    }
 
    public function create() {
        Flight::render('villes/create');
    }

    public function store() {
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'region' => trim($_POST['region'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];
        
        // Validation
        $errors = [];
        if (empty($data['nom'])) {
            $errors[] = 'Le nom de la ville est requis';
        }
        if (empty($data['region'])) {
            $errors[] = 'La région est requise';
        }
        
        if (!empty($errors)) {
            Flight::render('villes/create', [
                'errors' => $errors,
                'data' => $data
            ]);
            return;
        }
        
        $id = $this->villeModel->create($data);
        Flight::redirect('/villes?success=Ville créée avec succès');
    }

    public function edit($id) {
        $ville = $this->villeModel->find($id);
        
        if (!$ville) {
            Flight::redirect('/villes?error=Ville non trouvée');
            return;
        }
        
        Flight::render('villes/edit', ['ville' => $ville]);
    }

    public function update($id) {
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'region' => trim($_POST['region'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];
        
        // Validation
        $errors = [];
        if (empty($data['nom'])) {
            $errors[] = 'Le nom de la ville est requis';
        }
        if (empty($data['region'])) {
            $errors[] = 'La région est requise';
        }
        
        if (!empty($errors)) {
            $ville = $this->villeModel->find($id);
            Flight::render('villes/edit', [
                'errors' => $errors,
                'ville' => $ville,
                'data' => $data
            ]);
            return;
        }
        
        $this->villeModel->update($id, $data);
        Flight::redirect('/villes?success=Ville mise à jour avec succès');
    }

    public function delete($id) {
        $this->villeModel->delete($id);
        Flight::redirect('/villes?success=Ville supprimée avec succès');
    }

    public function search() {
        $term = $_GET['q'] ?? '';
        $villes = $this->villeModel->search($term);
        Flight::json($villes);
    }
}
