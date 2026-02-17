<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'BNGRC - Gestion des Dons'; ?></title>
    
    <!-- Google Fonts - DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Immersive Design System -->
    <link href="<?php echo Flight::get('base_url'); ?>/assets/css/immersive-bngrc.css" rel="stylesheet">
</head>
<body>
    <!-- Background -->
    <div class="app-background"></div>

    <!-- Immersive Navigation -->
    <nav class="immersive-nav">
        <div class="nav-container">
            <!-- Brand -->
            <a href="<?php echo Flight::get('base_url'); ?>/" class="nav-brand">
                <div class="nav-brand-icon">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <div class="nav-brand-text">
                    <span class="nav-brand-title">BNGRC</span>
                    <span class="nav-brand-subtitle">Gestion des Dons</span>
                </div>
            </a>

            <!-- Tab Navigation -->
            <div class="nav-tabs-custom" id="navTabs">
                <a class="nav-tab <?php echo $active_page === 'dashboard' ? 'active' : ''; ?>" 
                   href="<?php echo Flight::get('base_url'); ?>/">
                    <i class="bi bi-grid-1x2 nav-tab-icon"></i>
                    <span>Vue globale</span>
                </a>
                <a class="nav-tab <?php echo $active_page === 'villes' ? 'active' : ''; ?>" 
                   href="<?php echo Flight::get('base_url'); ?>/villes">
                    <i class="bi bi-buildings nav-tab-icon"></i>
                    <span>Villes</span>
                </a>
                <a class="nav-tab <?php echo $active_page === 'dons' ? 'active' : ''; ?>" 
                   href="<?php echo Flight::get('base_url'); ?>/dons">
                    <i class="bi bi-gift nav-tab-icon"></i>
                    <span>Dons</span>
                </a>
                <a class="nav-tab <?php echo $active_page === 'besoins' ? 'active' : ''; ?>" 
                   href="<?php echo Flight::get('base_url'); ?>/besoins">
                    <i class="bi bi-basket nav-tab-icon"></i>
                    <span>Besoins</span>
                </a>
                <a class="nav-tab <?php echo $active_page === 'attributions' ? 'active' : ''; ?>" 
                   href="<?php echo Flight::get('base_url'); ?>/attributions">
                    <i class="bi bi-arrow-left-right nav-tab-icon"></i>
                    <span>Attributions</span>
                </a>
                <a class="nav-tab <?php echo $active_page === 'achats' ? 'active' : ''; ?>" 
                   href="<?php echo Flight::get('base_url'); ?>/achats">
                    <i class="bi bi-cart nav-tab-icon"></i>
                    <span>Achats</span>
                </a>
                <a class="nav-tab <?php echo $active_page === 'ventes' ? 'active' : ''; ?>" 
                   href="<?php echo Flight::get('base_url'); ?>/ventes">
                    <i class="bi bi-currency-dollar nav-tab-icon"></i>
                    <span>Ventes</span>
                </a>
                <a class="nav-tab <?php echo $active_page === 'settings' ? 'active' : ''; ?>" 
                   href="<?php echo Flight::get('base_url'); ?>/ventes/settings">
                    <i class="bi bi-gear nav-tab-icon"></i>
                    <span>Paramètres</span>
                </a>
            </div>

            <!-- Nav Actions -->
            <div class="nav-actions">
                <div class="nav-date">
                    <i class="bi bi-calendar3"></i>
                    <span><?php echo date('d M Y'); ?></span>
                </div>
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Page Header -->
        <header class="page-header">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1>
                        <?php if (isset($page_icon)): ?>
                            <i class="bi bi-<?php echo $page_icon; ?>"></i>
                        <?php endif; ?>
                        <?php echo $page_title ?? 'Tableau de bord'; ?>
                    </h1>
                    <p><?php echo $page_subtitle ?? 'Suivez les collectes et distributions de dons pour les sinistrés'; ?></p>
                </div>
                <?php if (isset($page_actions)): ?>
                <div class="page-actions">
                    <?php echo $page_actions; ?>
                </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Messages -->
        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
        <?php endif; ?>

        <!-- Main Content -->
        <main class="main-content">
            <?php echo $content; ?>
        </main>

        <!-- Footer -->
        <footer class="footer" style="margin-top: 48px; padding: 24px 0; border-top: 1px solid var(--border-light);">
            <div style="text-align: center; color: var(--text-muted); font-size: 13px;">
                <strong>Bureau National de Gestion des Risques et des Catastrophes</strong> &copy; <?php echo date('Y'); ?>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navTabs = document.getElementById('navTabs');
            
            if (mobileMenuBtn && navTabs) {
                mobileMenuBtn.addEventListener('click', function() {
                    navTabs.classList.toggle('mobile-open');
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    if (!navTabs.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                        navTabs.classList.remove('mobile-open');
                    }
                }
            });
        });
    </script>
</body>
</html>
