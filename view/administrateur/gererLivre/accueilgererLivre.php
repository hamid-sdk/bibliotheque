<?php ob_start(); // Démarre l'enregistrement de la page 
// J'affiche les erreurs lors de la gestion des livres 
if (isset($_SESSION['erreur'])): ?>
    <div class="alert alert-info text-center" role="alert">
        <?= $_SESSION['erreur']; ?>
    </div>
    <?php unset($_SESSION['erreur']); // Supprime le message après l'affichage 
    ?>
<?php endif; ?>
<!-- J'affiche les succes lors de la gestion des livres -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<h1 class="text-center display-4 fw-bold text-primary my-4">Gérer les livres</h1>

<!-- Fil d'Ariane (Breadcrumb) avec Bootstrap amélioré -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-3 rounded-3 shadow-sm">
        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-primary fw-bold">Accueil</a></li>
        <li class="breadcrumb-item"><a href="index.php?p=administrateur" class="text-decoration-none text-primary fw-bold">Tableau de Bord Administrateur</a></li>
        <li class="breadcrumb-item active text-secondary" aria-current="page">Gérer les livres</li>
    </ol>
</nav>

<?php if (!empty($livres)): ?>
    <div class="container">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>ISBN</th>
                    <th>Statut</th>
                    <th>Date d'ajout</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livres as $livre): ?>
                    <tr>
                        <td><?= mb_strimwidth($livre["titre"], 0, 30, "..."); ?></td>
                        <td><?= mb_strimwidth($livre["auteur"], 0, 20, "..."); ?></td>
                        <td><?= mb_strimwidth($livre["categorie"], 0, 20, "..."); ?></td>
                        <td><?= mb_strimwidth($livre["isbn"], 0, 20, "..."); ?></td>
                        <td><?= mb_strimwidth($livre["statut"], 0, 20, "..."); ?></td>
                        <td><?= mb_strimwidth($livre["date_ajout"], 0, 20, "..."); ?></td>
                        <td>
                            <?php if (!empty($livre['image'])): ?>
                                <img src="<?= $livre['image'] ?>"
                                    alt="Image du livre <?= htmlspecialchars($livre['titre']); ?>"
                                    class="img-fluid"
                                    style="max-width: 80px;">
                            <?php else: ?>
                                <span class="text-muted">Aucune image disponible pour ce livre</span>
                            <?php endif; ?>
                        </td>
                        <td><?= mb_strimwidth($livre["description"], 0, 50, "..."); ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="index.php?p=detailLivre&idLivre=<?= $livre["id_livre"] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                                <a href="index.php?p=administrateur/gererLivres/modifierLivres&idLivre=<?= $livre["id_livre"] ?>" class="btn btn-sm btn-outline-warning">Modifier</a>
                                <a href="index.php?p=administrateur/gererLivres/supprimerLivres&idLivre=<?= $livre["id_livre"] ?>" class="btn btn-sm btn-outline-danger">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Bouton pour ajouter un nouveau livre  -->
        <div class="text-center">
            <a href="index.php?p=administrateur/gererLivres/ajouterLivres" class="btn btn-primary">Ajouter un livre</a>
        </div>
    </div>
<?php else: ?>
    <!-- Si il n'y a pas de livres  -->
    <div class="alert alert-warning text-center" role="alert">
        Vous n'avez actuellement aucun livre.
    </div>
    <!-- Bouton pour ajouter des nouveaux livres -->
    <div class="text-center">
        <a href="index.php?p=administrateur/gererLivres/ajouterLivres" class="btn btn-primary">Ajouter un livre</a>
    </div>
<?php endif; ?>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Gérer les livres"; // Titre de la page
require("./view/template/templateAccueil.php"); // Inclut le template
?>