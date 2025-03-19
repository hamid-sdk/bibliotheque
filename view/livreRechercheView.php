<?php ob_start(); // Démarre l'enregistrement de la page ?>
<!-- Titre de la page -->
<div class="container my-5">
    <h2 class="text-center display-4 fw-bold text-primary">Résultats de la recherche</h2>
</div>

<!-- Section pour afficher les livres recherchés -->
<div class="container text-center">
    <?php if (isset($message)): ?>
        <!-- Si aucun livre n'est trouvé, afficher un message -->
        <div class="alert alert-warning" role="alert">
            <?php echo $message; ?>
        </div>
        <a href="index.php" class="btn btn-primary w-100 mt-4">Retour à l'accueil</a>
    <?php elseif (!empty($livreRecherche)): ?>
        <!-- Si des livres sont trouvés, les afficher -->
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            <?php foreach ($livreRecherche as $livre): ?>
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
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Si aucun livre n'est trouvé, afficher un message avec un bouton de retour -->
        <div class="alert alert-warning" role="alert">
            Aucun livre trouvé pour votre recherche.
        </div>
        <a href="index.php" class="btn btn-primary w-100 mt-4">Retour à l'accueil</a>
    <?php endif; ?>
</div>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Résultats de Recherche";
require("./view/template/templateAccueil.php");
?>
