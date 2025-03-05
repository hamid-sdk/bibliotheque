<?php
// cette page est dedié a toutes les fonctions qui concernent les utilisateurs
require_once("./model/utilisateurModel.php");
//Cette fonction permet  de differencier la vue d'un utilisateur connecter a celui d'un utilisateur non connecté dans l'accueil
function accueilPage()
{
    // je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    // cette fonction permet d'afficher quelques  livres presents dans la bdd
    $livres = afficheLivres(false);
    //Cette fonction permet d'afficher quelques nouveaux livres dans la bdd
    $nouveauxlivres = afficheNouveauxLivres();
    //Cette fonction permet d'afficher les categories de livres 
    $categories = afficheCategoriesLivres();
    require("./view/accueilView.php");
}
// cette fonction est une fonction pour l'inscription de chaque utilisateur 
function inscriptionPage()
{
    // si l'utilisateur est connecté il ne peut pas accéder à la page d'inscription 
    if (isloggedIn()) {
        // Rediriger l'utilisateur vers une autre page (accueil, profil, etc.)
        header("Location: index.php?p=accueil");  // 
        exit();  // Toujours mettre un exit après une redirection pour s'assurer que le script s'arrête
    }
    // si l'utilisateur est connecté il ne peux pas acceder a la page inscription 
    if (isset($_POST["bouton"])) {
        $nom = $_POST["nom"];
        $email = $_POST["email"];
        $mot_de_passe = $_POST["mot_de_passe"];
        $confirm_mot_de_passe = $_POST["confirm_mot_de_passe"];

        // je mets toutes les verifications des champs dans une variable $erreur
        $erreurs = VerifInscriptionChamp($nom, $email, $mot_de_passe, $confirm_mot_de_passe);
        if (empty($erreurs)) {
            inscriptionInserer($nom, $email, $mot_de_passe);
            // je redirige vers la page connexion une fois que la personne est connectée 
            header("location:index.php?p=connexion");
        } else {
            // si des erreurs sont présentes je les affiche dans la page inscription 
            require("./view/utilisateur/inscriptionView.php");
        }
    } else {
        require("./view/utilisateur/inscriptionView.php");
    }
}
// cette fonction verifie toutes les champs de lapage inscription pour voir si elles sont correctes
function VerifInscriptionChamp($nom, $email, $mot_de_passe, $confirm_mot_de_passe): array
{
    // j'initialise le tableau par un tableau vide 
    $erreurs = [];
    // Vérification du nom
    if (empty($nom)) {
        $erreurs['nom'] = "Veuillez entrer votre nom";
    }
    // Vérification de l'email
    if (empty($email)) {
        $erreurs['email'] = "Veuillez entrer votre email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "L'email n'est pas valide";
    } elseif (VerifUtilisateurExiste($email)) {
        $erreurs['email'] = "Cet email est déjà utilisé";
    }

    // Vérification du mot de passe
    if (empty($mot_de_passe)) {
        $erreurs['mot_de_passe'] = "Veuillez entrer un mot de passe";
    } elseif (strlen($mot_de_passe) < 8) {
        $erreurs['mot_de_passe'] = "Le mot de passe doit contenir au moins 8 caractères";
    } elseif (!preg_match('/[A-Z]/', $mot_de_passe)) {
        $erreurs['mot_de_passe'] = "Le mot de passe doit contenir au moins une majuscule";
    } elseif (!preg_match('/[a-z]/', $mot_de_passe)) {
        $erreurs['mot_de_passe'] = "Le mot de passe doit contenir au moins une minuscule";
    } elseif (!preg_match('/[0-9]/', $mot_de_passe)) {
        $erreurs['mot_de_passe'] = "Le mot de passe doit contenir au moins un chiffre";
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $mot_de_passe)) {
        $erreurs['mot_de_passe'] = "Le mot de passe doit contenir au moins un caractère spécial (par exemple: !@#$%^&*)";
    }
    // Vérification de la confirmation du mot de passe
    if (empty($confirm_mot_de_passe)) {
        $erreurs['confirm_mot_de_passe'] = "Veuillez confirmer votre mot de passe";
    } elseif ($mot_de_passe !== $confirm_mot_de_passe) {
        $erreurs['confirm_mot_de_passe'] = "Les mots de passe ne correspondent pas";
    }
    return $erreurs;
}
// Cette fonction est une fonction pour la connexion de chaque utilisateur 
function connexionPage()
{
    // si l'utilisateur est connecté il ne peut pas accéder à la page de connexion 
    if (isloggedIn()) {
        // Rediriger l'utilisateur vers une autre page (accueil, profil, etc.)
        header("Location: index.php?p=accueil");  // 
        exit();  // Toujours mettre un exit après une redirection pour s'assurer que le script s'arrête
    }
    if (isset($_POST["bouton"])) {


        $email = $_POST["email"];
        $mot_de_passe = $_POST['mot_de_passe'];
        // je verifie les champs de la page connexion(syntaxe et format) avant d'inserer en base de donnée
        $erreurs = verifConnexionChamp($email, $mot_de_passe);
        if (empty($erreurs)) {

            // j'utilise maintenant la fonction qui permet de verifier si l'utilisateur existe dans la base de donnée 
            $utilisateur = VerifUtilisateurExiste($email);
            // si l'utilisateur existe en base de donnée 
            if ($utilisateur) {
                // je compare le mot de passe entrer dans le champ avec celui hasher présent dans la base de donnée
                if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
                    $_SESSION["utilisateur"] = $utilisateur;
                    $userId = $_SESSION["utilisateur"]["id_utilisateur"];
                    //Mon but est d'afficher une fois connecter le nombre exacte de livre réserver et emprunter dans le header(debut)
                    // si il s'agit de l'administrateur j'affiche le nombre de l'ensemble des emprunts et des réservations 
                    if ($userId == 7) {
                        // Récupérer le total des livres reserver  
                        $livresReserves = afficheTousLesReservations();
                        // Récupérer le totaldes livres empruntés 
                        $livresEmpruntes = afficheTousLesEmprunts();
                    } else {
                        // Récupérer la liste des livres reserver par l'utilisateur 
                        $livresReserves = afficheLivreReserver($userId);
                        // Récupérer la liste des livres empruntés par l'utilisateur
                        $livresEmpruntes = afficheLivreEmprunter($userId);
                    }
                        // Récupérer le total des messages 
                    $messages = afficheMessagesUtilisateurs($userId);
                    if ($livresEmpruntes) {
                        // je compte le nombre de livre qu'il a emprunter pour l'afficher dans le header 
                        $_SESSION["nombreEmprunts"] = count($livresEmpruntes);
                    } else {
                        // si il n'a emprunter aucun livre alors 
                        $_SESSION["nombreEmprunts"] = 0;
                    }
                    if ($livresReserves) {
                        // je compte le nombre de livre qu'il a reserver pour l'afficher dans le header 
                        $_SESSION["nombreReserves"] = count($livresReserves);
                    } else {
                        // Sinon, le nomnbre de reservation ets egale à 0
                        $_SESSION["nombreReserves"] = 0;
                    }
                    if ($messages) {
                        // je compte le nombre de livre qu'il a reserver pour l'afficher dans le header 
                        $_SESSION["nombreMessages"] = count($messages);
                    } else {
                        // Sinon, le nomnbre de reservation ets egale à 0
                        $_SESSION["nombreMessages"] = 0;
                    }
                    //(fin)
                    // je le redirige vers la page principale du site 
                    header("location:index.php?p=accueil");
                } else {
                    $erreurs['connexion'] = "Identifiant ou mot de passe incorrect";
                    require("./view/utilisateur/connexionView.php");
                }
            } else {
                $erreurs['connexion'] = "Identifiant ou mot de passe incorrect";
                require("./view/utilisateur/connexionView.php");
            }
        } else {
            // sinon je le redirige vers la connexion en affichant les erreurs 
            require("./view/utilisateur/connexionView.php");
        }
    } else {
        require("./view/utilisateur/connexionView.php");
    }
}
// cette fonction permet de verifier si l'utilisateur est connecté ou pas 
function isloggedIn(): bool
{
    if (empty($_SESSION["utilisateur"])) {
        return false;
    } else {
        return true;
    }
}
// Cette fonction permet de verifier tous les champs de la page connexion 
function verifConnexionChamp($email, $mot_de_passe): array
{
    // j'initialise le tableau par un tableau vide 
    $erreurs = [];
    // Vérification de l'email
    if (empty($email)) {
        $erreurs['email'] = "Veuillez entrer votre email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "L'email n'est pas valide";
    }
    // Vérification du mot de passe
    if (empty($mot_de_passe)) {
        $erreurs['mot_de_passe'] = "Veuillez entrer votre mot de passe";
    }
    return $erreurs;
}

