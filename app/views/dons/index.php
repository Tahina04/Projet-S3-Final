<?php 
// Page header is handled by layout
?>

<!-- Filter and Actions Bar -->
<div class="filter-bar">
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="<?php echo Flight::get('base_url'); ?>/dons" 
           class="btn btn-sm <?php echo !$type_filter ? 'btn-primary' : 'btn-secondary'; ?>">
            Tous
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/dons?type=nature" 
           class="btn btn-sm filter-tab <?php echo $type_filter === 'nature' ? 'active' : ''; ?>">
            <i class="bi bi-carrot"></i> Nature
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/dons?type=materiaux" 
           class="btn btn-sm filter-tab <?php echo $type_filter === 'materiaux' ? 'active' : ''; ?>">
            <i class="bi bi-hammer"></i> Matériaux
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/dons?type=argent" 
           class="btn btn-sm filter-tab <?php echo $type_filter === 'argent' ? 'active' : ''; ?>">
            <i class="bi bi-cash-coin"></i> Argent
        </a>
    </div>
    
    <a href="<?php echo Flight::get('base_url'); ?>/dons/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nouveau don
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

<!-- Dons Grid -->
<?php if (empty($dons)): ?>
<div class="section-card">
    <div class="section-body">
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
    </div>
</div>
<?php else: ?>
<div class="ville-grid">
    <?php foreach ($dons as $don): 
        $typeClass = $don['type_don'] === 'nature' ? 'nature' 
            : ($don['type_don'] === 'materiaux' ? 'materiaux' : 'argent');
        $isAvailable = $don['disponible'] > 0;
    ?>
    <div class="don-card">
        <div class="don-card-header">
            <div class="don-card-type">
                <div class="don-card-type-icon <?php echo $typeClass; ?>">
                    <i class="bi bi-<?php echo $typeClass === 'nature' ? 'carrot' : ($typeClass === 'materiaux' ? 'hammer' : 'cash-coin'); ?>"></i>
                </div>
                <span class="don-card-type-name"><?php echo htmlspecialchars($don['nom']); ?></span>
            </div>
            <span class="don-card-status <?php echo $isAvailable ? 'available' : 'expired'; ?>">
                <?php echo $isAvailable ? 'Disponible' : 'Épuisé'; ?>
            </span>
        </div>
        
        <?php if (!empty($don['description'])): ?>
        <p class="card-description">
            <?php echo htmlspecialchars($don['description']); ?>
        </p>
        <?php endif; ?>
        
        <div class="don-card-quantity">
            <?php echo number_format($don['quantite_disponible'], 0, ',', ' '); ?>
            <span class="unit">
                <?php echo htmlspecialchars($don['unite']); ?>
            </span>
        </div>
        <div class="don-card-quantity-label">Quantité disponible</div>
        
        <div class="don-card-details">
            <div class="don-card-detail">
                <i class="bi bi-person"></i>
                <?php echo htmlspecialchars($don['donateur'] ?? 'Anonyme'); ?>
            </div>
            <?php if (!empty($don['date_expiration'])): ?>
            <div class="don-card-detail">
                <i class="bi bi-calendar"></i>
                Expire: <?php echo date('d/m/Y', strtotime($don['date_expiration'])); ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="card-actions">
            <a href="<?php echo Flight::get('base_url'); ?>/dons/<?php echo $don['id']; ?>" 
               class="btn btn-secondary btn-sm" style="flex: 1;">
                <i class="bi bi-eye"></i> Voir
            </a>
            <a href="<?php echo Flight::get('base_url'); ?>/dons/<?php echo $don['id']; ?>/edit" 
               class="btn btn-outline-primary btn-sm" style="flex: 1;">
                <i class="bi bi-pencil"></i> Éditer
            </a>
            <a href="<?php echo Flight::get('base_url'); ?>/dons/<?php echo $don['id']; ?>/delete" 
               class="btn btn-outline-danger btn-sm"
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?');">
                <i class="bi bi-trash"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
