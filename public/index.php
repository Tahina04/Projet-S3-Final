<?php

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');

// Autoload - fixed for case sensitivity
spl_autoload_register(function ($class) {
    // Controllers
    $controllers = [
        'DashboardController' => 'app/controllers/DashboardController.php',
        'VilleController' => 'app/controllers/VilleController.php',
        'BesoinController' => 'app/controllers/BesoinController.php',
        'DonController' => 'app/controllers/DonController.php',
        'AttributionController' => 'app/controllers/AttributionController.php',
        'AchatController' => 'app/controllers/AchatController.php',
        'VenteController' => 'app/controllers/VenteController.php'
    ];
    
    if (isset($controllers[$class])) {
        require_once BASE_PATH . '/' . $controllers[$class];
        return;
    }
    
    // Models
    $models = [
        'Model' => 'app/models/Model.php',
        'Ville' => 'app/models/Ville.php',
        'Besoin' => 'app/models/Besoin.php',
        'Don' => 'app/models/Don.php',
        'Attribution' => 'app/models/Attribution.php',
        'Achat' => 'app/models/Achat.php',
        'Setting' => 'app/models/Setting.php',
        'Vente' => 'app/models/Vente.php'
    ];
    
    if (isset($models[$class])) {
        require_once BASE_PATH . '/' . $models[$class];
        return;
    }
});

// Load Flight framework (assuming it's installed via Composer)
require_once BASE_PATH . '/vendor/autoload.php';

// Load configuration
require_once CONFIG_PATH . '/database.php';

// Configure Flight - base URL for the application
Flight::set('flight.base_url', '');
Flight::set('base_url', '');

// Custom render function
Flight::map('render', function($view, $data = []) {
    // Extract data to variables
    extract($data);
    
    // Set active page based on view
    $active_page = '';
    if (strpos($view, 'dashboard') !== false) {
        $active_page = 'dashboard';
    } elseif (strpos($view, 'villes') !== false) {
        $active_page = 'villes';
    } elseif (strpos($view, 'besoins') !== false) {
        $active_page = 'besoins';
    } elseif (strpos($view, 'dons') !== false) {
        $active_page = 'dons';
    } elseif (strpos($view, 'attributions') !== false) {
        $active_page = 'attributions';
    } elseif (strpos($view, 'achats') !== false) {
        $active_page = 'achats';
    } elseif (strpos($view, 'ventes') !== false) {
        $active_page = 'ventes';
    }
    
    // Set page title and subtitle
    $title = 'BNGRC - Gestion des Dons';
    $page_title = 'BNGRC';
    $page_subtitle = 'Gestion des collectes et distributions de dons';
    $page_icon = 'speedometer2';
    
    if (strpos($view, 'dashboard') !== false) {
        $title = 'Vue globale - BNGRC';
        $page_title = 'Vue globale';
        $page_subtitle = 'Suivez les collectes et distributions de dons pour les sinistrés';
        $page_icon = 'speedometer2';
    } elseif (strpos($view, 'villes') !== false) {
        $title = 'Villes - BNGRC';
        $page_title = 'Villes';
        $page_subtitle = 'Gestion des villes sinistrées';
        $page_icon = 'buildings';
    } elseif (strpos($view, 'besoins') !== false) {
        $title = 'Besoins - BNGRC';
        $page_title = 'Besoins';
        $page_subtitle = 'Gestion des besoins des sinistrés';
        $page_icon = 'basket';
    } elseif (strpos($view, 'dons') !== false) {
        $title = 'Dons - BNGRC';
        $page_title = 'Dons';
        $page_subtitle = 'Gestion des dons reçus';
        $page_icon = 'gift';
    } elseif (strpos($view, 'attributions') !== false) {
        $title = 'Attributions - BNGRC';
        $page_title = 'Attributions';
        $page_subtitle = 'Attribution des dons aux besoins';
        $page_icon = 'arrow-left-right';
    } elseif (strpos($view, 'achats') !== false) {
        $title = 'Achats - BNGRC';
        $page_title = 'Achats';
        $page_subtitle = 'Gestion des achats de besoins avec les dons en argent';
        $page_icon = 'cart';
    } elseif (strpos($view, 'ventes/settings') !== false) {
        $title = 'Paramètres - BNGRC';
        $page_title = 'Paramètres';
        $page_subtitle = 'Paramètres généraux';
        $page_icon = 'gear';
    } elseif (strpos($view, 'ventes') !== false) {
        $title = 'Ventes - BNGRC';
        $page_title = 'Ventes';
        $page_subtitle = 'Gestion des ventes';
        $page_icon = 'currency-dollar';
    }
    ob_start();
    
    // Include the view file
    $view_file = APP_PATH . '/views/' . $view . '.php';
    if (file_exists($view_file)) {
        include $view_file;
    } else {
        echo "View not found: " . $view;
    }
    
    $content = ob_get_clean();
    
    // Include layout - use vertical layout with sidebar navigation
    include APP_PATH . '/views/layouts/vertical.php';
});

// Error handling
Flight::map('error', function(\Throwable $e) {
    echo "<h1>Erreur</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
});

// Not found handler
Flight::map('notFound', function() {
    echo "<h1>404 - Page non trouvée</h1>";
    echo "<p>La page demandée n'existe pas.</p>";
    echo "<a href='" . Flight::get('base_url') . "/'>Retour au tableau de bord</a>";
});

// ===================
// ROUTES
// ===================

