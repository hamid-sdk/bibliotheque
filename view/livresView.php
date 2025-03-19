<?php ob_start(); // Démarre l'enregistrement de la page 
?>
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
                        <img src="<?= URL_ASSETS . 'images/' . $livre['image']; ?>" class="card-img-top" alt="Image du livre">

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
<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Accueil";
require("./view/template/templateAccueil.php");
?>