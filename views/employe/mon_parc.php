<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="nk-page-header">
    <h1 class="nk-page-title">Mon Parc Matériel</h1>
</div>

<div class="row g-3">
    <?php if (!empty($liste)): ?>
        <?php foreach ($liste as $m): ?>
        <div class="col-md-4">
            <div class="nk-card h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="nk-stat-icon"><i class="bi bi-pc-display"></i></div>
                    <div>
                        <div style="font-weight:700"><?= htmlspecialchars($m->getNom()) ?></div>
                        <div class="text-muted-nk" style="font-size:.8rem"><?= htmlspecialchars($m->getType()) ?></div>
                    </div>
                </div>
                <table style="width:100%;font-size:.85rem">
                    <tr><td class="text-muted-nk" style="padding:3px 0;width:40%">N° Série</td><td><?= htmlspecialchars($m->getNumSerie()) ?></td></tr>
                    <tr><td class="text-muted-nk" style="padding:3px 0">Statut</td><td><span class="nk-badge nk-badge-<?= $m->getStatut() ?>"><?= ucfirst($m->getStatut()) ?></span></td></tr>
                    <tr><td class="text-muted-nk" style="padding:3px 0">Achat</td><td><?= $m->getDateAchat() ? date('d/m/Y', strtotime($m->getDateAchat())) : '—' ?></td></tr>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="nk-card text-center py-5 text-muted-nk">
                <i class="bi bi-hdd-stack" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                Aucun matériel affecté
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
