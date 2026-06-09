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
        require_once __DIR__ . '/../views/technicien/materiel.php';
    }

    public function utilisateurs(): void
    {
        $this->checkAuth();
        $liste = (new User())->findAll();
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
}