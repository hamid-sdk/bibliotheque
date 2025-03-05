<?php
// cette page est dedier a toute les requettes en bdd qui concernent les utilsateurs 
// j'inclus dans cette page le dbModel.php
require("dbModel.php");
function inscriptionInserer($nom, $email, $mot_de_passe)
{
    try {
        // je me connecte a la base de donnée 
        $pdo = dbConnect();
        // je lance la requette d'insertion 
        $pdoStatement = $pdo->prepare('INSERT INTO utilisateurs (nom,email,mot_de_passe) VALUES (:nom,:email,:mot_de_passe)');
        $pdoStatement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $pdoStatement->bindParam(':email', $email, PDO::PARAM_STR);
        // je hashe le mot de passe
        $mot_de_passe_hasher = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $pdoStatement->bindParam(':mot_de_passe', $mot_de_passe_hasher, PDO::PARAM_STR);
        $pdoStatement->execute();
    } catch (PDOException $e) {
        // Gestion des erreurs : vous pouvez journaliser ou afficher une erreur personnalisée
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}
// cette fonction permet de retourner l'utilisateur si il existe dans la base de donnée(si l'utilisateur 'existe pas dans la base de donnée je retourne fase)
function VerifUtilisateurExiste($email)
{
    try {

        // je me connexte a la base de donnée 
        $pdo = dbConnect();
        // je lance maintenant ma requette 
        $pdoStatement = $pdo->prepare('SELECT * FROM utilisateurs WHERE email=:email');
        $pdoStatement->bindParam(':email', $email, PDO::PARAM_STR);
        // j'execute maintenant la requette 
        $pdoStatement->execute();
        $utilisateur = $pdoStatement->fetch();
        // si un utilisateur existe je retourne l'utilistauer sinon je retourne false
        return $utilisateur ? $utilisateur : false;
    } catch (PDOException $e) {
        // Gestion des erreurs : vous pouvez journaliser ou afficher une erreur personnalisée
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}

// Cette fonction permet de vérifier si un livre est disponible dans la base de données
function verifLivreDisponible($id_livre)
{
    try {
        // je me connecte à la base de donnée
        $pdo = dbConnect();

        // je prépare la requête pour vérifier si le livre est disponible
        $pdoStatement = $pdo->prepare('SELECT * FROM livres WHERE id_livre = :id_livre AND statut = "disponible"');
        // je lie le paramètre id_livre à la requête
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);

        // j'exécute la requête
        $pdoStatement->execute();

        // je récupère le résultat
        $livre = $pdoStatement->fetch();

        // si le livre est disponible, je retourne ses informations, sinon je retourne false
        return $livre ? $livre : false;
    } catch (PDOException $e) {
        // Gestion des erreurs : vous pouvez journaliser ou afficher une erreur personnalisée
        echo "Erreur : " . $e->getMessage();
        return false; // Retourner false en cas d'erreur
    }
}
// Cette fonction permet d'enregistrer l'emprunt du livre par un utilisateur
function  effectuerEmprunt($userId, $id_livre): bool
{
    try {
        // Je me connecte à la base de données
        $pdo = dbConnect();

        // Récupérer la date actuelle pour l'emprunt
        $date_emprunt = date('Y-m-d');

        // Définir la date d'échéance, par exemple 14 jours après la date d'emprunt
        $date_echeance = date('Y-m-d', strtotime('+14 days', strtotime($date_emprunt)));

        // La date de retour est laissée à NULL au moment de l'emprunt
        $date_retour = NULL;

        // Le statut est "actif" lors de l'emprunt
        $statut = 'actif';

        // Préparer la requête SQL pour insérer l'emprunt dans la base de données
        $pdoStatement = $pdo->prepare('INSERT INTO emprunts (id_utilisateur, id_livre, date_emprunt, date_retour, date_echeance, statut) 
                                      VALUES (:id_utilisateur, :id_livre, :date_emprunt, :date_retour, :date_echeance, :statut)');
        // Lier les paramètres
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->bindParam(':date_emprunt', $date_emprunt, PDO::PARAM_STR);
        $pdoStatement->bindParam(':date_retour', $date_retour, PDO::PARAM_STR);
        $pdoStatement->bindParam(':date_echeance', $date_echeance, PDO::PARAM_STR);
        $pdoStatement->bindParam(':statut', $statut, PDO::PARAM_STR);

        // Exécuter la requête
        $pdoStatement->execute();

        // Mettre à jour le statut du livre dans la table livres pour "emprunté"
        $pdoStatement = $pdo->prepare('UPDATE livres SET statut = "emprunté" WHERE id_livre = :id_livre');
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->execute();
        return true;
    } catch (PDOException $e) {
        // Gestion des erreurs : afficher l'erreur
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
// Cette fonction permet d'afficher les livres empruntés par un utilisateur
function afficheLivreEmprunter($userId): array
{
    try {
        // Je me connecte à la base de données
        $pdo = dbConnect();

        // Récupérer la liste des livres empruntés par l'utilisateur avec leur statut
        $pdoStatement = $pdo->prepare('SELECT l.id_livre, l.titre, l.auteur,e.date_emprunt, e.date_echeance 
                                      FROM emprunts e 
                                      INNER JOIN livres l ON e.id_livre = l.id_livre 
                                      WHERE e.id_utilisateur = :id_utilisateur AND e.statut = "actif"');
        // Lier le paramètre pour l'ID de l'utilisateur
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);

        // Exécuter la requête
        $pdoStatement->execute();

        // Récupérer tous les livres empruntés sous forme de tableau associatif
        $livresEmpruntes = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        // Retourner la liste des livres empruntés
        return $livresEmpruntes;
    } catch (PDOException $e) {
        // Gestion des erreurs : afficher l'erreur
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}
// Cette fonction permet de vérifier qu'un livre est bien emprunté par l'utilisateur spécifié et qu'il ne l'a pas retourner d'aurénavant 
function verifLivreEmprunteParUtilisateur($userId, $id_livre)
{
    try {
        // Je me connecte à la base de données
        $pdo = dbConnect();

        // Préparer la requête pour vérifier si le livre est emprunté par l'utilisateur
        $pdoStatement = $pdo->prepare('SELECT * FROM emprunts 
                                      WHERE id_utilisateur = :id_utilisateur 
                                      AND id_livre = :id_livre 
                                      AND statut = "actif"');
        // Lier les paramètres
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);

        // Exécuter la requête
        $pdoStatement->execute();

        // Récupérer le résultat
        $livreEmprunte = $pdoStatement->fetch();

        // Si le livre est emprunté par l'utilisateur, on retourne les informations, sinon on retourne false
        return $livreEmprunte ? $livreEmprunte : false;
    } catch (PDOException $e) {
        // Gestion des erreurs : afficher l'erreur
        echo "Erreur : " . $e->getMessage();
        return false; // Retourner false en cas d'erreur
    }
}
//Cette fonction permet de retourner le livre emprunter par un utilisateur 
function effectuerRetour($userId, $id_livre): bool
{
    try {
        // Je me connecte à la base de données
        $pdo = dbConnect();

        // Récupérer la date actuelle pour le retour
        $date_retour = date('Y-m-d');
        // Supprimer le message associé a l'emprunt d'un utilisateur dont la date d'echeance est proche 
        $pdoStatement = $pdo->prepare('DELETE FROM messages WHERE id_livre = :id_livre AND id_utilisateur = :id_utilisateur');
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);
        $pdoStatement->execute();

        // Mettre à jour le statut du livre pour "disponible"
        $pdoStatement = $pdo->prepare('UPDATE livres SET statut = "disponible" WHERE id_livre = :id_livre');
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->execute();

        // Mettre à jour le statut de l'emprunt dans la table emprunts pour "retourne"
        $pdoStatement = $pdo->prepare('UPDATE emprunts SET statut = "retourne", date_retour = :date_retour WHERE id_livre = :id_livre AND id_utilisateur = :id_utilisateur');
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);
        $pdoStatement->bindParam(':date_retour', $date_retour, PDO::PARAM_STR);
        $pdoStatement->execute();

        // Vérifier s'il y a des réservations en attente pour ce livre
        $pdoStatement = $pdo->prepare('SELECT * FROM reservations WHERE id_livre = :id_livre AND notification_envoyee = 0 ORDER BY date_reservation ASC LIMIT 1');
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->execute();
        // Vérifier s'il existe une réservation
        $reservation = $pdoStatement->fetch();

        if ($reservation) {
            // Si une réservation existe, on accorde l'emprunt à la première personne qui a réservé
            $userIdReservation = $reservation['id_utilisateur'];

            // On enregistre l'emprunt pour cette personne
            $date_emprunt = date('Y-m-d');
            $date_echeance = date('Y-m-d', strtotime('+14 days', strtotime($date_emprunt)));
            $statut = 'actif';

            // Préparer la requête pour insérer l'emprunt
            $pdoStatement = $pdo->prepare('INSERT INTO emprunts (id_utilisateur, id_livre, date_emprunt, date_retour, date_echeance, statut) 
                                          VALUES (:id_utilisateur, :id_livre, :date_emprunt, NULL, :date_echeance, :statut)');
            $pdoStatement->bindParam(':id_utilisateur', $userIdReservation, PDO::PARAM_INT);
            $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
            $pdoStatement->bindParam(':date_emprunt', $date_emprunt, PDO::PARAM_STR);
            $pdoStatement->bindParam(':date_echeance', $date_echeance, PDO::PARAM_STR);
            $pdoStatement->bindParam(':statut', $statut, PDO::PARAM_STR);
            $pdoStatement->execute();

            // Mettre à jour le statut du livre dans la table livres pour "emprunte"
            $pdoStatement = $pdo->prepare('UPDATE livres SET statut = "emprunte" WHERE id_livre = :id_livre');
            $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
            $pdoStatement->execute();

            // Mettre à jour la réservation pour marquer qu'elle a été convertie en emprunt
            $pdoStatement = $pdo->prepare('UPDATE reservations SET notification_envoyee = 1 WHERE id_livre = :id_livre AND id_utilisateur = :id_utilisateur');
            $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
            $pdoStatement->bindParam(':id_utilisateur', $userIdReservation, PDO::PARAM_INT);
            $pdoStatement->execute();
            // Envoyer un message à l'utilisateur qui avait reserver ce livre pour l'informer qu'il peut emprunter le livre
            // Récupérer le nom du livre à partir de la base de données
            $pdoStatement = $pdo->prepare('SELECT * FROM livres WHERE id_livre = :id_livre');
            $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
            $pdoStatement->execute();
            $livre = $pdoStatement->fetch(PDO::FETCH_ASSOC);
            // Vérifier si le livre existe et récupérer son nom
            if ($livre) {
                $nom_livre = $livre['titre'];

                // Envoyer un message à l'utilisateur qui avait réservé ce livre pour l'informer qu'il peut emprunter le livre
                $contenu_message = "Bonjour, le livre '$nom_livre' que vous avez réservé est maintenant disponible. Vous pouvez procéder à l'emprunt.";
                $pdoStatement = $pdo->prepare('INSERT INTO messages (id_utilisateur, id_livre, contenu, date_envoi, expediteur) 
                                  VALUES (:id_utilisateur, :id_livre, :contenu, NOW(), "Système")');
                $pdoStatement->bindParam(':id_utilisateur', $userIdReservation, PDO::PARAM_INT);
                $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
                $pdoStatement->bindParam(':contenu', $contenu_message, PDO::PARAM_STR);
                $pdoStatement->execute();
            } else {
                echo "mal";
                die();
            }
        }

        return true;
    } catch (PDOException $e) {
        // Gestion des erreurs : afficher l'erreur
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}

// cette fonction permet de reserver un livre deja emprunter pour que une fois le livre disponible on puisse l'emprunter a son tour 
function enregistrerReservation($userId, $id_livre)
{
    try {
        // Je me connecte à la base de données
        $pdo = dbConnect();

        // Récupérer la date actuelle pour la réservation
        $date_reservation = date('Y-m-d H:i:s');

        // Par défaut, la notification n'a pas encore été envoyée
        $notification_envoyee = 0;

        // Préparer la requête SQL pour insérer la réservation dans la base de données
        $pdoStatement = $pdo->prepare('INSERT INTO reservations (id_utilisateur, id_livre, date_reservation, notification_envoyee) 
                                      VALUES (:id_utilisateur, :id_livre, :date_reservation, :notification_envoyee)');

        // Lier les paramètres
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->bindParam(':date_reservation', $date_reservation, PDO::PARAM_STR);
        $pdoStatement->bindParam(':notification_envoyee', $notification_envoyee, PDO::PARAM_INT);

        // Exécuter la requête
        $pdoStatement->execute();

        return true;
    } catch (PDOException $e) {
        // Gestion des erreurs : afficher l'erreur
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
// Cette fonction permet d'afficher les livres reserver par un utilisateur mais uniquement ceux auquelles ils n'a pas encore recu de message
function afficheLivreReserver($userId): array
{
    try {
        // Je me connecte à la base de données
        $pdo = dbConnect();

        // Récupérer la liste des livres réservés par l'utilisateur
        $pdoStatement = $pdo->prepare('SELECT l.*, r.* 
                                      FROM reservations r 
                                      INNER JOIN livres l ON r.id_livre = l.id_livre 
                                      WHERE r.id_utilisateur = :id_utilisateur AND r.notification_envoyee = 0');
        // Lier le paramètre pour l'ID de l'utilisateur
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);

        // Exécuter la requête
        $pdoStatement->execute();

        // Récupérer tous les livres réservés sous forme de tableau associatif
        $livresReserves = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        // Retourner la liste des livres réservés
        return $livresReserves;
    } catch (PDOException $e) {
        // Gestion des erreurs : afficher l'erreur
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}
// cette foction permet de verifier si l'utilisateur n'a pas encore reserver un livre spécifique 
function verifSiUtilisateurPasDejaReserverCeLivre($userId, $id_livre): bool
{
    try {
        // Se connecter à la base de données
        $pdo = dbConnect();

        // Préparer la requête pour vérifier si une réservation existe
        $pdoStatement = $pdo->prepare('SELECT * FROM reservations 
                                       WHERE id_utilisateur = :id_utilisateur 
                                       AND id_livre = :id_livre 
                                       AND notification_envoyee = 0');
        // Lier les paramètres
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);

        // Exécuter la requête
        $pdoStatement->execute();

        // Récupérer le résultat
        $reservation = $pdoStatement->fetch();

        // Retourner true si aucune réservation trouvée, sinon false

        return $reservation ? true : false;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
// Cette fonction permet de vérifier si une réservation existe pour un utilisateur donné et un livre spécifique
function verifReservationParUtilisateur($userId, $id_reservation)
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();

        // Préparer la requête pour vérifier l'existence de la réservation
        $pdoStatement = $pdo->prepare('SELECT * FROM reservations 
                                       WHERE id_utilisateur = :id_utilisateur 
                                       AND id_reservation = :id_reservation');
        // Liaison des paramètres
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);

        // Exécuter la requête
        $pdoStatement->execute();

        // Retourner le résultat : true si la réservation existe, false sinon
        $reservationValide = $pdoStatement->fetch();
        return $reservationValide ? $reservationValide : false;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
// Cette fonction permet d'annuler une réservation pour un utilisateur donné et un livre spécifique
function effectuerAnnulation($userId, $id_reservation)
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();

        // Préparer la requête pour supprimer la réservation
        $pdoStatement = $pdo->prepare('DELETE FROM reservations 
                                       WHERE id_reservation = :id_reservation 
                                       AND id_utilisateur = :id_utilisateur');
        // Liaison des paramètres
        $pdoStatement->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);

        // Exécuter la requête
        $pdoStatement->execute();

        // Retourner true si l'annulation a été effectuée avec succès
        return true;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
// Cette fonction permet de modifier les informations d'un utilisateur 
function modifierProfil($nom, $prenom, $userId, $photoUrl): bool
{
    try {
        // Se connecter à la base de données
        $pdo = dbConnect();

        // Préparer la requête pour mettre à jour le profil de l'utilisateur
        $pdoStatement = $pdo->prepare('UPDATE utilisateurs SET nom = :nom, prenom = :prenom, photo = :photo WHERE id_utilisateur = :id_utilisateur');

        $pdoStatement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $pdoStatement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $pdoStatement->bindParam(':photo', $photoUrl, PDO::PARAM_STR);
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);

        // Exécution de la requête
        $modificationreussie = $pdoStatement->execute();

        // Retourner true si l'exécution a réussi, sinon false
        return $modificationreussie ? true : false;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
//Cette fonction permet de verifier si lors de la modification d'un email par un utilisateur il ne soit pas encore utiliser par un autre
function verifEmailExiste($email, $userId)
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();

        // Requête pour vérifier si un autre utilisateur utilise cet email
        $pdoStatement = $pdo->prepare('SELECT id_utilisateur 
                                FROM utilisateurs 
                                WHERE email = :email AND id_utilisateur != :id_utilisateur');

        $pdoStatement->bindParam(':email', $email, PDO::PARAM_STR);
        $pdoStatement->bindParam(':id_utilisateur', $userId, PDO::PARAM_INT);
        $pdoStatement->execute();

        // Si un résultat est trouvé, l'email existe déjà
        return $pdoStatement->fetch() ? true : false;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
//Cette fonciton permet de recuperer les utilisateurs qui ont emprunter un livre mais ne l'ont pas encore retourner a temps alors que la date d'echeance est proche 
function afficheUtilisateurDateEcheanceProche($userId)
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();
        // Date actuelle + 3 jours
        $dateLimite = date('Y-m-d', strtotime('+3 days'));
        // Requête pour récupérer les utilisateurs dont la date d'échéance est proche (3 jours avant aujourd'hui)
        $pdoStatement = $pdo->prepare('SELECT u.*, e.*,l.*
            FROM emprunts e
            INNER JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            INNER JOIN livres l ON e.id_livre = l.id_livre
            WHERE e.date_echeance <= :dateLimite
            AND e.statut = "actif" AND e.id_utilisateur=:userId');

        $pdoStatement->bindParam(':dateLimite', $dateLimite, PDO::PARAM_STR);
        $pdoStatement->bindParam(':userId', $userId, PDO::PARAM_STR);
        $pdoStatement->execute();
        $utilisateurDateEcheanceProche = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        // je retourne les utilisateurs dont leur date d'echence est proche 
        return $utilisateurDateEcheanceProche;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
// Cette fonction permet d'envoyer un message automatisé de l'administrateur à un utilisateur lui rappelant de retourner un livre dont la date d'échéance est proche.
function envoyerMessageEcheance($id_emprunteur, $id_livre, $id_administrateur, $message): bool
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();
        $pdoStatement = $pdo->prepare('INSERT INTO messages (id_utilisateur, id_livre, contenu, date_envoi, expediteur) 
                 VALUES (:id_utilisateur, :id_livre, :contenu, NOW(), :expediteur)');
        $pdoStatement->bindParam(':id_utilisateur', $id_emprunteur, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->bindParam(':contenu', $message, PDO::PARAM_STR);
        $pdoStatement->bindParam(':expediteur', $id_administrateur, PDO::PARAM_INT);
        $messageEcheanceEnvoyer = $pdoStatement->execute();
        return $messageEcheanceEnvoyer ? true : false;  // Si les informations n'ont pas pu être récupérées
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
/**
 * Vérifie si un message a déjà été envoyé pour un utilisateur et un livre spécifiques.
 *
 * @param int $id_utilisateur ID de l'utilisateur.
 * @param int $id_livre ID du livre.
 * @return bool Retourne true si le message existe déjà, false sinon.
 */
function messageDejaEnvoye($id_utilisateur, $id_livre): bool
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();
        $pdoStatement = $pdo->prepare("SELECT COUNT(*) FROM messages 
                WHERE id_utilisateur = :id_utilisateur 
                AND id_livre = :id_livre");
        $pdoStatement->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
        $pdoStatement->execute();

        // Si un message existe déjà, renvoyer true
        return $pdoStatement->fetchColumn() > 0;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}


//Cette fonction permet d'afficher les messages d'un utilisateurs 
function afficheMessagesUtilisateurs($userId): array
{
    try {

        // Connexion à la base de données
        $pdo = dbConnect();
        if ($userId != 7) {
            // Requête afficher les messages d'un utilisateur 
            $pdoStatement = $pdo->prepare('SELECT * FROM messages WHERE id_utilisateur = :userId ORDER BY date_envoi DESC');
            $pdoStatement->bindParam(':userId', $userId, PDO::PARAM_INT);
        } else {
            // Requête afficher tous les messages a l'administrateur 
            $pdoStatement = $pdo->prepare('SELECT m.*,u.* FROM messages m LEFT JOIN utilisateurs u ON m.id_utilisateur = u.id_utilisateur ');
        }

        $pdoStatement->execute();
        $messages = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        // Si un résultat est trouvé, l'email existe déjà
        return $messages;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
