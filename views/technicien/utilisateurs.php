<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="nk-page-header">
    <h1 class="nk-page-title">Utilisateurs</h1>
    <button class="nk-btn nk-btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjoutUser">
        <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
    </button>
</div>

<div class="nk-card">
    <div class="nk-table-wrap">
        <table class="nk-table">
            <thead><tr><th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (!empty($liste)): ?>
                    <?php foreach ($liste as $u): ?>
                    <tr>
                        <td class="text-muted-nk"><?= $u->getId() ?></td>
                        <td><?= htmlspecialchars($u->getNom()) ?></td>
                        <td><?= htmlspecialchars($u->getPrenom()) ?></td>
                        <td class="text-muted-nk"><?= htmlspecialchars($u->getEmail()) ?></td>
                        <td><span class="nk-badge nk-badge-<?= $u->getRole() ?>"><?= htmlspecialchars($u->getLibelleRole()) ?></span></td>
                        <td class="text-nowrap">
                            <button class="nk-btn nk-btn-ghost nk-btn-sm" title="Modifier"
                                data-bs-toggle="modal" data-bs-target="#modalEditUser"
                                data-id="<?= $u->getId() ?>" data-nom="<?= htmlspecialchars($u->getNom()) ?>"
                                data-prenom="<?= htmlspecialchars($u->getPrenom()) ?>" data-email="<?= htmlspecialchars($u->getEmail()) ?>"
                                data-role="<?= $u->getRole() ?>" data-niveau="<?= $u instanceof Technicien ? $u->getNiveauSupport() : 1 ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <?php if ($u->getId() !== (int) $_SESSION['user_id']): ?>
                            <a href="index.php?page=supprimer-utilisateur&id=<?= $u->getId() ?>" class="nk-btn nk-btn-danger nk-btn-sm" title="Supprimer"
                               onclick="return confirm('Supprimer cet utilisateur ?')"><i class="bi bi-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted-nk py-4"><i class="bi bi-people"></i> Aucun utilisateur</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="modalAjoutUser" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="index.php?page=ajouter-utilisateur">
        <div class="modal-header"><h5 class="modal-title">Ajouter un utilisateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="nk-form-group"><label class="nk-label">Nom</label>
                <input type="text" name="nom" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Prénom</label>
                <input type="text" name="prenom" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Email</label>
                <input type="email" name="email" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Mot de passe</label>
                <input type="password" name="password" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Rôle</label>
                <select name="role" class="nk-select" id="add-role" onchange="document.getElementById('add-niveau-group').style.display=this.value==='technicien'?'block':'none'">
                    <option value="employe">Employé</option>
                    <option value="technicien">Technicien</option>
                </select></div>
            <div class="nk-form-group" id="add-niveau-group" style="display:none">
                <label class="nk-label">Niveau de support</label>
                <select name="niveau_support" class="nk-select">
                    <option value="1">N1</option><option value="2">N2</option><option value="3">N3</option>
                </select></div>
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
<div class="modal fade" id="modalEditUser" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="index.php?page=modifier-utilisateur">
        <input type="hidden" name="id" id="edit-uid">
        <div class="modal-header"><h5 class="modal-title">Modifier l'utilisateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="nk-form-group"><label class="nk-label">Nom</label>
                <input type="text" name="nom" id="edit-unom" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Prénom</label>
                <input type="text" name="prenom" id="edit-uprenom" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Email</label>
                <input type="email" name="email" id="edit-uemail" class="nk-input" required></div>
            <div class="nk-form-group"><label class="nk-label">Rôle</label>
                <select name="role" id="edit-urole" class="nk-select" onchange="document.getElementById('edit-niveau-group').style.display=this.value==='technicien'?'block':'none'">
                    <option value="employe">Employé</option>
                    <option value="technicien">Technicien</option>
                </select></div>
            <div class="nk-form-group" id="edit-niveau-group">
                <label class="nk-label">Niveau de support</label>
                <select name="niveau_support" id="edit-univeau" class="nk-select">
                    <option value="1">N1</option><option value="2">N2</option><option value="3">N3</option>
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
document.getElementById('modalEditUser').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    document.getElementById('edit-uid').value = btn.dataset.id;
    document.getElementById('edit-unom').value = btn.dataset.nom;
    document.getElementById('edit-uprenom').value = btn.dataset.prenom;
    document.getElementById('edit-uemail').value = btn.dataset.email;
    document.getElementById('edit-urole').value = btn.dataset.role;
    document.getElementById('edit-univeau').value = btn.dataset.niveau;
    document.getElementById('edit-niveau-group').style.display = btn.dataset.role === 'technicien' ? 'block' : 'none';
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
