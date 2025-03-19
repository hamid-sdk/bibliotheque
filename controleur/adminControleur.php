<?php
// cette page est dediée a toute les fonctions qui concernent uniquement l'administrateur
require_once("./model/adminModel.php");
//Cette fonction permet d'afficher tous les changement que peut faire un administrateur 
function accueilAdminPage()
{
   // Récupérer la session utilisateur
   $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
   // je mets le role de l'utilisateur connecté dans une variable 
   $userRole = isset($user) ? $user["role"] : null;
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   if (!$user || $user['role'] !== 'admin') {
      $_SESSION['annulation'] = "Vous devez être administrateur pour accéder à cette page.";
      header("Location: index.php"); // Rediriger vers la page de connexion
      exit;
   }
   require("./view/administrateur/accueilAdmin.php");
}
//Cette fonction permet d'afficher tous les livres disponibles afi que l'administrateur puisse faire des ajouts,des modifs,ou des suppression
function gererLivresPage()
{
   // Récupérer la session utilisateur
   $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
   $userRole = isset($user) ? $user["role"] : null;
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   if (!$user || $user['role'] !== 'admin') {
      $_SESSION['annulation'] = "Vous devez être administrateur pour accéder à cette page.";
      header("Location: index.php");
      exit;
   }
   // Cette fonction retourne tous les livres dans ma base de donnée 
   $livres = afficheLivres(true);
   require("./view/administrateur/gererLivres/accueilgererLivres.php");
}

//Cette fonction permet d'afficher tous les utilisateurs disponibles afin que l'administrateur puisse faire des ajouts,des modifs,ou des suppression
function gererUtilisateursPage()
{
   // Récupérer la session utilisateur
   $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
   $userRole = isset($user) ? $user["role"] : null;
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   if (!$user || $user['role'] !== 'admin') {
      $_SESSION['annulation'] = "Vous devez être administrateur pour accéder à cette page.";
      header("Location: index.php");
      exit;
   }
   // Cette fonction retourne tous les livres dans ma base de donnée 
   $utilisateurs = afficheUtilisateurs();
   require("./view/administrateur/gererUtilisateurs/accueilgererUtilisateurs.php");
}

