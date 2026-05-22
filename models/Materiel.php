<?php
require_once __DIR__ . '/../config/database.php';

class Materiel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    // Tout le matériel
    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM materiels');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Matériel d'un utilisateur spécifique
    public function findByUser(int $id_utilisateur): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM materiels WHERE id_utilisateur = :id'
        );
        $stmt->execute([':id' => $id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un matériel
    public function create(string $nom, string $type, string $num_serie, string $date_achat): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO materiels (nom, type, num_serie, date_achat) 
             VALUES (:nom, :type, :num_serie, :date_achat)'
        );
        return $stmt->execute([
            ':nom' => $nom,
            ':type' => $type,
            ':num_serie' => $num_serie,
            ':date_achat' => $date_achat
        ]);
    }
}