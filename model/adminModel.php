<!-- //Cette page est dédiée a toutes les requettes qui concernent l'administrateur  -->
<?php
// Cette fonction permet a l'administrateur  de modifier un livre 
function modifierLivre($id_livre, $titre, $auteur, $categorieNom, $isbn, $statut, $description, $imageUrl)
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
                image = :imageUrl
            WHERE id_livre = :id_livre');

        $pdoStatement->bindParam(':titre', $titre, PDO::PARAM_STR);
        $pdoStatement->bindParam(':auteur', $auteur, PDO::PARAM_STR);
        $pdoStatement->bindParam(':categorie', $categorieNom, PDO::PARAM_STR);
        $pdoStatement->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $pdoStatement->bindParam(':statut', $statut, PDO::PARAM_STR);
        $pdoStatement->bindParam(':description', $description, PDO::PARAM_STR);
        $pdoStatement->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);
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
function ajouterLivre($titre, $auteur, $categorieNom, $isbn, $statut, $description, $imageUrl)
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
                :imageUrl
            )');

        // Liaison des paramètres
        $pdoStatement->bindParam(':titre', $titre, PDO::PARAM_STR);
        $pdoStatement->bindParam(':auteur', $auteur, PDO::PARAM_STR);
        $pdoStatement->bindParam(':categorie', $categorieNom, PDO::PARAM_STR);
        $pdoStatement->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $pdoStatement->bindParam(':statut', $statut, PDO::PARAM_STR);
        $pdoStatement->bindParam(':description', $description, PDO::PARAM_STR);
        $pdoStatement->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);

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
