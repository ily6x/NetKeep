<?php
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'tickets':
        break;
    case 'materiel':
        break;
    default:
        echo "Bienvenue sur NetKeep";
        break;
}