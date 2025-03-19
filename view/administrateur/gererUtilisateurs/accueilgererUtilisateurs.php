<?php ob_start(); // Démarre l'enregistrement de la page 
// J'affiche les erreurs lors de la gestion des utilisateurs
if (isset($_SESSION['erreur'])): ?>
    <div class="alert alert-info text-center" role="alert">
        <?= $_SESSION['erreur']; ?>
    </div>
    <?php unset($_SESSION['erreur']); // Supprime le message après l'affichage 
    ?>
<?php endif; ?>
<!-- J'affiche les succes lors de la gestion des utilisateurs -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<h1 class="text-center display-4 fw-bold text-primary my-4">Gérer les utilisateurs</h1>

<!-- Fil d'Ariane (Breadcrumb) avec Bootstrap amélioré -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-3 rounded-3 shadow-sm">
        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-primary fw-bold">Accueil</a></li>
        <li class="breadcrumb-item"><a href="index.php?p=administrateur" class="text-decoration-none text-primary fw-bold">Tableau de Bord Administrateur</a></li>
        <li class="breadcrumb-item active text-secondary" aria-current="page">Gérer les utilisateurs</li>
    </ol>
</nav>

<?php if (!empty($utilisateurs)): ?>
    <div class="container">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td>
                            <?php if (!empty($utilisateur['photo'])): ?>
                                <img src="<?= htmlspecialchars($utilisateur['photo']); ?>"
                                    alt="Photo de <?= htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom']); ?>"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <span class="text-muted">Aucune photo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($utilisateur["nom"]); ?></td>
                        <td><?= htmlspecialchars($utilisateur["prenom"]); ?></td>
                        <td><?= htmlspecialchars($utilisateur["email"]); ?></td>
                        <td><?= htmlspecialchars($utilisateur["date_inscription"]); ?></td>
                        <td>
                        <div class="d-flex gap-2">
                                <!-- Lien pour désinscrire l'utilisateur -->
                                <a href="#" class="btn btn-sm btn-outline-danger" onclick="confirmerDesinscription(<?= $utilisateur['id_utilisateur'] ?>)">Désinscrire</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <!-- Si il n'y a pas d'utilisateurs  -->
    <div class="alert alert-warning text-center" role="alert">
        Aucun utilisateur trouvé.
    </div>
<?php endif; ?>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Confirmer l'action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir désinscrire cet utilisateur ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
        <button type="button" class="btn btn-primary" id="confirmBtn">Oui</button>
      </div>
    </div>
  </div>
</div>
<script>
  // Fonction pour afficher la modal et stocker l'ID de l'utilisateur
  function confirmerDesinscription(idUtilisateur) {
    // Afficher le modal
    var modal = new bootstrap.Modal(document.getElementById('confirmationModal'), {
      keyboard: false,
      backdrop: 'static' // Empêche de fermer la modal en cliquant à l'extérieur
    });
    modal.show();

    // Action lorsque l'utilisateur clique sur "Oui"
    document.getElementById('confirmBtn').onclick = function() {
      // Rediriger vers la page de désinscription avec l'ID de l'utilisateur
      window.location.href = "index.php?p=administrateur/gererUtilisateurs/desinscrireUtilisateurs&id=" + idUtilisateur;
    };
  }
</script>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Gérer les utilisateurs"; // Titre de la page
require("./view/template/templateAccueil.php"); // Inclut le template
?>