// Dashboard
Flight::route('GET /', function() {
    $controller = new DashboardController();
    $controller->index();
});

// Dashboard API
Flight::route('GET /dashboard/data', function() {
    $controller = new DashboardController();
    $controller->data();
});

// Villes
Flight::route('GET /villes', function() {
    $controller = new VilleController();
    $controller->index();
});

Flight::route('GET /villes/create', function() {
    $controller = new VilleController();
    $controller->create();
});

Flight::route('POST /villes/store', function() {
    $controller = new VilleController();
    $controller->store();
});

Flight::route('GET /villes/@id', function($id) {
    $controller = new VilleController();
    $controller->show($id);
});

Flight::route('GET /villes/@id/edit', function($id) {
    $controller = new VilleController();
    $controller->edit($id);
});

Flight::route('POST /villes/@id/update', function($id) {
    $controller = new VilleController();
    $controller->update($id);
});

Flight::route('GET /villes/@id/delete', function($id) {
    $controller = new VilleController();
    $controller->delete($id);
});

Flight::route('GET /villes/search', function() {
    $controller = new VilleController();
    $controller->search();
});

// Besoins
Flight::route('GET /besoins', function() {
    $controller = new BesoinController();
    $controller->index();
});

Flight::route('GET /besoins/create', function() {
    $controller = new BesoinController();
    $controller->create();
});

Flight::route('POST /besoins/store', function() {
    $controller = new BesoinController();
    $controller->store();
});

Flight::route('GET /besoins/@id', function($id) {
    $controller = new BesoinController();
    $controller->show($id);
});

Flight::route('GET /besoins/@id/edit', function($id) {
    $controller = new BesoinController();
    $controller->edit($id);
});

Flight::route('POST /besoins/@id/update', function($id) {
    $controller = new BesoinController();
    $controller->update($id);
});

Flight::route('GET /besoins/@id/delete', function($id) {
    $controller = new BesoinController();
    $controller->delete($id);
});

// Dons
Flight::route('GET /dons', function() {
    $controller = new DonController();
    $controller->index();
});

Flight::route('GET /dons/create', function() {
    $controller = new DonController();
    $controller->create();
});

Flight::route('POST /dons/store', function() {
    $controller = new DonController();
    $controller->store();
});

Flight::route('GET /dons/@id', function($id) {
    $controller = new DonController();
    $controller->show($id);
});

Flight::route('GET /dons/@id/edit', function($id) {
    $controller = new DonController();
    $controller->edit($id);
});

Flight::route('POST /dons/@id/update', function($id) {
    $controller = new DonController();
    $controller->update($id);
});

Flight::route('GET /dons/@id/delete', function($id) {
    $controller = new DonController();
    $controller->delete($id);
});

// Attributions
Flight::route('GET /attributions', function() {
    $controller = new AttributionController();
    $controller->index();
});

Flight::route('GET /attributions/create', function() {
    $controller = new AttributionController();
    $donId = $_GET['don_id'] ?? null;
    $controller->create($donId);
});

Flight::route('POST /attributions/store', function() {
    $controller = new AttributionController();
    $controller->store();
});

Flight::route('GET /attributions/@id', function($id) {
    $controller = new AttributionController();
    $controller->show($id);
});

Flight::route('GET /attributions/@id/delete', function($id) {
    $controller = new AttributionController();
    $controller->delete($id);
});

// Attribution API endpoints
Flight::route('GET /attributions/by-type', function() {
    $controller = new AttributionController();
    $controller->byType();
});

Flight::route('GET /attributions/besoins-by-type', function() {
    $controller = new AttributionController();
    $controller->besoinsByType();
});

Flight::route('GET /attributions/don/@id', function($id) {
    $controller = new AttributionController();
    $controller->getDonDetails();
});

Flight::route('GET /attributions/besoin/@id', function($id) {
    $controller = new AttributionController();
    $controller->getBesoinDetails();
});

// Achats
Flight::route('GET /achats', function() {
    $controller = new AchatController();
    $controller->index();
});

Flight::route('GET /achats/create', function() {
    $controller = new AchatController();
    $controller->create();
});

Flight::route('POST /achats/store', function() {
    $controller = new AchatController();
    $controller->store();
});

Flight::route('GET /achats/recap', function() {
    $controller = new AchatController();
    $controller->recap();
});

Flight::route('GET /achats/recap-data', function() {
    $controller = new AchatController();
    $controller->recapData();
});

Flight::route('GET /achats/total-by-ville', function() {
    $controller = new AchatController();
    $controller->totalByVille();
});

// Ventes
Flight::route('GET /ventes', function() {
    $controller = new VenteController();
    $controller->index();
});

Flight::route('GET /ventes/create', function() {
    $controller = new VenteController();
    $controller->create();
});

Flight::route('POST /ventes/store', function() {
    $controller = new VenteController();
    $controller->store();
});

Flight::route('GET /ventes/@id/delete', function($id) {
    $controller = new VenteController();
    $controller->delete($id);
});

Flight::route('GET /ventes/settings', function() {
    $controller = new VenteController();
    $controller->settings();
});

Flight::route('POST /ventes/settings', function() {
    $controller = new VenteController();
    $controller->settings();
});

Flight::route('GET /ventes/check/@id', function($id) {
    $controller = new VenteController();
    $controller->checkCanSell($id);
});

// Reset database
Flight::route('GET /reset', function() {
    $controller = new VenteController();
    $controller->reset();
});

// Run Flight
Flight::start();
