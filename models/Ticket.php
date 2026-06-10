<?php
require_once __DIR__ . '/../config/database.php';

class Ticket
{
    private int $id;
    private string $titre;
    private string $description;
    private string $urgence;
    private string $statut;
    private string $type_probleme;
    private int $niveau_escalade;
    private ?string $message_resolution;
    private string $date_creation;
    private ?int $id_materiel;
    private int $id_auteur;
    private ?int $id_technicien;

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConnection();
    }

    private function hydrate(array $data): self
    {
        $this->id                = $data['id'];
        $this->titre             = $data['titre'];
        $this->description       = $data['description'];
        $this->urgence           = $data['urgence'];
        $this->statut            = $data['statut'];
        $this->type_probleme     = $data['type_probleme'] ?? 'autre';
        $this->niveau_escalade   = $data['niveau_escalade'];
        $this->message_resolution = $data['message_resolution'];
        $this->date_creation     = $data['date_creation'];
        $this->id_materiel       = $data['id_materiel'];
        $this->id_auteur         = $data['id_auteur'];
        $this->id_technicien     = $data['id_technicien'];
        return $this;
    }

    public function getId(): int              { return $this->id; }
    public function getTitre(): string        { return $this->titre; }
    public function getDescription(): string  { return $this->description; }
    public function getUrgence(): string      { return $this->urgence; }
    public function getStatut(): string       { return $this->statut; }
    public function getTypeProbleme(): string { return $this->type_probleme; }
    public function getNiveauEscalade(): int  { return $this->niveau_escalade; }
    public function getMessageResolution(): ?string { return $this->message_resolution; }
    public function getDateCreation(): string { return $this->date_creation; }
    public function getIdMateriel(): ?int     { return $this->id_materiel; }
    public function getIdAuteur(): int        { return $this->id_auteur; }
    public function getIdTechnicien(): ?int   { return $this->id_technicien; }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM tickets ORDER BY date_creation DESC');
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => (new self())->hydrate($row), $results);
    }

    public function findByAuteur(int $id_auteur): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM tickets WHERE id_auteur = :id ORDER BY date_creation DESC'
        );
        $stmt->execute([':id' => $id_auteur]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => (new self())->hydrate($row), $results);
    }

    public function findById(int $id): self|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tickets WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? (new self())->hydrate($data) : false;
    }

    public function create(
        string $titre,
        string $description,
        string $urgence,
        ?int $id_materiel,
        int $id_auteur,
        string $type_probleme = 'autre'
    ): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO tickets (titre, description, urgence, type_probleme, id_materiel, id_auteur)
             VALUES (:titre, :description, :urgence, :type_probleme, :id_materiel, :id_auteur)'
        );
        return $stmt->execute([
            ':titre'         => $titre,
            ':description'   => $description,
            ':urgence'       => $urgence,
            ':type_probleme' => $type_probleme,
            ':id_materiel'   => $id_materiel,
            ':id_auteur'     => $id_auteur,
        ]);
    }

    public function updateStatut(int $id, string $statut): bool
    {
        $stmt = $this->pdo->prepare('UPDATE tickets SET statut = :statut WHERE id = :id');
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }

    public function assignerTechnicien(int $id_ticket, int $id_technicien): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE tickets SET id_technicien = :id_technicien, statut = :statut WHERE id = :id'
        );
        return $stmt->execute([
            ':id_technicien' => $id_technicien,
            ':statut'        => 'en_cours',
            ':id'            => $id_ticket,
        ]);
    }

    public function resoudre(int $id, string $message_resolution, int $id_technicien): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE tickets SET statut = :statut, message_resolution = :message, id_technicien = :id_technicien WHERE id = :id'
        );
        return $stmt->execute([
            ':statut'        => 'resolu',
            ':message'       => $message_resolution,
            ':id_technicien' => $id_technicien,
            ':id'            => $id,
        ]);
    }

    public function escalader(int $id, int $niveau): bool
    {
        $stmt = $this->pdo->prepare('UPDATE tickets SET niveau_escalade = :niveau WHERE id = :id');
        return $stmt->execute([':niveau' => $niveau, ':id' => $id]);
    }
}