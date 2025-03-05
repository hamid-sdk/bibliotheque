<?php ob_start(); // Démarre l'enregistrement de la page 
?>

<!-- Section pour afficher les livres par catégorie -->
<?php if (isset($livresParCategorie)): ?>
    <?php foreach ($livresParCategorie as $categorie => $livres): ?>
        <div class="container my-5">
            <h3 class="text-center display-4 fw-bold text-primary"><?= $categorie ?></h3>
        </div>

        <!-- Section pour afficher les livres de la catégorie -->
        <div class="container text-center">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <?php foreach ($livres as $livre): ?>
                    <div class="col">
                        <div class="card hover-zoom">
                            <img src="<?= URL_ASSETS; ?>images/image.jpeg" class="card-img-top" alt="Image du livre">
                            <div class="card-body">
                                <h5 class="card-title"><?= mb_strimwidth($livre["titre"], 0, 20, "..."); ?></h5>
                                <p class="card-text">Auteur: <?= mb_strimwidth($livre["auteur"], 0, 20, "..."); ?></p>
                                <p class="card-text">Statut: <?= $livre["statut"] ?></p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="index.php?p=detailLivre&idLivre=<?= $livre["id_livre"] ?>" class="btn btn-outline-primary w-100">Voir Détails</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenu
$titrePage = "Catégories";
require("./view/template/templateAccueil.php");
?>