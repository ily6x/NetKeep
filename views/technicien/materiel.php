<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php $types = ['PC','Ecran','Serveur','Clavier','Souris','Imprimante','Telephone','Tablette','Autre'];
$statuts = ['actif' => 'Actif', 'en_reparation' => 'En réparation', 'archive' => 'Archivé']; ?>

<div class="nk-page-header">
    <h1 class="nk-page-title">Parc Matériel</h1>
    <button class="nk-btn nk-btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjoutMateriel">
        <i class="bi bi-plus-circle"></i> Ajouter un matériel
    </button>
</div>

<div class="nk-card">
    <div class="nk-table-wrap">
        <table class="nk-table">
            <thead><tr><th>#</th><th>Nom</th><th>Type</th><th>N° Série</th><th>Achat</th><th>Statut</th><th>Affecté à</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (!empty($liste)): ?>
                    <?php foreach ($liste as $m): ?>
                    <tr>
                        <td class="text-muted-nk"><?= $m->getId() ?></td>
                        <td><?= htmlspecialchars($m->getNom()) ?></td>
                        <td class="text-muted-nk"><?= htmlspecialchars($m->getType()) ?></td>
                        <td class="text-muted-nk"><?= htmlspecialchars($m->getNumSerie()) ?></td>
                        <td class="text-muted-nk"><?= date('d/m/Y', strtotime($m->getDateAchat())) ?></td>
                        <td><span class="nk-badge nk-badge-<?= $m->getStatut() ?>"><?= $statuts[$m->getStatut()] ?? ucfirst($m->getStatut()) ?></span></td>
                        <td>
                            <form method="POST" action="index.php?page=affecter-materiel" onchange="this.submit()">
                                <input type="hidden" name="id" value="<?= $m->getId() ?>">
                                <select name="id_utilisateur" class="nk-select" style="font-size:.8rem;padding:.35rem .6rem;">
                                    <option value="">— Non affecté —</option>
                                    <?php foreach ($utilisateurs as $u): ?>
                                    <option value="<?= $u->getId() ?>" <?= $m->getIdUtilisateur() === $u->getId() ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($u->getPrenom() . ' ' . $u->getNom()) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td class="text-nowrap">
                            <button class="nk-btn nk-btn-ghost nk-btn-sm" title="Modifier"
                                data-bs-toggle="modal" data-bs-target="#modalEditMateriel"
                                data-id="<?= $m->getId() ?>" data-nom="<?= htmlspecialchars($m->getNom()) ?>"
                                data-type="<?= $m->getType() ?>" data-numserie="<?= htmlspecialchars($m->getNumSerie()) ?>"
                                data-date="<?= $m->getDateAchat() ?>" data-statut="<?= $m->getStatut() ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="index.php?page=archiver-materiel&id=<?= $m->getId() ?>" class="nk-btn nk-btn-ghost nk-btn-sm" title="Archiver"
                               onclick="return confirm('Archiver ce matériel ?')"><i class="bi bi-archive"></i></a>
                            <a href="index.php?page=supprimer-materiel&id=<?= $m->getId() ?>" class="nk-btn nk-btn-danger nk-btn-sm" title="Supprimer"
                               onclick="return confirm('Supprimer définitivement ce matériel ?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted-nk py-4"><i class="bi bi-hdd-stack"></i> Aucun matériel</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="modalAjoutMateriel" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="index.php?page=ajouter-materiel">
        <div class="modal-header"><h5 class="modal-title">Ajouter un matériel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="nk-form-group"><label class="nk-label">Nom</label>
                <input type="text" name="nom" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Type</label>
                <select name="type" class="nk-select" required>
                    <?php foreach ($types as $t): ?><option value="<?= $t ?>"><?= $t ?></option><?php endforeach; ?>
                </select></div>
            <div class="nk-form-group"><label class="nk-label">N° Série</label>
                <input type="text" name="num_serie" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Date d'achat</label>
                <input type="date" name="date_achat" class="nk-input" required></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="nk-btn nk-btn-ghost" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="nk-btn nk-btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edition -->
<div class="modal fade" id="modalEditMateriel" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="index.php?page=modifier-materiel">
        <input type="hidden" name="id" id="edit-id">
        <div class="modal-header"><h5 class="modal-title">Modifier le matériel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="nk-form-group"><label class="nk-label">Nom</label>
                <input type="text" name="nom" id="edit-nom" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Type</label>
                <select name="type" id="edit-type" class="nk-select" required>
                    <?php foreach ($types as $t): ?><option value="<?= $t ?>"><?= $t ?></option><?php endforeach; ?>
                </select></div>
            <div class="nk-form-group"><label class="nk-label">N° Série</label>
                <input type="text" name="num_serie" id="edit-numserie" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Date d'achat</label>
                <input type="date" name="date_achat" id="edit-date" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Statut</label>
                <select name="statut" id="edit-statut" class="nk-select" required>
                    <?php foreach ($statuts as $val => $label): ?><option value="<?= $val ?>"><?= $label ?></option><?php endforeach; ?>
                </select></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="nk-btn nk-btn-ghost" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="nk-btn nk-btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('modalEditMateriel').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    document.getElementById('edit-id').value = btn.dataset.id;
    document.getElementById('edit-nom').value = btn.dataset.nom;
    document.getElementById('edit-type').value = btn.dataset.type;
    document.getElementById('edit-numserie').value = btn.dataset.numserie;
    document.getElementById('edit-date').value = btn.dataset.date;
    document.getElementById('edit-statut').value = btn.dataset.statut;
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
