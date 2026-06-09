<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $role;
    private ?int $niveau_support;

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConnection();
    }

    private function hydrate(array $data): self
    {
        $this->id = $data['id'];
        $this->nom = $data['nom'];
        $this->prenom = $data['prenom'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->role = $data['role'];
        $this->niveau_support = $data['niveau_support'];
        return $this;
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }
    public function getNiveauSupport(): ?int { return $this->niveau_support; }

    public function isTechnicien(): bool { return $this->role === 'technicien'; }
    public function isEmploye(): bool { return $this->role === 'employe'; }

    public function findByEmail(string $email): self|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? (new self())->hydrate($data) : false;
    }

    public function findById(int $id): self|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? (new self())->hydrate($data) : false;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM utilisateurs ORDER BY nom, prenom');
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => (new self())->hydrate($row), $results);
    }

    public function create(string $nom, string $prenom, string $email, string $password, string $role): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO utilisateurs (nom, prenom, email, password, role)
             VALUES (:nom, :prenom, :email, :password, :role)'
        );

        return $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':role' => $role,
        ]);
    }

    public function update(int $id, string $nom, string $prenom, string $email, string $role): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE utilisateurs
             SET nom = :nom, prenom = :prenom, email = :email, role = :role
             WHERE id = :id'
        );

        return $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':role' => $role,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM utilisateurs WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function authenticate(string $email, string $password): self|false
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
        return $user;
     }

     return false;
    }
    
}