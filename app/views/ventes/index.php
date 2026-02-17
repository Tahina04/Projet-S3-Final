<?php 
// Page header is handled by layout
$filter_type = $filter_type ?? null;
$filter_date = $filter_date ?? null;
?>

<!-- Filter and Actions Bar -->
<div class="filter-bar">
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="<?php echo Flight::get('base_url'); ?>/ventes" 
           class="btn btn-sm <?php echo !$filter_type ? 'btn-primary' : 'btn-secondary'; ?>">
            Tous
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/ventes?type=nature" 
           class="btn btn-sm filter-tab <?php echo $filter_type === 'nature' ? 'active' : ''; ?>">
            <i class="bi bi-carrot"></i> Nature
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/ventes?type=materiaux" 
           class="btn btn-sm filter-tab <?php echo $filter_type === 'materiaux' ? 'active' : ''; ?>">
            <i class="bi bi-hammer"></i> Matériaux
        </a>
        <a href="<?php echo Flight::get('base_url'); ?>/ventes?type=argent" 
           class="btn btn-sm filter-tab <?php echo $filter_type === 'argent' ? 'active' : ''; ?>">
            <i class="bi bi-cash-coin"></i> Argent
        </a>
    </div>
    
    <div style="display: flex; gap: 10px; align-items: center;">
        <form method="GET" action="<?php echo Flight::get('base_url'); ?>/ventes" style="display: flex; gap: 8px; align-items: center;">
            <?php if ($filter_type): ?>
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($filter_type); ?>">
            <?php endif; ?>
            <input type="date" name="date" value="<?php echo htmlspecialchars($filter_date ?? ''); ?>" 
                   class="form-control" style="padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 13px; width: 140px;">
            <button type="submit" class="btn btn-sm btn-secondary" title="Filtrer par date">
                <i class="bi bi-calendar"></i>
            </button>
            <?php if ($filter_date): ?>
            <a href="<?php echo Flight::get('base_url'); ?>/ventes<?php echo $filter_type ? '?type=' . $filter_type : ''; ?>" class="btn btn-sm btn-outline-secondary" title="Effacer la date">
                <i class="bi bi-x-lg"></i>
            </a>
            <?php endif; ?>
        </form>
        <a href="<?php echo Flight::get('base_url'); ?>/ventes/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Nouvelle vente
        </a>
    </div>
</div>

<!-- Ventes List -->
<?php if (empty($ventes)): ?>
<div class="section-card">
    <div class="section-body">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <h3 class="empty-state-title">Aucune vente enregistrée</h3>
            <p class="empty-state-text">Les ventes de dons apparaîtront ici une fois effectuées</p>
            <a href="<?php echo Flight::get('base_url'); ?>/ventes/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Effectuer une vente
            </a>
        </div>
    </div>
</div>
<?php else: ?>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
    <?php foreach ($ventes as $vente): 
        $typeClass = $vente['type_don'] === 'nature' ? 'nature' 
            : ($vente['type_don'] === 'materiaux' ? 'materiaux' : 'argent');
    ?>
    <div class="card" style="border-radius: 12px; border: 1px solid var(--border); overflow: hidden; transition: all 0.3s ease;">
        <!-- Card Header with Gradient -->
        <div style="background: linear-gradient(135deg, var(--primary-ultra-pale) 0%, #f8f9ff 100%); padding: 16px 20px; border-bottom: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
                        <?php if ($typeClass === 'nature'): ?>
                        background: rgba(16, 185, 129, 0.15); color: var(--accent-green);
                        <?php elseif ($typeClass === 'materiaux'): ?>
                        background: rgba(245, 158, 11, 0.15); color: var(--accent-amber);
                        <?php else: ?>
                        background: var(--primary); color: white;
                        <?php endif; ?>
                    ">
                        <i class="bi bi-currency-dollar" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 16px; color: var(--text-primary);">
                            <?php echo htmlspecialchars($vente['don_nom']); ?>
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted);">
                            <?php echo date('d/m/Y', strtotime($vente['date_vente'])); ?>
                        </div>
                    </div>
                </div>
                <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase;
                    <?php if ($typeClass === 'nature'): ?>
                    background: rgba(16, 185, 129, 0.15); color: var(--accent-green);
                    <?php elseif ($typeClass === 'materiaux'): ?>
                    background: rgba(245, 158, 11, 0.15); color: var(--accent-amber);
                    <?php else: ?>
                    background: var(--primary); color: white;
                    <?php endif; ?>
                ">
                    <?php echo ucfirst($vente['type_don']); ?>
                </span>
            </div>
        </div>
        
        <!-- Card Body -->
        <div class="card-body" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 2px;">Quantité</div>
                    <div style="font-weight: 600; color: var(--text-primary);">
                        <?php echo number_format($vente['quantite_vendue'], 0, ',', ' '); ?> 
                        <?php echo htmlspecialchars($vente['don_unite']); ?>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 2px;">Prix après réduction</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--accent-green);">
                        <?php echo number_format($vente['prix_apres_reduction'], 0, ',', ' '); ?> Ar
                    </div>
                </div>
            </div>
            
            <div style="background: var(--primary-ultra-pale); padding: 10px 14px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 13px; color: var(--text-secondary);">Réduction appliquée</span>
                <span style="font-weight: 600; color: var(--primary);">-<?php echo $vente['reduction_pourcentage']; ?>%</span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<style>
.card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
    border-color: var(--primary-200);
}
</style>
