CREATE DATABASE IF NOT EXISTS `ETU4208_4256_4332`;

USE ETU4208_4256_4332;

CREATE TABLE villes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    region VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: besoins (Needs)
-- Besoins par ville (en nature, matériaux, argent)
CREATE TABLE besoins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    type_besoin ENUM('nature', 'materiaux', 'argent') NOT NULL,
    nom VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    quantite_requise DECIMAL(12,2) NOT NULL,
    unite VARCHAR(20) NOT NULL,
    prix_unitaire DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: dons (Donations)
-- Dons disponibles
CREATE TABLE dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_don ENUM('nature', 'materiaux', 'argent') NOT NULL,
    nom VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    quantite_disponible DECIMAL(12,2) NOT NULL,
    unite VARCHAR(20) NOT NULL,
    date_expiration DATE,
    donateur VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: attributions (Attributions)
-- Attribution d'un don à un besoin existant
CREATE TABLE attributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite_attribuee DECIMAL(12,2) NOT NULL,
    date_attribution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observations TEXT,
    FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE CASCADE,
    FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: achats (Purchases)
-- Achats de besoins avec les dons en argent
CREATE TABLE IF NOT EXISTS achats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id INT NOT NULL,
    don_argent_id INT NOT NULL,
    quantite_achetee DECIMAL(12,2) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    montant_total DECIMAL(12,2) NOT NULL,
    date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observations TEXT,
    FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE,
    FOREIGN KEY (don_argent_id) REFERENCES dons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Données des villes avec régions
INSERT INTO villes (nom, region, description) VALUES 
('Toamasina', 'Atsinanana', 'Port principal de Madagascar'),
('Mananjary', 'Fitovinany', 'Ville côtière du sud-est'),
('Farafangana', 'Fitovinany', 'Ville du sud-est'),
('Nosy Be', 'Diana', 'Île touristique du nord'),
('Morondava', 'Menabe', 'Ville côtière de l\'ouest');

-- Besoins pour Toamasina (ville_id = 1)
INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
(1, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 800, 'kg', 3000),
(1, 'nature', 'Eau (L)', 'Eau potable', 1500, 'L', 1000),
(1, 'materiaux', 'Tôle', 'Tôles pour reconstruction', 120, 'unités', 25000),
(1, 'materiaux', 'Bâche', 'Bâches de protection', 200, 'unités', 15000),
(1, 'argent', 'Argent', 'Aide financière', 12000000, 'Ar', 1),
(1, 'materiaux', 'Groupe', 'Groupe électrogène', 3, 'unités', 6750000);

-- Besoins pour Mananjary (ville_id = 2)
INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
(2, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 500, 'kg', 3000),
(2, 'nature', 'Huile (L)', 'Huile de cuisine', 120, 'L', 6000),
(2, 'materiaux', 'Tôle', 'Tôles pour reconstruction', 80, 'unités', 25000),
(2, 'materiaux', 'Clous (kg)', 'Clous de construction', 60, 'kg', 8000),
(2, 'argent', 'Argent', 'Aide financière', 6000000, 'Ar', 1);

-- Besoins pour Farafangana (ville_id = 3)
INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
(3, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 600, 'kg', 3000),
(3, 'nature', 'Eau (L)', 'Eau potable', 1000, 'L', 1000),
(3, 'materiaux', 'Bâche', 'Bâches de protection', 150, 'unités', 15000),
(3, 'materiaux', 'Bois', 'Bois de construction', 100, 'unités', 10000),
(3, 'argent', 'Argent', 'Aide financière', 8000000, 'Ar', 1);

-- Besoins pour Nosy Be (ville_id = 4)
INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
(4, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 300, 'kg', 3000),
(4, 'nature', 'Haricots', 'Haricots secs', 200, 'kg', 4000),
(4, 'materiaux', 'Tôle', 'Tôles pour reconstruction', 40, 'unités', 25000),
(4, 'materiaux', 'Clous (kg)', 'Clous de construction', 30, 'kg', 8000),
(4, 'argent', 'Argent', 'Aide financière', 4000000, 'Ar', 1);

-- Besoins pour Morondava (ville_id = 5)
INSERT INTO besoins (ville_id, type_besoin, nom, description, quantite_requise, unite, prix_unitaire) VALUES
(5, 'nature', 'Riz (kg)', 'Riz pour les familles sinistrées', 700, 'kg', 3000),
(5, 'nature', 'Eau (L)', 'Eau potable', 1200, 'L', 1000),
(5, 'materiaux', 'Bâche', 'Bâches de protection', 180, 'unités', 15000),
(5, 'materiaux', 'Bois', 'Bois de construction', 150, 'unités', 10000),
(5, 'argent', 'Argent', 'Aide financière', 10000000, 'Ar', 1);

-- Dons disponibles
INSERT INTO dons (type_don, nom, description, quantite_disponible, unite, donateur) VALUES
('nature', 'Riz', 'Riz blanc de qualité', 10000, 'kg', 'ONG Internationale'),
('nature', 'Eau', 'Eau minérale', 20000, 'L', 'Caritas'),
('nature', 'Huile', 'Huile végétale', 2000, 'L', 'Programme Alimentaire'),
('materiaux', 'Tôle', 'Tôles galvanisées', 1000, 'unités', 'ONG Reconstruction'),
('materiaux', 'Bâche', 'Bâches plastiques', 1500, 'unités', 'Protection Civile'),
('materiaux', 'Bois', 'Bois de construction', 800, 'unités', 'Entreprise BTP'),
('materiaux', 'Clous', 'Clous de construction', 500, 'kg', 'Magasin Bâtiment'),
('materiaux', 'Groupe', 'Groupe électrogène', 10, 'unités', 'ONG Energie'),
('argent', 'Don financier', 'Contribution financière', 50000000, 'Ar', 'Donateurs divers');
