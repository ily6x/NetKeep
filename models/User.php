<?php
require_once __DIR__ . '/../config/database.php';

abstract class User
{
    protected int $id;
    protected string $nom;
    protected string $prenom;
    protected string $email;
    protected string $password;
    protected string $role;

    protected static function pdo(): PDO
    {
        return (new Database())->getConnection();
    }

    protected function hydrate(array $data): static
    {
        $this->id       = (int) $data['id'];
        $this->nom      = $data['nom'];
        $this->prenom   = $data['prenom'];
        $this->email    = $data['email'];
        $this->password = $data['password'];
        $this->role     = $data['role'];
        return $this;
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }

    public function isTechnicien(): bool { return $this instanceof Technicien; }
    public function isEmploye(): bool { return $this instanceof Employe; }

    // Méthode polymorphe : libellé du rôle propre à chaque sous-classe
    abstract public function getLibelleRole(): string;

    // Fabrique : instancie Employe ou Technicien selon le champ "role"
    private static function creerDepuisLigne(array $data): User
    {
        $instance = $data['role'] === 'technicien' ? new Technicien() : new Employe();
        return $instance->hydrate($data);
    }

    public static function findByEmail(string $email): User|false
    {
        $stmt = self::pdo()->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? self::creerDepuisLigne($data) : false;
    }

    public static function findById(int $id): User|false
    {
        $stmt = self::pdo()->prepare('SELECT * FROM utilisateurs WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? self::creerDepuisLigne($data) : false;
    }

    public static function findAll(): array
    {
        $stmt = self::pdo()->query('SELECT * FROM utilisateurs ORDER BY nom, prenom');
        return array_map(fn($row) => self::creerDepuisLigne($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function create(string $nom, string $prenom, string $email, string $password, string $role, ?int $niveauSupport = null): bool
    {
        $stmt = self::pdo()->prepare(
            'INSERT INTO utilisateurs (nom, prenom, email, password, role, niveau_support)
             VALUES (:nom, :prenom, :email, :password, :role, :niveau_support)'
        );
        return $stmt->execute([
            ':nom'            => $nom,
            ':prenom'         => $prenom,
            ':email'          => $email,
            ':password'       => password_hash($password, PASSWORD_BCRYPT),
            ':role'           => $role,
            ':niveau_support' => $role === 'technicien' ? ($niveauSupport ?? 1) : null,
        ]);
    }

    public static function update(int $id, string $nom, string $prenom, string $email, string $role, ?int $niveauSupport = null): bool
    {
        $stmt = self::pdo()->prepare(
            'UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email, role = :role, niveau_support = :niveau_support
             WHERE id = :id'
        );
        return $stmt->execute([
            ':nom'            => $nom,
            ':prenom'         => $prenom,
            ':email'          => $email,
            ':role'           => $role,
            ':niveau_support' => $role === 'technicien' ? ($niveauSupport ?? 1) : null,
            ':id'             => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = self::pdo()->prepare('DELETE FROM utilisateurs WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public static function emailExiste(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM utilisateurs WHERE email = :email';
        $params = [':email' => $email];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $excludeId;
        }
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public static function authenticate(string $email, string $password): User|false
    {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user->getPassword())) {
            return $user;
        }
        return false;
    }
}

require_once __DIR__ . '/Employe.php';
require_once __DIR__ . '/Technicien.php';
