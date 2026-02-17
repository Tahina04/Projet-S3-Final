/**
 * Handles all interactive features
 */

(function() {
    'use strict';

    // ==================== CONFIGURATION ====================
    const CONFIG = {
        baseUrl: '',
        animationDuration: 400,
        nodeBaseSize: 110,
        nodeMaxSize: 160,
        nodeMinSize: 90,
        hubOffset: 0,
        levels: {
            1: 'network',
            2: 'city',
            3: 'needs',
            4: 'attribution'
        }
    };

    // ==================== STATE ====================
    let state = {
        currentLevel: 1,
        selectedCity: null,
        cities: [],
        besoins: [],
        dons: [],
        attributions: [],
        isLoading: false,
        nodePositions: []
    };

    // ==================== DOM ELEMENTS ====================
    const elements = {
        container: null,
        nodesContainer: null,
        hub: null,
        detailPanel: null,
        modalOverlay: null,
        toastContainer: null,
        nav: null,
        fab: null
    };

    // ==================== INITIALIZATION ====================
    function init() {
        // Wait for DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }

        // Cache DOM elements
        cacheElements();

        // Load initial data
        loadData();

        // Setup event listeners
        setupEventListeners();

        // Position elements
        positionNodes();

        // Start breathing animation
        startBreathingAnimation();
    }

    function cacheElements() {
        elements.container = document.querySelector('.eos-container');
        elements.nodesContainer = document.querySelector('.eos-nodes-container');
        elements.hub = document.querySelector('.eos-hub-circle');
        elements.detailPanel = document.querySelector('.eos-detail-panel');
        elements.modalOverlay = document.querySelector('.eos-modal-overlay');
        elements.toastContainer = document.querySelector('.eos-toast-container');
        elements.nav = document.querySelector('.eos-nav');
        elements.fab = document.querySelector('.eos-fab');
    }

    // ==================== DATA LOADING ====================
    async function loadData() {
        try {
            const response = await fetch(`${CONFIG.baseUrl}/ecosystem/data`);
            const data = await response.json();

            state.cities = data.villes || [];
            state.besoins = data.besoins || [];
            state.dons = data.dons || [];
            state.attributions = data.recentAttributions || [];

            // Calculate coverage for each city
            calculateCityCoverage();

            // Render nodes
            renderNodes();

            // Render hub stats
            renderHubStats();

        } catch (error) {
            console.error('Error loading data:', error);
            showToast('error', 'Erreur', 'Impossible de charger les données');
        }
    }

    function calculateCityCoverage() {
        state.cities.forEach(city => {
            // Get besoins for this city
            const cityBesoins = state.besoins.filter(b => b.ville_nom === city.nom);
            
            let totalRequired = 0;
            let totalAttributed = 0;

            cityBesoins.forEach(besoin => {
                totalRequired += parseFloat(besoin.quantite_requise || 0);
                totalAttributed += parseFloat(besoin.total_attribue || 0);
            });

            city.coverage = totalRequired > 0 ? (totalAttributed / totalRequired) * 100 : 0;
            city.totalRequired = totalRequired;
            city.totalAttributed = totalAttributed;
            city.besoinsCount = cityBesoins.length;
            city.urgentCount = cityBesoins.filter(b => {
                const reste = parseFloat(b.reste || 0);
                return reste > 0 && (reste / parseFloat(b.quantite_requise)) > 0.5;
            }).length;
        });
    }

    // ==================== NODE POSITIONING ====================
    function positionNodes() {
        if (!elements.nodesContainer || state.cities.length === 0) return;

        const containerWidth = window.innerWidth;
        const containerHeight = window.innerHeight;
        const centerX = containerWidth / 2;
        const centerY = containerHeight / 2;

        // Position nodes in organic circular layout
        const radius = Math.min(containerWidth, containerHeight) * 0.32;
        const totalCities = state.cities.length;

        state.cities.forEach((city, index) => {
            // Distribute nodes in a circle, slightly randomized for organic feel
            const angle = (index / totalCities) * (2 * Math.PI) - (Math.PI / 2);
            const randomOffset = (Math.random() - 0.5) * 0.3;
            const finalAngle = angle + randomOffset;

            const x = centerX + Math.cos(finalAngle) * radius;
            const y = centerY + Math.sin(finalAngle) * radius;

            city.x = x;
            city.y = y;
        });
    }

    // ==================== RENDERING ====================
    function renderNodes() {
        if (!elements.nodesContainer) return;

        elements.nodesContainer.innerHTML = '';

        state.cities.forEach((city, index) => {
            const node = createNodeElement(city, index);
            elements.nodesContainer.appendChild(node);

            // Position the node
            node.style.left = `${city.x - 55}px`; // Half of default node size
            node.style.top = `${city.y - 55}px`;
        });
    }

    function createNodeElement(city, index) {
        const node = document.createElement('div');
        node.className = 'eos-node';
        node.dataset.city = city.id;
        node.dataset.index = index;

        // Size based on coverage (smaller = more need)
        const coverage = city.coverage || 0;
        const size = CONFIG.nodeBaseSize + (coverage / 100) * (CONFIG.nodeMaxSize - CONFIG.nodeBaseSize);
        node.style.width = `${size}px`;
        node.style.height = `${size}px`;

        // Check if urgent
        if (city.urgentCount > 0) {
            node.classList.add('urgent');
        }

        // Progress ring
        const progressRing = document.createElement('div');
        progressRing.className = 'eos-node-progress';
        progressRing.innerHTML = `
            <svg viewBox="0 0 100 100">
                <circle class="bg" cx="50" cy="50" r="46"/>
                <circle class="progress" cx="50" cy="50" r="46" 
                    style="stroke-dashoffset: ${100 - coverage}"/>
            </svg>
        `;
        node.appendChild(progressRing);

        // Node content
        node.innerHTML += `
            <div class="eos-node-name">${city.nom}</div>
            <div class="eos-node-region">${city.region}</div>
            <div class="eos-node-stats">
                <div class="eos-node-stat">
                    <i class="bi bi-basket"></i>
                    <span>${city.besoinsCount || 0}</span>
                </div>
            </div>
            <div class="eos-node-urgency">
                <i class="bi bi-exclamation"></i>
            </div>
        `;

        // Click handler
        node.addEventListener('click', () => selectCity(city, node));

        return node;
    }

    function renderHubStats() {
        // Calculate global stats
        const totalBesoins = state.besoins.length;
        const coveredBesoins = state.besoins.filter(b => parseFloat(b.reste || 0) <= 0).length;
        const globalCoverage = totalBesoins > 0 ? (coveredBesoins / totalBesoins) * 100 : 0;

        // Update hub display if needed
        const hubLabel = document.querySelector('.eos-hub-label');
        if (hubLabel) {
            hubLabel.textContent = `${Math.round(globalCoverage)}% couvert`;
        }
    }

    // ==================== INTERACTIONS ====================
    function selectCity(city, nodeElement) {
        // Deselect previous
        document.querySelectorAll('.eos-node.selected').forEach(n => n.classList.remove('selected'));

        // Select new
        nodeElement.classList.add('selected');
        state.selectedCity = city;
        state.currentLevel = 2;

        // Update level indicator
        updateLevelIndicator(2);

        // Show detail panel
        showDetailPanel(city);

        // Animate other nodes away
        animateNodesAway(city);
    }

    function deselectCity() {
        document.querySelectorAll('.eos-node.selected').forEach(n => n.classList.remove('selected'));
        state.selectedCity = null;
        state.currentLevel = 1;

        updateLevelIndicator(1);
        hideDetailPanel();
        animateNodesBack();
    }

    function animateNodesAway(selectedCity) {
        const allNodes = document.querySelectorAll('.eos-node');
        const centerX = window.innerWidth / 2;
        const centerY = window.innerHeight / 2;

        allNodes.forEach(node => {
            if (node.dataset.city == selectedCity.id) return;

            const nodeCity = state.cities.find(c => c.id == node.dataset.city);
            if (!nodeCity) return;

            // Push away from selected city
            const dx = nodeCity.x - selectedCity.x;
            const dy = nodeCity.y - selectedCity.y;
            const distance = Math.sqrt(dx * dx + dy * dy);

            const pushFactor = 80;
            const newX = nodeCity.x + (dx / distance) * pushFactor;
            const newY = nodeCity.y + (dy / distance) * pushFactor;

            node.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
            node.style.left = `${newX - 55}px`;
            node.style.top = `${newY - 55}px`;
            node.style.opacity = '0.5';
            node.style.transform = 'scale(0.8)';
        });
    }

    function animateNodesBack() {
        const allNodes = document.querySelectorAll('.eos-node');

        allNodes.forEach(node => {
            const city = state.cities.find(c => c.id == node.dataset.city);
            if (!city) return;

            node.style.transition = 'all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)';
            node.style.left = `${city.x - 55}px`;
            node.style.top = `${city.y - 55}px`;
            node.style.opacity = '1';
            node.style.transform = 'scale(1)';
        });
    }

    // ==================== DETAIL PANEL ====================
    function showDetailPanel(city) {
        if (!elements.detailPanel) return;

        // Get city's besoins
        const cityBesoins = state.besoins.filter(b => b.ville_nom === city.nom);

        // Calculate stats
        const totalRequired = cityBesoins.reduce((sum, b) => sum + parseFloat(b.quantite_requise || 0), 0);
        const totalAttributed = cityBesoins.reduce((sum, b) => sum + parseFloat(b.total_attribue || 0), 0);
        const natureCount = cityBesoins.filter(b => b.type_besoin === 'nature').length;
        const materiauxCount = cityBesoins.filter(b => b.type_besoin === 'materiaux').length;
        const argentCount = cityBesoins.filter(b => b.type_besoin === 'argent').length;

        // Build content
        const content = `
            <div class="eos-detail-header">
                <div class="eos-detail-back" onclick="Ecosystem.closeDetail()">
                    <i class="bi bi-arrow-left"></i>
                    <span>Retour au réseau</span>
                </div>
                <div class="eos-detail-title">${city.nom}</div>
                <div class="eos-detail-subtitle">${city.region}</div>
            </div>
            <div class="eos-detail-content">
                <div class="eos-detail-stats">
                    <div class="eos-stat-card">
                        <div class="eos-stat-card-icon nature">
                            <i class="bi bi-basket"></i>
                        </div>
                        <div class="eos-stat-card-value">${natureCount}</div>
                        <div class="eos-stat-card-label">Besoins nature</div>
                    </div>
                    <div class="eos-stat-card">
                        <div class="eos-stat-card-icon materiaux">
                            <i class="bi bi-hammer"></i>
                        </div>
                        <div class="eos-stat-card-value">${materiauxCount}</div>
                        <div class="eos-stat-card-label">Besoins matériaux</div>
                    </div>
                    <div class="eos-stat-card">
                        <div class="eos-stat-card-icon argent">
                            <i class="bi bi-cash"></i>
                        </div>
                        <div class="eos-stat-card-value">${argentCount}</div>
                        <div class="eos-stat-card-label">Besoins financiers</div>
                    </div>
                    <div class="eos-stat-card">
                        <div class="eos-stat-card-icon" style="background: var(--eos-violet-light); color: var(--eos-violet-dark);">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <div class="eos-stat-card-value">${Math.round(city.coverage || 0)}%</div>
                        <div class="eos-stat-card-label">Couverture</div>
                    </div>
                </div>

                <div class="eos-needs-section">
                    <div class="eos-section-title">
                        <i class="bi bi-list-ul"></i>
                        Besoins identifiés
                    </div>
                    ${cityBesoins.map(besoin => {
                        const progress = parseFloat(besoin.quantite_requise) > 0 
                            ? (parseFloat(besoin.total_attribue) / parseFloat(besoin.quantite_requise)) * 100 
                            : 0;
                        const reste = parseFloat(besoin.reste || 0);
                        const isCritical = progress < 50;

                        return `
                            <div class="eos-need-item" onclick="Ecosystem.selectNeed(${besoin.id})">
                                <div class="eos-need-header">
                                    <div class="eos-need-name">${besoin.nom}</div>
                                    <div class="eos-need-type ${besoin.type_besoin}">${besoin.type_besoin}</div>
                                </div>
                                <div class="eos-need-progress">
                                    <div class="eos-need-progress-bar ${isCritical ? 'critical' : ''}" 
                                        style="width: ${Math.min(progress, 100)}%"></div>
                                </div>
                                <div class="eos-need-stats">
                                    <span>Attribué: <strong>${formatNumber(besoin.total_attribue)} ${besoin.unite}</strong></span>
                                    <span>Reste: <strong>${formatNumber(reste)} ${besoin.unite}</strong></span>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;

        elements.detailPanel.innerHTML = content;
        elements.detailPanel.classList.add('show');
    }

    function hideDetailPanel() {
        if (elements.detailPanel) {
            elements.detailPanel.classList.remove('show');
        }
    }

    // ==================== MODAL ====================
    function showModal(type = 'don') {
        if (!elements.modalOverlay) return;

        let title = '';
        let content = '';

        if (type === 'don') {
            title = 'Ajouter un don';
            content = getDonFormContent();
        }

        const modal = elements.modalOverlay.querySelector('.eos-modal');
        if (modal) {
            modal.innerHTML = `
                <div class="eos-modal-header">
                    <div class="eos-modal-title">
                        <i class="bi bi-gift"></i>
                        ${title}
                    </div>
                    <button class="eos-modal-close" onclick="Ecosystem.closeModal()">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="eos-modal-body">
                    ${content}
                </div>
            `;
        }

        elements.modalOverlay.classList.add('show');
    }

    function closeModal() {
        if (elements.modalOverlay) {
            elements.modalOverlay.classList.remove('show');
        }
    }

    function getDonFormContent() {
        return `
            <form id="eos-don-form" onsubmit="Ecosystem.submitDon(event)">
                <div class="eos-form-group">
                    <label class="eos-form-label">Type de don</label>
                    <select class="eos-form-select" name="type_don" required>
                        <option value="">Sélectionner...</option>
                        <option value="nature">Nature (nourriture)</option>
                        <option value="materiaux">Matériaux</option>
                        <option value="argent">Argent</option>
                    </select>
                </div>
                <div class="eos-form-group">
                    <label class="eos-form-label">Nom du don</label>
                    <input class="eos-form-input" type="text" name="nom" placeholder="Ex: Riz blanc, Tôles..." required>
                </div>
                <div class="eos-form-row">
                    <div class="eos-form-group">
                        <label class="eos-form-label">Quantité</label>
                        <input class="eos-form-input" type="number" name="quantite_disponible" min="1" required>
                    </div>
                    <div class="eos-form-group">
                        <label class="eos-form-label">Unité</label>
                        <select class="eos-form-select" name="unite" required>
                            <option value="kg">kg</option>
                            <option value="litres">litres</option>
                            <option value="unités">unités</option>
                            <option value="Ar">Ariary</option>
                        </select>
                    </div>
                </div>
                <div class="eos-form-group">
                    <label class="eos-form-label">Donateur</label>
                    <input class="eos-form-input" type="text" name="donateur" placeholder="Nom du donateur">
                </div>
                <div class="eos-form-group">
                    <label class="eos-form-label">Description</label>
                    <input class="eos-form-input" type="text" name="description" placeholder="Description optionnelle">
                </div>
                <div class="eos-modal-footer">
                    <button type="button" class="eos-btn eos-btn-secondary" onclick="Ecosystem.closeModal()">Annuler</button>
                    <button type="submit" class="eos-btn eos-btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ajouter le don
                    </button>
                </div>
            </form>
        `;
    }

    async function submitDon(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        const data = {
            type_don: formData.get('type_don'),
            nom: formData.get('nom'),
            quantite_disponible: parseFloat(formData.get('quantite_disponible')),
            unite: formData.get('unite'),
            donateur: formData.get('donateur'),
            description: formData.get('description')
        };

        try {
            const response = await fetch(`${CONFIG.baseUrl}/dons/store`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(data)
            });

            const result = await response.json();

            if (result.success || response.ok) {
                showToast('success', 'Succès', 'Don ajouté avec succès');

                // Show impact visualization
                if (state.selectedCity) {
                    showImpactVisualization(state.selectedCity);
                }

                closeModal();
                loadData(); // Reload data
            } else {
                showToast('error', 'Erreur', result.message || 'Impossible d\'ajouter le don');
            }
        } catch (error) {
            console.error('Error submitting don:', error);
            showToast('error', 'Erreur', 'Une erreur est survenue');
        }
    }

    // ==================== IMPACT VISUALIZATION ====================
    function showImpactVisualization(city) {
        // Find the node element
        const node = document.querySelector(`.eos-node[data-city="${city.id}"]`);
        if (!node) return;

        const rect = node.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        // Create impact waves
        for (let i = 0; i < 3; i++) {
            setTimeout(() => {
                const wave = document.createElement('div');
                wave.className = 'eos-impact-wave';
                wave.style.left = `${centerX}px`;
                wave.style.top = `${centerY}px`;
                wave.style.width = '20px';
                wave.style.height = '20px';
                document.body.appendChild(wave);

                setTimeout(() => wave.remove(), 1500);
            }, i * 300);
        }

        // Animate the progress ring
        const progressCircle = node.querySelector('.eos-node-progress .progress');
        if (progressCircle) {
            const currentOffset = parseFloat(progressCircle.style.strokeDashoffset) || 100;
            progressCircle.style.transition = 'stroke-dashoffset 1s ease-out';
            progressCircle.style.strokeDashoffset = Math.max(0, currentOffset - 20);
        }
    }

    // ==================== TOAST NOTIFICATIONS ====================
    function showToast(type, title, message) {
        if (!elements.toastContainer) {
            elements.toastContainer = document.createElement('div');
            elements.toastContainer.className = 'eos-toast-container';
            document.body.appendChild(elements.toastContainer);
        }

        const icons = {
            success: 'bi-check-lg',
            error: 'bi-x-lg',
            warning: 'bi-exclamation-lg'
        };

        const toast = document.createElement('div');
        toast.className = `eos-toast ${type}`;
        toast.innerHTML = `
            <div class="eos-toast-icon">
                <i class="bi ${icons[type]}"></i>
            </div>
            <div class="eos-toast-content">
                <div class="eos-toast-title">${title}</div>
                <div class="eos-toast-message">${message}</div>
            </div>
        `;

        elements.toastContainer.appendChild(toast);

        // Auto remove
        setTimeout(() => {
            toast.style.animation = 'eos-toast-appear 0.4s ease-out reverse';
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    // ==================== LEVEL INDICATOR ====================
    function updateLevelIndicator(level) {
        const dots = document.querySelectorAll('.eos-level-dot');
        dots.forEach((dot, index) => {
            if (index + 1 <= level) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    // ==================== BREATHING ANIMATION ====================
    function startBreathingAnimation() {
        // Add subtle movement to nodes
        let time = 0;

        setInterval(() => {
            time += 0.02;

            document.querySelectorAll('.eos-node').forEach((node, index) => {
                const city = state.cities[index];
                if (!city) return;

                const offsetX = Math.sin(time + index) * 3;
                const offsetY = Math.cos(time + index * 0.7) * 3;

                node.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
            });
        }, 50);
    }

    // ==================== EVENT LISTENERS ====================
    function setupEventListeners() {
        // Hub click
        if (elements.hub) {
            elements.hub.addEventListener('click', () => {
                showToast('info', 'Vue réseau', 'Vue globale du réseau humanitarian');
            });
        }

        // FAB click
        if (elements.fab) {
            elements.fab.addEventListener('click', () => showModal('don'));
        }

        // Modal overlay click to close
        if (elements.modalOverlay) {
            elements.modalOverlay.addEventListener('click', (e) => {
                if (e.target === elements.modalOverlay) {
                    closeModal();
                }
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
                if (state.currentLevel > 1) {
                    deselectCity();
                }
            }
        });

        // Window resize
        window.addEventListener('resize', () => {
            positionNodes();
            renderNodes();
        });

        // Context menu (right click)
        document.addEventListener('contextmenu', (e) => {
            e.preventDefault();
        });
    }

    // ==================== UTILITY FUNCTIONS ====================
    function formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toLocaleString('fr-FR');
    }

    // ==================== EXPOSE PUBLIC API ====================
    window.Ecosystem = {
        selectCity: selectCity,
        closeDetail: deselectCity,
        selectNeed: function(besoinId) {
            showToast('info', 'Attribution', 'Interface d\'attribution à venir');
            state.currentLevel = 4;
            updateLevelIndicator(4);
        },
        showModal: showModal,
        closeModal: closeModal,
        submitDon: submitDon,
        showToast: showToast
    };

    // Start
    init();

})();
