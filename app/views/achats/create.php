<?php 
// Page header is handled by layout
?>

<!-- Back Button -->
<div class="page-actions">
    <div></div>
    <a href="<?php echo Flight::get('base_url'); ?>/achats" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour aux achats
    </a>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <?php echo htmlspecialchars($_GET['error']); ?>
</div>
<?php endif; ?>

<div class="form-grid" style="grid-template-columns: 2fr 1fr;">
    <!-- Form Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-cart-plus"></i>
                <span>Effectuer un achat</span>
            </div>
        </div>
        <div class="section-body">
            <form method="POST" action="<?php echo Flight::get('base_url'); ?>/achats/store">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="besoin_id" class="form-label">Besoin à acheter <span style="color: var(--accent-red);">*</span></label>
                        <select name="besoin_id" id="besoin_id" class="form-select" required>
                            <option value="">Sélectionner un besoin</option>
                            <?php foreach ($besoins as $besoin): ?>
                                <option value="<?php echo $besoin['id']; ?>" 
                                    data-prix="<?php echo $besoin['prix_unitaire']; ?>"
                                    data-unite="<?php echo htmlspecialchars($besoin['unite']); ?>"
                                    data-reste="<?php echo $besoin['reste']; ?>">
                                    <?php echo $besoin['type_besoin'] === 'nature' ? '🥕' : ($besoin['type_besoin'] === 'materiaux' ? '🔨' : '💰'); ?> <?php echo htmlspecialchars($besoin['ville_nom'] . ' - ' . $besoin['nom'] . ' (' . $besoin['unite'] . ') - Prix: ' . number_format($besoin['prix_unitaire'], 0, ',', ' ') . ' Ar'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-hint">Sélectionnez un besoin en nature ou en matériaux</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="don_argent_id" class="form-label">Don en argent <span style="color: var(--accent-red);">*</span></label>
                        <select name="don_argent_id" id="don_argent_id" class="form-select" required>
                            <option value="">Sélectionner un don</option>
                            <?php foreach ($dons as $don): ?>
                                <option value="<?php echo $don['id']; ?>"
                                    data-disponible="<?php echo $don['disponible']; ?>">
                                    <?php echo $don['type_don'] === 'nature' ? '🥕' : ($don['type_don'] === 'materiaux' ? '🔨' : '💰'); ?> <?php echo htmlspecialchars($don['nom'] . ' - Disponible: ' . number_format($don['disponible'], 0, ',', ' ') . ' Ar'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-hint">Sélectionnez un don en argent disponible</small>
                    </div>
                </div>
                
                <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr;">
                    <div class="form-group">
                        <label for="quantite" class="form-label">Quantité <span style="color: var(--accent-red);">*</span></label>
                        <div class="input-with-suffix">
                            <input type="number" class="form-control" id="quantite" name="quantite" 
                                   min="1" step="1" required>
                            <span class="input-suffix" id="unite-display">-</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Prix unitaire</label>
                        <div class="form-value">
                            <span id="prix-unitaire-display">0</span> Ar
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Montant total</label>
                        <div class="form-value">
                            <strong><span id="montant-total-display">0</span> Ar</strong>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="observations" class="form-label">Observations</label>
                    <textarea class="form-control" id="observations" name="observations" 
                              rows="3" placeholder="Observations optionnelles..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Confirmer l'achat
                    </button>
                    <a href="<?php echo Flight::get('base_url'); ?>/achats" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-info-circle"></i>
                <span>Informations</span>
            </div>
        </div>
        <div class="section-body">
            <h6 style="font-weight: 600; margin-bottom: 12px;">À propos des achats :</h6>
            <ul class="info-list">
                <li>Les achats permettent d'utiliser les dons en argent pour satisfaire les besoins en nature et en matériaux.</li>
                <li>Les prix unitaires des besoins sont fixes et définis dans le système.</li>
                <li>Le montant total de l'achat ne peut pas dépasser le don en argent disponible.</li>
                <li>Après l'achat, une attribution est automatiquement créée pour le besoin.</li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const besoinSelect = document.getElementById('besoin_id');
    const donSelect = document.getElementById('don_argent_id');
    const quantiteInput = document.getElementById('quantite');
    const uniteDisplay = document.getElementById('unite-display');
    const prixUnitaireDisplay = document.getElementById('prix-unitaire-display');
    const montantTotalDisplay = document.getElementById('montant-total-display');
    
    function updateCalcul() {
        const selectedOption = besoinSelect.options[besoinSelect.selectedIndex];
        const prixUnitaire = parseFloat(selectedOption.dataset.prix) || 0;
        const unite = selectedOption.dataset.unite || '-';
        const quantite = parseFloat(quantiteInput.value) || 0;
        
        uniteDisplay.textContent = unite;
        prixUnitaireDisplay.textContent = prixUnitaire.toLocaleString('fr-FR');
        montantTotalDisplay.textContent = (prixUnitaire * quantite).toLocaleString('fr-FR');
    }
    
    besoinSelect.addEventListener('change', updateCalcul);
    quantiteInput.addEventListener('input', updateCalcul);
});
</script>
