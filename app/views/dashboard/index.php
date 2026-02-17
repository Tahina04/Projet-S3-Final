<?php 
// Get page info for layout
$page_title = 'Vue globale';
$page_subtitle = 'Suivez les collectes et distributions de dons pour les sinistrés';
$page_icon = 'speedometer2';
$active_page = 'dashboard';
?>

<style>
.stat-card-clickable {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.stat-card-clickable:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-card-clickable:hover .stat-card-icon i {
    transform: scale(1.2);
}

.stat-card-clickable .stat-card-icon i {
    transition: transform 0.3s ease;
}

html {
    scroll-behavior: smooth;
}
</style>

<!-- Statistics Cards -->
<div class="stats-grid">
    <a href="#section-villes" class="stat-card stat-card-clickable">
        <div class="stat-card-header">
            <div>
                <div class="stat-card-value"><?php echo $stats['total_villes']; ?></div>
                <div class="stat-card-label">Villes sinistrées</div>
            </div>
            <div class="stat-card-icon violet">
                <i class="bi bi-buildings"></i>
            </div>
        </div>
    </a>
    
    <a href="#section-besoins" class="stat-card stat-card-clickable">
        <div class="stat-card-header">
            <div>
                <div class="stat-card-value"><?php echo $stats['total_besoins']; ?></div>
                <div class="stat-card-label">Besoins identifiés</div>
            </div>
            <div class="stat-card-icon amber">
                <i class="bi bi-basket"></i>
            </div>
        </div>
    </a>
    
    <a href="#section-dons" class="stat-card stat-card-clickable">
        <div class="stat-card-header">
            <div>
                <div class="stat-card-value"><?php echo $stats['total_dons']; ?></div>
                <div class="stat-card-label">Dons collectés</div>
            </div>
            <div class="stat-card-icon green">
                <i class="bi bi-gift"></i>
            </div>
        </div>
    </a>
    
    <a href="#section-attributions" class="stat-card stat-card-clickable">
        <div class="stat-card-header">
            <div>
                <div class="stat-card-value"><?php echo $stats['total_attributions']; ?></div>
                <div class="stat-card-label">Attributions réalisées</div>
            </div>
            <div class="stat-card-icon violet">
                <i class="bi bi-arrow-left-right"></i>
            </div>
        </div>
    </a>
</div>

<div class="section-card" id="section-besoins">
    <div class="section-header">
        <div class="section-title">
            <i class="bi bi-pie-chart"></i>
            <span>État des besoins par type</span>
        </div>
    </div>
    <div class="section-body">
        <div class="type-summary-grid">
            <?php 
            $typeLabels = [
                'nature' => ['icon' => 'bi-carrot', 'label' => 'Besoins en nature', 'class' => 'nature'],
                'materiaux' => ['icon' => 'bi-hammer', 'label' => 'Matériaux', 'class' => 'materiaux'],
                'argent' => ['icon' => 'bi-cash-coin', 'label' => 'Aide financière', 'class' => 'argent']
            ];
            
            foreach ($totalsByType as $type => $data): 
                $progress = $data['required'] > 0 ? ($data['attributed'] / $data['required']) * 100 : 0;
                $typeInfo = $typeLabels[$type];
                $badgeClass = $progress >= 100 ? 'success' : ($progress >= 50 ? 'warning' : 'danger');
            ?>
            <div class="type-summary-card">
                <div class="type-summary-header">
                    <span class="type-summary-title <?php echo $typeInfo['class']; ?>">
                        <i class="bi <?php echo $typeInfo['icon']; ?>"></i>
                        <?php echo $typeInfo['label']; ?>
                    </span>
                    <span class="type-summary-badge <?php echo $badgeClass; ?>">
                        <?php echo round($progress); ?>%
                    </span>
                </div>
                <div class="type-progress">
                    <div class="type-progress-bar <?php echo $typeInfo['class']; ?>" 
                         style="width: <?php echo min($progress, 100); ?>%"></div>
                </div>
                <div class="type-summary-stats">
                    <span>Attribué: <strong><?php echo number_format($data['attributed'], 0, ',', ' '); ?></strong></span>
                    <span>Requis: <strong><?php echo number_format($data['required'], 0, ',', ' '); ?></strong></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Villes et leurs besoins - Block List -->
<div class="section-card" id="section-villes">
    <div class="section-header">
        <div class="section-title">
            <i class="bi bi-buildings"></i>
            <span>Villes sinistrées</span>
        </div>
        <a href="<?php echo Flight::get('base_url'); ?>/villes" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-right"></i>
            Voir tout
        </a>
    </div>
    <div class="section-body" style="padding: 0;">
        <?php if (empty($villes)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-buildings"></i>
            </div>
            <h3 class="empty-state-title">Aucune ville enregistrée</h3>
            <p class="empty-state-text">Commencez par ajouter des villes sinistrées</p>
            <a href="<?php echo Flight::get('base_url'); ?>/villes/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Ajouter une ville
            </a>
        </div>
        <?php else: ?>
        <div class="block-list">
            <?php foreach ($villes as $ville): ?>
            <a href="<?php echo Flight::get('base_url'); ?>/villes/<?php echo $ville['id']; ?>" class="block-item">
                <div class="block-item-content">
                    <div class="block-item-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="block-item-info">
                        <div class="block-item-title"><?php echo htmlspecialchars($ville['nom']); ?></div>
                        <div class="block-item-subtitle"><?php echo htmlspecialchars($ville['region']); ?></div>
                    </div>
                </div>
                <div class="block-item-meta">
                    <div class="block-item-stat">
                        <div class="block-item-stat-value"><?php echo $ville['besoins_count']; ?></div>
                        <div class="block-item-stat-label">Besoins</div>
                    </div>
                    <i class="bi bi-chevron-right block-item-arrow"></i>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Attributions récentes - Block List -->
<div class="section-card" id="section-attributions">
    <div class="section-header">
        <div class="section-title">
            <i class="bi bi-clock-history"></i>
            <span>Attributions récentes</span>
        </div>
        <a href="<?php echo Flight::get('base_url'); ?>/attributions" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-right"></i>
            Voir tout
        </a>
    </div>
    <div class="section-body" style="padding: 0;">
        <?php if (empty($recentAttributions)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <h3 class="empty-state-title">Aucune attribution</h3>
            <p class="empty-state-text">Les attributions de dons apparaîtront ici</p>
        </div>
        <?php else: ?>
        <div class="block-list">
            <?php foreach ($recentAttributions as $attr): ?>
            <div class="block-item">
                <div class="block-item-content">
                    <div class="block-item-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="block-item-info">
                        <div class="block-item-title"><?php echo htmlspecialchars($attr['don_nom']); ?></div>
                        <div class="block-item-subtitle">
                            → <?php echo htmlspecialchars($attr['besoin_nom']); ?> (<?php echo htmlspecialchars($attr['ville_nom']); ?>)
                        </div>
                    </div>
                </div>
                <div class="block-item-meta">
                    <span class="badge badge-success">
                        <i class="bi bi-check"></i>
                        <?php echo number_format($attr['quantite_attribuee'], 0, ',', ' '); ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Dons - Block List -->
<div class="section-card" id="section-dons">
    <div class="section-header">
        <div class="section-title">
            <i class="bi bi-gift"></i>
            <span>Dons disponibles</span>
        </div>
        <a href="<?php echo Flight::get('base_url'); ?>/dons" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-right"></i>
            Voir tout
        </a>
    </div>
    <div class="section-body" style="padding: 0;">
        <?php if (empty($dons)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-gift"></i>
            </div>
            <h3 class="empty-state-title">Aucun don enregistré</h3>
            <p class="empty-state-text">Commencez par ajouter des dons</p>
            <a href="<?php echo Flight::get('base_url'); ?>/dons/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Ajouter un don
            </a>
        </div>
        <?php else: ?>
        <div class="block-list">
            <?php foreach (array_slice($dons, 0, 5) as $don): ?>
            <a href="<?php echo Flight::get('base_url'); ?>/dons/<?php echo $don['id']; ?>" class="block-item">
                <div class="block-item-content">
                    <div class="block-item-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="block-item-info">
                        <div class="block-item-title"><?php echo htmlspecialchars($don['nom']); ?></div>
                        <div class="block-item-subtitle">
                            <?php echo ucfirst($don['type_don']); ?> • 
                            <?php echo number_format($don['quantite_disponible'], 0, ',', ' '); ?> <?php echo htmlspecialchars($don['unite']); ?>
                        </div>
                    </div>
                </div>
                <div class="block-item-meta">
                    <div class="block-item-stat">
                        <div class="block-item-stat-value" style="color: var(--accent-green);">
                            <?php echo number_format($don['disponible'], 0, ',', ' '); ?>
                        </div>
                        <div class="block-item-stat-label">Disponible</div>
                    </div>
                    <i class="bi bi-chevron-right block-item-arrow"></i>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Besoins non couverts - Need Cards -->
<?php if (!empty($uncoveredNeeds)): ?>
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="bi bi-exclamation-triangle" style="color: var(--accent-amber);"></i>
            <span>Besoins non couverts</span>
            <span class="badge bg-warning text-dark ms-2"><?php echo $totalUncoveredNeeds; ?></span>
        </div>
        <a href="<?php echo Flight::get('base_url'); ?>/attributions/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i>
            Nouvelle attribution
        </a>
    </div>
    <div class="section-body">
        <div class="ville-grid">
            <?php foreach ($uncoveredNeeds as $besoin): 
                $progress = $besoin['quantite_requise'] > 0 
                    ? ($besoin['total_attribue'] / $besoin['quantite_requise']) * 100 
                    : 0;
                $typeClass = $besoin['type_besoin'] === 'nature' ? 'nature' 
                    : ($besoin['type_besoin'] === 'materiaux' ? 'materiaux' : 'argent');
            ?>
            <div class="need-card">
                <div class="need-card-header">
                    <div>
                        <div class="need-card-title"><?php echo htmlspecialchars($besoin['nom']); ?></div>
                        <div class="need-card-ville"><?php echo htmlspecialchars($besoin['ville_nom']); ?> • <?php echo htmlspecialchars($besoin['region']); ?></div>
                    </div>
                    <span class="badge badge-<?php echo $typeClass; ?>"><?php echo ucfirst($besoin['type_besoin']); ?></span>
                </div>
                <div class="need-progress">
                    <div class="need-progress-header">
                        <span><?php echo number_format($besoin['total_attribue'], 0, ',', ' '); ?> attribués</span>
                        <span><?php echo number_format($besoin['quantite_requise'], 0, ',', ' '); ?> <?php echo htmlspecialchars($besoin['unite']); ?></span>
                    </div>
                    <div class="need-progress-bar">
                        <div class="need-progress-fill" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>
                <div class="need-card-footer">
                    <span style="color: var(--accent-red); font-weight: 600;">
                        <i class="bi bi-exclamation-circle"></i>
                        <?php echo number_format($besoin['reste'], 0, ',', ' '); ?> restant(s)
                    </span>
                    <a href="<?php echo Flight::get('base_url'); ?>/attributions/create?besoin=<?php echo $besoin['id']; ?>" class="btn btn-sm btn-primary">
                       Attribuer
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <nav aria-label="Pagination besoins non couverts" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 1 && $i <= $currentPage + 1)): ?>
                    <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php elseif ($i == $currentPage - 2 || $i == $currentPage + 2): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Achats par ville -->
<?php if (!empty($achatsParVille)): ?>
<div class="section-card">
    <div class="section-header">
        <div class="section-title">
            <i class="bi bi-cart"></i>
            <span>Montant des achats par ville</span>
        </div>
        <a href="<?php echo Flight::get('base_url'); ?>/achats" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-right"></i>
            Voir tout
        </a>
    </div>
    <div class="section-body" style="padding: 0;">
        <div class="block-list">
            <?php foreach ($achatsParVille as $aville): ?>
            <div class="block-item">
                <div class="block-item-content">
                    <div class="block-item-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--accent-amber);">
                        <i class="bi bi-cart"></i>
                    </div>
                    <div class="block-item-info">
                        <div class="block-item-title"><?php echo htmlspecialchars($aville['ville_nom']); ?></div>
                        <div class="block-item-subtitle"><?php echo htmlspecialchars($aville['region']); ?></div>
                    </div>
                </div>
                <div class="block-item-meta">
                    <div class="block-item-stat">
                        <div class="block-item-stat-value"><?php echo number_format($aville['total_achats'], 0, ',', ' '); ?> Ar</div>
                        <div class="block-item-stat-label">Total achats</div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="block-item" style="background: var(--bg-soft);">
                <div class="block-item-content">
                    <div class="block-item-info">
                        <div class="block-item-title" style="font-weight: 700;">Total général</div>
                    </div>
                </div>
                <div class="block-item-meta">
                    <div class="block-item-stat">
                        <div class="block-item-stat-value" style="font-size: 20px; color: var(--primary);"><?php echo number_format($totalAchats, 0, ',', ' '); ?> Ar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
