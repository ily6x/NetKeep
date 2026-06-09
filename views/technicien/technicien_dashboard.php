<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="nk-page-header">
    <h1 class="nk-page-title">
        Dashboard
        <span>Bonjour <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Technicien') ?></span>
    </h1>
    <a href="index.php?page=tickets" class="nk-btn nk-btn-primary">
        <i class="bi bi-ticket-detailed"></i> Voir les tickets
    </a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="nk-stat">
            <div class="nk-stat-icon"><i class="bi bi-ticket-detailed"></i></div>
            <div class="nk-stat-label">Tickets ouverts</div>
            <div class="nk-stat-value text-accent">
                <?php
                    $nb = 0;
                    foreach ($tickets ?? [] as $t) {
                        if ($t->getStatut() === 'ouvert') $nb++;
                    }
                    echo $nb;
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="nk-stat">
            <div class="nk-stat-icon" style="background:rgba(243,156,18,.15);color:#f39c12"><i class="bi bi-hourglass-split"></i></div>
            <div class="nk-stat-label">En cours</div>
            <div class="nk-stat-value" style="color:#f39c12">
                <?php
                    $nb = 0;
                    foreach ($tickets ?? [] as $t) {
                        if ($t->getStatut() === 'en_cours') $nb++;
                    }
                    echo $nb;
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="nk-stat">
            <div class="nk-stat-icon" style="background:rgba(231,76,60,.15);color:#e74c3c"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="nk-stat-label">Critiques</div>
            <div class="nk-stat-value" style="color:#e74c3c">
                <?php
                    $nb = 0;
                    foreach ($tickets ?? [] as $t) {
                        if ($t->getUrgence() === 'critique') $nb++;
                    }
                    echo $nb;
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="nk-stat">
            <div class="nk-stat-icon" style="background:rgba(46,204,113,.15);color:#2ecc71"><i class="bi bi-hdd-stack"></i></div>
            <div class="nk-stat-label">Matériels actifs</div>
            <div class="nk-stat-value" style="color:#2ecc71">
                <?php echo count($materiels ?? []); ?>
            </div>
        </div>
    </div>
</div>

<!-- Derniers tickets -->
<div class="nk-card">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 style="font-size:1rem;font-weight:700;margin:0;">Derniers tickets</h2>
        <a href="index.php?page=tickets" class="nk-btn nk-btn-ghost nk-btn-sm">
            Voir tout <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="nk-table-wrap">
        <table class="nk-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Urgence</th>
                    <th>Statut</th>
                    <th>Escalade</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tickets)): ?>
                    <?php foreach (array_slice($tickets, 0, 5) as $ticket): ?>
                    <tr>
                        <td class="text-muted-nk">#<?= $ticket->getId() ?></td>
                        <td><?= htmlspecialchars($ticket->getTitre()) ?></td>
                        <td>
                            <span class="nk-badge nk-badge-<?= $ticket->getUrgence() ?>">
                                <?= ucfirst($ticket->getUrgence()) ?>
                            </span>
                        </td>
                        <td>
                            <span class="nk-badge nk-badge-<?= $ticket->getStatut() ?>">
                                <?= ucfirst(str_replace('_', ' ', $ticket->getStatut())) ?>
                            </span>
                        </td>
                        <td class="text-muted-nk">N<?= $ticket->getNiveauEscalade() ?></td>
                        <td class="text-muted-nk">
                            <?= date('d/m/Y', strtotime($ticket->getDateCreation())) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted-nk py-4">
                        <i class="bi bi-check-circle"></i> Aucun ticket pour le moment
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>