//Cette fonction permet de modifier un livre 
function modifierLivresPage()
{
   // Vérifier si l'utilisateur est connecté
   $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
   $userRole = isset($user) ? $user["role"] : null;
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   if (!$user || $user['role'] !== 'admin') {
      $_SESSION['annulation'] = "Vous devez être administrateur pour accéder à cette page.";
      header("Location: index.php");
      exit;
   }

   // Vérifier si un ID de livre est fourni
   if (!isset($_GET['idLivre']) || empty($_GET['idLivre'])) {
      $_SESSION['erreur'] = "Aucun livre sélectionné pour modification.";
      header("Location: index.php?p=administrateur/gererLivres");
      exit;
   }
   $id_livre = intval($_GET['idLivre']);
   // je recupere les informations du livre concernée 
   $livre = afficheDetailLivre($id_livre);
   if (!$livre) {
      $_SESSION['erreur'] = "Livre introuvable.";
      header("Location: index.php?p=administrateur/gererLivres");
      exit;
   }
   $categories = afficheCategorie(); // Récupérer les catégories disponibles
   // si la personne appuie sur le bouton de modifications alors 
   if (isset($_POST["bouton"])) {
      // je recupere les informations rentrer dans le formulaire 
      $titre = htmlspecialchars(trim($_POST['titre']));
      $auteur = htmlspecialchars(trim($_POST['auteur']));
      $categorieId = isset($_POST['categorie']) ? htmlspecialchars(trim($_POST['categorie'])) : $livre['categorie'];
      $isbn = htmlspecialchars(trim($_POST['isbn']));
      // Vérification si le livre est déjà emprunté
      $livreEmprunter = verifLivreEmprunter($id_livre); // Vérifie si le livre est emprunté ou réservé
      $statutActuel = $livre['statut']; // Récupérer le statut actuel du livre

      // Si le livre est emprunté, on ne permet pas de changer le statut
      if ($livreEmprunter) {
         $statut = $statutActuel; // Ne pas changer le statut
         // je veux afficher ce message uniquement si le livre a été modifier si c'est pas le cas ca ne sert a rien d'afficher ce message 
         if ($_POST['statut'] !== $livre['statut']) {
            $_SESSION["erreur"] = "Le livre a été modifié, mais le statut n'a pas été changé car il est déjà emprunté.";
         }
      } else {
         $statut = htmlspecialchars(trim($_POST['statut'])); // Si le livre n'est pas emprunté, on prend le statut du formulaire
      }
      $description = htmlspecialchars(trim($_POST['description']));
      // Initialiser la variable $imageUrl
      $imageUrl = $livre['image']; // Garder l'ancienne image par défaut

      // Vérification des champs
      $erreurs = verifLivreChamp($titre, $auteur, $categorieId, $isbn, $statut, $description);
      // Récupérer le nom de la catégorie à partir de son ID
      $categorieNom = '';
      if (!empty($categorieId)) {
         $categorie = afficheCategorie($categorieId); // Fonction pour récupérer la catégorie par ID
         if ($categorie) {
            $categorieNom = $categorie[0]['nom_categorie']; // Nom de la catégorie
         } else {
            $erreurs['categorie'] = "Catégorie invalide.";
         }
      }
      // Gestion de l'image
      if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
         // Dossier de stockage des images
         $dossierUploads = 'assets/images/';
         // Vérifier le type de fichier (uniquement .jpg, .jpeg, .png)
         $allowedExtensions = ['jpg', 'jpeg', 'png'];
         $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

         if (!in_array($fileExtension, $allowedExtensions)) {
            $erreurs['image'] = "Le fichier doit être une image (jpg, jpeg, png).";
         } else {
            // Générer un nom unique pour l'image
            $imageNom = cleanTitreLivre($titre) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // Déplacer le fichier vers le dossier de destination
            $imagePath = $dossierUploads . $imageNom;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
               // L'URL de l'image
               $image = $imageNom;
            } else {
               $erreurs['image'] = "Une erreur est survenue lors de la modification de l'image.";
            }
         }
      }
      if (empty($erreurs)) {
         // Mise à jour du livre dans la base de données
         $modifierLivre = modifierLivre($id_livre, $titre, $auteur, $categorieNom, $isbn, $statut, $description, $image);
         if ($modifierLivre) {
            $_SESSION['success'] = "Le livre a été modifié avec succès.";
            header("Location: index.php?p=administrateur/gererLivres");
            exit;
         } else {
            $erreurs['modification'] = "Nous n\'avons pas pu modifier les informations du livre.";
         }
      } else {
         // si des erreurs sont présentes je les affiche dans la page profil 
         $erreurs['modification'] = "Une erreur est survenue lors de la mise à jour.";
      }
   }
   // Charger la vue pour modifier le livre
   require("./view/administrateur/gererLivres/modifLivre.php");
}
//Cette fonction permet d'ajouter un livre 
function ajouterLivresPage()
{
   // Vérifier si l'utilisateur est connecté
   $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
   $userRole = isset($user) ? $user["role"] : null;
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   if (!$user || $user['role'] !== 'admin') {
      $_SESSION['annulation'] = "Vous devez être administrateur pour accéder à cette page.";
      header("Location: index.php");
      exit;
   }
   $imageUrl = '';
   $categories = afficheCategorie(); // Récupère les catégories
   // Si l'utilisateur soumet le formulaire
   if (isset($_POST["bouton"])) {
      // Récupérer les données du formulaire
      $titre = htmlspecialchars(trim($_POST['titre']));
      $auteur = htmlspecialchars(trim($_POST['auteur']));
      $categorieId = isset($_POST['categorie']) ? htmlspecialchars(trim($_POST['categorie'])) : '';
      $isbn = htmlspecialchars(trim($_POST['isbn']));
      $statut = isset($_POST['statut']) ? htmlspecialchars(trim($_POST['statut'])) : '';
      $description = htmlspecialchars(trim($_POST['description']));
      // Vérification des champs
      $erreurs = verifLivreChamp($titre, $auteur, $categorieId, $isbn, $statut, $description);
      // Récupérer le nom de la catégorie à partir de son ID
      $categorieNom = '';
      if (!empty($categorieId)) {
         $categorie = afficheCategorie($categorieId); // Fonction pour récupérer la catégorie par ID
         if ($categorie) {
            $categorieNom = $categorie[0]['nom_categorie']; // Nom de la catégorie
         } else {
            $erreurs['categorie'] = "Catégorie invalide.";
         }
      }
      // Gestion de l'image
      if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
         // Dossier de stockage des images
         $dossierUploads = 'assets/images/';
         // Vérifier le type de fichier (uniquement .jpg, .jpeg, .png)
         $allowedExtensions = ['jpg', 'jpeg', 'png'];
         $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

         if (!in_array($fileExtension, $allowedExtensions)) {
            $erreurs['image'] = "Le fichier doit être une image (jpg, jpeg, png).";
         } else {
            // Générer un nom unique pour l'image
            $imageNom = cleanTitreLivre($titre) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // Déplacer le fichier vers le dossier de destination
            $imagePath = $dossierUploads . $imageNom;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
               // L'URL de l'image
               $image = $imageNom;
            } else {
               $erreurs['image'] = "Une erreur est survenue lors de l'ajout de l'image.";
            }
         }
      }
      if (empty($erreurs)) {
         // Ajouter le livre dans la base de données
         $ajoutLivre = ajouterLivre($titre, $auteur, $categorieNom, $isbn, $statut, $description, $image);
         if ($ajoutLivre) {
            $_SESSION['success'] = "Le livre a été ajouté avec succès.";
            header("Location: index.php?p=administrateur/gererLivres");
            exit;
         } else {
            $erreurs['ajout'] = "Une erreur est survenue lors de l'ajout du livre.";
         }
      }
   }

   require("./view/administrateur/gererLivres/ajoutLivre.php");
}
// Fonction pour nettoyer le titre du livre et le rendre valide pour un nom de fichier
function cleanTitreLivre($titre)
{
   // Retirer les espaces inutiles et convertir en minuscules
   $titre = strtolower(trim($titre));

   // Remplacer les caractères spéciaux par des caractères valides pour un nom de fichier
   $titre = preg_replace('/[^a-z0-9]+/', '_', $titre); // Remplacer tous les caractères non alphanumériques par des underscores

   // Retourner le titre nettoyé
   return $titre;
}
// Cette fonction permet de verifier les champs de modification et aussi de l'ajout 
function verifLivreChamp($titre, $auteur, $categorieId, $isbn, $statut, $description): array
{
   // j'initialise le tableau par un tableau vide 
   $erreurs = [];
   // Validation des champs
   if (empty($titre)) {
      $erreurs['titre'] = "Le titre est obligatoire.";
   }
   if (empty($auteur)) {
      $erreurs['auteur'] = "L'auteur est obligatoire.";
   }
   if (empty($categorieId)) {
      $erreurs['categorie'] = "La catégorie est obligatoire.";
   }
   if (empty($isbn)) {
      $erreurs['isbn'] = "L'ISBN est obligatoire.";
   } elseif (!preg_match('/^\d{10}(\d{3})?$/', $isbn)) {
      $erreurs['isbn'] = "L'ISBN doit être valide (10 ou 13 chiffres).";
   }
   if (empty($statut)) {
      $erreurs['statut'] = "Le statut est obligatoire.";
   }
   if (empty($description)) {
      $erreurs['description'] = "La description est obligatoire.";
   }
   return $erreurs;
}
// Cette fonction permet à l'administrateur de supprimer un livre 
function supprimerLivresPage()
{
   // Récupérer la session utilisateur
   $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
   $userRole = isset($user) ? $user["role"] : null;
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   // Vérifier si l'utilisateur est connecté et si c'est l'administrateur 
   if (!$user || $user['role'] !== 'admin') {
      $_SESSION['annulation'] = "Vous devez être administrateur pour accéder à cette page.";
      header("Location: index.php"); // Rediriger vers la page d'accueil ou de connexion
      exit;
   }

   // Vérifier si un ID de livre est fourni dans l'URL
   if (!isset($_GET['idLivre']) || empty($_GET['idLivre'])) {
      $_SESSION['erreur'] = "Aucun livre sélectionné pour suppression.";
      header("Location: index.php?p=administrateur/gererLivres"); // Rediriger vers la gestion des livres
      exit;
   }

   // Récupérer l'ID du livre à supprimer
   $id_livre = intval($_GET['idLivre']);

   // Vérifier si le livre existe dans la base de données
   $livre = afficheDetailLivre($id_livre);
   if (!$livre) {
      $_SESSION['erreur'] = "Livre introuvable.";
      header("Location: index.php?p=administrateur/gererLivres"); // Rediriger vers la gestion des livres
      exit;
   }

   // Vérifier si le livre est emprunté avant de le supprimer
   $livreEmprunter = verifLivreEmprunter($id_livre);
   if ($livreEmprunter) {
      $_SESSION['erreur'] = "Impossible de supprimer ce livre, il est actuellement emprunté.";
      header("Location: index.php?p=administrateur/gererLivres"); // Rediriger vers la gestion des livres
      exit;
   }

   // Supprimer le livre de la base de données
   $suppressionLivre = supprimerLivre($id_livre);
   if ($suppressionLivre) {
      $_SESSION['success'] = "Le livre a été supprimé avec succès.";
   } else {
      $_SESSION['erreur'] = "Une erreur est survenue lors de la suppression du livre.";
   }

   // Rediriger vers la page de gestion des livres après la suppression
   header("Location: index.php?p=administrateur/gererLivres");
   exit;
}


