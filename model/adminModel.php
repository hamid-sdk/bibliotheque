<!-- //Cette page est dédiée a toutes les requettes qui concernent l'administrateur  -->
<?php
// Cette fonction permet a l'administrateur  de modifier un livre 
function modifierLivre($id_livre, $titre, $auteur, $categorieNom, $isbn, $statut, $description, $image)
{
    try {
        // Se connecter à la base de données
        $pdo = dbConnect();

        // Préparer la requête pour mettre à jour le profil de l'utilisateur
        $pdoStatement = $pdo->prepare('UPDATE livres
            SET titre = :titre,
                auteur = :auteur,
                categorie = :categorie,
                isbn = :isbn,
                statut = :statut,
                description = :description,
                image = :image
            WHERE id_livre = :id_livre');

        $pdoStatement->bindParam(':titre', $titre, PDO::PARAM_STR);
        $pdoStatement->bindParam(':auteur', $auteur, PDO::PARAM_STR);
        $pdoStatement->bindParam(':categorie', $categorieNom, PDO::PARAM_STR);
        $pdoStatement->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $pdoStatement->bindParam(':statut', $statut, PDO::PARAM_STR);
        $pdoStatement->bindParam(':description', $description, PDO::PARAM_STR);
        $pdoStatement->bindParam(':image', $image, PDO::PARAM_STR);
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);

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
//Cette focntion permet a l'adminstrateur d'ajouter un livre dans la base de données
function ajouterLivre($titre, $auteur, $categorieNom, $isbn, $statut, $description, $image)
{
    try {
        // Se connecter à la base de données
        $pdo = dbConnect();

        // Préparer la requête pour insérer un nouveau livre
        $pdoStatement = $pdo->prepare('INSERT INTO livres (
                titre, 
                auteur, 
                categorie, 
                isbn, 
                statut, 
                description, 
                image
            ) VALUES (
                :titre, 
                :auteur, 
                :categorie, 
                :isbn, 
                :statut, 
                :description, 
                :image
            )');

        // Liaison des paramètres
        $pdoStatement->bindParam(':titre', $titre, PDO::PARAM_STR);
        $pdoStatement->bindParam(':auteur', $auteur, PDO::PARAM_STR);
        $pdoStatement->bindParam(':categorie', $categorieNom, PDO::PARAM_STR);
        $pdoStatement->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $pdoStatement->bindParam(':statut', $statut, PDO::PARAM_STR);
        $pdoStatement->bindParam(':description', $description, PDO::PARAM_STR);
        $pdoStatement->bindParam(':image', $image, PDO::PARAM_STR);

        // Exécution de la requête
        $insertionReussie = $pdoStatement->execute();

        // Retourner true si l'exécution a réussi, sinon false
        return $insertionReussie ? true : false;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
// Cette fonction permet a l'administrateur de supprimer un livre de la base de donnée 
function supprimerLivre($id_livre)
{
    try {
        // Se connecter à la base de données
        $pdo = dbConnect();

        // Préparer la requête pour supprimer un livre en fonction de son ID
        $pdoStatement = $pdo->prepare('DELETE FROM livres WHERE id_livre = :id_livre');

        // Liaison du paramètre :id_livre
        $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);

        // Exécution de la requête
        $suppressionReussie = $pdoStatement->execute();

        // Retourner true si la suppression a réussi, sinon false
        return $suppressionReussie ? true : false;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
// Cette focntion permet de recuperer tous les emprunts avec les informations se chaque utilisateur 
function afficheTousLesEmprunts(): array
{
    try {
        // Je me connecte à la base de données
        $pdo = dbConnect();

        // Récupérer tous les emprunts avec les informations des utilisateurs
        $pdoStatement = $pdo->prepare('SELECT l.id_livre, l.titre, l.auteur, e.date_emprunt, e.date_echeance, u.nom, u.prenom, u.email 
                                      FROM emprunts e 
                                      INNER JOIN livres l ON e.id_livre = l.id_livre 
                                      INNER JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
                                      WHERE e.statut = "actif"');
        // Exécuter la requête
        $pdoStatement->execute();

        // Récupérer tous les emprunts sous forme de tableau associatif
        $livresEmpruntes = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        // Retourner la liste des emprunts
        return $livresEmpruntes;
    } catch (PDOException $e) {
        // Gestion des erreurs : afficher l'erreur
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}
// Cette fonction permet de récupérer toutes les réservations avec les informations de chaque utilisateur
function afficheTousLesReservations(): array
{
    try {
        // Je me connecte à la base de données
        $pdo = dbConnect();

        // Récupérer toutes les réservations avec les informations des utilisateurs
        $pdoStatement = $pdo->prepare('SELECT l.id_livre, l.titre, l.auteur, r.*, u.nom, u.prenom, u.email 
                                      FROM reservations r 
                                      INNER JOIN livres l ON r.id_livre = l.id_livre 
                                      INNER JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur
                                      WHERE r.notification_envoyee = 0');
        // Exécuter la requête
        $pdoStatement->execute();

        // Récupérer toutes les réservations sous forme de tableau associatif
        $livresReserves = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        // Retourner la liste des réservations
        return $livresReserves;
    } catch (PDOException $e) {
        // Gestion des erreurs : afficher l'erreur
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}
// Cette fonction permet d'afficher tous les messages a l'administrateur 
// Cette fonction permet de récupérer tous les messages dans la base de données
function afficheTousLesMessages()
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();

        // Requête pour récupérer tous les messages
        $pdoStatement = $pdo->prepare('SELECT m.*, u.nom AS nom_utilisateur, l.titre AS titre_livre 
                                      FROM messages m
                                      INNER JOIN utilisateurs u ON m.id_utilisateur = u.id_utilisateur
                                      LEFT JOIN emprunts e ON m.id_emprunt = e.id_emprunt
                                      LEFT JOIN livres l ON e.id_livre = l.id_livre
                                      ORDER BY m.date_envoi DESC');
        $pdoStatement->execute();
        $messages = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        // Retourne tous les messages
        return $messages;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}

// Cette fonction permet d'afficher tous les utilisateurs sauf l'administrateur (avec rôle 'admin')
function afficheUtilisateurs(): array
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();

        // Requête pour récupérer tous les utilisateurs sauf ceux ayant le rôle 'admin'
        $pdoStatement = $pdo->prepare('SELECT * 
                                       FROM utilisateurs 
                                       WHERE role != :admin_role
                                       ORDER BY nom ASC');
        $pdoStatement->bindValue(':admin_role', 'admin', PDO::PARAM_STR);
        $pdoStatement->execute();

        // Récupérer tous les utilisateurs sous forme de tableau associatif
        $utilisateurs = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        // Retourne la liste des utilisateurs
        return $utilisateurs;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}

// Cette fonction permet de changer le role d'un utilisateur dans la base de données par l'administrateur 
function changerRoleUtilisateur(int $id_utilisateur, string $new_role): bool
{
    try {
        // Connexion à la base de données
        $pdo = dbConnect();

        // Requête pour mettre à jour le rôle d'un utilisateur
        $pdoStatement = $pdo->prepare('UPDATE utilisateurs 
                                       SET role = :new_role
                                       WHERE id_utilisateur = :id_utilisateur');
        $pdoStatement->bindValue(':new_role', $new_role, PDO::PARAM_STR);
        $pdoStatement->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $pdoStatement->execute();

        // Si aucune ligne n'a été mise à jour, c'est que l'utilisateur n'existe pas
        if ($pdoStatement->rowCount() > 0) {
            return true;
        } else {
            return false; // Aucune mise à jour effectuée (utilisateur non trouvé)
        }
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}


// Fonction pour vérifier les réservations ou emprunts de l'utilisateur
function aDesReservationsOuEmprunts($id_utilisateur)
{

    try {
        // Connexion à la base de données
        $pdo = dbConnect();
        // Vérification des réservations actives (notification_envoyee = 0 et date_reservation passée)
        $pdoStatement = $pdo->prepare("SELECT COUNT(*) 
                               FROM reservations 
                               WHERE id_utilisateur = :id_utilisateur 
                               AND notification_envoyee = 0 
                               AND date_reservation <= NOW()");
        $pdoStatement->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $pdoStatement->execute();
        $reservationsActives = $pdoStatement->fetchColumn();

        // Vérification des emprunts actifs (date_retour = NULL)
        $pdoStatement = $pdo->prepare("SELECT COUNT(*) 
                               FROM emprunts 
                               WHERE id_utilisateur = :id_utilisateur 
                               AND date_retour IS NULL");
        $pdoStatement->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $pdoStatement->execute();
        $empruntsActifs = $pdoStatement->fetchColumn();

        // Retourner vrai si l'utilisateur a des réservations ou emprunts actifs
        return ($reservationsActives > 0 || $empruntsActifs > 0);
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}

// Fonction pour supprimer l'utilisateur de la base de données
function supprimerUtilisateur($id_utilisateur)
{

    try {
        // Connexion à la base de données
        $pdo = dbConnect();
        // Suppression de l'utilisateur de la base de données
        $pdoStatement = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = :id_utilisateur");
        $pdoStatement->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        return $pdoStatement->execute();
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
