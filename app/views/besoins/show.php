<?php 
// Page header is handled by layout

$typeClass = $besoin['type_besoin'] === 'nature' ? 'nature' 
    : ($besoin['type_besoin'] === 'materiaux' ? 'materiaux' : 'argent');
$progress = $besoin['quantite_requise'] > 0 
    ? (($besoin['total_attribue'] ?? 0) / $besoin['quantite_requise']) * 100 
    : 0;
$isCovered = $besoin['reste'] <= 0;
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <a href="<?php echo Flight::get('base_url'); ?>/besoins" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour aux besoins
    </a>
    <div style="display: flex; gap: 12px;">
        <a href="<?php echo Flight::get('base_url'); ?>/besoins/<?php echo $besoin['id']; ?>/edit" class="btn btn-secondary">
            <i class="bi bi-pencil"></i>
            Modifier
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/besoins/<?php echo $besoin['id']; ?>/delete" 
           class="btn btn-secondary" 
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce besoin ?');">
            <i class="bi bi-trash"></i>
            Supprimer
        </a>
    </div>
</div>

<!-- Besoin Details -->
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Info Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-basket"></i>
                <span>Informations</span>
            </div>
            <span class="badge badge-<?php echo $typeClass; ?>"><?php echo ucfirst($besoin['type_besoin']); ?></span>
        </div>
        <div class="section-body">
            <div style="margin-bottom: 24px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">Besoin</div>
                <div style="font-size: 24px; font-weight: 700; color: var(--text-primary);">
                    <?php echo htmlspecialchars($besoin['nom']); ?>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">Ville</div>
                <span class="badge" style="background: var(--primary-50); color: var(--primary); font-size: 14px; padding: 8px 16px;">
                    <i class="bi bi-buildings"></i>
                    <?php echo htmlspecialchars($besoin['ville_nom']); ?>
                </span>
            </div>
            
            <div style="margin-bottom: 20px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">Description</div>
                <div style="font-size: 14px; color: var(--text-secondary);">
                    <?php echo htmlspecialchars($besoin['description'] ?? 'Aucune description'); ?>
                </div>
            </div>
            
            <!-- Progress -->
            <div style="margin-bottom: 20px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">Progression</div>
                <div class="need-progress">
                    <div class="need-progress-header">
                        <span><?php echo number_format($besoin['total_attribue'] ?? 0, 0, ',', ' '); ?> attribués</span>
                        <span><?php echo number_format($besoin['quantite_requise'], 0, ',', ' '); ?> <?php echo htmlspecialchars($besoin['unite']); ?></span>
                    </div>
                    <div class="need-progress-bar">
                        <div class="need-progress-fill <?php echo $isCovered ? 'complete' : ''; ?>" 
                             style="width: <?php echo min($progress, 100); ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div style="text-align: center; padding: 16px; background: var(--bg-soft); border-radius: var(--radius-md);">
                    <div style="font-size: 24px; font-weight: 700; color: var(--text-primary);">
                        <?php echo number_format($besoin['quantite_requise'], 0, ',', ' '); ?>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">Requis</div>
                </div>
                <div style="text-align: center; padding: 16px; background: var(--bg-soft); border-radius: var(--radius-md);">
                    <div style="font-size: 24px; font-weight: 700; color: <?php echo $isCovered ? 'var(--accent-green)' : 'var(--accent-red)'; ?>;">
                        <?php echo number_format($besoin['reste'], 0, ',', ' '); ?>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">Restant</div>
                </div>
            </div>
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
            <?php if (empty($besoin['attributions'])): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <h3 class="empty-state-title">Aucune attribution</h3>
                <p class="empty-state-text">Ce besoin n'a pas encore reçu d'attributions</p>
                <a href="<?php echo Flight::get('base_url'); ?>/attributions/create" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Créer une attribution
                </a>
            </div>
            <?php else: ?>
            <div class="block-list">
                <?php foreach ($besoin['attributions'] as $attr): ?>
                <div class="block-item">
                    <div class="block-item-content">
                        <div class="block-item-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">
                            <i class="bi bi-gift"></i>
                        </div>
                        <div class="block-item-info">
                            <div class="block-item-title"><?php echo htmlspecialchars($attr['don_nom']); ?></div>
                            <div class="block-item-subtitle">
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
