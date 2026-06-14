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

    case 'ajouter-materiel':
        (new TechnicienController())->ajouterMateriel();
        break;

    case 'modifier-materiel':
        (new TechnicienController())->modifierMateriel();
        break;

    case 'archiver-materiel':
        (new TechnicienController())->archiverMateriel();
        break;

    case 'supprimer-materiel':
        (new TechnicienController())->supprimerMateriel();
        break;

    case 'affecter-materiel':
        (new TechnicienController())->affecterMateriel();
        break;

    case 'ajouter-utilisateur':
        (new TechnicienController())->ajouterUtilisateur();
        break;

    case 'modifier-utilisateur':
        (new TechnicienController())->modifierUtilisateur();
        break;

    case 'supprimer-utilisateur':
        (new TechnicienController())->supprimerUtilisateur();
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