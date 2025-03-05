<?php ob_start(); // Démarre l'enregistrement de la page 
?>
<!-- cette partie permet d'afficher les erreurs au cours de l'emprunt d'un livre  -->
<?php if (isset($_SESSION['emprunt'])): ?>
    <div class="alert alert-info text-center" role="alert">
        <?= $_SESSION['emprunt']; ?>
    </div>
    <?php unset($_SESSION['emprunt']); // Supprime le message après l'affichage 
    ?>
<?php endif; ?>
<!-- cette partie permet d'afficher les erreurs au cours de la réservation d'un livre  -->
<?php if (isset($_SESSION['reservation'])): ?>
    <div class="alert alert-info text-center" role="alert">
        <?= $_SESSION['reservation']; ?>
    </div>
    <?php unset($_SESSION['reservation']); // Supprime le message après l'affichage 
    ?>
<?php endif; ?>
<!-- cette partie permet d'afficher les erreurs qui concernent l'administrateur  -->
<?php if (isset($_SESSION['annulation'])): ?>
    <div class="alert alert-info text-center" role="alert">
        <?= $_SESSION['annulation']; ?>
    </div>
    <?php unset($_SESSION['annulation']); // Supprime le message après l'affichage 
    ?>
<?php endif; ?>
<h1 class="text-center my-4">Bienvenue sur votre Bibliotheque</h1>
<!-- Titre de la page -->
<div class="container my-5">
    <h2 class="text-center display-4 fw-bold text-primary">Decouvrez notre collection de livres</h2>
    <p class="text-center text-muted fs-5">Découvrez notre sélection de livres en stock</p>
</div>
<!-- Section pour afficher quelques livres -->
<div class="container text-center">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
        <?php if (isset($livres)):
            foreach ($livres as $livre):
        ?>
                <div class="col">
                    <div class="card hover-zoom">
                        <img src="<?= $livre['image'] ?>" class="card-img-top" alt="Image du livre">
                        <div class="card-body">
                            <h5 class="card-title"> <?= mb_strimwidth($livre["titre"], 0, 20, "..."); ?></h5>
                            <p class="card-text">Auteur: <?= mb_strimwidth($livre["auteur"], 0, 20, "..."); ?></p>
                            <p class="card-text">Catégorie: <?= $livre["categorie"] ?></p>
                            <p class="card-text">Statut: <?= $livre["statut"] ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="index.php?p=detailLivre&idLivre=<?= $livre["id_livre"] ?>" class="btn btn-outline-primary w-100">Voir Détails</a>
                        </div>
                    </div>
                </div>

        <?php
            endforeach;
        endif;
        ?>
    </div>
    <!-- Bouton pour afficher le reste des livres -->
    <a href="index.php?p=livres" class="btn btn-primary w-100 mt-4">Afficher tous les livres</a>
</div>
<div class="container my-5">
    <h2 class="text-center display-4 fw-bold text-primary">Nouveaux Livres</h2>
    <p class="text-center text-muted fs-5">Les dernières nouveautés ajoutées à notre collection</p>
</div>
<!-- Section pour afficher quelques  nouveaux livres ajoutés -->
<div class="container text-center">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
        <?php if (isset($nouveauxlivres)):
            foreach ($nouveauxlivres as $nouveaulivre):
        ?>
                <div class="col">
                    <div class="card hover-zoom">
                        <img src="<?= URL_ASSETS . 'images/' . $nouveaulivre['image']; ?>" class="card-img-top" alt="Image du livre">
                        <div class="card-body">
                            <h5 class="card-title"> <?= mb_strimwidth($nouveaulivre["titre"], 0, 20, "..."); ?></h5>
                            <p class="card-text">Auteur: <?= mb_strimwidth($nouveaulivre["auteur"], 0, 20, "..."); ?></p>
                            <p class="card-text">Catégorie: <?= $nouveaulivre["categorie"] ?></p>
                            <p class="card-text">Statut: <?= $nouveaulivre["statut"] ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="index.php?p=detailLivre&idLivre=<?= $nouveaulivre["id_livre"] ?>" class="btn btn-outline-primary w-100">Voir Détails</a>
                        </div>
                    </div>
                </div>

        <?php
            endforeach;
        endif;
        ?>
    </div>
    <!-- Bouton pour afficher le reste des nouveaux livres -->
    <a href="index.php?p=nouveauxLivres" class="btn btn-primary w-100 mt-4">Afficher tous les nouveaux livres</a>
</div>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Accueil";
require("./view/template/templateAccueil.php");
?>