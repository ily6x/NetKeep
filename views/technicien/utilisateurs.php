<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="nk-page-header">
    <h1 class="nk-page-title">Utilisateurs</h1>
</div>

<div class="nk-card">
    <div class="nk-table-wrap">
        <table class="nk-table">
            <thead><tr><th>#</th><th>Nom</th><th>Email</th><th>Rôle</th></tr></thead>
            <tbody>
                <?php if (!empty($liste)): ?>
                    <?php foreach ($liste as $u): ?>
                    <tr>
                        <td class="text-muted-nk"><?= $u->getId() ?></td>
                        <td><?= htmlspecialchars($u->getNom()) ?></td>
                        <td class="text-muted-nk"><?= htmlspecialchars($u->getEmail()) ?></td>
                        <td><span class="nk-badge nk-badge-<?= $u->getRole() ?>"><?= ucfirst($u->getRole()) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted-nk py-4"><i class="bi bi-people"></i> Aucun utilisateur</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
