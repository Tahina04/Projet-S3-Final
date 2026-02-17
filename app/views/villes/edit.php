<?php 
// Page header is handled by layout
?>

<!-- Back Button -->
<div style="margin-bottom: 24px;">
    <a href="<?php echo Flight::get('base_url'); ?>/villes" class="btn" style="padding: 10px 16px; border-radius: 8px; font-weight: 500; background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border);">
        <i class="bi bi-arrow-left" style="margin-right: 6px;"></i>
        Retour aux villes
    </a>
</div>

<!-- Edit Form -->
<div class="card" style="border-radius: 12px; border: 1px solid var(--border); max-width: 700px;">
    <div style="background: var(--primary-ultra-pale); padding: 16px 24px; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0;">
        <h5 style="margin: 0; font-weight: 600; color: var(--text-primary);">
            <i class="bi bi-pencil-square" style="color: var(--primary); margin-right: 8px;"></i>
            Modifier la ville
        </h5>
    </div>
    <div class="card-body" style="padding: 24px;">
        <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger" role="alert" style="border-radius: 8px; margin-bottom: 20px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul class="mb-0" style="padding-left: 16px;">
                <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo Flight::get('base_url'); ?>/villes/<?php echo $ville['id']; ?>/update">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="nom" class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 8px; display: block;">
                        Nom de la ville <span style="color: var(--accent-red);">*</span>
                    </label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($data['nom'] ?? $ville['nom']); ?>" required
                           style="padding: 12px 16px; font-size: 14px; border-radius: 8px; border: 1px solid var(--border);">
                </div>

                <div>
                    <label for="region" class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 8px; display: block;">
                        Région <span style="color: var(--accent-red);">*</span>
                    </label>
                    <input type="text" class="form-control" id="region" name="region" 
                           value="<?php echo htmlspecialchars($data['region'] ?? $ville['region']); ?>" required
                           style="padding: 12px 16px; font-size: 14px; border-radius: 8px; border: 1px solid var(--border);">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label for="description" class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 8px; display: block;">
                    Description
                </label>
                <textarea class="form-control" id="description" name="description" rows="4"
                          placeholder="Description de la situation dans cette ville..."
                          style="padding: 12px 16px; font-size: 14px; border-radius: 8px; border: 1px solid var(--border); resize: vertical;"><?php echo htmlspecialchars($data['description'] ?? $ville['description'] ?? ''); ?></textarea>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; border-radius: 8px; font-weight: 600;">
                    <i class="bi bi-check-circle" style="margin-right: 6px;"></i>
                    Mettre à jour
                </button>
                <a href="<?php echo Flight::get('base_url'); ?>/villes" class="btn" style="padding: 12px 24px; border-radius: 8px; font-weight: 500; background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border);">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
