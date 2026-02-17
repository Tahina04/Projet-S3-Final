<?php 
// Page header is handled by layout

$typeClass = $don['type_don'] === 'nature' ? 'nature' 
    : ($don['type_don'] === 'materiaux' ? 'materiaux' : 'argent');
$progress = $don['quantite_disponible'] > 0 
    ? ($don['total_attribue'] / $don['quantite_disponible']) * 100 
    : 0;
?>

<!-- Action Buttons -->
<div class="page-actions">
    <a href="<?php echo Flight::get('base_url'); ?>/dons" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour aux dons
    </a>
    <div class="action-buttons">
        <a href="<?php echo Flight::get('base_url'); ?>/attributions/create?don_id=<?php echo $don['id']; ?>" class="btn btn-primary">
            <i class="bi bi-arrow-left-right"></i>
            Attribuer ce don
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/dons/<?php echo $don['id']; ?>/edit" class="btn btn-secondary">
            <i class="bi bi-pencil"></i>
            Modifier
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/dons/<?php echo $don['id']; ?>/delete" 
           class="btn btn-secondary" 
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?');">
            <i class="bi bi-trash"></i>
            Supprimer
        </a>
    </div>
</div>

<!-- Don Details -->
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Info Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-gift"></i>
                <span>Informations</span>
            </div>
            <span class="don-card-status <?php echo $don['disponible'] > 0 ? 'available' : 'expired'; ?>">
                <?php echo $don['disponible'] > 0 ? 'Disponible' : 'Épuisé'; ?>
            </span>
        </div>
        <div class="section-body">
            <div class="detail-item">
                <div class="don-card-type">
                    <div class="don-card-type-icon <?php echo $typeClass; ?>">
                        <i class="bi bi-<?php echo $typeClass === 'nature' ? 'carrot' : ($typeClass === 'materiaux' ? 'hammer' : 'cash-coin'); ?>"></i>
                    </div>
                    <span class="don-card-type-name"><?php echo htmlspecialchars($don['nom']); ?></span>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Type</div>
                <span class="badge badge-<?php echo $typeClass; ?>"><?php echo ucfirst($don['type_don']); ?></span>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Description</div>
                <div class="detail-text">
                    <?php echo htmlspecialchars($don['description'] ?? 'Aucune description'); ?>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Donateur</div>
                <div class="detail-value">
                    <i class="bi bi-person"></i> <?php echo htmlspecialchars($don['donateur'] ?? 'Anonyme'); ?>
                </div>
            </div>
            
            <!-- Progress -->
            <div style="margin-bottom: 20px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">Utilisation</div>
                <div class="need-progress">
                    <div class="need-progress-header">
                        <span><?php echo number_format($don['total_attribue'], 0, ',', ' '); ?> attribués</span>
                        <span><?php echo number_format($don['quantite_disponible'], 0, ',', ' '); ?> total</span>
                    </div>
                    <div class="need-progress-bar">
                        <div class="need-progress-fill <?php echo $progress >= 100 ? 'complete' : ''; ?>" 
                             style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div style="text-align: center; padding: 16px; background: var(--bg-soft); border-radius: var(--radius-md);">
                    <div style="font-size: 24px; font-weight: 700; color: var(--primary);">
                        <?php echo number_format($don['quantite_disponible'], 0, ',', ' '); ?>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">Total</div>
                </div>
                <div style="text-align: center; padding: 16px; background: var(--bg-soft); border-radius: var(--radius-md);">
                    <div style="font-size: 24px; font-weight: 700; color: var(--accent-green);">
                        <?php echo number_format($don['disponible'], 0, ',', ' '); ?>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">Disponible</div>
                </div>
            </div>
            
            <?php if ($don['date_expiration']): ?>
            <div class="expiration-box">
                <i class="bi bi-calendar"></i>
                <span>
                    Expire le <?php echo date('d/m/Y', strtotime($don['date_expiration'])); ?>
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Attributions Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-arrow-left-right"></i>
                <span>Attributions</span>
            </div>
            <a href="<?php echo Flight::get('base_url'); ?>/attributions/create" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                Nouvelle attribution
            </a>
        </div>
        <div class="section-body" style="padding: 0;">
            <?php if (empty($don['attributions'])): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <h3 class="empty-state-title">Aucune attribution</h3>
                <p class="empty-state-text">Ce don n'a pas encore été attribué</p>
                <a href="<?php echo Flight::get('base_url'); ?>/attributions/create" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Créer une attribution
                </a>
            </div>
            <?php else: ?>
            <div class="block-list">
                <?php foreach ($don['attributions'] as $attr): ?>
                <div class="block-item">
                    <div class="block-item-content">
                        <div class="block-item-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        <div class="block-item-info">
                            <div class="block-item-title"><?php echo htmlspecialchars($attr['besoin_nom']); ?></div>
                            <div class="block-item-subtitle">
                                <?php echo htmlspecialchars($attr['ville_nom']); ?> • 
                                <?php echo date('d/m/Y H:i', strtotime($attr['date_attribution'])); ?>
                            </div>
                        </div>
                    </div>
                    <div class="block-item-meta">
                        <span class="badge badge-success">
                            <?php echo number_format($attr['quantite_attribuee'], 0, ',', ' '); ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