//Cette fonction permet a l'admin  de désinscrire un utilisateur 
function desinscrireUtilisateursPage() {
   // Vérifier si l'utilisateur est bien connecté et qu'il a le rôle d'administrateur
   $user = isloggedIn() ? $_SESSION["utilisateur"] : null;
   $userRole = isset($user) ? $user["role"] : null;
   
   if (!$user || $user['role'] !== 'admin') {
       $_SESSION['annulation'] = "Vous devez être administrateur pour effectuer cette action.";
       header("Location: index.php");
       exit;
   }

   // Vérifier si l'ID de l'utilisateur est bien passé en paramètre
   if (!isset($_GET['id']) || empty($_GET['id'])) {
       $_SESSION['erreur'] = "Aucun utilisateur sélectionné pour la désinscription.";
       header("Location: index.php?p=administrateur/gererUtilisateurs");
       exit;
   }

   // Récupérer l'ID de l'utilisateur à désinscrire
   $id_utilisateur = intval($_GET['id']);

   // Vérifier si l'utilisateur a des réservations ou emprunts en cours
   if (aDesReservationsOuEmprunts($id_utilisateur)) {
       $_SESSION['erreur'] = "L'utilisateur a des réservations ou emprunts en cours. Impossible de le désinscrire.";
       header("Location: index.php?p=administrateur/gererUtilisateurs");
       exit;
   }

   // Appeler la fonction pour supprimer l'utilisateur de la base de données
   $utilisateurSupprime = supprimerUtilisateur($id_utilisateur);

   if ($utilisateurSupprime) {
       $_SESSION['success'] = "L'utilisateur a été désinscrit avec succès.";
   } else {
       $_SESSION['erreur'] = "Une erreur est survenue lors de la désinscription de l'utilisateur.";
   }

   // Rediriger vers la page de gestion des utilisateurs
   header("Location: index.php?p=administrateur/gererUtilisateurs");
   exit;
}