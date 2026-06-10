<?php 
require_once __DIR__ . '/../layout/header.php';
$materiels = $materiels ?? [];
$liste = $liste ?? [];
?>

<div class="nk-page-header">
    <h1 class="nk-page-title">Mes tickets</h1>
    <button class="nk-btn nk-btn-primary" onclick="document.getElementById('modal-ticket').style.display='flex'">
        <i class="bi bi-plus-circle"></i> Nouveau ticket
    </button>
</div>

<!-- Modal -->
<div id="modal-ticket" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1000;align-items:center;justify-content:center;" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:var(--bg-card);border-radius:12px;padding:2rem;width:100%;max-width:520px;position:relative;">
        <button onclick="document.getElementById('modal-ticket').style.display='none'" style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:var(--text-muted);font-size:1.2rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
        <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.5rem;">Nouveau ticket</h2>
        <form method="POST" action="index.php?page=creer-ticket">
            <div class="mb-3">
                <label style="font-size:.85rem;color:var(--text-muted);display:block;margin-bottom:.4rem;">Titre</label>
                <input type="text" name="titre" required style="width:100%;background:var(--bg-body);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;color:var(--text);font-size:.9rem;">
            </div>
            <div class="mb-3">
                <label style="font-size:.85rem;color:var(--text-muted);display:block;margin-bottom:.4rem;">Type de problème</label>
                <select name="type_probleme" id="sel-type" onchange="toggleMateriel(this.value)" style="width:100%;background:var(--bg-body);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;color:var(--text);font-size:.9rem;appearance:none;">
                    <option value="materiel">Matériel</option>
                    <option value="logiciel">Logiciel</option>
                    <option value="reseau">Réseau</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="mb-3" id="champ-materiel">
                <label style="font-size:.85rem;color:var(--text-muted);display:block;margin-bottom:.4rem;">Appareil concerné</label>
                <select name="id_materiel" style="width:100%;background:var(--bg-body);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;color:var(--text);font-size:.9rem;appearance:none;">
                    <option value="">— Aucun —</option>
                    <?php foreach ($materiels as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?> (<?= $m['type'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label style="font-size:.85rem;color:var(--text-muted);display:block;margin-bottom:.4rem;">Urgence</label>
                <select name="urgence" style="width:100%;background:var(--bg-body);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;color:var(--text);font-size:.9rem;appearance:none;">
                    <option value="faible">Faible</option>
                    <option value="moyen">Moyen</option>
                    <option value="critique">Critique</option>
                </select>
            </div>
            <div class="mb-4">
                <label style="font-size:.85rem;color:var(--text-muted);display:block;margin-bottom:.4rem;">Description</label>
                <textarea name="description" required rows="4" style="width:100%;background:var(--bg-body);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;color:var(--text);font-size:.9rem;resize:vertical;"></textarea>
            </div>
            <button type="submit" class="nk-btn nk-btn-primary" style="width:100%;">Envoyer le ticket</button>
        </form>
    </div>
</div>

<script>
function toggleMateriel(val) {
    document.getElementById('champ-materiel').style.display = val === 'materiel' ? 'block' : 'none';
}
</script>

<!-- Liste tickets -->
<div class="nk-card">
    <div class="nk-table-wrap">
        <table class="nk-table">
            <thead>
                <tr><th>#</th><th>Titre</th><th>Urgence</th><th>Statut</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php if (!empty($liste)): ?>
                    <?php foreach ($liste as $ticket): ?>
                    <tr>
                        <td class="text-muted-nk">#<?= $ticket->getId() ?></td>
                        <td><?= htmlspecialchars($ticket->getTitre()) ?></td>
                        <td><span class="nk-badge nk-badge-<?= $ticket->getUrgence() ?>"><?= ucfirst($ticket->getUrgence()) ?></span></td>
                        <td><span class="nk-badge nk-badge-<?= $ticket->getStatut() ?>"><?= ucfirst(str_replace('_', ' ', $ticket->getStatut())) ?></span></td>
                        <td class="text-muted-nk"><?= date('d/m/Y', strtotime($ticket->getDateCreation())) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted-nk py-4"><i class="bi bi-check-circle"></i> Aucun ticket pour le moment</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>