// cette fonction permet à l'utilisateur de se deconnecter en supprimant la session 
function deconnexionPage()
{
    if (isLoggedIn()) {
        unset($_SESSION['utilisateur']);
        session_destroy();
        header('Location:index.php?p=connexion');
    } else {
        header("Location:index.php?p=inscription");
    }
}
//Cette fonction permet de gerer les emprunts d'un utilisateur
function emprunterLivrePage()
{
    // Je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    //  si l'utilisateur n'est pas connecté il ne peux acceder a la page de reservation
    if (!$user) {
        $_SESSION['emprunt'] = "Veuillez vous connectez pour emprunter un livre";
        header("Location: index.php?p=connexion");
        exit;
    }
    // Vérifiez si un identifiant de livre a été envoyé
    if (isset($_POST["id_livre"])) {
        $id_livre = intval($_POST["id_livre"]);
        // je dois maintenant verifier si le livre selectionner est bien disponible dans la bdd
        $livre = verifLivreDisponible($id_livre);
        if ($livre) {
            $userId = $user['id_utilisateur'];
            // Si le livre est disponible, effectuez l'emprunt
            $empruntEffectue = effectuerEmprunt($userId, $id_livre);
            // Vérifier si l'emprunt a réussi (booléen retourné par effectuerEmprunt)
            if ($empruntEffectue) {
                // Message de succès et redirection
                $_SESSION['emprunt'] = "Le livre a été emprunté avec succès.";
                header("Location: index.php?p=emprunt");
                exit;
            } else {
                // Si l'emprunt a échoué
                $_SESSION['emprunt'] = "Une erreur est survenue lors de l'emprunt du livre.";
                header("Location: index.php?p=emprunt");
                exit;
            }
        } else {
            // Si le livre n'est pas disponible
            $_SESSION['emprunt'] = "Ce livre n'existe pas";
            header("Location: index.php?p=accueil");
            exit;
        }
    } else {
        // Si aucun identifiant n'est envoyé
        $_SESSION['emprunt'] = "Veuillez selectionnez un livre pour emprunter.";
        header("Location: index.php?p=accueil");
        exit;
    }
}
//  Cette fonction permet d'afficher tous les emprunts d'un utilisateur
function empruntPage()
{
    // Je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;

    // Si l'utilisateur n'est pas connecté, il ne peut pas accéder à ses emprunts 
    if (!$user) {
        $_SESSION['emprunt'] = "Veuillez vous connecter pour voir vos emprunts";
        header("Location: index.php?p=connexion");
        exit;
    }
    // Récupérer l'id de l'utilisateur
    $userId = $user['id_utilisateur'];

    // Si l'utilisateur est l'administrateur (id_utilisateur == 7)
    if ($userId == 7) {
        // Récupérer tous les emprunts avec les informations des utilisateurs
        $livresEmpruntes = afficheTousLesEmprunts();
    } else {
        // Sinon, récupérer les emprunts de l'utilisateur connecté
        $livresEmpruntes = afficheLivreEmprunter($userId);
    }
    // Si l'utilisateur a fait des emprunts, je les affiche
    if ($livresEmpruntes) {
        // je compte le nombre de livre qu'il a emprunter pour l'afficher dans le header 
        $_SESSION["nombreEmprunts"] = count($livresEmpruntes);
        // Passer cette liste à la vue
        require("./view/utilisateur/empruntView.php");
    } else {
        // si il n'a emprunter aucun livre alors 
        $_SESSION["nombreEmprunts"] = 0;
        // Sinon, je l'informe qu'il n'a aucun emprunt
        require("./view/utilisateur/empruntView.php");
    }
}
// cette fonction permet de retourner un livre emprunter par un utilisateur 
function retournerLivrePage()
{
    // Je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;

    // Si l'utilisateur n'est pas connecté, il ne peut pas accéder à la page de retour de livre
    if (!$user) {
        $_SESSION['emprunt'] = "Veuillez vous connecter pour retourner un livre.";
        header("Location: index.php?p=connexion");
        exit;
    }

    // Vérifiez si un identifiant de livre a été envoyé
    if (isset($_GET["idEmprunt"])) {
        $id_livre = intval($_GET["idEmprunt"]);
        $userId = $user['id_utilisateur'];

        // Vérifie si le livre appartient à l'utilisateur et a été emprunté
        $livreEmprunte = verifLivreEmprunteParUtilisateur($userId, $id_livre);

        if ($livreEmprunte) {
            // Si le livre appartient à l'utilisateur et a été emprunté, procéder à la suppression de l'emprunt
            $retourEffectue = effectuerRetour($userId, $id_livre);

            // Vérifie si le retour a réussi
            if ($retourEffectue) {
                $_SESSION['emprunt'] = "Le livre a été retourné avec succès.";
                header("Location: index.php?p=emprunt");
                exit;
            } else {
                // Si une erreur survient lors du retour du livre
                $_SESSION['emprunt'] = "Une erreur est survenue lors du retour du livre.";
                header("Location: index.php?p=emprunt");
                exit;
            }
        } else {
            // Si le livre n'est pas emprunté par l'utilisateur
            $_SESSION['emprunt'] = "Ce livre n'a pas été emprunté par vous.";
            header("Location: index.php?p=accueil");
            exit;
        }
    } else {
        // Si aucun identifiant de livre n'est envoyé
        $_SESSION['emprunt'] = "Veuillez sélectionner un livre à retourner.";
        header("Location: index.php?p=accueil");
        exit;
    }
}
// Cette fonction permet de reserver un livre deja emprunter 
function reserverLivrePage()
{
    // Je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
    //  si l'utilisateur n'est pas connecté il ne peux acceder a la page de reservation
    if (!$user) {
        $_SESSION['emprunt'] = "Veuillez vous connectez pour réserver un livre";
        header("Location: index.php?p=connexion");
        exit;
    }
    // Vérifiez si un identifiant de livre a été envoyé
    if (isset($_POST["id_livre"])) {
        // J'ai deja verifier que le livre n'est pas disponible dans la vue detailLivreView.php donc ici je n'ai pas besoin de verifier je mets directement l'id du livre dans une variable 
        $id_livre = intval($_POST["id_livre"]);
        $userId = $user['id_utilisateur'];
        // je veux maintenant eviter qu'un meme utilisateur puisse reserver deux fois un meme livre 
        if (verifSiUtilisateurPasDejaReserverCeLivre($userId, $id_livre)) {
            $_SESSION['reservation'] = "Vous avez déjà réservé ce livre.";
            header("Location: index.php?p=reservation");
            exit;
        }
        // je reserve maintenant le livre choisi 
        $reservationEffectuee = enregistrerReservation($userId, $id_livre);
        // Vérifie si la réservation a été correctement enregistrée
        if ($reservationEffectuee) {
            $_SESSION['reservation'] = "Le livre a été réservé avec succès.";
            header("Location: index.php?p=reservation");
            exit;
        } else {
            // Si la réservation a échoué
            $_SESSION['reservation'] = "Une erreur est survenue lors de la réservation du livre.";
            header("Location: index.php?p=reservation");
            exit;
        }
    } else {
        // Si aucun identifiant n'est envoyé
        $_SESSION['reservation'] = "Veuillez selectionnez un livre pour réserver.";
        header("Location: index.php?p=accueil");
        exit;
    }
}
// cette fonction permet d'afficher les livres reserve par un utilisateur 
function reservationPage()
{
    // Je mets la session dans une variable 
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;

    // Si l'utilisateur n'est pas connecté, il ne peut pas accéder à ses réservations 
    if (!$user) {
        $_SESSION['emprunt'] = "Veuillez vous connecter pour voir vos réservation";
        header("Location: index.php?p=connexion");
        exit;
    }
    // Récupérer l'id de l'utilisateur
    $userId = $user['id_utilisateur'];
    // Si l'utilisateur est l'administrateur (id_utilisateur == 7)
    if ($userId == 7) {
        // Récupérer tous les emprunts avec les informations des utilisateurs
        $livresReserves = afficheTousLesReservations();
    } else {
        // Sinon, récupérer les emprunts de l'utilisateur connecté
        $livresReserves =  afficheLivreReserver($userId);
    }

    // Si l'utilisateur a fait des réservations, je les affiche
    if ($livresReserves) {
        // je compte le nombre de livre qu'il a reserver pour l'afficher dans le header 
        $_SESSION["nombreReserves"] = count($livresReserves);
        // Passer cette liste à la vue
    } else {
        // Sinon, je l'informe qu'il n'a aucune réservation
        $_SESSION["nombreReserves"] = 0;
    }
    require("./view/utilisateur/reservationView.php");
}
// Cette fonction permet à un utilisateur d'annuler une réservation
function annulerReservationPage()
{

    // Récupérer la session utilisateur
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;

    // Vérifier si l'utilisateur est connecté
    if (!$user) {
        $_SESSION['annulation'] = "Veuillez vous connecter pour annuler une réservation.";
        header("Location: index.php?p=connexion");
        exit;
    }

    // Vérifier si un identifiant de réservation a été fourni
    if (isset($_GET["idReservation"])) {

        $id_reservation = intval($_GET["idReservation"]);
        $userId = $user['id_utilisateur'];

        // Vérifier si la réservation appartient à l'utilisateur
        $reservationValide = verifReservationParUtilisateur($userId, $id_reservation);

        if ($reservationValide) {

            // Si la réservation est valide, procéder à son annulation
            $annulationEffectuee = effectuerAnnulation($userId, $id_reservation);

            if ($annulationEffectuee) {
                // Si l'annulation est réussie
                $_SESSION['annulation'] = "La réservation a été annulée avec succès.";
                header("Location: index.php?p=reservation");
                exit;
            } else {
                // Si une erreur survient lors de l'annulation
                $_SESSION['annulation'] = "Une erreur est survenue lors de l'annulation de la réservation.";
                header("Location: index.php?p=reservation");
                exit;
            }
        } else {
            // Si la réservation n'appartient pas à l'utilisateur ou n'existe pas
            $_SESSION['annulation'] = "Réservation invalide ou inexistante.";
            header("Location: index.php?p=reservation");
            exit;
        }
    } else {
        // Si aucun identifiant de réservation n'est fourni
        $_SESSION['annulation'] = "Veuillez sélectionner une réservation à annuler.";
        header("Location: index.php?p=reservation");
        exit;
    }
}
//Cette fonction permet de modifier les informations d'un utilisateur 
function profilPage()
{
    // Récupérer la session utilisateur
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;

    // Vérifier si l'utilisateur est connecté
    if (!$user) {
        $_SESSION['annulation'] = "Veuillez vous connecter pour annuler une réservation.";
        header("Location: index.php?p=connexion");
        exit;
    }
    if (isset($_POST["bouton"])) {
        // Récupérer les nouvelles informations du formulaire
        $prenom = htmlspecialchars($_POST['prenom']);
        $nom = htmlspecialchars($_POST['nom']);
        $email = htmlspecialchars($_POST['email']);
        $userId = $user['id_utilisateur'];
        // Initialiser la variable $photoUrl
        $photoUrl = $user['photo']; // Garder l'ancienne photo par défaut
        // je vérifie maintenant les champs 
        $erreurs = verifModificationChamp($nom, $prenom, $email);
        // je veux verifier si l'email existe seulement si il est modifié
        if ($email !== $user["email"]) {
            // Vérifier si l'email existe seulement s'il est modifié
            if (VerifEmailExiste($email, $userId)) {
                $erreurs['email'] = "Cet email est déjà utilisé par un autre utilisateur.";
            }
        }
        // Gestion de la photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            // Dossier de stockage des images
            $dossierUploads = 'assets/images/';
            // Vérifier le type de fichier (uniquement .jpg, .jpeg, .png)
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                $erreurs['photo'] = "Le fichier doit être une image (jpg, jpeg, png).";
            } else {
                // Générer un nom unique pour l'image basé sur l'email (en remplaçant les caractères spéciaux)
                $photoNom = cleanEmail($email) . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                // Déplacer le fichier vers le dossier de destination
                $photoPath = $dossierUploads . $photoNom;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                    // L'URL de la photo sera basée sur la constante URL_ASSETS
                    $photoUrl = URL_ASSETS . 'images/' . $photoNom;
                } else {
                    $erreurs['photo'] = "Une erreur est survenue lors de la modification de la photo.";
                }
            }
        }
        if (empty($erreurs)) {
            $modifierProfil = modifierProfil($nom, $prenom, $userId, $photoUrl);
            if ($modifierProfil) {
                // Je mets à jour la session user de l'utilisateur connectée 
                $user["prenom"] = $prenom;
                $user["nom"] = $nom;
                $user["email"] = $email;
                $user["photo"] = $photoUrl;
                $_SESSION['success'] = "Vos informations ont été mises à jour avec succès.";
                // Mettre à jour la session utilisateur
                $_SESSION["utilisateur"] = $user;
            } else {
                $erreurs['modification'] = 'Nous n\'avons pas pu modifier vos informations';
            }
        } else {
            // si des erreurs sont présentes je les affiche dans la page profil 
            $erreurs['modification'] = "Une erreur est survenue lors de la mise à jour.";
        }
    }
    // j'affiche maintenant la vue du profil  
    require("./view/utilisateur/profilView.php");
}
// La fonction cleanEmail() va nettoyer l'email en remplaçant les caractères spéciaux, comme le @, le . ou autres, afin qu'il puisse être utilisé en toute sécurité dans un nom de fichier.
function cleanEmail($email)
{
    // Retirer les espaces inutiles et convertir en minuscules
    $email = strtolower(trim($email));

    // Remplacer les caractères spéciaux par des caractères valides pour un nom de fichier
    $email = str_replace('@', '_at_', $email);
    $email = str_replace('.', '_dot_', $email);

    // Retourner l'email nettoyé
    return $email;
}
//Cette fonction permet de faire toutes les verifications nécessaires pour la modification des informations d'un formulaire 
function verifModificationChamp($nom, $prenom, $email): array
{
    // j'initialise le tableau par un tableau vide 
    $erreurs = [];
    // Vérification du nom
    if (empty($nom)) {
        $erreurs['nom'] = "Veuillez entrer votre nom";
    }
    // Vérification du prenom
    if (empty($prenom)) {
        $erreurs['prenom'] = "Veuillez entrer votre prenom";
    }
    if (empty($email)) {
        $erreurs['email'] = "Veuillez entrer votre email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "Veuillez entrer un email valide";
    }
    return $erreurs;
}
// Cette fonction permet de gerer les messages 
function messagesPage()
{
    // Récupérer la session utilisateur
    $user = isloggedIn() ? $_SESSION["utilisateur"] : null;

    // Vérifier si l'utilisateur est connecté
    if (!$user) {
        $_SESSION['annulation'] = "Veuillez vous connecter pour accéder à vos messages.";
        header("Location: index.php?p=connexion");
        exit;
    }

    // ID de l'utilisateur connecté
    $userId = $user['id_utilisateur'];
    if ($userId != 7) {

        // Récupérer les emprunts avec une date d'échéance proche pour l'utilisateur connecter 
        $utilisateursEcheanceProche = afficheUtilisateurDateEcheanceProche($userId);

        if ($utilisateursEcheanceProche && count($utilisateursEcheanceProche) > 0) {
            foreach ($utilisateursEcheanceProche as $emprunt) {
                // Vérifie si les clés nécessaires sont disponibles
                if (isset($emprunt['id_utilisateur'], $emprunt['id_livre'], $emprunt['titre'], $emprunt['date_echeance'])) {
                    $id_emprunteur = $emprunt['id_utilisateur'];
                    $id_livre = $emprunt['id_livre'];
                    $titre_livre = $emprunt['titre'];
                    $date_echeance = $emprunt['date_echeance'];
                    $id_administrateur = 7; // ID de l'administrateur par défaut

                    // Format de la date d'échéance
                    $dateEcheanceFormattee = date('d-m-Y', strtotime($date_echeance));

                    // Message à envoyer
                    $message = "Bonjour,\n\nNous vous rappelons que votre emprunt pour le livre '$titre_livre' arrive à échéance le $dateEcheanceFormattee. Merci de le retourner rapidement.\n\nCordialement,\nL'Administrateur.";
                    // je verifie si le message a deja ete envoyer a la personne connecter pour ce meme livre avant d'executer la requette d'envoie 
                    if (!messageDejaEnvoye($id_emprunteur, $id_livre)) {
                        // Envoyer le message
                        envoyerMessageEcheance($id_emprunteur, $id_livre, $id_administrateur, $message);
                    }
                }
            }
        }
    }
    //  j'affiche maintenant les messages 
    $messages = afficheMessagesUtilisateurs($userId);
    // Si l'utilisateur ou l'administrateur a des messages je les comptent 
    if ($messages) {
        $_SESSION["nombreMessages"] = count($messages);
    } else {
        // Sinon, je l'informe qu'il n'a pas de messages
        $_SESSION["nombreMessages"] = 0;
    }
    // Passer les messages à la vue
    require('./view/utilisateur/messagesView.php');
}
