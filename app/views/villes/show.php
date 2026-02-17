<?php 
// Page header is handled by layout
?>

<!-- Action Buttons -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <a href="<?php echo Flight::get('base_url'); ?>/villes" class="btn" style="padding: 10px 16px; border-radius: 8px; font-weight: 500; background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border);">
        <i class="bi bi-arrow-left" style="margin-right: 6px;"></i>
        Retour aux villes
    </a>
    <div style="display: flex; gap: 12px;">
        <a href="<?php echo Flight::get('base_url'); ?>/villes/<?php echo $ville['id']; ?>/edit" class="btn" style="padding: 10px 16px; border-radius: 8px; font-weight: 500; background: var(--primary-ultra-pale); color: var(--primary); border: none;">
            <i class="bi bi-pencil" style="margin-right: 6px;"></i>
            Modifier
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/villes/<?php echo $ville['id']; ?>/delete" 
           class="btn" 
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ville ?');"
           style="padding: 10px 16px; border-radius: 8px; font-weight: 500; background: #dc3545; color: white; border: none;">
            <i class="bi bi-trash" style="margin-right: 6px;"></i>
            Supprimer
        </a>
    </div>
</div>

<!-- Ville Details -->
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Info Card -->
    <div class="card" style="border-radius: 12px; border: 1px solid var(--border);">
        <div style="background: var(--primary-ultra-pale); padding: 16px 20px; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0;">
            <h5 style="margin: 0; font-weight: 600; color: var(--text-primary);">
                <i class="bi bi-buildings" style="color: var(--primary); margin-right: 8px;"></i>
                Informations
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <div style="margin-bottom: 20px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Nom</div>
                <div style="font-size: 20px; font-weight: 700; color: var(--text-primary);">
                    <?php echo htmlspecialchars($ville['nom']); ?>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Région</div>
                <span style="display: inline-block; background: var(--primary); color: white; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                    <?php echo htmlspecialchars($ville['region']); ?>
                </span>
            </div>
            
            <div>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Description</div>
                <div style="font-size: 14px; color: var(--text-secondary); line-height: 1.6;">
                    <?php echo htmlspecialchars($ville['description'] ?? 'Aucune description'); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Besoins Card -->
    <div class="card" style="border-radius: 12px; border: 1px solid var(--border);">
        <div style="background: var(--primary-ultra-pale); padding: 16px 20px; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-weight: 600; color: var(--text-primary);">
                <i class="bi bi-basket" style="color: var(--primary); margin-right: 8px;"></i>
                Besoins de la ville
            </h5>
            <a href="<?php echo Flight::get('base_url'); ?>/besoins/create" class="btn btn-primary btn-sm" style="padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                <i class="bi bi-plus-lg" style="margin-right: 4px;"></i>
                Ajouter un besoin
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if (empty($ville['besoins'])): ?>
            <div style="padding: 60px 24px; text-align: center;">
                <div style="width: 70px; height: 70px; background: var(--primary-ultra-pale); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="bi bi-basket" style="font-size: 28px; color: var(--primary);"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Aucun besoin enregistré</h3>
                <p style="color: var(--text-muted); margin-bottom: 24px;">Ajoutez les besoins pour cette ville</p>
                <a href="<?php echo Flight::get('base_url'); ?>/besoins/create" class="btn btn-primary" style="padding: 10px 24px; border-radius: 8px; font-weight: 600;">
                    <i class="bi bi-plus-lg" style="margin-right: 6px;"></i>
                    Ajouter un besoin
                </a>
            </div>
            <?php else: ?>
            <div style="display: flex; flex-direction: column;">
                <?php foreach ($ville['besoins'] as $besoin): 
                    $typeClass = $besoin['type_besoin'] === 'nature' ? 'nature' 
                        : ($besoin['type_besoin'] === 'materiaux' ? 'materiaux' : 'argent');
                    $progress = $besoin['quantite_requise'] > 0 
                        ? (($besoin['total_attribue'] ?? 0) / $besoin['quantite_requise']) * 100 
                        : 0;
                ?>
                <a href="<?php echo Flight::get('base_url'); ?>/besoins/<?php echo $besoin['id']; ?>" 
                   style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border); text-decoration: none; transition: background 0.2s;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="
                            width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
                            <?php if ($typeClass === 'nature'): ?>
                            background: rgba(16, 185, 129, 0.1); color: var(--accent-green);
                            <?php elseif ($typeClass === 'materiaux'): ?>
                            background: rgba(245, 158, 11, 0.1); color: var(--accent-amber);
                            <?php else: ?>
                            background: rgba(147, 51, 234, 0.1); color: #9333ea;
                            <?php endif; ?>
                        ">
                            <i class="bi bi-<?php echo $typeClass === 'nature' ? 'carrot' : ($typeClass === 'materiaux' ? 'hammer' : 'cash-coin'); ?>" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 2px;"><?php echo htmlspecialchars($besoin['nom']); ?></div>
                            <span style="display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: uppercase;
                                <?php if ($typeClass === 'nature'): ?>
                                background: rgba(16, 185, 129, 0.15); color: var(--accent-green);
                                <?php elseif ($typeClass === 'materiaux'): ?>
                                background: rgba(245, 158, 11, 0.15); color: var(--accent-amber);
                                <?php else: ?>
                                background: var(--primary-ultra-pale); color: var(--primary);
                                <?php endif; ?>
                            "><?php echo ucfirst($besoin['type_besoin']); ?></span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="text-align: right;">
                            <div style="font-size: 16px; font-weight: 700; color: var(--text-primary);"><?php echo number_format($besoin['quantite_requise'], 0, ',', ' '); ?></div>
                            <div style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($besoin['unite']); ?></div>
                        </div>
                        <i class="bi bi-chevron-right" style="color: var(--text-muted); font-size: 14px;"></i>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
a[style*="border-bottom"]:hover {
    background: var(--primary-ultra-pale);
}
</style>
