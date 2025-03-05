<?php ob_start(); // Démarre l'enregistrement de la page ?>
<h1 class="text-center display-4 fw-bold text-primary my-4">Tableau de Bord Administrateur</h1>

<!-- Fil d'Ariane (Breadcrumb) avec Bootstrap amélioré -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light p-3 rounded-3 shadow-sm">
    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-primary fw-bold">Accueil</a></li>
    <li class="breadcrumb-item active text-secondary" aria-current="page">Tableau de Bord Administrateur</li>
  </ol>
</nav>

<!-- Grille des boutons avec Bootstrap -->
<div class="container">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <!-- Bouton pour gérer les livres -->
        <div class="col">
            <a href="index.php?p=administrateur/gererLivres" class="btn btn-lg btn-primary w-100 p-4 text-white shadow-sm fw-bold">Gérer les Livres</a>
        </div>

        <!-- Bouton pour gérer les utilisateurs -->
        <div class="col">
            <a href="index.php?p=gestionUtilisateurs" class="btn btn-lg btn-primary w-100 p-4 text-white shadow-sm fw-bold">Gérer les Utilisateurs</a>
        </div>

        <!-- Bouton pour voir les statistiques -->
        <div class="col">
            <a href="index.php?p=statistiques" class="btn btn-lg btn-primary w-100 p-4 text-white shadow-sm fw-bold">Voir les Statistiques</a>
        </div>

        <!-- Bouton pour paramétrer le site -->
        <div class="col">
            <a href="index.php?p=parametresSite" class="btn btn-lg btn-primary w-100 p-4 text-white shadow-sm fw-bold">Paramètres du Site</a>
        </div>

        <!-- Bouton pour gérer les commandes -->
        <div class="col">
            <a href="index.php?p=gestionCommandes" class="btn btn-lg btn-primary w-100 p-4 text-white shadow-sm fw-bold">Gérer les Commandes</a>
        </div>

    </div>
</div>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Tableau de Bord Administrateur"; // Titre de la page
require("./view/template/templateAccueil.php"); // Inclut le template
?> 
