<?php 

?>

<div style="margin-bottom: 24px;">
    <a href="<?php echo Flight::get('base_url'); ?>/besoins" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour aux besoins
    </a>
</div>

<div class="section-card">
    <div class="section-body">
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

        <form method="POST" action="<?php echo Flight::get('base_url'); ?>/besoins/store">
            <div class="form-grid">
                <div class="form-group">
                    <label for="ville_id" class="form-label">Ville <span style="color: var(--accent-red);">*</span></label>
                    <select class="form-select" id="ville_id" name="ville_id" required>
                        <option value="">Sélectionner une ville</option>
                        <?php foreach ($villes as $ville): ?>
                        <option value="<?php echo $ville['id']; ?>" 
                                <?php echo (($data['ville_id'] ?? '') == $ville['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($ville['nom'] . ' - ' . $ville['region']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="type_besoin" class="form-label">Type de besoin <span style="color: var(--accent-red);">*</span></label>
                    <select class="form-select" id="type_besoin" name="type_besoin" required>
                        <option value="">Sélectionner le type</option>
                        <option value="nature" <?php echo (($data['type_besoin'] ?? '') === 'nature') ? 'selected' : ''; ?>>Nature (riz, huile, eau...)</option>
                        <option value="materiaux" <?php echo (($data['type_besoin'] ?? '') === 'materiaux') ? 'selected' : ''; ?>>Matériaux (tôle, clous, bois...)</option>
                        <option value="argent" <?php echo (($data['type_besoin'] ?? '') === 'argent') ? 'selected' : ''; ?>>Argent</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="nom" class="form-label">Nom du besoin <span style="color: var(--accent-red);">*</span></label>
                <input type="text" class="form-control" id="nom" name="nom" 
                       value="<?php echo htmlspecialchars($data['nom'] ?? ''); ?>" 
                       placeholder="Ex: Riz, Tôle, Aide financière..." required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                          placeholder="Description détaillée du besoin"><?php echo htmlspecialchars($data['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-grid" style="grid-template-columns: 2fr 1fr;">
                <div class="form-group">
                    <label for="quantite_requise" class="form-label">Quantité requise <span style="color: var(--accent-red);">*</span></label>
                    <input type="number" class="form-control" id="quantite_requise" name="quantite_requise" 
                           value="<?php echo htmlspecialchars($data['quantite_requise'] ?? ''); ?>" 
                           min="0" step="0.01" placeholder="0" required>
                </div>

                <div class="form-group">
                    <label for="unite" class="form-label">Unité <span style="color: var(--accent-red);">*</span></label>
                    <input type="text" class="form-control" id="unite" name="unite" 
                           value="<?php echo htmlspecialchars($data['unite'] ?? ''); ?>" 
                           placeholder="Ex: kg, unités, Ar" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer le besoin
                </button>
                <a href="<?php echo Flight::get('base_url'); ?>/besoins" class="btn btn-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
