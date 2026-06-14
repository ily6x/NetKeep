<?php
require_once __DIR__ . '/../models/Materiel.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Ticket.php';

class TechnicienController
{
    private function checkAuth(): void
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'technicien') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function dashboard(): void
    {
        $this->checkAuth();
        $tickets = (new Ticket())->findAll();
        $materiels = (new Materiel())->findAll();
        require_once __DIR__ . '/../views/technicien/technicien_dashboard.php';
    }

    public function materiel(): void
    {
        $this->checkAuth();
        $liste = (new Materiel())->findAll();
        $utilisateurs = User::findAll();
        require_once __DIR__ . '/../views/technicien/materiel.php';
    }

    public function utilisateurs(): void
    {
        $this->checkAuth();
        $liste = User::findAll();
        require_once __DIR__ . '/../views/technicien/utilisateurs.php';
    }

    public function prendreEnCharge(): void
    {
        $this->checkAuth();
        $id = (int) ($_GET['id'] ?? 0);

        if ($id) {
            (new Ticket())->updateStatut($id, 'en_cours');
        }

        header('Location: index.php?page=tickets');
        exit;
    }

    // --- CRUD Matériel ---

    private const TYPES_MATERIEL = ['PC', 'Ecran', 'Serveur', 'Clavier', 'Souris', 'Imprimante', 'Telephone', 'Tablette', 'Autre'];
    private const STATUTS_MATERIEL = ['actif', 'en_reparation', 'archive'];

    public function ajouterMateriel(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=materiel');
            exit;
        }

        $nom        = trim($_POST['nom'] ?? '');
        $type       = $_POST['type'] ?? '';
        $num_serie  = trim($_POST['num_serie'] ?? '');
        $date_achat = trim($_POST['date_achat'] ?? '');

        $materielModel = new Materiel();

        if ($nom === '' || $num_serie === '' || $date_achat === '' || !in_array($type, self::TYPES_MATERIEL, true)) {
            $_SESSION['flash_error'] = 'Tous les champs sont obligatoires.';
        } elseif ($materielModel->numSerieExiste($num_serie)) {
            $_SESSION['flash_error'] = 'Ce numéro de série existe déjà.';
        } else {
            $materielModel->create($nom, $type, $num_serie, $date_achat);
            $_SESSION['flash_success'] = 'Matériel ajouté avec succès.';
        }

        header('Location: index.php?page=materiel');
        exit;
    }

    public function modifierMateriel(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=materiel');
            exit;
        }

        $id         = (int) ($_POST['id'] ?? 0);
        $nom        = trim($_POST['nom'] ?? '');
        $type       = $_POST['type'] ?? '';
        $num_serie  = trim($_POST['num_serie'] ?? '');
        $date_achat = trim($_POST['date_achat'] ?? '');
        $statut     = $_POST['statut'] ?? '';

        $materielModel = new Materiel();

        if (!$id || $nom === '' || $num_serie === '' || $date_achat === '' || !in_array($type, self::TYPES_MATERIEL, true) || !in_array($statut, self::STATUTS_MATERIEL, true)) {
            $_SESSION['flash_error'] = 'Tous les champs sont obligatoires.';
        } elseif ($materielModel->numSerieExiste($num_serie, $id)) {
            $_SESSION['flash_error'] = 'Ce numéro de série existe déjà.';
        } else {
            $materielModel->update($id, $nom, $type, $num_serie, $date_achat, $statut);
            $_SESSION['flash_success'] = 'Matériel modifié avec succès.';
        }

        header('Location: index.php?page=materiel');
        exit;
    }

    public function archiverMateriel(): void
    {
        $this->checkAuth();
        $id = (int) ($_GET['id'] ?? 0);

        if ($id) {
            (new Materiel())->archiver($id);
            $_SESSION['flash_success'] = 'Matériel archivé.';
        }

        header('Location: index.php?page=materiel');
        exit;
    }

    public function supprimerMateriel(): void
    {
        $this->checkAuth();
        $id = (int) ($_GET['id'] ?? 0);

        if ($id) {
            (new Materiel())->delete($id);
            $_SESSION['flash_success'] = 'Matériel supprimé.';
        }

        header('Location: index.php?page=materiel');
        exit;
    }

    public function affecterMateriel(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=materiel');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $id_utilisateur = !empty($_POST['id_utilisateur']) ? (int) $_POST['id_utilisateur'] : null;

        if ($id) {
            (new Materiel())->affecter($id, $id_utilisateur);
            $_SESSION['flash_success'] = 'Affectation mise à jour.';
        }

        header('Location: index.php?page=materiel');
        exit;
    }

    // --- CRUD Utilisateurs ---

    public function ajouterUtilisateur(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=utilisateurs');
            exit;
        }

        $nom            = trim($_POST['nom'] ?? '');
        $prenom         = trim($_POST['prenom'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $password       = $_POST['password'] ?? '';
        $role           = $_POST['role'] ?? '';
        $niveau_support = !empty($_POST['niveau_support']) ? (int) $_POST['niveau_support'] : null;

        if ($nom === '' || $prenom === '' || $email === '' || $password === '' || !in_array($role, ['employe', 'technicien'], true)) {
            $_SESSION['flash_error'] = 'Tous les champs sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Format d\'email invalide.';
        } elseif (User::emailExiste($email)) {
            $_SESSION['flash_error'] = 'Cet email est déjà utilisé.';
        } else {
            User::create($nom, $prenom, $email, $password, $role, $niveau_support);
            $_SESSION['flash_success'] = 'Utilisateur ajouté avec succès.';
        }

        header('Location: index.php?page=utilisateurs');
        exit;
    }

    public function modifierUtilisateur(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=utilisateurs');
            exit;
        }

        $id             = (int) ($_POST['id'] ?? 0);
        $nom            = trim($_POST['nom'] ?? '');
        $prenom         = trim($_POST['prenom'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $role           = $_POST['role'] ?? '';
        $niveau_support = !empty($_POST['niveau_support']) ? (int) $_POST['niveau_support'] : null;

        if (!$id || $nom === '' || $prenom === '' || $email === '' || !in_array($role, ['employe', 'technicien'], true)) {
            $_SESSION['flash_error'] = 'Tous les champs sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Format d\'email invalide.';
        } elseif (User::emailExiste($email, $id)) {
            $_SESSION['flash_error'] = 'Cet email est déjà utilisé.';
        } else {
            User::update($id, $nom, $prenom, $email, $role, $niveau_support);
            $_SESSION['flash_success'] = 'Utilisateur modifié avec succès.';
        }

        header('Location: index.php?page=utilisateurs');
        exit;
    }

    public function supprimerUtilisateur(): void
    {
        $this->checkAuth();
        $id = (int) ($_GET['id'] ?? 0);

        if ($id === (int) $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Vous ne pouvez pas supprimer votre propre compte.';
        } elseif ($id) {
            User::delete($id);
            $_SESSION['flash_success'] = 'Utilisateur supprimé.';
        }

        header('Location: index.php?page=utilisateurs');
        exit;
    }
}
