-- Base de données NetKeep
CREATE DATABASE IF NOT EXISTS bdd_netkeep CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bdd_netkeep;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('employe', 'technicien') NOT NULL DEFAULT 'employe',
    niveau_support TINYINT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE materiels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type ENUM('PC', 'Ecran', 'Serveur', 'Clavier', 'Souris', 'Imprimante', 'Telephone', 'Tablette', 'Autre') NOT NULL,
    num_serie VARCHAR(50) NOT NULL UNIQUE,
    date_achat DATE NOT NULL,
    statut ENUM('actif', 'en_reparation', 'archive') DEFAULT 'actif',
    est_archive TINYINT DEFAULT 0,
    id_utilisateur INT DEFAULT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    urgence ENUM('faible', 'moyen', 'critique') NOT NULL,
    type_probleme ENUM('materiel', 'logiciel', 'reseau', 'autre') NOT NULL DEFAULT 'materiel',
    statut ENUM('ouvert', 'en_cours', 'resolu') DEFAULT 'ouvert',
    niveau_escalade TINYINT DEFAULT 1,
    message_resolution TEXT DEFAULT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_materiel INT DEFAULT NULL,
    id_auteur INT NOT NULL,
    id_technicien INT DEFAULT NULL,
    FOREIGN KEY (id_materiel) REFERENCES materiels(id) ON DELETE SET NULL,
    FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_technicien) REFERENCES utilisateurs(id)
) ENGINE=InnoDB;

