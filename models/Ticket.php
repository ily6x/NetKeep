<?php
require_once __DIR__ . '/../config/database.php';

class Ticket {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    // Tous les tickets (pour le technicien)
    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM tickets ORDER BY date_creation DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tickets d'un employé spécifique
    public function findByAuteur(int $id_auteur): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM tickets WHERE id_auteur = :id ORDER BY date_creation DESC'
        );
        $stmt->execute([':id' => $id_auteur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Créer un ticket
    public function create(string $titre, string $description, string $urgence, int $id_materiel, int $id_auteur): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO tickets (titre, description, urgence, id_materiel, id_auteur)
             VALUES (:titre, :description, :urgence, :id_materiel, :id_auteur)'
        );
        return $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':urgence' => $urgence,
            ':id_materiel' => $id_materiel,
            ':id_auteur' => $id_auteur
        ]);
    }

    // Changer le statut d'un ticket
    public function updateStatut(int $id, string $statut): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE tickets SET statut = :statut WHERE id = :id'
        );
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }
}