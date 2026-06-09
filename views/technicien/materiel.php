<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="nk-page-header">
    <h1 class="nk-page-title">Parc Matériel</h1>
</div>

<div class="nk-card">
    <div class="nk-table-wrap">
        <table class="nk-table">
            <thead><tr><th>#</th><th>Nom</th><th>Type</th><th>N° Série</th><th>Statut</th><th>Affecté à</th></tr></thead>
            <tbody>
                <?php if (!empty($liste)): ?>
                    <?php foreach ($liste as $m): ?>
                    <tr>
                        <td class="text-muted-nk"><?= $m['id'] ?></td>
                        <td><?= htmlspecialchars($m['nom']) ?></td>
                        <td class="text-muted-nk"><?= htmlspecialchars($m['type']) ?></td>
                        <td class="text-muted-nk"><?= htmlspecialchars($m['num_serie']) ?></td>
                        <td><span class="nk-badge nk-badge-<?= $m['statut'] ?>"><?= ucfirst($m['statut']) ?></span></td>
                        <td class="text-muted-nk"><?= $m['id_utilisateur'] ? '#'.$m['id_utilisateur'] : '—' ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted-nk py-4"><i class="bi bi-hdd-stack"></i> Aucun matériel</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
