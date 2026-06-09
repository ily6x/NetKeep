<?php
session_start();

require_once 'controllers/AuthController.php';
require_once 'controllers/EmployeController.php';
require_once 'controllers/TechnicienController.php';
require_once 'controllers/TicketController.php';

$page = $_GET['page'] ?? 'login';

switch ($page) {

    case 'login':
        (new AuthController())->login();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'user_dashboard':
        (new EmployeController())->dashboard();
        break;

    case 'mon-parc':
        (new EmployeController())->monParc();
        break;

    case 'mes-tickets':
        (new EmployeController())->mesTickets();
        break;

    case 'creer-ticket':
        (new EmployeController())->creerTicket();
        break;

    case 'technicien_dashboard':
        (new TechnicienController())->dashboard();
        break;

    case 'tickets':
        (new TicketController())->index();
        break;

    case 'materiel':
        (new TechnicienController())->materiel();
        break;

    case 'utilisateurs':
        (new TechnicienController())->utilisateurs();
        break;

    case 'prendre-en-charge':
        (new TechnicienController())->prendreEnCharge();
        break;

    case 'resoudre':
        (new TicketController())->resoudre();
        break;

    case 'escalader':
        (new TicketController())->escalader();
        break;

    default:
        header('Location: index.php?page=login');
        exit;
}