-- Mot de passe : "password" (hash PHP natif)
INSERT INTO utilisateurs (nom, prenom, email, password, role, niveau_support) VALUES
('Moreau', 'Karim', 'karim.moreau@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'technicien', 1),
('Fontaine', 'Lucie', 'lucie.fontaine@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'technicien', 2),
('Nguyen', 'Antoine', 'antoine.nguyen@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'technicien', 1),
('Petit', 'Camille', 'camille.petit@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'employe', NULL),
('Girard', 'Romain', 'romain.girard@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'employe', NULL),
('Leclerc', 'Inès', 'ines.leclerc@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'employe', NULL),
('Bouchard', 'Maxime', 'maxime.bouchard@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'employe', NULL),
('Vasseur', 'Amina', 'amina.vasseur@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'employe', NULL),
('Chevalier', 'Théo', 'theo.chevalier@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'employe', NULL),
('Blanchard', 'Nadia', 'nadia.blanchard@netkeep.fr', '$2y$10$87iJpsMtkv5.HSyHf9sKj.TqAL1iMVuAeD5HEluylR0HdEQx9zvIu', 'employe', NULL);

INSERT INTO materiels (nom, type, num_serie, date_achat, statut, est_archive, id_utilisateur) VALUES
('Lenovo ThinkPad X1 Carbon', 'PC', 'LNV-X1C-001', '2025-03-12', 'actif', 0, 4),
('Apple MacBook Pro 14"', 'PC', 'APL-MBP-001', '2026-01-20', 'actif', 0, 5),
('HP EliteBook 840 G10', 'PC', 'HP-ELT-001', '2024-11-05', 'actif', 0, 6),
('Dell Latitude 5540', 'PC', 'DLL-LAT-001', '2025-09-18', 'actif', 0, 7),
('Asus ProArt Studiobook 16', 'PC', 'ASU-PRO-001', '2026-02-14', 'actif', 0, 8),
('Microsoft Surface Pro 9', 'PC', 'MSF-SFP-001', '2025-06-30', 'en_reparation', 0, 9),
('Lenovo IdeaPad Slim 5', 'PC', 'LNV-IDP-001', '2024-08-22', 'actif', 0, 10),
('Dell Precision 5680', 'PC', 'DLL-PRE-001', '2025-12-01', 'actif', 0, 1),
('HP ZBook Fury 16 G10', 'PC', 'HP-ZBK-001', '2026-03-10', 'actif', 0, 2),
('LG UltraWide 34WP85C', 'Ecran', 'LG-ULW-001', '2025-01-15', 'actif', 0, 4),
('Dell UltraSharp U2723DE', 'Ecran', 'DLL-ULS-001', '2024-07-08', 'actif', 0, 5),
('Samsung ViewFinity S8', 'Ecran', 'SAM-VFS-001', '2025-10-20', 'actif', 0, 6),
('BenQ PD2725U', 'Ecran', 'BNQ-PD2-001', '2026-01-05', 'actif', 0, 8),
('AOC Q27G3XMN', 'Ecran', 'AOC-Q27-001', '2024-05-30', 'archive', 1, NULL),
('Dell PowerEdge R750', 'Serveur', 'DLL-PER-001', '2023-06-10', 'actif', 0, NULL),
('HPE ProLiant DL360 Gen11', 'Serveur', 'HPE-DL3-001', '2024-03-25', 'actif', 0, NULL),
('Lenovo ThinkSystem SR650', 'Serveur', 'LNV-TSR-001', '2022-11-14', 'en_reparation', 0, NULL),
('Logitech MX Keys S', 'Clavier', 'LOG-MXK-001', '2025-04-18', 'actif', 0, 4),
('Keychron K8 Pro', 'Clavier', 'KCH-K8P-001', '2025-08-12', 'actif', 0, 7),
('Apple Magic Keyboard', 'Clavier', 'APL-MGK-001', '2026-01-20', 'actif', 0, 5),
('Logitech MX Master 3S', 'Souris', 'LOG-MXM-001', '2025-04-18', 'actif', 0, 4),
('Razer DeathAdder V3', 'Souris', 'RZR-DAD-001', '2025-11-02', 'actif', 0, 7),
('Apple Magic Mouse', 'Souris', 'APL-MGM-001', '2026-01-20', 'actif', 0, 5),
('HP LaserJet Pro 4002dw', 'Imprimante', 'HP-LJP-001', '2024-09-01', 'actif', 0, NULL),
('Brother MFC-L8900CDW', 'Imprimante', 'BTH-MFC-001', '2023-12-15', 'en_reparation', 0, NULL),
('iPhone 15 Pro', 'Telephone', 'APL-I15-001', '2026-02-01', 'actif', 0, 6),
('Samsung Galaxy S24', 'Telephone', 'SAM-GS2-001', '2026-01-10', 'actif', 0, 9),
('iPhone 14', 'Telephone', 'APL-I14-001', '2025-03-05', 'actif', 0, 10),
('iPad Pro 12.9" M2', 'Tablette', 'APL-IPD-001', '2025-05-22', 'actif', 0, 8),
('Samsung Galaxy Tab S9', 'Tablette', 'SAM-GTS-001', '2025-09-14', 'actif', 0, NULL),
('Cisco IP Phone 8861', 'Autre', 'CSC-IPP-001', '2023-04-20', 'actif', 0, NULL),
('APC Smart-UPS 1500', 'Autre', 'APC-SMU-001', '2022-08-30', 'actif', 0, NULL);

INSERT INTO tickets (titre, description, urgence, type_probleme, statut, niveau_escalade, message_resolution, date_creation, id_materiel, id_auteur, id_technicien) VALUES
('Écran qui scintille au démarrage', 'Mon écran LG clignote pendant 30 secondes à chaque démarrage.', 'moyen', 'materiel', 'resolu', 1, 'Câble DisplayPort défectueux remplacé.', '2026-02-10 09:15:00', 10, 4, 1),
('Impossible de se connecter au VPN', 'Le client VPN Cisco AnyConnect plante au lancement depuis la mise à jour Windows.', 'critique', 'logiciel', 'resolu', 2, 'Réinstallation du client VPN en version 4.10.08029.', '2026-02-12 14:30:00', NULL, 5, 2),
('Imprimante hors ligne bureau 214', 'Imprimante HP indiquée hors ligne sur tous les postes du bureau 214.', 'moyen', 'reseau', 'resolu', 1, 'IP fixée en statique après renouvellement DHCP.', '2026-02-17 11:00:00', 24, 6, 1),
('MacBook très lent depuis mise à jour macOS', 'Depuis macOS Sequoia, le MacBook rame énormément.', 'moyen', 'logiciel', 'resolu', 1, 'SSD à 95% nettoyé, 40 Go libérés.', '2026-02-21 16:45:00', 2, 5, 3),
('Clavier qui tape des caractères en double', 'Le clavier Logitech tape parfois deux lettres au lieu d''une.', 'faible', 'materiel', 'resolu', 1, 'Nettoyage + mise à jour firmware Logitech.', '2026-02-24 10:20:00', 18, 4, 1),
('Accès refusé au dossier partagé Compta', 'Plus d''accès au dossier serveur compta depuis lundi.', 'critique', 'logiciel', 'resolu', 2, 'Droits ACL reconfigurés après migration Active Directory.', '2026-02-27 08:55:00', NULL, 7, 2),
('PC qui redémarre aléatoirement', 'Le Dell Latitude s''éteint et redémarre sans prévenir 2 à 3 fois par jour.', 'critique', 'materiel', 'en_cours', 2, NULL, '2026-03-14 09:30:00', 4, 7, 1),
('Teams ne charge plus les vidéos en réunion', 'Les caméras des participants restent noires en réunion Teams.', 'moyen', 'logiciel', 'en_cours', 1, NULL, '2026-03-18 13:15:00', NULL, 6, 3),
('Surface Pro ne charge plus', 'Le Surface Pro 9 ne se charge plus avec le chargeur d''origine.', 'critique', 'materiel', 'en_cours', 1, NULL, '2026-03-20 08:00:00', 6, 9, 2),
('Wifi instable open-space 3ème étage', 'Le wifi coupe toutes les 20-30 minutes au 3ème étage.', 'moyen', 'reseau', 'en_cours', 2, NULL, '2026-04-07 10:45:00', NULL, 8, 1),
('Outlook plante à l''ouverture des PDF', 'Outlook se ferme brutalement quand on clique sur un PDF en pièce jointe.', 'moyen', 'logiciel', 'en_cours', 1, NULL, '2026-04-14 14:00:00', NULL, 10, 3),
('Souris qui lag en Bluetooth', 'La souris Razer a un lag important en Bluetooth.', 'faible', 'materiel', 'ouvert', 1, NULL, '2026-05-05 09:10:00', 22, 7, NULL),
('Impossible d''installer Adobe Acrobat', 'Installation bloque à 67% avec erreur 1603.', 'moyen', 'logiciel', 'ouvert', 1, NULL, '2026-05-05 11:30:00', NULL, 4, NULL),
('Téléphone sans réseau 4G', 'iPhone 15 Pro sans données mobiles depuis ce matin.', 'moyen', 'reseau', 'ouvert', 1, NULL, '2026-05-19 07:50:00', 26, 6, NULL),
('Moniteur Dell qui reste noir au réveil', 'L''écran Dell reste noir après une pause.', 'faible', 'materiel', 'ouvert', 1, NULL, '2026-05-19 15:20:00', 11, 5, NULL),
('Serveur de fichiers très lent', 'Accès aux fichiers serveur extrêmement lent depuis vendredi.', 'critique', 'reseau', 'ouvert', 2, NULL, '2026-06-02 08:05:00', 15, 8, NULL),
('Demande licence Figma pour équipe design', 'L''équipe design a besoin de 4 licences Figma Pro.', 'faible', 'autre', 'ouvert', 1, NULL, '2026-06-02 10:00:00', NULL, 10, NULL),
('Casque Bluetooth non reconnu en visio', 'Le casque Sony WH-1000XM5 non détecté par Zoom et Teams.', 'moyen', 'logiciel', 'ouvert', 1, NULL, '2026-06-04 09:45:00', NULL, 9, NULL);