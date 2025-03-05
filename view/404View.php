<?php ob_start(); // Démarre l'enregistrement de la page ?>
<!-- Page d'erreur 404 -->
<div class="container text-center my-5">
    <h1 class="display-1 text-danger">Erreur 404</h1>
    <p class="lead">La page que vous recherchez est introuvable.</p>
    <p>
        <a href="index.php?p=accueil" class="btn btn-dark btn-lg">Retour à l'accueil</a>
    </p>
</div>
<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Page 404";
require("./view/template/templateAccueil.php");
?>
