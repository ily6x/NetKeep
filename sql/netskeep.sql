-- Base de données NetKeep
CREATE DATABASE IF NOT EXISTS bdd_netkeep CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bdd_netkeep;

-- Table utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('employe', 'technicien') NOT NULL DEFAULT 'employe',
    niveau_support TINYINT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table materiels
CREATE TABLE materiels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type ENUM('PC', 'Ecran', 'Serveur', 'Clavier', 'Imprimante') NOT NULL,
    num_serie VARCHAR(50) NOT NULL UNIQUE,
    date_achat DATE NOT NULL,
    statut ENUM('actif', 'en_reparation', 'archive') DEFAULT 'actif',
    id_utilisateur INT DEFAULT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

-- Table tickets
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    urgence ENUM('faible', 'moyen', 'critique') NOT NULL,
    statut ENUM('ouvert', 'en_cours', 'resolu') DEFAULT 'ouvert',
    niveau_escalade TINYINT DEFAULT 1,
    message_resolution TEXT DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_materiel INT NOT NULL,
    id_auteur INT NOT NULL,
    id_technicien INT DEFAULT NULL,
    FOREIGN KEY (id_materiel) REFERENCES materiels(id),
    FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_technicien) REFERENCES utilisateurs(id)
);

-- Données de test
INSERT INTO utilisateurs (nom, email, password, role, niveau_support) VALUES
('Martin', 'sophie.martin@biotech.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uAmub8W', 'employe', NULL),
('Dubois', 'marc.dubois@biotech.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uAmub8W', 'technicien', 1),
('Bernard', 'julie.bernard@biotech.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uAmub8W', 'technicien', 2),
('Leroy', 'thomas.leroy@biotech.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uAmub8W', 'employe', NULL);

INSERT INTO materiels (nom, type, num_serie, date_achat, statut, id_utilisateur) VALUES
('Dell XPS 15 9530', 'PC', 'DLL-XPS-001', '2023-06-15', 'actif', 1),
('Samsung Odyssey G5', 'Ecran', 'SAM-SCR-001', '2022-03-10', 'actif', 1),
('HP ProLiant DL380 Gen10', 'Serveur', 'HP-SRV-001', '2021-01-20', 'actif', NULL),
('Lenovo ThinkPad T14', 'PC', 'LNV-TPD-001', '2024-01-08', 'actif', 4),
('Dell UltraSharp U2722D', 'Ecran', 'DLL-SCR-002', '2023-06-15', 'actif', 4);