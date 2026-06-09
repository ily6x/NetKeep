<?php
require_once __DIR__ . '/../models/Ticket.php';

class TicketController
{
    private function checkTechnicien(): void
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'technicien') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function index(): void
    {
        $this->checkTechnicien();
        $ticket = new Ticket();
        $liste = $ticket->findAll();
        require_once __DIR__ . '/../views/technicien/tickets.php';
    }

    public function resoudre(): void
    {
        $this->checkTechnicien();

        $id = (int) ($_GET['id'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if ($id && $message) {
            $ticket = new Ticket();
            $ticket->resoudre($id, $message, $_SESSION['user_id']);
        }

        header('Location: index.php?page=tickets');
        exit;
    }

    public function escalader(): void
    {
        $this->checkTechnicien();

        $id = (int) ($_GET['id'] ?? 0);
        $niveau = (int) ($_GET['niveau'] ?? 0);

        if ($id && $niveau >= 1 && $niveau <= 3) {
            $ticket = new Ticket();
            $ticket->escalader($id, $niveau);
        }

        header('Location: index.php?page=tickets');
        exit;
    }
}