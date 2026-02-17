<?php 
// Page header is handled by layout
?>

<!-- Back Button -->
<div style="margin-bottom: 24px;">
    <a href="<?php echo Flight::get('base_url'); ?>/dons" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour aux dons
    </a>
</div>

<!-- Edit Form -->
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

        <form method="POST" action="<?php echo Flight::get('base_url'); ?>/dons/<?php echo $don['id']; ?>/update">
            <div class="form-grid">
                <div class="form-group">
                    <label for="type_don" class="form-label">Type de don <span style="color: var(--accent-red);">*</span></label>
                    <select class="form-select" id="type_don" name="type_don" required>
                        <option value="nature" <?php echo ($don['type_don'] === 'nature') ? 'selected' : ''; ?>>Nature</option>
                        <option value="materiaux" <?php echo ($don['type_don'] === 'materiaux') ? 'selected' : ''; ?>>Matériaux</option>
                        <option value="argent" <?php echo ($don['type_don'] === 'argent') ? 'selected' : ''; ?>>Argent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nom" class="form-label">Nom du don <span style="color: var(--accent-red);">*</span></label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($don['nom']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                          placeholder="Description détaillée du don"><?php echo htmlspecialchars($don['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label for="quantite_disponible" class="form-label">Quantité disponible <span style="color: var(--accent-red);">*</span></label>
                    <input type="number" class="form-control" id="quantite_disponible" name="quantite_disponible" 
                           value="<?php echo htmlspecialchars($don['quantite_disponible']); ?>" 
                           min="0" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="unite" class="form-label">Unité <span style="color: var(--accent-red);">*</span></label>
                    <input type="text" class="form-control" id="unite" name="unite" 
                           value="<?php echo htmlspecialchars($don['unite']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="donateur" class="form-label">Donateur</label>
                    <input type="text" class="form-control" id="donateur" name="donateur" 
                           value="<?php echo htmlspecialchars($don['donateur'] ?? ''); ?>"
                           placeholder="Nom du donateur">
                </div>
            </div>

            <div class="form-group">
                <label for="date_expiration" class="form-label">Date d'expiration</label>
                <input type="date" class="form-control" id="date_expiration" name="date_expiration" 
                       value="<?php echo htmlspecialchars($don['date_expiration'] ?? ''); ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Mettre à jour
                </button>
                <a href="<?php echo Flight::get('base_url'); ?>/dons" class="btn btn-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
