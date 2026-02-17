<?php
/**
 * BNGRC - HUMANITARIAN OPERATING SYSTEM
 * Living Ecosystem View
 * Revolutionary immersive interface
 */

// Get data passed from controller
$stats = $stats ?? [];
$villes = $villes ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Système Humanitaire</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Ecosystem CSS -->
    <link href="<?php echo Flight::get('base_url'); ?>/assets/css/ecosystem.css" rel="stylesheet">
</head>
<body class="eos-reset">

    <!-- ==================== MAIN CONTAINER ==================== -->
    <div class="eos-container">
        
        <!-- Background Effects -->
        <div class="eos-background">
            <div class="eos-bg-gradient"></div>
            <div class="eos-orb eos-orb-1"></div>
            <div class="eos-orb eos-orb-2"></div>
            <div class="eos-orb eos-orb-3"></div>
        </div>

        <!-- ==================== LEVEL INDICATOR ==================== -->
        <div class="eos-level-indicator">
            <div class="eos-level-dot level-1 active" title="Vue réseau"></div>
            <div class="eos-level-dot level-2" title="Vue ville"></div>
            <div class="eos-level-dot level-3" title="Vue besoins"></div>
            <div class="eos-level-dot level-4" title="Attribution"></div>
        </div>

        <!-- ==================== CENTRAL HUB ==================== -->
        <div class="eos-central-hub">
            <div class="eos-hub-pulse"></div>
            <div class="eos-hub-pulse"></div>
            <div class="eos-hub-pulse"></div>
            <div class="eos-hub-ring"></div>
            <div class="eos-hub-circle" onclick="Ecosystem.showToast('info', 'BNGRC', 'Système de gestion des risques et catastrophes - Madagascar')">
                <i class="bi bi-globe-americas eos-hub-icon"></i>
                <span class="eos-hub-label">Réseau</span>
            </div>
        </div>

        <!-- ==================== CITY NODES ==================== -->
        <div class="eos-nodes-container">
            <!-- Nodes will be positioned dynamically by JavaScript -->
        </div>

        <!-- ==================== CONNECTIONS SVG ==================== -->
        <svg class="eos-connections" id="eosConnections">
            <!-- Connection lines will be drawn by JavaScript -->
        </svg>

        <!-- ==================== DETAIL PANEL ==================== -->
        <div class="eos-detail-panel">
            <!-- Content loaded dynamically -->
        </div>

    </div>

    <!-- ==================== FLOATING NAVIGATION ==================== -->
    <nav class="eos-nav">
        <a href="<?php echo Flight::get('base_url'); ?>/" class="eos-nav-item active" onclick="Ecosystem.showToast('info', 'Écosystème', 'Vue principale du réseau humanitarian'); return true;">
            <i class="bi bi-diagram-3"></i>
            <span class="eos-nav-tooltip">Écosystème</span>
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/villes" class="eos-nav-item">
            <i class="bi bi-building"></i>
            <span class="eos-nav-tooltip">Villes</span>
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/besoins" class="eos-nav-item">
            <i class="bi bi-basket"></i>
            <span class="eos-nav-tooltip">Besoins</span>
        </a>
        <div class="eos-nav-divider"></div>
        <a href="<?php echo Flight::get('base_url'); ?>/dons" class="eos-nav-item">
            <i class="bi bi-gift"></i>
            <span class="eos-nav-tooltip">Dons</span>
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/attributions" class="eos-nav-item">
            <i class="bi bi-arrow-left-right"></i>
            <span class="eos-nav-tooltip">Attributions</span>
        </a>
        <div class="eos-nav-divider"></div>
        <a href="<?php echo Flight::get('base_url'); ?>/achats" class="eos-nav-item">
            <i class="bi bi-cart"></i>
            <span class="eos-nav-tooltip">Achats</span>
        </a>
    </nav>

    <!-- ==================== FLOATING ACTION BUTTON ==================== -->
    <button class="eos-fab" onclick="Ecosystem.showModal('don')" title="Ajouter un don">
        <i class="bi bi-plus"></i>
    </button>

    <!-- ==================== MODAL OVERLAY ==================== -->
    <div class="eos-modal-overlay">
        <div class="eos-modal">
            <!-- Modal content loaded dynamically -->
        </div>
    </div>

    <!-- ==================== TOAST CONTAINER ==================== -->
    <div class="eos-toast-container"></div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script src="<?php echo Flight::get('base_url'); ?>/assets/js/ecosystem.js"></script>
    
    <script>
        // Initialize base URL from PHP
        if (typeof CONFIG !== 'undefined') {
            CONFIG.baseUrl = '<?php echo Flight::get('base_url'); ?>';
        }
    </script>
</body>
</html>
