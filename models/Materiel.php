<?php
require_once __DIR__ . '/../config/database.php';

class Materiel
{
    private int $id;
    private string $nom;
    private string $type;
    private string $num_serie;
    private string $date_achat;
    private string $statut;
    private bool $est_archive;
    private ?int $id_utilisateur;

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConnection();
    }

    private function hydrate(array $data): self
    {
        $this->id             = (int) $data['id'];
        $this->nom            = $data['nom'];
        $this->type           = $data['type'];
        $this->num_serie      = $data['num_serie'];
        $this->date_achat     = $data['date_achat'];
        $this->statut         = $data['statut'];
        $this->est_archive    = (bool) $data['est_archive'];
        $this->id_utilisateur = $data['id_utilisateur'] !== null ? (int) $data['id_utilisateur'] : null;
        return $this;
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getType(): string { return $this->type; }
    public function getNumSerie(): string { return $this->num_serie; }
    public function getDateAchat(): string { return $this->date_achat; }
    public function getStatut(): string { return $this->statut; }
    public function isArchive(): bool { return $this->est_archive; }
    public function getIdUtilisateur(): ?int { return $this->id_utilisateur; }

    // Tout le matériel non archivé
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM materiels WHERE est_archive = 0 ORDER BY id');
        return array_map(fn($row) => (new self())->hydrate($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findById(int $id): self|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM materiels WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? (new self())->hydrate($data) : false;
    }

    // Matériel d'un utilisateur spécifique
    public function findByUser(int $id_utilisateur): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM materiels WHERE id_utilisateur = :id AND est_archive = 0'
        );
        $stmt->execute([':id' => $id_utilisateur]);
        return array_map(fn($row) => (new self())->hydrate($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function numSerieExiste(string $num_serie, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM materiels WHERE num_serie = :num_serie';
        $params = [':num_serie' => $num_serie];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $excludeId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    // Ajouter un matériel
    public function create(string $nom, string $type, string $num_serie, string $date_achat): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO materiels (nom, type, num_serie, date_achat, statut, est_archive, id_utilisateur)
             VALUES (:nom, :type, :num_serie, :date_achat, :statut, :est_archive, :id_utilisateur)'
        );
        return $stmt->execute([
            ':nom'            => $nom,
            ':type'           => $type,
            ':num_serie'      => $num_serie,
            ':date_achat'     => $date_achat,
            ':statut'         => 'actif',
            ':est_archive'    => 0,
            ':id_utilisateur' => null,
        ]);
    }

    // Modifier un matériel
    public function update(int $id, string $nom, string $type, string $num_serie, string $date_achat, string $statut): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE materiels SET nom = :nom, type = :type, num_serie = :num_serie, date_achat = :date_achat, statut = :statut
             WHERE id = :id'
        );
        return $stmt->execute([
            ':nom'        => $nom,
            ':type'       => $type,
            ':num_serie'  => $num_serie,
            ':date_achat' => $date_achat,
            ':statut'     => $statut,
            ':id'         => $id,
        ]);
    }

    // Archiver un matériel (retiré du parc actif sans suppression définitive)
    public function archiver(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE materiels SET est_archive = 1, statut = :statut, id_utilisateur = NULL WHERE id = :id'
        );
        return $stmt->execute([':statut' => 'archive', ':id' => $id]);
    }

    // Affecter un matériel à un utilisateur (ou retirer l'affectation si null)
    public function affecter(int $id_materiel, ?int $id_utilisateur): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE materiels SET id_utilisateur = :id_utilisateur WHERE id = :id'
        );
        return $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id'             => $id_materiel,
        ]);
    }

    // Supprimer définitivement un matériel
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM materiels WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
