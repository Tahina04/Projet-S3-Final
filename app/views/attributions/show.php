<?php 
// Page header is handled by layout

$typeClass = $attribution['type_don'] === 'nature' ? 'nature' 
    : ($attribution['type_don'] === 'materiaux' ? 'materiaux' : 'argent');
?>

<!-- Action Buttons -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <a href="<?php echo Flight::get('base_url'); ?>/attributions" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour aux attributions
    </a>
    <a href="<?php echo Flight::get('base_url'); ?>/attributions/<?php echo $attribution['id']; ?>/delete" 
       class="btn btn-secondary" 
       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette attribution ?');">
        <i class="bi bi-trash"></i>
        Supprimer
    </a>
</div>

<!-- Attribution Details -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
    <!-- Attribution Info Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-arrow-left-right"></i>
                <span>Attribution</span>
            </div>
        </div>
        <div class="section-body">
            <div style="text-align: center; margin-bottom: 24px;">
                <div style="font-size: 48px; font-weight: 700; color: var(--accent-green);">
                    <?php echo number_format($attribution['quantite_attribuee'], 0, ',', ' '); ?>
                </div>
                <div style="font-size: 14px; color: var(--text-muted);">
                    <?php echo htmlspecialchars($attribution['don_unite']); ?>
                </div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">Date</div>
                <div style="font-size: 15px; color: var(--text-primary);">
                    <?php echo date('d/m/Y H:i', strtotime($attribution['date_attribution'])); ?>
                </div>
            </div>
            
            <?php if ($attribution['observations']): ?>
            <div>
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">Observations</div>
                <div style="font-size: 14px; color: var(--text-secondary);">
                    <?php echo htmlspecialchars($attribution['observations']); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Don Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-gift"></i>
                <span>Don</span>
            </div>
        </div>
        <div class="section-body">
            <div style="margin-bottom: 16px;">
                <div style="font-size: 18px; font-weight: 600; color: var(--text-primary);">
                    <?php echo htmlspecialchars($attribution['don_nom']); ?>
                </div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <span class="badge badge-<?php echo $typeClass; ?>"><?php echo ucfirst($attribution['type_don']); ?></span>
            </div>
            
            <div>
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">Quantité totale</div>
                <div style="font-size: 15px; color: var(--text-primary);">
                    <?php echo number_format($attribution['quantite_disponible'], 0, ',', ' '); ?> <?php echo htmlspecialchars($attribution['don_unite']); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Besoin Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-basket"></i>
                <span>Besoin</span>
            </div>
        </div>
        <div class="section-body">
            <div style="margin-bottom: 16px;">
                <div style="font-size: 18px; font-weight: 600; color: var(--text-primary);">
                    <?php echo htmlspecialchars($attribution['besoin_nom']); ?>
                </div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <span class="badge badge-<?php echo $typeClass; ?>"><?php echo ucfirst($attribution['type_besoin']); ?></span>
            </div>
            
            <div>
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">Ville</div>
                <div style="font-size: 15px; color: var(--text-primary);">
                    <i class="bi bi-buildings"></i>
                    <?php echo htmlspecialchars($attribution['ville_nom']); ?> (<?php echo htmlspecialchars($attribution['region']); ?>)
                </div>
            </div>
        </div>
    </div>
</div>
