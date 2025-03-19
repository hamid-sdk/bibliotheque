<?php
// j'ouvre une session pour passer les informations d'une page a l'autre
session_start();
define('URL_ASSETS', 'http://localhost/bibliotheque/assets/');
require_once('controleur/adminControleur.php');
require_once('controleur/utilisateurControleur.php');
require_once('controleur/interfaceControleur.php');
// Si je mets un parametre dans l'url alors tu diriges vers la page correspondante 
if (isset($_GET["p"])) {
    switch ($_GET["p"]) {
        case 'accueil';
            accueilPage();
            break;
        case 'inscription';
            inscriptionPage();
            break;
        case 'connexion';
            connexionPage();
            break;
        case 'deconnexion';
            deconnexionPage();
            break;
        case 'livres';
            livresPage();
            break;
        case 'nouveauxLivres';
            nouveauxLivresPage();
            break;
        case 'categories';
            categoriesPage();
            break;
        case 'detailLivre';
            detailLivrePage();
            break;
        case 'rechercheLivre';
            rechercheLivrePage();
            break;
        case 'emprunterLivre';
            emprunterLivrePage();
            break;
        case 'emprunt';
            empruntPage();
            break;
        case 'retournerLivre';
            retournerLivrePage();
            break;
        case 'reserverLivre';
            reserverLivrePage();
            break;
        case 'reservation';
            reservationPage();
            break;
        case 'annulerReservation';
            annulerReservationPage();
            break;
        case 'annulerReservation';
            annulerReservationPage();
            break;
        case 'profil';
            profilPage();
            break;
        case 'administrateur';
            accueilAdminPage();
            break;
        case 'administrateur/gererLivres';
            gererLivresPage();
            break;

        case 'administrateur/gererLivres/modifierLivres';
            modifierLivresPage();
            break;
        case 'administrateur/gererLivres/ajouterLivres';
            ajouterLivresPage();
            break;
        case 'administrateur/gererLivres/supprimerLivres';
            supprimerLivresPage();
            break;
        case 'administrateur/gererUtilisateurs';
            gererUtilisateursPage();
            break;
        case 'administrateur/gererUtilisateurs/desinscrireUtilisateurs';
            desinscrireUtilisateursPage();
            break;
        case 'messages';
            messagesPage();
            break;

        default:
            page404();
            break;
    }
} else {
    accueilPage();
}
