<?php 
// Page header is handled by layout
?>

<!-- Actions Bar -->
<div class="page-actions">
    <div></div>
    <a href="<?php echo Flight::get('base_url'); ?>/attributions/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nouvelle attribution
    </a>
</div>

<!-- Attributions List -->
<?php if (empty($attributions)): ?>
<div class="section-card">
    <div class="section-body">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <h3 class="empty-state-title">Aucune attribution enregistrée</h3>
            <p class="empty-state-text">Commencez par attribuer des dons aux besoins</p>
            <a href="<?php echo Flight::get('base_url'); ?>/attributions/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Créer une attribution
            </a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="section-card">
    <div class="section-body" style="padding: 0;">
        <div class="block-list">
            <?php foreach ($attributions as $attr): 
                $typeClass = $attr['type_don'] === 'nature' ? 'nature' 
                    : ($attr['type_don'] === 'materiaux' ? 'materiaux' : 'argent');
            ?>
            <div class="block-item">
                <div class="block-item-content">
                    <div class="block-item-icon" style="
                        <?php if ($typeClass === 'nature'): ?>
                        background: rgba(16, 185, 129, 0.1); color: var(--accent-green);
                        <?php elseif ($typeClass === 'materiaux'): ?>
                        background: rgba(245, 158, 11, 0.1); color: var(--accent-amber);
                        <?php else: ?>
                        background: var(--primary-50); color: var(--primary);
                        <?php endif; ?>
                    ">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="block-item-info">
                        <div class="block-item-title">
                            <?php echo htmlspecialchars($attr['don_nom']); ?>
                            <i class="bi bi-arrow-right" style="margin: 0 8px; color: var(--text-muted);"></i>
                            <?php echo htmlspecialchars($attr['besoin_nom']); ?>
                        </div>
                        <div class="block-item-subtitle">
                            <?php echo htmlspecialchars($attr['ville_nom']); ?> • 
                            <?php echo date('d/m/Y H:i', strtotime($attr['date_attribution'])); ?>
                        </div>
                    </div>
                </div>
                <div class="block-item-meta">
                    <span class="badge badge-<?php echo $typeClass; ?>"><?php echo ucfirst($attr['type_don']); ?></span>
                    <span class="badge badge-success">
                        <?php echo number_format($attr['quantite_attribuee'], 0, ',', ' '); ?>
                    </span>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?php echo Flight::get('base_url'); ?>/attributions/<?php echo $attr['id']; ?>" 
                           class="btn btn-sm btn-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?php echo Flight::get('base_url'); ?>/attributions/<?php echo $attr['id']; ?>/delete" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette attribution ?');">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
