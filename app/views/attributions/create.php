<?php 
// Page header is handled by layout
?>

<!-- Back Button -->
<div style="margin-bottom: 24px;">
    <a href="<?php echo Flight::get('base_url'); ?>/attributions" class="btn btn-secondary" style="border-radius: 8px;">
        <i class="bi bi-arrow-left"></i>
        Retour aux attributions
    </a>
</div>

<?php if (isset($errors) && !empty($errors)): ?>
<div class="alert alert-danger" role="alert" style="border-radius: 8px; margin-bottom: 24px;">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <strong>Erreur :</strong>
    <ul class="mb-0 mt-2" style="padding-left: 16px;">
        <?php foreach ($errors as $error): ?>
        <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Main Form Card -->
    <div class="card" style="border-radius: 12px; border: 1px solid var(--border);">
        <div class="card-header" style="background: var(--primary-ultra-pale); padding: 16px 20px; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0;">
            <h5 style="margin: 0; font-weight: 600; color: var(--text-primary);">
                <i class="bi bi-arrow-left-right" style="color: var(--primary); margin-right: 8px;"></i>
                Nouvelle Attribution
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form method="POST" action="<?php echo Flight::get('base_url'); ?>/attributions/store" id="attributionForm">
                <!-- Don Selection -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="don_id" class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 10px; display: block;">
                        <i class="bi bi-gift" style="color: var(--primary); margin-right: 6px;"></i>
                        Sélectionner un don <span style="color: var(--accent-red);">*</span>
                    </label>
                    <select class="form-select" id="don_id" name="don_id" required style="border-radius: 8px; padding: 12px;">
                        <option value="">Sélectionner un don disponible</option>
                        <?php foreach ($dons as $don): ?>
                        <option value="<?php echo $don['id']; ?>" 
                                data-type="<?php echo $don['type_don']; ?>"
                                data-disponible="<?php echo $don['disponible']; ?>"
                                data-unite="<?php echo htmlspecialchars($don['unite']); ?>"
                                <?php echo (($preselected_don_id ?? '') == $don['id']) ? 'selected' : ''; ?>
                                <?php echo (($data['don_id'] ?? '') == $don['id']) ? 'selected' : ''; ?>>
                            <?php echo $don['type_don'] === 'nature' ? '🥕' : ($don['type_don'] === 'materiaux' ? '🔨' : '💰'); ?> <?php echo htmlspecialchars($don['nom']); ?> 
                            (<?php echo number_format($don['disponible'], 0, ',', ' '); ?> <?php echo htmlspecialchars($don['unite']); ?>)
                            - <?php echo ucfirst($don['type_don']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div style="font-size: 13px; margin-top: 8px; color: var(--text-muted);">
                        <i class="bi bi-info-circle" style="margin-right: 4px;"></i>
                        Disponible: <strong id="donDispo" style="color: var(--primary);">-</strong>
                    </div>
                </div>

                <!-- Besoin Selection -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="besoin_id" class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 10px; display: block;">
                        <i class="bi bi-basket" style="color: var(--primary); margin-right: 6px;"></i>
                        Sélectionner un besoin <span style="color: var(--accent-red);">*</span>
                    </label>
                    <select class="form-select" id="besoin_id" name="besoin_id" required style="border-radius: 8px; padding: 12px;">
                        <option value="">Sélectionner un besoin à couvrir</option>
                        <?php foreach ($besoins as $besoin): ?>
                        <option value="<?php echo $besoin['id']; ?>"
                                data-type="<?php echo $besoin['type_besoin']; ?>"
                                data-reste="<?php echo $besoin['reste']; ?>"
                                data-unite="<?php echo htmlspecialchars($besoin['unite']); ?>"
                                <?php echo (($data['besoin_id'] ?? '') == $besoin['id']) ? 'selected' : ''; ?>>
                            <?php echo $besoin['type_besoin'] === 'nature' ? '🥕' : ($besoin['type_besoin'] === 'materiaux' ? '🔨' : '💰'); ?> <?php echo htmlspecialchars($besoin['nom']); ?> 
                            (<?php echo htmlspecialchars($besoin['ville_nom']); ?>)
                            - reste: <?php echo number_format($besoin['reste'], 0, ',', ' '); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div style="font-size: 13px; margin-top: 8px; color: var(--text-muted);">
                        <i class="bi bi-info-circle" style="margin-right: 4px;"></i>
                        Reste à couvrir: <strong id="besoinReste" style="color: var(--accent-red);">-</strong>
                    </div>
                </div>

                <!-- Type Match Alert -->
                <div class="alert" id="typeMatchAlert" style="display: none; border-radius: 8px; margin-bottom: 20px;">
                    <i class="bi bi-info-circle me-2"></i>
                    <span id="typeMatchMessage"></span>
                </div>

                <!-- Quantité -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="quantite_attribuee" class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 10px; display: block;">
                        <i class="bi bi-plus-circle" style="color: var(--primary); margin-right: 6px;"></i>
                        Quantité à attribuer <span style="color: var(--accent-red);">*</span>
                    </label>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <input type="number" class="form-control" id="quantite_attribuee" name="quantite_attribuee" 
                               value="<?php echo htmlspecialchars($data['quantite_attribuee'] ?? ''); ?>" 
                               min="0" step="0.01" style="flex: 1; border-radius: 8px; padding: 12px;" required>
                        <span id="uniteLabel" style="font-weight: 600; color: var(--text-muted); min-width: 60px;">-</span>
                    </div>
                    <div style="font-size: 13px; margin-top: 8px; color: var(--text-muted);" id="quantiteHelp"></div>
                </div>

                <!-- Observations -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="observations" class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 10px; display: block;">
                        <i class="bi bi-chat-text" style="color: var(--primary); margin-right: 6px;"></i>
                        Observations
                    </label>
                    <textarea class="form-control" id="observations" name="observations" rows="3"
                              placeholder="Ajouter des observations si nécessaire" style="border-radius: 8px; padding: 12px;"><?php echo htmlspecialchars($data['observations'] ?? ''); ?></textarea>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" id="submitBtn" style="flex: 1; padding: 12px 24px; border-radius: 8px; font-weight: 600;">
                        <i class="bi bi-check-circle"></i>
                        Créer l'attribution
                    </button>
                    <a href="<?php echo Flight::get('base_url'); ?>/attributions" class="btn" style="padding: 12px 24px; border-radius: 8px; font-weight: 500; background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border);">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Card -->
    <div class="card" style="border-radius: 12px; border: 1px solid var(--border);">
        <div class="card-header" style="background: var(--primary-ultra-pale); padding: 16px 20px; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0;">
            <h5 style="margin: 0; font-weight: 600; color: var(--text-primary);">
                <i class="bi bi-lightbulb" style="color: var(--primary); margin-right: 8px;"></i>
                Aide
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <h6 style="font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">Règles d'attribution :</h6>
            <ul style="font-size: 13px; color: var(--text-muted); padding-left: 16px; margin-bottom: 24px; line-height: 1.8;">
                <li style="margin-bottom: 8px;">Le type de don doit correspondre au type de besoin</li>
                <li style="margin-bottom: 8px;">La quantité ne peut pas dépasser le don disponible</li>
                <li>Vous pouvez attribuer un don à plusieurs besoins</li>
            </ul>
            
            <hr style="margin: 20px 0; border-color: var(--border);">
            
            <h6 style="font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">Légende des types :</h6>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                <span style="font-size: 16px;">🥕</span>
                <span style="font-size: 13px; color: var(--text-muted);">Nature - Produits alimentaires, eau...</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                <span style="font-size: 16px;">🔨</span>
                <span style="font-size: 13px; color: var(--text-muted);">Matériaux - Tôles, clous, bois...</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 16px;">💰</span>
                <span style="font-size: 13px; color: var(--text-muted);">Argent - Contributions financières</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const donSelect = document.getElementById('don_id');
    const besoinSelect = document.getElementById('besoin_id');
    const quantiteInput = document.getElementById('quantite_attribuee');
    const donDispo = document.getElementById('donDispo');
    const besoinReste = document.getElementById('besoinReste');
    const uniteLabel = document.getElementById('uniteLabel');
    const typeMatchAlert = document.getElementById('typeMatchAlert');
    const typeMatchMessage = document.getElementById('typeMatchMessage');
    const quantiteHelp = document.getElementById('quantiteHelp');
    const submitBtn = document.getElementById('submitBtn');
    
    // Auto-trigger update if a don is pre-selected (from don show page)
    if (donSelect.value) {
        updateDonInfo();
    }

    function updateDonInfo() {
        const option = donSelect.options[donSelect.selectedIndex];
        if (option && option.value) {
            const disponible = option.dataset.disponible;
            const unite = option.dataset.unite;
            donDispo.textContent = number_format(disponible) + ' ' + unite;
            uniteLabel.textContent = unite;
            updateQuantiteHelp();
        } else {
            donDispo.textContent = '-';
            uniteLabel.textContent = '-';
        }
    }

    function updateBesoinInfo() {
        const option = besoinSelect.options[besoinSelect.selectedIndex];
        if (option && option.value) {
            const reste = option.dataset.reste;
            const unite = option.dataset.unite;
            besoinReste.textContent = number_format(reste) + ' ' + unite;
            updateQuantiteHelp();
        } else {
            besoinReste.textContent = '-';
        }
    }

    function updateQuantiteHelp() {
        const donOption = donSelect.options[donSelect.selectedIndex];
        const besoinOption = besoinSelect.options[besoinSelect.selectedIndex];
        
        if (donOption && donOption.value && besoinOption && besoinOption.value) {
            const disponible = parseFloat(donOption.dataset.disponible);
            const reste = parseFloat(besoinOption.dataset.reste);
            const maxQuantite = Math.min(disponible, reste);
            const unite = donOption.dataset.unite;
            
            quantiteHelp.innerHTML = '<i class="bi bi-info-circle" style="margin-right: 4px;"></i> Maximum attribuable: <strong style="color: var(--primary);">' + number_format(maxQuantite) + ' ' + unite + '</strong>';
            quantiteInput.max = maxQuantite;
            
            const donType = donOption.dataset.type;
            const besoinType = besoinOption.dataset.type;
            
            if (donType !== besoinType) {
                typeMatchAlert.className = 'alert alert-danger';
                typeMatchAlert.style.display = 'block';
                typeMatchMessage.textContent = 'Attention: Le type du don ne correspond pas au type du besoin. L\'attribution sera refusée.';
                submitBtn.disabled = true;
            } else {
                typeMatchAlert.className = 'alert alert-success';
                typeMatchAlert.style.background = 'var(--primary-ultra-pale)';
                typeMatchAlert.style.color = 'var(--primary)';
                typeMatchAlert.style.border = '1px solid var(--primary-200)';
                typeMatchAlert.style.display = 'block';
                typeMatchMessage.textContent = 'Les types correspondent. L\'attribution est possible.';
                submitBtn.disabled = false;
            }
        } else {
            quantiteHelp.textContent = '';
            typeMatchAlert.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    function number_format(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    donSelect.addEventListener('change', function() {
        updateDonInfo();
    });

    besoinSelect.addEventListener('change', function() {
        updateBesoinInfo();
    });

    quantiteInput.addEventListener('input', function() {
        const donOption = donSelect.options[donSelect.selectedIndex];
        const besoinOption = besoinSelect.options[besoinSelect.selectedIndex];
        
        if (donOption && donOption.value && besoinOption && besoinOption.value) {
            const disponible = parseFloat(donOption.dataset.disponible);
            const quantite = parseFloat(this.value) || 0;
            
            if (quantite > disponible) {
                this.style.borderColor = 'var(--accent-red)';
            } else {
                this.style.borderColor = '';
            }
        }
    });

    // Initialize
    updateDonInfo();
    updateBesoinInfo();
});
</script>
