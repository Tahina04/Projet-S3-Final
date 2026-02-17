<div class="achats-recap page-enter">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1>
                    <i class="bi bi-card-list"></i>
                    Récapitulatif financier
                </h1>
                <p>Vue d'ensemble des besoins et des dons en montant</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="refresh-btn" onclick="loadRecapData()">
                    <i class="bi bi-arrow-clockwise"></i> Actualiser
                </button>
                <a href="/achats" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
        <span class="text-muted ms-2" id="last-update"></span>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid">
        <!-- Besoins Totaux -->
        <div class="stat-card stat-card-besoins">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-value" id="besoins-totaux">0</div>
                    <div class="stat-card-label">Besoins totaux (Ar)</div>
                </div>
                <div class="stat-card-icon">
                    <i class="bi bi-basket"></i>
                </div>
            </div>
        </div>
        
        <!-- Besoins Satisfaits -->
        <div class="stat-card stat-card-villes">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-value" id="besoins-satisfaits">0</div>
                    <div class="stat-card-label">Besoins satisfaits (Ar)</div>
                </div>
                <div class="stat-card-icon violet">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
        
        <!-- Dons Reçus -->
        <div class="stat-card stat-card-dons">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-value" id="dons-recus">0</div>
                    <div class="stat-card-label">Dons reçus (Ar)</div>
                </div>
                <div class="stat-card-icon green">
                    <i class="bi bi-gift"></i>
                </div>
            </div>
        </div>
        
        <!-- Dons Dispatchés -->
        <div class="stat-card stat-card-attributions">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-value" id="dons-dispatche">0</div>
                    <div class="stat-card-label">Dons dispatchés (Ar)</div>
                </div>
                <div class="stat-card-icon violet">
                    <i class="bi bi-share"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bars Section -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-pie-chart"></i>
                <span>Taux de satisfaction et d'utilisation</span>
            </div>
        </div>
        <div class="section-body">
            <?php 
            $taux = 0;
            if ($recap['besoins_totaux_montant'] > 0) {
                $taux = ($recap['besoins_satisfaits_montant'] / $recap['besoins_totaux_montant']) * 100;
            }
            
            $tauxUtilisation = 0;
            if ($recap['dons_recus_montant'] > 0) {
                $tauxUtilisation = ($recap['dons_dispatche_montant'] / $recap['dons_recus_montant']) * 100;
            }
            ?>
            
            <!-- Satisfaction Rate -->
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-medium"><i class="bi bi-check2-circle text-success me-2"></i>Taux de satisfaction des besoins</span>
                    <span class="badge bg-success fs-6"><?php echo number_format($taux, 1, ',', ' '); ?>%</span>
                </div>
                <div class="progress" style="height: 28px; border-radius: 14px;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: <?php echo $taux; ?>%" 
                         aria-valuenow="<?php echo $taux; ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <span class="px-2"><?php echo number_format($taux, 1, ',', ' '); ?>%</span>
                    </div>
                </div>
            </div>
            
            <!-- Utilization Rate -->
            <div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-medium"><i class="bi bi-recycle text-info me-2"></i>Utilisation des dons</span>
                    <span class="badge bg-info fs-6"><?php echo number_format($tauxUtilisation, 1, ',', ' '); ?>%</span>
                </div>
                <div class="progress" style="height: 28px; border-radius: 14px;">
                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: <?php echo $tauxUtilisation; ?>%" 
                         aria-valuenow="<?php echo $tauxUtilisation; ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <span class="px-2"><?php echo number_format($tauxUtilisation, 1, ',', ' '); ?>%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Table -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-table"></i>
                <span>Détails du récapitulatif</span>
            </div>
        </div>
        <div class="section-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4"><i class="bi bi-diagram-3 me-2"></i>Indicateur</th>
                            <th class="text-end pe-4"><i class="bi bi-currency-exchange me-2"></i>Montant (Ar)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-row-primary">
                            <td class="ps-4 fw-bold"><i class="bi bi-basket text-danger me-2"></i>Besoins totaux (quantité × prix unitaire)</td>
                            <td class="text-end pe-4 fw-bold text-danger"><?php echo number_format($recap['besoins_totaux_montant'], 0, ',', ' '); ?></td>
                        </tr>
                        <tr>
                            <td class="ps-4"><i class="bi bi-check-circle text-success me-2"></i>Besoins satisfaits (via attributions)</td>
                            <td class="text-end pe-4 text-success"><?php echo number_format($recap['besoins_satisfaits_montant'], 0, ',', ' '); ?></td>
                        </tr>
                        <tr class="table-warning">
                            <td class="ps-4"><i class="bi bi-exclamation-circle text-warning me-2"></i>Reste à satisfaire</td>
                            <td class="text-end pe-4 fw-bold text-warning"><?php echo number_format($recap['besoins_totaux_montant'] - $recap['besoins_satisfaits_montant'], 0, ',', ' '); ?></td>
                        </tr>
                        <tr class="table-divider">
                            <td colspan="2" class="p-0"></td>
                        </tr>
                        <tr class="table-row-primary">
                            <td class="ps-4 fw-bold"><i class="bi bi-cash-coin text-success me-2"></i>Dons en argent reçus</td>
                            <td class="text-end pe-4 fw-bold text-success"><?php echo number_format($recap['dons_recus_montant'], 0, ',', ' '); ?></td>
                        </tr>
                        <tr>
                            <td class="ps-4"><i class="bi bi-cart text-primary me-2"></i>Dons dispatchés (achats effectués)</td>
                            <td class="text-end pe-4 text-primary"><?php echo number_format($recap['dons_dispatche_montant'], 0, ',', ' '); ?></td>
                        </tr>
                        <tr class="table-success">
                            <td class="ps-4"><i class="bi bi-piggy-bank text-success me-2"></i>Dons restants</td>
                            <td class="text-end pe-4 fw-bold text-success"><?php echo number_format($recap['dons_recus_montant'] - $recap['dons_dispatche_montant'], 0, ',', ' '); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table-row-primary {
        background-color: var(--bngrc-primary-ultra-pale, rgba(99, 102, 241, 0.1));
    }
    
    .table-warning {
        background-color: rgba(245, 158, 11, 0.1);
    }
    
    .table-success {
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .table-divider {
        height: 8px;
    }
    
    .table-divider td {
        padding: 0 !important;
        background-color: var(--bngrc-surface, #f8fafc);
    }
    
    .table-hover tbody tr:hover {
        transform: scale(1.005);
        transition: transform 0.2s ease;
    }
    
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
    }
    
    .stat-card-icon i {
        font-size: 1.5rem;
    }
</style>

<script>
function loadRecapData() {
    const refreshBtn = document.getElementById('refresh-btn');
    refreshBtn.disabled = true;
    refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Chargement...';
    
    fetch('/achats/recap-data')
        .then(response => response.json())
        .then(data => {
            document.getElementById('besoins-totaux').textContent = data.besoins_totaux_montant.toLocaleString('fr-FR');
            document.getElementById('besoins-satisfaits').textContent = data.besoins_satisfaits_montant.toLocaleString('fr-FR');
            document.getElementById('dons-recus').textContent = data.dons_recus_montant.toLocaleString('fr-FR');
            document.getElementById('dons-dispatche').textContent = data.dons_dispatche_montant.toLocaleString('fr-FR');
            
            // Update progress bars
            let tauxSatisfaction = 0;
            if (data.besoins_totaux_montant > 0) {
                tauxSatisfaction = (data.besoins_satisfaits_montant / data.besoins_totaux_montant) * 100;
            }
            
            let tauxUtilisation = 0;
            if (data.dons_recus_montant > 0) {
                tauxUtilisation = (data.dons_dispatche_montant / data.dons_recus_montant) * 100;
            }
            
            document.querySelector('.progress-bar.bg-success').style.width = tauxSatisfaction + '%';
            document.querySelector('.progress-bar.bg-success').innerHTML = '<span class="px-2">' + tauxSatisfaction.toFixed(1) + '%</span>';
            document.querySelector('.progress-bar.bg-success').setAttribute('aria-valuenow', tauxSatisfaction);
            
            document.querySelector('.progress-bar.bg-info').style.width = tauxUtilisation + '%';
            document.querySelector('.progress-bar.bg-info').innerHTML = '<span class="px-2">' + tauxUtilisation.toFixed(1) + '%</span>';
            document.querySelector('.progress-bar.bg-info').setAttribute('aria-valuenow', tauxUtilisation);
            
            // Update timestamp
            const now = new Date();
            document.getElementById('last-update').textContent = 'Dernière actualisation: ' + now.toLocaleTimeString('fr-FR');
        })
        .catch(error => {
            console.error('Error loading recap data:', error);
            alert('Erreur lors du chargement des données');
        })
        .finally(() => {
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualiser';
        });
}

// Auto-refresh every 30 seconds
setInterval(loadRecapData, 30000);
</script>
