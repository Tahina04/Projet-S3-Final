<div class="achats-index page-enter">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="bi bi-cart"></i>
                    Liste des achats
                </h1>
                <p>Achats de besoins en nature et matériaux effectués avec les dons en argent</p>
            </div>
            <div>
                <a href="/achats/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvel achat
                </a>
                <a href="/achats/recap" class="btn btn-info">
                    <i class="bi bi-card-list"></i> Récapitulatif
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter by City -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/achats" class="row g-3">
                <div class="col-md-4">
                    <label for="ville_id" class="form-label">Filtrer par ville</label>
                    <select name="ville_id" id="ville_id" class="form-select">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?php echo $ville['id']; ?>" 
                                <?php echo ($villeId == $ville['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ville['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                    <?php if ($villeId): ?>
                        <a href="/achats" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="stat-card stat-card-gradient stat-card-dons">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-card-value"><?php echo number_format($totalAchats, 0, ',', ' '); ?> Ar</div>
                        <div class="stat-card-label">Total des achats</div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($achats)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-cart-x" style="font-size: 3rem; color: #6c757d;"></i>
                    <p class="mt-3 text-muted">Aucun achat enregistré</p>
                    <a href="/achats/create" class="btn btn-primary">Effectuer un achat</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Ville</th>
                                <th>Besoin</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Montant total</th>
                                <th>Don utilisé</th>
                                <th>Observations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($achats as $achat): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($achat['date_achat'])); ?></td>
                                    <td><?php echo htmlspecialchars($achat['ville_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($achat['besoin_nom']); ?></td>
                                    <td>
                                        <?php 
                                        $typeClass = [
                                            'nature' => 'bg-success',
                                            'materiaux' => 'bg-warning',
                                            'argent' => 'bg-info'
                                        ];
                                        $typeLabels = [
                                            'nature' => 'Nature',
                                            'materiaux' => 'Matériaux',
                                            'argent' => 'Argent'
                                        ];
                                        ?>
                                        <span class="badge <?php echo $typeClass[$achat['type_besoin']] ?? 'bg-secondary'; ?>">
                                            <?php echo $typeLabels[$achat['type_besoin']] ?? $achat['type_besoin']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($achat['quantite_achetee'], 0, ',', ' ') . ' ' . htmlspecialchars($achat['besoin_unite']); ?></td>
                                    <td><?php echo number_format($achat['prix_unitaire'], 0, ',', ' '); ?> Ar</td>
                                    <td><strong><?php echo number_format($achat['montant_total'], 0, ',', ' '); ?> Ar</strong></td>
                                    <td><?php echo htmlspecialchars($achat['don_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($achat['observations'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
