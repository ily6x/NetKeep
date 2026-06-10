<?php
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/Materiel.php';

class EmployeController
{
    private function checkAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function dashboard(): void
    {
        $this->checkAuth();
        $liste    = (new Ticket())->findByAuteur($_SESSION['user_id']);
        $materiels = (new Materiel())->findByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/employe/dashboard.php';
    }

    public function monParc(): void
    {
        $this->checkAuth();
        $liste = (new Materiel())->findByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/employe/mon_parc.php';
    }

    public function mesTickets(): void
    {
        $this->checkAuth();
        $liste    = (new Ticket())->findByAuteur($_SESSION['user_id']);
        $materiels = (new Materiel())->findByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/employe/mes_tickets.php';
    }

    public function creerTicket(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=mes-tickets');
            exit;
        }

        $titre         = trim($_POST['titre'] ?? '');
        $description   = trim($_POST['description'] ?? '');
        $urgence       = $_POST['urgence'] ?? 'faible';
        $type_probleme = $_POST['type_probleme'] ?? 'autre';
        $id_materiel   = !empty($_POST['id_materiel']) ? (int) $_POST['id_materiel'] : null;

        if ($titre && $description) {
            (new Ticket())->create(
                $titre,
                $description,
                $urgence,
                $id_materiel,
                $_SESSION['user_id'],
                $type_probleme
            );
        }

        header('Location: index.php?page=mes-tickets');
        exit;
    }
}