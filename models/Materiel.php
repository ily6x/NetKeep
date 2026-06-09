<?php
require_once __DIR__ . '/../config/database.php';

class Materiel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    // Tout le matériel non archivé
    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM materiels WHERE est_archive = 0');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Matériel d'un utilisateur spécifique
    public function findByUser(int $id_utilisateur): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM materiels WHERE id_utilisateur = :id AND est_archive = 0'
        );
        $stmt->execute([':id' => $id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un matériel
    public function create(string $nom, string $type, string $num_serie, string $date_achat): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO materiels (nom, type, num_serie, date_achat, statut, est_archive, id_utilisateur) 
             VALUES (:nom, :type, :num_serie, :date_achat, :statut, :est_archive, :id_utilisateur)'
        );
        return $stmt->execute([
            ':nom' => $nom,
            ':type' => $type,
            ':num_serie' => $num_serie,
            ':date_achat' => $date_achat,
            ':statut' => 'actif',
            ':est_archive' => 0,
            ':id_utilisateur' => null
        ]);
    }

    // Modifier un matériel
    public function update(int $id, string $nom, string $type, string $num_serie, string $date_achat): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE materiels SET nom = :nom, type = :type, num_serie = :num_serie, date_achat = :date_achat WHERE id = :id'
        );
        return $stmt->execute([
            ':nom' => $nom,
            ':type' => $type,
            ':num_serie' => $num_serie,
            ':date_achat' => $date_achat,
            ':id' => $id
        ]);
    }

    // Archiver un matériel
    public function archiver(int $id): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE materiels SET est_archive = 1 WHERE id = :id'
        );
        return $stmt->execute([':id' => $id]);
    }

    // Affecter un matériel à un utilisateur
    public function affecter(int $id_materiel, int $id_utilisateur): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE materiels SET id_utilisateur = :id_utilisateur WHERE id = :id'
        );
        return $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id' => $id_materiel
        ]);
    }

    // Supprimer un matériel
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM materiels WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}