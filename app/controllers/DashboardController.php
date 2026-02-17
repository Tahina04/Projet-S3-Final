<?php

require_once __DIR__ . '/../models/Ville.php';
require_once __DIR__ . '/../models/Besoin.php';
require_once __DIR__ . '/../models/Don.php';
require_once __DIR__ . '/../models/Attribution.php';
require_once __DIR__ . '/../models/Achat.php';

class DashboardController {

    public function index() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        $attributionModel = new Attribution();
        $achatModel = new Achat();
        
       
        $stats = [
            'total_villes' => count($villeModel->all()),
            'total_besoins' => count($besoinModel->all()),
            'total_dons' => count($donModel->all()),
            'total_attributions' => count($attributionModel->all())
        ];
        
        $villes = $villeModel->getAllWithNeedsCount();

        $besoins = $besoinModel->getAllWithDetails();

        $recentAttributions = $attributionModel->getRecent(10);

        // Pagination for uncovered needs
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $itemsPerPage = 6;
        $offset = ($page - 1) * $itemsPerPage;
        
        $totalUncoveredNeeds = $besoinModel->countUncoveredNeeds();
        $totalPages = ceil($totalUncoveredNeeds / $itemsPerPage);
        $uncoveredNeeds = $besoinModel->getUncoveredNeeds($itemsPerPage, $offset);

        $totalsByType = [
            'nature' => ['required' => 0, 'attributed' => 0],
            'materiaux' => ['required' => 0, 'attributed' => 0],
            'argent' => ['required' => 0, 'attributed' => 0]
        ];
        
        foreach ($besoins as $b) {
            $type = $b['type_besoin'];
            $totalsByType[$type]['required'] += $b['quantite_requise'];
            $totalsByType[$type]['attributed'] += $b['total_attribue'];
        }

        // Achats par ville
        $achatsParVille = $achatModel->getTotalByVille();
        $totalAchats = $achatModel->getTotalAchats();

        Flight::render('dashboard/index', [
            'stats' => $stats,
            'villes' => $villes,
            'besoins' => $besoins,
            'dons' => $donModel->getAllWithAvailable(),
            'recentAttributions' => $recentAttributions,
            'uncoveredNeeds' => $uncoveredNeeds,
            'totalsByType' => $totalsByType,
            'achatsParVille' => $achatsParVille,
            'totalAchats' => $totalAchats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUncoveredNeeds' => $totalUncoveredNeeds
        ]);
    }

    public function data() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        $attributionModel = new Attribution();
        $achatModel = new Achat();
        
        $data = [
            'stats' => [
                'total_villes' => count($villeModel->all()),
                'total_besoins' => count($besoinModel->all()),
                'total_dons' => count($donModel->all()),
                'total_attributions' => count($attributionModel->all())
            ],
            'villes' => $villeModel->getAllWithNeedsCount(),
            'besoins' => $besoinModel->getAllWithDetails(),
            'dons' => $donModel->getAllWithAvailable(),
            'recentAttributions' => $attributionModel->getRecent(10),
            'achatsParVille' => $achatModel->getTotalByVille(),
            'totalAchats' => $achatModel->getTotalAchats()
        ];
        
        Flight::json($data);
    }
}
