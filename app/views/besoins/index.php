<?php 

?>

<div class="filter-bar">
   
    <div class="filter-tabs">
        <a href="<?php echo Flight::get('base_url'); ?>/besoins" 
           class="btn btn-sm <?php echo !$type_filter ? 'btn-primary' : 'btn-secondary'; ?>">
            Tous
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/besoins?type=nature" 
           class="btn btn-sm filter-tab <?php echo $type_filter === 'nature' ? 'active' : ''; ?>">
            <i class="bi bi-carrot"></i> Nature
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/besoins?type=materiaux" 
           class="btn btn-sm filter-tab <?php echo $type_filter === 'materiaux' ? 'active' : ''; ?>">
            <i class="bi bi-hammer"></i> Matériaux
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/besoins?type=argent" 
           class="btn btn-sm filter-tab <?php echo $type_filter === 'argent' ? 'active' : ''; ?>">
            <i class="bi bi-cash-coin"></i> Argent
        </a>
    </div>
    
    <a href="<?php echo Flight::get('base_url'); ?>/besoins/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nouveau besoin
    </a>
</div>

<!-- Besoins Grid -->
<?php if (empty($besoins)): ?>
<div class="section-card">
    <div class="section-body">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-basket"></i>
            </div>
            <h3 class="empty-state-title">Aucun besoin enregistré</h3>
            <p class="empty-state-text">Commencez par ajouter des besoins</p>
            <a href="<?php echo Flight::get('base_url'); ?>/besoins/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Ajouter un besoin
            </a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="ville-grid">
    <?php foreach ($besoins as $besoin): 
        $typeClass = $besoin['type_besoin'] === 'nature' ? 'nature' 
            : ($besoin['type_besoin'] === 'materiaux' ? 'materiaux' : 'argent');
        $progress = $besoin['quantite_requise'] > 0 
            ? ($besoin['total_attribue'] / $besoin['quantite_requise']) * 100 
            : 0;
        $isCovered = $besoin['reste'] <= 0;
    ?>
    <div class="need-card">
        <div class="need-card-header">
            <div>
                <div class="need-card-title"><?php echo htmlspecialchars($besoin['nom']); ?></div>
                <div class="need-card-ville">
                    <i class="bi bi-buildings"></i> 
                    <?php echo htmlspecialchars($besoin['ville_nom']); ?> • <?php echo htmlspecialchars($besoin['region']); ?>
                </div>
            </div>
            <span class="badge badge-<?php echo $typeClass; ?>"><?php echo ucfirst($besoin['type_besoin']); ?></span>
        </div>
        
        <div class="need-progress">
            <div class="need-progress-header">
                <span><?php echo number_format($besoin['total_attribue'], 0, ',', ' '); ?> attribués</span>
                <span><?php echo number_format($besoin['quantite_requise'], 0, ',', ' '); ?> <?php echo htmlspecialchars($besoin['unite']); ?></span>
            </div>
            <div class="need-progress-bar">
                <div class="need-progress-fill <?php echo $isCovered ? 'complete' : ''; ?>" 
                     style="width: <?php echo min($progress, 100); ?>%"></div>
            </div>
        </div>
        
        <div class="need-card-footer">
            <?php if ($isCovered): ?>
            <span class="status-covered">
                <i class="bi bi-check-circle"></i> Besoin couvert
            </span>
            <?php else: ?>
            <span class="status-remaining">
                <i class="bi bi-exclamation-circle"></i>
                <?php echo number_format($besoin['reste'], 0, ',', ' '); ?> <?php echo htmlspecialchars($besoin['unite']); ?> restant(s)
            </span>
            <?php endif; ?>
            
            <div class="action-buttons">
                <a href="<?php echo Flight::get('base_url'); ?>/besoins/<?php echo $besoin['id']; ?>" 
                   class="btn btn-sm btn-secondary">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="<?php echo Flight::get('base_url'); ?>/besoins/<?php echo $besoin['id']; ?>/edit" 
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
