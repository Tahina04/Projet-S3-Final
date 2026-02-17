-- Table: Paramètres du système
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insérer le pourcentage de réduction par défaut
INSERT INTO settings (setting_key, setting_value) VALUES 
('reduction_pourcentage', '20')
ON DUPLICATE KEY UPDATE setting_value = '20';

-- Table: Ventes (Sales of donations)
-- Vente des dons qui ne sont plus nécessaires par aucune ville
CREATE TABLE IF NOT EXISTS ventes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    quantite_vendue DECIMAL(12,2) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    prix_total DECIMAL(12,2) NOT NULL,
    reduction_pourcentage DECIMAL(5,2) DEFAULT 0,
    prix_apres_reduction DECIMAL(12,2) NOT NULL,
    date_vente TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observations TEXT,
    FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
