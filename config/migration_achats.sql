-- Migration: Créer la table achats
-- Achats de besoins avec les dons en argent

USE ETU4208_4256_4332;

-- Créer la table achats
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

-- Mettre à jour les prix unitaires dans la table besoins
UPDATE besoins SET prix_unitaire = 500 WHERE nom = 'Riz' AND type_besoin = 'nature';
UPDATE besoins SET prix_unitaire = 1500 WHERE nom = 'Huile' AND type_besoin = 'nature';
UPDATE besoins SET prix_unitaire = 2500 WHERE nom = 'Eau' AND type_besoin = 'nature';
UPDATE besoins SET prix_unitaire = 8000 WHERE nom = 'Tôle' AND type_besoin = 'materiaux';
UPDATE besoins SET prix_unitaire = 2500 WHERE nom = 'Clous' AND type_besoin = 'materiaux';
UPDATE besoins SET prix_unitaire = 5000 WHERE nom = 'Bois' AND type_besoin = 'materiaux';
UPDATE besoins SET prix_unitaire = 1 WHERE type_besoin = 'argent';
