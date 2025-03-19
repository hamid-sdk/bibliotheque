<!-- Cette page a dedié a toutes les fonction qui concernent l'affichage des bases du site comme les livres,les categories etc... -->
<?php
require_once("./model/interfaceModel.php");
// cette page est dedie a une erreur dans le lien en gros une page 404
function page404()
{
    // je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    // je mets le role de l'utilisateur connecté dans une variable 
    $userRole = isset($user) ? $user["role"] : null;
    require("./view/404View.php");
}
// Cette fonction permet  permet d'afficher tous les livres dans la bdd
function livresPage()
{
    // je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    // je mets le role de l'utilisateur connecté dans une variable 
    $userRole = isset($user) ? $user["role"] : null;
    $livres = afficheLivres(true);
    require("./view/livresView.php");
}
function nouveauxLivresPage()
{
    // je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    // je mets le role de l'utilisateur connecté dans une variable 
    $userRole = isset($user) ? $user["role"] : null;
    $nouveauxlivres = afficheNouveauxLivres(true);
    require("./view/nouveauxLivresView.php");
}
function categoriesPage()
{
    // je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    // je mets le role de l'utilisateur connecté dans une variable 
    $userRole = isset($user) ? $user["role"] : null;
    $livresParCategorie = afficheLivresParCategorie();
    require("./view/livresParCategorieView.php");
}
function detailLivrePage()
{
    // je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    // je mets le role de l'utilisateur connecté dans une variable 
    $userRole = isset($user) ? $user["role"] : null;
    // Vérification si l'ID du livre est passé dans l'URL
    if (isset($_GET["idLivre"])) {
        $id_livre = $_GET["idLivre"];  // Récupération de l'ID du livre depuis l'URL
        // je verifie deja si le livre est emprunter ou pas par l'utilisateur actuelle
        $verifLivreEmprunter = verifLivreEmprunter($id_livre);
        // si le tableau n'est pas vide cela veut dire que le livre actuelle est emprunter par l'utilisateur actuelle donc je recupere l'id de cet utilisateur
        if (!empty($verifLivreEmprunter)) {
            $emprunteurDuLivre = $verifLivreEmprunter["id_utilisateur"];
        }
        $livre = afficheDetailLivre($id_livre);   // Appel à la fonction pour afficher les détails du livre
        // Vérification si le livre existe
        if (!empty($livre)) {
            // Passer les détails du livre à la vue
            require("./view/detailLivreView.php");
        } else {
            // Si le livre n'est pas trouvé, rediriger vers la page principale
            header("Location: index.php");
            exit;
        }
    } else {
        header("Location: index.php");  // Redirige vers la page principale des livres
        exit;  // Arrêter le script après la redirection
    }
}
function rechercheLivrePage()
{
    // Je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    // je mets le role de l'utilisateur connecté dans une variable 
    $userRole = isset($user) ? $user["role"] : null;

    // Je vérifie si le formulaire a été envoyé 
    if (isset($_POST["bouton"])) {
        // Je récupère l'entrée saisie par l'utilisateur
        $rechercheLivre = trim($_POST["rechercheLivre"]); // Supprime les espaces inutiles au début et à la fin

        // Vérification si le champ de recherche est vide
        if (empty($rechercheLivre)) {
            // Si le champ est vide, on prépare un message d'erreur
            $message = "Veuillez entrer un terme de recherche.";
            require("./view/livreRechercheView.php");
            return; // Arrêt de l'exécution de la fonction pour éviter les étapes suivantes
        }
        // J'appelle la fonction de recherche avec le terme saisi
        $livreRecherche = afficheLivreRecherche($rechercheLivre);

        // Vérification si des livres ont été trouvés
        if (empty($livreRecherche)) {
            // Aucun livre trouvé, on prépare un message à afficher
            $message = "Aucun livre trouvé pour votre recherche.";
        } else {
            $message = null; // Pas de message d'erreur si des livres sont trouvés
        }

        // Passer les résultats et le message à la vue
        require("./view/livreRechercheView.php");
    } else {
        // Redirection vers la page d'accueil si le formulaire n'a pas été soumis
        header("Location: index.php");
        exit;
    }
}


?>