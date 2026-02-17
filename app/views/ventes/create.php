<?php 
// Page header is handled by layout
$page_title = "Effectuer une vente";
?>

<!-- Back Button -->
<div class="page-actions">
    <a href="<?php echo Flight::get('base_url'); ?>/ventes" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour aux ventes
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

<div class="form-grid" style="grid-template-columns: 2fr 1fr;">
    <!-- Form Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-currency-dollar"></i>
                <span>Effectuer une vente</span>
            </div>
        </div>
        <div class="section-body">
            <form method="POST" action="<?php echo Flight::get('base_url'); ?>/ventes/store" id="venteForm">
                <div class="form-group">
                    <label for="don_id" class="form-label">Sélectionner un don à vendre <span style="color: var(--accent-red);">*</span></label>
                    <select name="don_id" id="don_id" class="form-select" required onchange="updateDonInfo()">
                        <option value="">Sélectionner un don</option>
                        <?php foreach ($dons as $don): 
                            $disabled = !$don['peut_vendre'] ? 'disabled' : '';
                            $reason = $don['peut_vendre'] ? '' : $don['raison_non_vendable'];
                        ?>
                        <option value="<?php echo $don['id']; ?>" 
                                data-disponible="<?php echo $don['quantite_disponible']; ?>"
                                data-unite="<?php echo htmlspecialchars($don['unite']); ?>"
                                data-peut-vendre="<?php echo $don['peut_vendre']; ?>"
                                data-reason="<?php echo htmlspecialchars($reason); ?>"
                                <?php echo $disabled; ?>
                                <?php echo (($data['don_id'] ?? '') == $don['id']) ? 'selected' : ''; ?>>
                            <?php echo $don['type_don'] === 'nature' ? '🥕' : ($don['type_don'] === 'materiaux' ? '🔨' : '💰'); ?> <?php echo htmlspecialchars($don['nom']); ?> 
                            (<?php echo number_format($don['quantite_disponible'], 0, ',', ' '); ?> <?php echo htmlspecialchars($don['unite']); ?>)
                            <?php if (!$don['peut_vendre']): ?> - Non vendable <?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="donStatus" style="font-size: 13px; margin-top: 8px; color: var(--text-secondary);"></div>
                </div>
                
                <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                    <div class="form-group">
                        <label for="quantite" class="form-label">Quantité à vendre <span style="color: var(--accent-red);">*</span></label>
                        <div class="input-with-suffix">
                            <input type="number" class="form-control" id="quantite" name="quantite" 
                                   value="<?php echo htmlspecialchars($data['quantite'] ?? ''); ?>" 
                                   min="0.01" step="0.01" required oninput="calculateTotal()">
                            <span class="input-suffix" id="unite-display">-</span>
                        </div>
                        <small class="form-hint" id="disponibleHint"></small>
                    </div>

                    <div class="form-group">
                        <label for="prix_unitaire" class="form-label">Prix unitaire (Ar) <span style="color: var(--accent-red);">*</span></label>
                        <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" 
                               value="<?php echo htmlspecialchars($data['prix_unitaire'] ?? ''); ?>" 
                               min="0" step="1" required oninput="calculateTotal()">
                    </div>
                </div>
                
                <!-- Prix Summary -->
                <div class="form-group" style="background: var(--bg-soft); padding: 20px; border-radius: 12px; margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="color: var(--text-secondary);">Prix total:</span>
                        <span style="font-size: 18px; font-weight: 600;" id="prixTotal">0 Ar</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="color: var(--text-secondary);">Réduction (<?php echo $reduction_pourcentage; ?>%):</span>
                        <span style="color: var(--accent-red);" id="reductionAmount">-0 Ar</span>
                    </div>
                    <hr style="margin: 12px 0; border-color: var(--border-light);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; color: var(--text-primary);">Prix après réduction:</span>
                        <span style="font-size: 24px; font-weight: 700; color: var(--accent-green);" id="prixFinal">0 Ar</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="observations" class="form-label">Observations</label>
                    <textarea class="form-control" id="observations" name="observations" rows="3"
                              placeholder="Observations optionnelles..."><?php echo htmlspecialchars($data['observations'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-check-circle"></i>
                        Confirmer la vente
                    </button>
                    <a href="<?php echo Flight::get('base_url'); ?>/ventes" class="btn btn-secondary">
                        Annuler
                    </a>
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
            <h6 style="font-weight: 600; margin-bottom: 12px;">Règles de vente :</h6>
            <ul class="info-list">
                <li>Un don ne peut être vendu que s'il n'est plus nécessaire par aucune ville</li>
                <li>Si une ville a encore un besoin pour ce produit, la vente n'est pas possible</li>
                <li>Le pourcentage de réduction est défini dans les paramètres</li>
            </ul>
            
            <hr style="margin: 24px 0;">
            
            <h6 style="font-weight: 600; margin-bottom: 12px;">Statut actuel :</h6>
            <div id="statusInfo" style="font-size: 13px; color: var(--text-secondary);">
                Sélectionnez un don pour voir le statut
            </div>
        </div>
    </div>
</div>

<script>
const reductionPourcentage = <?php echo $reduction_pourcentage; ?>;

function updateDonInfo() {
    const select = document.getElementById('don_id');
    const option = select.options[select.selectedIndex];
    const statusInfo = document.getElementById('statusInfo');
    const disponibleHint = document.getElementById('disponibleHint');
    const uniteDisplay = document.getElementById('unite-display');
    const submitBtn = document.getElementById('submitBtn');
    
    if (option && option.value) {
        const disponible = option.dataset.disponible;
        const unite = option.dataset.unite;
        const peutVendre = option.dataset.peutVendre === '1';
        const reason = option.dataset.reason;
        
        uniteDisplay.textContent = unite;
        disponibleHint.textContent = 'Disponible: ' + parseInt(disponible).toLocaleString('fr-FR') + ' ' + unite;
        
        if (peutVendre) {
            statusInfo.innerHTML = '<span style="color: var(--accent-green); font-weight: 600;">✓ Ce don peut être vendu</span>';
            submitBtn.disabled = false;
        } else {
            statusInfo.innerHTML = '<span style="color: var(--accent-red); font-weight: 600;">✗ Ce don ne peut pas être vendu</span><br><small>' + reason + '</small>';
            submitBtn.disabled = true;
        }
        
        calculateTotal();
    } else {
        uniteDisplay.textContent = '-';
        disponibleHint.textContent = '';
        statusInfo.textContent = 'Sélectionnez un don pour voir le statut';
        submitBtn.disabled = false;
    }
}

function calculateTotal() {
    const quantite = parseFloat(document.getElementById('quantite').value) || 0;
    const prixUnitaire = parseFloat(document.getElementById('prix_unitaire').value) || 0;
    
    const prixTotal = quantite * prixUnitaire;
    const reduction = prixTotal * (reductionPourcentage / 100);
    const prixFinal = prixTotal - reduction;
    
    document.getElementById('prixTotal').textContent = prixTotal.toLocaleString('fr-FR') + ' Ar';
    document.getElementById('reductionAmount').textContent = '-' + reduction.toLocaleString('fr-FR') + ' Ar';
    document.getElementById('prixFinal').textContent = prixFinal.toLocaleString('fr-FR') + ' Ar';
}

// Initialize
updateDonInfo();
</script>
