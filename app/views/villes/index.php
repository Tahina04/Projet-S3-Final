<?php 
// Page header is handled by layout
?>

<!-- Actions Bar -->
<div class="page-actions">
    <div></div>
    <a href="<?php echo Flight::get('base_url'); ?>/villes/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nouvelle ville
    </a>
</div>

<!-- Error Messages -->
<?php if (isset($errors) && !empty($errors)): ?>
<div class="alert alert-danger" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <ul class="mb-0" style="padding-left: 16px;">
        <?php foreach ($errors as $error): ?>
        <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- Villes Grid -->
<?php if (empty($villes)): ?>
<div class="section-card">
    <div class="section-body">
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
    </div>
</div>
<?php else: ?>
<div class="ville-grid">
    <?php foreach ($villes as $ville): ?>
    <div class="ville-card">
        <div class="ville-card-header">
            <div>
                <div class="ville-card-title"><?php echo htmlspecialchars($ville['nom']); ?></div>
                <div class="ville-card-region"><?php echo htmlspecialchars($ville['region']); ?></div>
            </div>
            <span class="ville-card-badge">
                <i class="bi bi-basket"></i>
                <?php echo $ville['besoins_count']; ?> besoin(s)
            </span>
        </div>
        
        <?php if (!empty($ville['description'])): ?>
        <p class="card-description">
            <?php echo htmlspecialchars($ville['description']); ?>
        </p>
        <?php endif; ?>
        
        <div class="ville-card-stats">
            <div class="ville-stat">
                <div class="ville-stat-value"><?php echo $ville['besoins_count']; ?></div>
                <div class="ville-stat-label">Besoins</div>
            </div>
            <div class="ville-stat">
                <a href="<?php echo Flight::get('base_url'); ?>/villes/<?php echo $ville['id']; ?>" 
                   class="btn btn-sm btn-secondary btn-block">
                    <i class="bi bi-eye"></i> Voir
                </a>
            </div>
            <div class="ville-stat">
                <a href="<?php echo Flight::get('base_url'); ?>/villes/<?php echo $ville['id']; ?>/edit" 
                   class="btn btn-sm btn-outline-primary btn-block">
                    <i class="bi bi-pencil"></i> Éditer
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
