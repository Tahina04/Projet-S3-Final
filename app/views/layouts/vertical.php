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
    
    <style>
        :root {
            --nav-width: 240px;
            --nav-collapsed: 72px;
        }
        
        body {
            margin-left: var(--nav-width);
            transition: margin-left var(--transition-base);
        }
        
        /* Vertical Navigation */
        .vertical-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--nav-width);
            height: 100vh;
            background: var(--bg-card);
            border-right: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: width var(--transition-base);
        }
        
        .vertical-nav-header {
            padding: 24px;
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        
        .vertical-nav-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }
        
        .vertical-nav-brand-icon {
            width: 44px;
            height: 44px;
            background: var(--gradient-primary);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .vertical-nav-brand-text {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .vertical-nav-brand-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap;
        }
        
        .vertical-nav-brand-subtitle {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }
        
        .vertical-nav-items {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }
        
        .vertical-nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            margin-bottom: 4px;
            transition: all var(--transition-base);
            font-weight: 500;
            font-size: 14px;
        }
        
        .vertical-nav-item:hover {
            color: var(--text-primary);
            background: var(--bg-soft);
        }
        
        .vertical-nav-item.active {
            color: var(--primary);
            background: var(--primary-50);
        }
        
        .vertical-nav-item-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }
        
        .vertical-nav-item-text {
            white-space: nowrap;
            overflow: hidden;
        }
        
        .vertical-nav-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-soft);
        }
        
        .vertical-nav-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            body {
                margin-left: 0;
            }
            
            .vertical-nav {
                transform: translateX(-100%);
            }
            
            .vertical-nav.open {
                transform: translateX(0);
            }
            
            .mobile-toggle {
                display: flex !important;
            }
        }
        
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 101;
            width: 44px;
            height: 44px;
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 24px;
            color: var(--text-primary);
        }
        
        .nav-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }
        
        .nav-overlay.show {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Background -->
    <div class="app-background"></div>

    <!-- Mobile Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Vertical Navigation -->
    <nav class="vertical-nav" id="verticalNav">
        <div class="vertical-nav-header">
            <a href="<?php echo Flight::get('base_url'); ?>/" class="vertical-nav-brand">
                <div class="vertical-nav-brand-icon">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <div class="vertical-nav-brand-text">
                    <span class="vertical-nav-brand-title">BNGRC</span>
                    <span class="vertical-nav-brand-subtitle">Gestion Dons</span>
                </div>
            </a>
        </div>

        <div class="vertical-nav-items">
            <a class="vertical-nav-item <?php echo $active_page === 'dashboard' ? 'active' : ''; ?>" 
               href="<?php echo Flight::get('base_url'); ?>/">
                <i class="bi bi-grid-1x2 vertical-nav-item-icon"></i>
                <span class="vertical-nav-item-text">Vue globale</span>
            </a>
            <a class="vertical-nav-item <?php echo $active_page === 'villes' ? 'active' : ''; ?>" 
               href="<?php echo Flight::get('base_url'); ?>/villes">
                <i class="bi bi-buildings vertical-nav-item-icon"></i>
                <span class="vertical-nav-item-text">Villes</span>
            </a>
            <a class="vertical-nav-item <?php echo $active_page === 'dons' ? 'active' : ''; ?>" 
               href="<?php echo Flight::get('base_url'); ?>/dons">
                <i class="bi bi-gift vertical-nav-item-icon"></i>
                <span class="vertical-nav-item-text">Dons</span>
            </a>
            <a class="vertical-nav-item <?php echo $active_page === 'besoins' ? 'active' : ''; ?>" 
               href="<?php echo Flight::get('base_url'); ?>/besoins">
                <i class="bi bi-basket vertical-nav-item-icon"></i>
                <span class="vertical-nav-item-text">Besoins</span>
            </a>
            <a class="vertical-nav-item <?php echo $active_page === 'attributions' ? 'active' : ''; ?>" 
               href="<?php echo Flight::get('base_url'); ?>/attributions">
                <i class="bi bi-arrow-left-right vertical-nav-item-icon"></i>
                <span class="vertical-nav-item-text">Attributions</span>
            </a>
            <a class="vertical-nav-item <?php echo $active_page === 'achats' ? 'active' : ''; ?>" 
               href="<?php echo Flight::get('base_url'); ?>/achats">
                <i class="bi bi-cart vertical-nav-item-icon"></i>
                <span class="vertical-nav-item-text">Achats</span>
            </a>
            <a class="vertical-nav-item <?php echo $active_page === 'ventes' ? 'active' : ''; ?>" 
               href="<?php echo Flight::get('base_url'); ?>/ventes">
                <i class="bi bi-currency-dollar vertical-nav-item-icon"></i>
                <span class="vertical-nav-item-text">Ventes</span>
            </a>
            <a class="vertical-nav-item <?php echo $active_page === 'settings' ? 'active' : ''; ?>" 
               href="<?php echo Flight::get('base_url'); ?>/ventes/settings">
                <i class="bi bi-gear vertical-nav-item-icon"></i>
                <span class="vertical-nav-item-text">Paramètres</span>
            </a>
        </div>

        <div class="vertical-nav-footer">
            <div class="vertical-nav-date">
                <i class="bi bi-calendar3"></i>
                <span><?php echo date('d M Y'); ?></span>
            </div>
        </div>
    </nav>

    <!-- Nav Overlay -->
    <div class="nav-overlay" id="navOverlay"></div>

    <!-- Toast Notification -->
    <div class="toast-container" id="toastContainer">
        <?php if (isset($_GET['success'])): ?>
        <div class="toast-notification show success" id="successToast">
            <div class="toast-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Succès</div>
                <div class="toast-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
            </div>
            <button class="toast-close" onclick="hideToast()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
        <div class="toast-notification show error" id="errorToast">
            <div class="toast-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Erreur</div>
                <div class="toast-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
            </div>
            <button class="toast-close" onclick="hideToast()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>

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

        <!-- Messages (hidden - using toast now) -->
        <?php /* Comments out old alerts - using toast notifications instead
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
        */ ?>

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
    
    <!-- Toast Auto-Hide Script -->
    <script>
        function hideToast() {
            document.querySelectorAll('.toast-notification').forEach(toast => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            });
        }
        
        // Auto-hide toast after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification.show');
            toasts.forEach(toast => {
                setTimeout(() => {
                    hideToast();
                }, 5000);
            });
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('mobileToggle');
            const verticalNav = document.getElementById('verticalNav');
            const navOverlay = document.getElementById('navOverlay');
            
            function toggleNav() {
                verticalNav.classList.toggle('open');
                navOverlay.classList.toggle('show');
            }
            
            if (mobileToggle) {
                mobileToggle.addEventListener('click', toggleNav);
            }
            
            if (navOverlay) {
                navOverlay.addEventListener('click', toggleNav);
            }
        });
    </script>
</body>
</html>
