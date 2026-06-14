<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetKeep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/NetKeep/public/css/style.css" rel="stylesheet">
</head>
<body>

<?php
$base = '/NetKeep/index.php';
$page = $_GET['page'] ?? '';
?>

<?php if (isset($_SESSION['user_id'])): ?>
<aside class="nk-sidebar">

    <div class="nk-logo">
        <i class="bi bi-pc-display-horizontal"></i> NetKeep
    </div>

    <nav class="nk-nav">
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'technicien'): ?>

            <div class="nk-nav-label">Principal</div>
            <a href="<?= $base ?>?page=technicien_dashboard"
               class="<?= $page === 'technicien_dashboard' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nk-nav-label">Helpdesk</div>
            <a href="<?= $base ?>?page=tickets"
               class="<?= $page === 'tickets' ? 'active' : '' ?>">
                <i class="bi bi-ticket-detailed"></i> Tickets
            </a>

            <div class="nk-nav-label">Parc informatique</div>
            <a href="<?= $base ?>?page=materiel"
               class="<?= $page === 'materiel' ? 'active' : '' ?>">
                <i class="bi bi-hdd-stack"></i> Matériel
            </a>

            <a href="<?= $base ?>?page=utilisateurs"
               class="<?= $page === 'utilisateurs' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Utilisateurs
            </a>

        <?php else: ?>

            <div class="nk-nav-label">Principal</div>
            <a href="<?= $base ?>?page=user_dashboard"
               class="<?= $page === 'user_dashboard' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nk-nav-label">Mon espace</div>
            <a href="<?= $base ?>?page=mes-tickets"
               class="<?= $page === 'mes-tickets' ? 'active' : '' ?>">
                <i class="bi bi-ticket-detailed"></i> Mes Tickets
            </a>

            <a href="<?= $base ?>?page=mon-parc"
               class="<?= $page === 'mon-parc' ? 'active' : '' ?>">
                <i class="bi bi-hdd-stack"></i> Mon Parc
            </a>

        <?php endif; ?>
    </nav>

    <div class="nk-sidebar-footer">
        <a href="<?= $base ?>?page=logout">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>
    </div>

</aside>

<main class="nk-main">
<?php
if (!empty($_SESSION['flash_success'])): ?>
    <div class="nk-alert nk-alert-success"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['flash_success']) ?></div>
<?php endif;
unset($_SESSION['flash_success']);
if (!empty($_SESSION['flash_error'])): ?>
    <div class="nk-alert nk-alert-danger"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['flash_error']) ?></div>
<?php endif;
unset($_SESSION['flash_error']);
?>
<?php endif; ?>