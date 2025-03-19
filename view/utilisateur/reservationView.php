<?php ob_start(); // Démarre l'enregistrement de la page 
// j'affiche les messages destinés a la réservation d'un livre 
if (isset($_SESSION['reservation'])): ?>
    <div class="alert alert-info text-center" role="alert">
        <?= $_SESSION['reservation']; ?>
    </div>
    <?php unset($_SESSION['reservation']); // Supprime le message après l'affichage 
    ?>
<?php endif;
// j'affiche les messages destinés a l'annulation d'une reservation 
if (isset($_SESSION['annulation'])): ?>
    <div class="alert alert-info text-center" role="alert">
        <?= $_SESSION['annulation']; ?>
    </div>
    <?php unset($_SESSION['annulation']); // Supprime le message après l'affichage 
    ?>
<?php endif; ?>



<h1 class="text-center my-4"><?= isset($user) && $user["role"] == 'admin' ? 'Tous les réservations' : 'Vos réservations' ?></h1>

<!-- Si l'utilisateur a des livres réservés -->
<?php if (!empty($livresReserves)): ?>
    <div class="container">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <?php if (isset($user) && $user["role"] == 'admin'): ?>
                        <!-- Affichage des colonnes spécifiques à l'administrateur -->
                        <th>Reserveur</th>
                        <th>Email</th>
                    <?php endif; ?>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Date de Réservation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livresReserves as $livre): ?>
                    <tr>
                        <?php if (isset($user) && $user["role"] == 'admin'): ?>

                            <!-- Si l'utilisateur est un administrateur, on affiche les informations de l'emprunteur -->
                            <td><?= $livre["nom"] . " " . $livre["prenom"]; ?></td>
                            <td><?= $livre["email"]; ?></td>
                        <?php endif; ?>
                        <td><?= mb_strimwidth($livre["titre"], 0, 30, "..."); ?></td>
                        <td><?= mb_strimwidth($livre["auteur"], 0, 20, "..."); ?></td>
                        <td><?= $livre["date_reservation"]; ?></td>
                        <td>
                            <a href="index.php?p=detailLivre&idLivre=<?= $livre["id_livre"] ?>" class="btn btn-sm btn-outline-primary">Voir Détails</a>
                            <?php if (isset($user) && $user["role"] != 'admin'): ?>
                                <a href="index.php?p=annulerReservation&idReservation=<?= $livre['id_reservation'] ?>" class="btn btn-sm btn-outline-danger">Annuler</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Bouton pour voir d'autres livres -->
        <div class="text-center">
            <a href="index.php?p=livres" class="btn btn-primary"><?= isset($user) && $user["role"] == 'admin' ? 'Voir les livres' : 'Voir notre collection de livres' ?></a>
        </div>
    </div>
<?php else: ?>
    <!-- Si aucun livre n'est réservé -->
    <div class="alert alert-warning text-center" role="alert">
        <?= isset($user) && $user["role"] == 'admin' ? 'Aucun utilisateur n\'a réservé de livre' : 'Vous n\'avez actuellement aucune réservation.' ?>
    </div>
    <!-- Bouton pour voir d'autres livres -->
    <div class="text-center">
        <a href="index.php?p=livres" class="btn btn-primary"><?= isset($user) && $user["role"] == 'admin' ? 'Voir d\'autres livres' : 'Voir notre collection de livres' ?></a>
    </div>

<?php endif; ?>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Mes Réservations"; // Le titre de la page
require("./view/template/templateAccueil.php"); // Inclure le template
?>