<?php ob_start(); // Démarre l'enregistrement de la page 
?>
<h1 class="text-center my-4">Détails du Livre</h1>

<!-- Conteneur principal -->
<div class="container my-5">
    <div class="row">
        <!-- Image du livre à gauche -->
        <div class="col-md-4 text-center">
            <img src="<?= URL_ASSETS . 'images/' . $livre['image']; ?>" class="img-fluid" alt="Image du livre">
        </div>

        <!-- Informations sur le livre à droite -->
        <div class="col-md-8">
            <h2 class="display-4"><?= htmlspecialchars($livre['titre']) ?></h2>
            <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
            <p><strong>Catégorie :</strong> <?= htmlspecialchars($livre['categorie']) ?></p>
            <p><strong>ISBN :</strong> <?= htmlspecialchars($livre['isbn']) ?></p>
            <p><strong>Statut :</strong> <?= htmlspecialchars($livre['statut']) ?></p>
            <p><strong>Date d'ajout :</strong> <?= htmlspecialchars($livre['date_ajout']) ?></p>
            <p><strong>Description :</strong></p>
            <p><?= nl2br(htmlspecialchars($livre['description'])) ?></p>
            <div class="text-center">
                <a href="index.php?p=livres" class="btn btn-primary">Retour à la liste des livres</a>
                <?php  if (isset($user) && $userRole !== 'admin'):  ?>
                    <!-- si le livre est disponible j'affiche le bouton pour emprunter  -->
                    <?php if ($livre['statut'] === "disponible"): ?>
                        <form action="index.php?p=emprunterLivre" method="post" style="display:inline;">
                            <input type="hidden" name="id_livre" value="<?= htmlspecialchars($livre['id_livre']); ?>">
                            <button type="submit" class="btn btn-success">Emprunter</button>
                        </form>
                        <!-- sinon si le livre n'est as disponible deux cas se présente:soit l'utilisateur actuelle est en possesion de ce livre dans ce cas il voit un message qui lui dit vous etes en posession du livre soit c'est un autre utilisateur et dans ce cas il voit reserver le livre  -->
                    <?php else: ?>
                        <!-- du coup si c'est l'utilisateur actuelle qui est en possesion du livre en question  -->
                        <?php if ($emprunteurDuLivre === $user["id_utilisateur"]): ?>
                            <a href="index.php?p=retournerLivre&idEmprunt=<?= $livre['id_livre'] ?>" class="btn btn-danger">Retourner</a>
                        <?php else: ?>
                            <form action="index.php?p=reserverLivre" method="post" style="display:inline;">
                                <input type="hidden" name="id_livre" value="<?= htmlspecialchars($livre['id_livre']); ?>">

                                <button type="submit" class="btn btn-success">Reservez</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Détails du Livre";
require("./view/template/templateAccueil.php");
?>