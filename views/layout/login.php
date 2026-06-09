<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetKeep — Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/NetKeep/public/css/style.css" rel="stylesheet">
</head>
<body>

<div class="nk-login-page">
    <div class="nk-login-box">

        <div class="nk-login-logo">
            <i class="bi bi-pc-display-horizontal"></i> NetKeep
        </div>
        <p class="nk-login-subtitle">Gestion de parc &amp; Helpdesk — BioTech Solutions</p>

        <?php if (isset($error)): ?>
            <div class="nk-alert nk-alert-danger">
                <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/NetKeep/index.php?page=login">
            <div class="nk-form-group">
                <label class="nk-label" for="email">Adresse e-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="nk-input"
                    placeholder="prenom.nom@biotech.fr"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="nk-form-group">
                <label class="nk-label" for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="nk-input"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="nk-btn nk-btn-primary w-100 justify-content-center mt-2">
                <i class="bi bi-box-arrow-in-right"></i> Se connecter
            </button>
        </form>

        <p class="text-center text-muted-nk mt-4" style="font-size:12px;">
            Accès réservé aux employés et techniciens BioTech Solutions
        </p>
    </div>
</div>

</body>
</html>