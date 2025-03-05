<?php ob_start(); // Démarre l'enregistrement de la page 
?>
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
                        <img src="<?= URL_ASSETS; ?>images/image.jpeg" class="card-img-top" alt="Image du livre">
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
    

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Accueil";
require("./view/template/templateAccueil.php");
?>