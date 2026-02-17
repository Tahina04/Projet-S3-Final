<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'BNGRC - Gestion des Dons'; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo Flight::get('base_url'); ?>/assets/css/style.css" rel="stylesheet">
    <!-- Modern Premium UI -->
    <link href="<?php echo Flight::get('base_url'); ?>/assets/css/modern-bngrc.css" rel="stylesheet">
    <!-- Purple Premium UI -->
    <link href="<?php echo Flight::get('base_url'); ?>/assets/css/purple-premium.css" rel="stylesheet">
    <!-- Green Premium UI -->
    <link href="<?php echo Flight::get('base_url'); ?>/assets/css/green-premium.css" rel="stylesheet">
    <!-- Immersive BNGRC Design (doit être en dernier pour primer) -->
    <link href="<?php echo Flight::get('base_url'); ?>/assets/css/immersive-bngrc.css" rel="stylesheet">
</head>
<body>
    <!-- Clean Background -->
    
    <div class="app-container page-enter">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo Flight::get('base_url'); ?>/" class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div>
                        <div class="sidebar-logo-text">BNGRC</div>
                        <div class="sidebar-logo-subtitle">Gestion des Dons</div>
                    </div>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <div class="sidebar-nav-section">
                    <div class="sidebar-nav-title">Principal</div>
                    <a class="sidebar-nav-item <?php echo $active_page === 'dashboard' ? 'active' : ''; ?>" 
                       href="<?php echo Flight::get('base_url'); ?>/">
                        <i class="bi bi-speedometer2 sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">Tableau de bord</span>
                    </a>
                </div>
                
                <div class="sidebar-nav-section">
                    <div class="sidebar-nav-title">Gestion</div>
                    <a class="sidebar-nav-item <?php echo $active_page === 'villes' ? 'active' : ''; ?>" 
                       href="<?php echo Flight::get('base_url'); ?>/villes">
                        <i class="bi bi-building sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">Villes</span>
                    </a>
                    <a class="sidebar-nav-item <?php echo $active_page === 'besoins' ? 'active' : ''; ?>" 
                       href="<?php echo Flight::get('base_url'); ?>/besoins">
                        <i class="bi bi-basket sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">Besoins</span>
                    </a>
                    <a class="sidebar-nav-item <?php echo $active_page === 'dons' ? 'active' : ''; ?>" 
                       href="<?php echo Flight::get('base_url'); ?>/dons">
                        <i class="bi bi-gift sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">Dons</span>
                    </a>
                    <a class="sidebar-nav-item <?php echo $active_page === 'attributions' ? 'active' : ''; ?>" 
                       href="<?php echo Flight::get('base_url'); ?>/attributions">
                        <i class="bi bi-arrow-left-right sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">Attributions</span>
                    </a>
                    <a class="sidebar-nav-item <?php echo $active_page === 'achats' ? 'active' : ''; ?>" 
                       href="<?php echo Flight::get('base_url'); ?>/achats">
                        <i class="bi bi-cart sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">Achats</span>
                    </a>
                    <a class="sidebar-nav-item <?php echo $active_page === 'ventes' ? 'active' : ''; ?>" 
                       href="<?php echo Flight::get('base_url'); ?>/ventes">
                        <i class="bi bi-currency-dollar sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">Ventes</span>
                    </a>
                    <a class="sidebar-nav-item <?php echo $active_page === 'settings' ? 'active' : ''; ?>" 
                       href="<?php echo Flight::get('base_url'); ?>/ventes/settings">
                        <i class="bi bi-gear sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">Paramètres</span>
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <div class="sidebar-date">
                    <i class="bi bi-calendar3"></i>
                    <span><?php echo date('d/m/Y'); ?></span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper" id="mainWrapper">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-title">
                    <button class="btn btn-icon btn-outline-secondary d-lg-none" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h1><?php echo $page_title ?? 'Tableau de bord'; ?></h1>
                        <div class="subtitle"><?php echo $page_subtitle ?? 'Suivi des collectes et distributions de dons'; ?></div>
                    </div>
                </div>
                <div class="header-actions">
                    <span class="badge bg-primary">
                        <i class="bi bi-shield-exclamation me-1"></i>
                        BNGRC
                    </span>
                </div>
            </header>

            <!-- Messages -->
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show m-3 mx-4" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show m-3 mx-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Main Content Area -->
            <main class="main-content">
                <?php echo $content; ?>
            </main>

            <!-- Footer -->
            <footer class="footer">
                <div class="footer-content">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>
                        <div class="footer-text">
                            <strong>Bureau National de Gestion des Risques et des Catastrophes</strong>
                            <br>Application de suivi des collectes et distributions de dons
                        </div>
                    </div>
                    <div class="footer-text">
                        &copy; <?php echo date('Y'); ?> - Projet S3 Design
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo Flight::get('base_url'); ?>/assets/js/script.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>
</html>
