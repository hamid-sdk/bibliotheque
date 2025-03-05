<?php ob_start(); // Démarre l'enregistrement de la page 
if (isset($_SESSION['emprunt'])): ?>
    <div class="alert alert-info text-center" role="alert">
        <?= $_SESSION['emprunt']; ?>
    </div>
    <?php unset($_SESSION['emprunt']); // Supprime le message après l'affichage 
    ?>
<?php endif; ?>



<h1 class="text-center my-4"><?= isset($user) && $user["id_utilisateur"] == 7 ? 'Tous les emprunts' : 'Vos emprunts' ?></h1>

<!-- Si l'utilisateur a des livres empruntés -->
<?php if (!empty($livresEmpruntes)): ?>
    <div class="container">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <?php if (isset($user) && $user["id_utilisateur"] == 7): ?>
                        <!-- Affichage des colonnes spécifiques à l'administrateur -->
                        <th>Emprunteur</th>
                        <th>Email</th>
                    <?php endif; ?>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Date d'Emprunt</th>
                    <th>Date d'Échéance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livresEmpruntes as $livre): ?>
                    <tr>
                        <?php if (isset($user) && $user["id_utilisateur"] == 7): ?>
                            <!-- Si l'utilisateur est un administrateur, on affiche les informations de l'emprunteur -->
                            <td><?= $livre["nom"] . " " . $livre["prenom"]; ?></td>
                            <td><?= $livre["email"]; ?></td>
                        <?php endif; ?>
                        <td><?= mb_strimwidth($livre["titre"], 0, 30, "..."); ?></td>
                        <td><?= mb_strimwidth($livre["auteur"], 0, 20, "..."); ?></td>
                        <td><?= $livre["date_emprunt"]; ?></td>
                        <td><?= $livre["date_echeance"]; ?></td>
                        <td>
                            <a href="index.php?p=detailLivre&idLivre=<?= $livre["id_livre"] ?>" class="btn btn-sm btn-outline-primary"> Détails</a>
                            <?php if (isset($user) && $user["id_utilisateur"] != 7): ?>
                                <a href="index.php?p=retournerLivre&idEmprunt=<?= $livre['id_livre'] ?>" class="btn btn-sm btn-outline-danger">Retourner</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Bouton pour emprunter un livre -->
        <div class="text-center">
            <a href="index.php?p=livres" class="btn btn-primary"><?= isset($user) && $user["id_utilisateur"] == 7 ? 'Voir d\'autres livres' : 'Emprunterun autre livre' ?></a>
        </div>
    </div>
<?php else: ?>
    <!-- Si aucun livre n'est emprunté -->
    <div class="alert alert-warning text-center" role="alert">
    <?= isset($user) && $user["id_utilisateur"] == 7 ? 'Aucun utilisateur n\'a emprunté de livre' : 'Vous n\'avez actuellement aucun emprunt.' ?>
    </div>
    <!-- Bouton pour emprunter un livre -->
    <div class="text-center">
        <a href="index.php?p=livres" class="btn btn-primary"><?= isset($user) && $user["id_utilisateur"] == 7 ? 'Voir d\'autres livres' : 'Emprunter un autre livre' ?></a>
    </div>

<?php endif; ?>



<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Mes Emprunts"; // Le titre de la page
require("./view/template/templateAccueil.php"); // Inclure le template
?>