<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    // Trouver un utilisateur par email
    public function findByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM utilisateurs WHERE email = :email'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tous les utilisateurs
    public function findAll(): array {
        $stmt = $this->pdo->query(
            'SELECT * FROM utilisateurs'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Connexion sécurisée
    public function authenticate(string $email, string $password): array|false {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}