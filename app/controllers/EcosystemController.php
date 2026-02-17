<?php
/**
 * BNGRC - Ecosystem Controller
 * Handles the revolutionary "Living Ecosystem" interface
 */

require_once __DIR__ . '/../models/Ville.php';
require_once __DIR__ . '/../models/Besoin.php';
require_once __DIR__ . '/../models/Don.php';
require_once __DIR__ . '/../models/Attribution.php';
require_once __DIR__ . '/../models/Achat.php';

class EcosystemController {

    public function index() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        $attributionModel = new Attribution();
        $achatModel = new Achat();
        
        // Get all data
        $stats = [
            'total_villes' => count($villeModel->all()),
            'total_besoins' => count($besoinModel->all()),
            'total_dons' => count($donModel->all()),
            'total_attributions' => count($attributionModel->all())
        ];
        
        $villes = $villeModel->getAllWithNeedsCount();
        $besoins = $besoinModel->getAllWithDetails();
        
        Flight::render('ecosystem/index', [
            'stats' => $stats,
            'villes' => $villes,
            'besoins' => $besoins
        ]);
    }

    /**
     * API endpoint for ecosystem data
     * Returns JSON with all needed data for the visual interface
     */
    public function data() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        $attributionModel = new Attribution();
        $achatModel = new Achat();
        
        // Get villes with needs count
        $villes = $villeModel->getAllWithNeedsCount();
        
        // Enrich villes with coverage data
        foreach ($villes as &$ville) {
            $villeBesoins = $besoinModel->getByVille($ville['id']);
            $totalRequired = 0;
            $totalAttributed = 0;
            
            foreach ($villeBesoins as $besoin) {
                $totalRequired += floatval($besoin['quantite_requise']);
                $totalAttributed += floatval($besoin['total_attribue']);
            }
            
            $ville['total_required'] = $totalRequired;
            $ville['total_attributed'] = $totalAttributed;
            $ville['coverage'] = $totalRequired > 0 ? ($totalAttributed / $totalRequired) * 100 : 0;
            $ville['besoins'] = $villeBesoins;
        }
        
        // Get all besoins with details
        $besoins = $besoinModel->getAllWithDetails();
        
        // Get available donations
        $dons = $donModel->getAvailableDonations();
        
        // Get recent attributions
        $attributions = $attributionModel->getRecent(20);
        
        // Get stats
        $stats = [
            'total_villes' => count($villes),
            'total_besoins' => count($besoins),
            'total_dons' => count($dons),
            'total_attributions' => count($attributions),
            'covered_besoins' => count(array_filter($besoins, function($b) {
                return floatval($b['reste']) <= 0;
            })),
            'uncovered_besoins' => count(array_filter($besoins, function($b) {
                return floatval($b['reste']) > 0;
            }))
        ];
        
        Flight::json([
            'stats' => $stats,
            'villes' => $villes,
            'besoins' => $besoins,
            'dons' => $dons,
            'attributions' => $attributions
        ]);
    }
}
