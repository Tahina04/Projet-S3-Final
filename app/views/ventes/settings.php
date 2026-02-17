<?php 
// Page header is handled by layout
$page_title = "Paramètres";
$active_page = 'settings';
?>

<div style="max-width: 650px;">
    <!-- Settings Card -->
    <div class="card" style="border-radius: 12px; border: 1px solid var(--border); margin-bottom: 24px;">
        <div class="card-header" style="background: var(--primary-ultra-pale); padding: 16px 20px; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0;">
            <h5 style="margin: 0; font-weight: 600; color: var(--text-primary);">
                <i class="bi bi-percent" style="color: var(--primary); margin-right: 8px;"></i>
                Pourcentage de Réduction
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form method="POST" action="<?php echo Flight::get('base_url'); ?>/ventes/settings">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="reduction_pourcentage" class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 10px; display: block;">
                        Pourcentage de réduction (%)
                        <span style="color: var(--accent-red);">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="number" class="form-control" id="reduction_pourcentage" name="reduction_pourcentage" 
                               value="<?php echo htmlspecialchars($reduction_pourcentage); ?>" 
                               min="0" max="100" step="1" required
                               style="padding: 12px 16px; font-size: 16px; border-radius: 8px; border: 1px solid var(--border);">
                    </div>
                    <small style="color: var(--text-muted); font-size: 13px; margin-top: 6px; display: block;">
                        Ce pourcentage sera appliqué au prix de vente pour calculer le montant réellement perçu
                    </small>
                </div>
                
                <!-- Progress Bar -->
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 13px; color: var(--text-muted);">Réduction appliquée</span>
                        <span style="font-size: 14px; font-weight: 700; color: var(--primary);"><?php echo htmlspecialchars($reduction_pourcentage); ?> %</span>
                    </div>
                    <div style="height: 8px; background: var(--border); border-radius: 4px; overflow: hidden;">
                        <div style="width: <?php echo htmlspecialchars($reduction_pourcentage); ?>%; height: 100%; background: var(--primary); border-radius: 4px; transition: width 0.3s ease;"></div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px 20px; border-radius: 8px; font-weight: 600;">
                        <i class="bi bi-check-circle"></i>
                        Enregistrer
                    </button>
                    <a href="<?php echo Flight::get('base_url'); ?>/ventes" class="btn" style="padding: 12px 20px; border-radius: 8px; font-weight: 500; background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border);">
                        <i class="bi bi-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Info Card -->
    <div class="card" style="border-radius: 12px; border: 1px solid var(--border); margin-bottom: 24px;">
        <div class="card-header" style="background: var(--primary-ultra-pale); padding: 16px 20px; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0;">
            <h5 style="margin: 0; font-weight: 600; color: var(--text-primary);">
                <i class="bi bi-lightbulb" style="color: var(--primary); margin-right: 8px;"></i>
                Guide des Réductions
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <p style="color: var(--text-secondary); font-size: 14px; line-height: 1.6; margin-bottom: 16px;">
                Le pourcentage de réduction permet de définir une décote sur le prix de vente des dons. 
                Cette réduction reflète généralement l'état des produits ou leur âge.
            </p>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div style="padding: 12px; background: var(--primary-ultra-pale); border-radius: 8px; border-left: 3px solid var(--primary);">
                    <strong style="color: var(--primary); font-size: 16px;">0%</strong>
                    <p style="margin: 4px 0 0; font-size: 12px; color: var(--text-muted);">Prix de vente intégral</p>
                </div>
                <div style="padding: 12px; background: var(--primary-ultra-pale); border-radius: 8px; border-left: 3px solid var(--primary);">
                    <strong style="color: var(--primary); font-size: 16px;">10%</strong>
                    <p style="margin: 4px 0 0; font-size: 12px; color: var(--text-muted);">Réduction modérée (défaut)</p>
                </div>
                <div style="padding: 12px; background: var(--primary-ultra-pale); border-radius: 8px; border-left: 3px solid var(--primary);">
                    <strong style="color: var(--primary); font-size: 16px;">50%</strong>
                    <p style="margin: 4px 0 0; font-size: 12px; color: var(--text-muted);">Réduction importante</p>
                </div>
                <div style="padding: 12px; background: var(--primary-ultra-pale); border-radius: 8px; border-left: 3px solid var(--primary);">
                    <strong style="color: var(--primary); font-size: 16px;">100%</strong>
                    <p style="margin: 4px 0 0; font-size: 12px; color: var(--text-muted);">Donation gratuite</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Danger Zone -->
    <div class="card" style="border-radius: 12px; border: 2px solid var(--accent-red-light); margin-bottom: 24px;">
        <div class="card-header" style="background: var(--accent-red-light); padding: 16px 20px; border-bottom: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px 12px 0 0;">
            <h5 style="margin: 0; font-weight: 600; color: var(--accent-red);">
                <i class="bi bi-exclamation-triangle" style="margin-right: 8px;"></i>
                Zone Dangereuse
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
                Cette action va réinitialiser la base de données complète. Toutes les données seront perdues.
            </p>
            <ul style="margin-bottom: 20px; padding-left: 20px; color: var(--text-muted); font-size: 14px;">
                <li style="margin-bottom: 6px;">Tous les dons initiaux</li>
                <li style="margin-bottom: 6px;">Tous les besoins</li>
                <li style="margin-bottom: 6px;">Toutes les attributions</li>
                <li style="margin-bottom: 6px;">Tous les achats</li>
                <li style="margin-bottom: 6px;">Toutes les ventes</li>
                <li>Le pourcentage de réduction (10%)</li>
            </ul>
            <a href="<?php echo Flight::get('base_url'); ?>/reset" 
               class="btn btn-danger"
               onclick="return confirm('Êtes-vous sûr de vouloir réinitialiser toutes les données? Cette action est irréversible.');"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 8px; font-weight: 600;">
                <i class="bi bi-arrow-counterclockwise"></i>
                Réinitialiser la base de données
            </a>
        </div>
    </div>
    

</div>
