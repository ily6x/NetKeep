<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="nk-page-header">
    <h1 class="nk-page-title">Tickets</h1>
</div>

<div class="nk-card">
    <div class="nk-table-wrap">
        <table class="nk-table">
            <thead><tr><th>#</th><th>Titre</th><th>Urgence</th><th>Statut</th><th>Escalade</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (!empty($liste)): ?>
                    <?php foreach ($liste as $t): ?>
                    <tr>
                        <td class="text-muted-nk">#<?= $t->getId() ?></td>
                        <td>
                            <div><?= htmlspecialchars($t->getTitre()) ?></div>
                            <div class="text-muted-nk" style="font-size:.78rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px"><?= htmlspecialchars($t->getDescription()) ?></div>
                        </td>
                        <td><span class="nk-badge nk-badge-<?= $t->getUrgence() ?>"><?= ucfirst($t->getUrgence()) ?></span></td>
                        <td><span class="nk-badge nk-badge-<?= $t->getStatut() ?>"><?= ucfirst(str_replace('_', ' ', $t->getStatut())) ?></span></td>
                        <td class="text-muted-nk">N<?= $t->getNiveauEscalade() ?></td>
                        <td class="text-muted-nk"><?= date('d/m/Y', strtotime($t->getDateCreation())) ?></td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <?php if ($t->getStatut() === 'ouvert'): ?>
                                    <a href="index.php?page=prendre-en-charge&id=<?= $t->getId() ?>" class="nk-btn nk-btn-sm nk-btn-primary">
                                        <i class="bi bi-hand-index"></i> Prendre
                                    </a>
                                <?php endif; ?>
                                <?php if ($t->getStatut() === 'en_cours'): ?>
                                    <button class="nk-btn nk-btn-sm nk-btn-ghost" data-bs-toggle="modal" data-bs-target="#modalResoudre<?= $t->getId() ?>">
                                        <i class="bi bi-check-circle"></i> Résoudre
                                    </button>
                                    <?php if ($t->getNiveauEscalade() < 3): ?>
                                    <a href="index.php?page=escalader&id=<?= $t->getId() ?>&niveau=<?= $t->getNiveauEscalade() + 1 ?>" class="nk-btn nk-btn-sm nk-btn-ghost">
                                        <i class="bi bi-arrow-up-circle"></i> N<?= $t->getNiveauEscalade() + 1 ?>
                                    </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted-nk py-4"><i class="bi bi-check-circle"></i> Aucun ticket</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modales sorties du tableau -->
<?php if (!empty($liste)): ?>
    <?php foreach ($liste as $t): ?>
        <?php if ($t->getStatut() === 'en_cours'): ?>
        <div class="modal fade" id="modalResoudre<?= $t->getId() ?>" tabindex="-1" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background:var(--nk-surface);color:var(--nk-text);border:1px solid var(--nk-border);box-shadow:0 8px 32px rgba(0,0,0,.5)">
                    <div class="modal-header" style="border-color:var(--nk-border)">
                        <h5 class="modal-title" style="font-weight:700">Résoudre #<?= $t->getId() ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="index.php?page=resoudre&id=<?= $t->getId() ?>">
                        <div class="modal-body">
                            <label class="form-label fw-semibold mb-2">Message de résolution</label>
                            <textarea name="message" rows="4" class="form-control" style="background:var(--nk-bg);border-color:var(--nk-border);color:var(--nk-text);resize:vertical" required placeholder="Décrivez la solution apportée..."></textarea>
                        </div>
                        <div class="modal-footer" style="border-color:var(--nk-border)">
                            <button type="button" class="nk-btn nk-btn-ghost" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="nk-btn nk-btn-primary">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>