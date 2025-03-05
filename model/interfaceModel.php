 <!-- cette page est dedié a tous les requettes qui concernent l'interface utilisateur c'est a dire tous le contenus du site -->
 <?php
    //Cette fonction permet d'afficher quelques livre ou tous les livres en fonction du paramettre rentrer 
    function afficheLivres($all = false): array
    {
        try {
            // Connexion à la base de données
            $pdo = dbConnect();
            // Si $all est vrai, on récupère tous les livres, sinon on limite à 10
            if ($all) {
                $pdoStatement = $pdo->prepare('SELECT * FROM livres ORDER BY titre');
            } else {
                $pdoStatement = $pdo->prepare('SELECT * FROM livres ORDER BY titre LIMIT 4');
            }

            $pdoStatement->execute();

            // Récupération des résultats sous forme de tableau associatif
            $livres = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

            return $livres;
        } catch (PDOException $e) {
            // Gestion des erreurs : vous pouvez journaliser ou afficher une erreur personnalisée
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    //Cette focntion permet d'afficher quelques ou tous les nouveaux livres en fonction du paramettre 
    function afficheNouveauxLivres($all = false): array
    {
        try {
            // Connexion à la base de données
            $pdo = dbConnect();

            // Préparation et exécution de la requête SQL pour récupérer les livres récents
            // Si $all est vrai, on récupère tous les nouveaux livres, sinon on limite à 10
            if ($all) {
                $pdoStatement = $pdo->prepare('SELECT * FROM livres WHERE date_ajout >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ORDER BY date_ajout DESC');
            } else {
                $pdoStatement = $pdo->prepare('SELECT * FROM livres WHERE date_ajout >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ORDER BY date_ajout DESC LIMIT 4');
            }
            $pdoStatement->execute();

            // Récupération des résultats sous forme de tableau associatif
            $nouveauxLivres = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

            return $nouveauxLivres;
        } catch (PDOException $e) {
            // Gestion des erreurs : vous pouvez journaliser ou afficher une erreur personnalisée
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    //Cette focntion permet 
    function afficheCategoriesLivres($all = false): array
    {
        try {
            // Connexion à la base de données
            $pdo = dbConnect();

            // Préparation et exécution de la requête SQL pour récupérer les catégories distinctes
            // Si $all est vrai, on récupère toutes les catégories, sinon on limite à 10
            if ($all) {
                $pdoStatement = $pdo->prepare('SELECT * FROM categories ORDER BY nom_categorie ASC');
            } else {
                $pdoStatement = $pdo->prepare('SELECT * FROM categories ORDER BY nom_categorie ASC LIMIT 4');
            }
            $pdoStatement->execute();

            // Récupération des résultats sous forme de tableau simple
            $categories = $pdoStatement->fetchAll(PDO::FETCH_COLUMN);

            return $categories;
        } catch (PDOException $e) {
            // Gestion des erreurs : vous pouvez journaliser ou afficher une erreur personnalisée
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    //Cette fonction permet d'afficher les livres par leur categorie 
    function afficheLivresParCategorie(): array
    {
        try {
            // Connexion à la base de données
            $pdo = dbConnect();

            // Préparation et exécution de la requête SQL
            $pdoStatement = $pdo->prepare('
            SELECT * 
            FROM livres 
            ORDER BY categorie ASC, titre ASC
        ');
            $pdoStatement->execute();

            // Organisation des résultats par catégorie
            $result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
            $livresParCategorie = [];

            foreach ($result as $row) {
                $categorie = $row['categorie'];
                // Ajouter toutes les informations du livre dans la catégorie correspondante
                $livresParCategorie[$categorie][] = $row;
            }

            return $livresParCategorie;
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    // Cette fonction permet d'afficher le detail de chaque livre 
    function afficheDetailLivre($id_livre): array
    {
        try {
            // Connexion à la base de données
            $pdo = dbConnect();

            // Préparation et exécution de la requête SQL pour récupérer un livre par son ID
            $pdoStatement = $pdo->prepare('SELECT * FROM livres WHERE id_livre = :id_livre');
            $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
            $pdoStatement->execute();

            // Récupération du résultat sous forme de tableau associatif
            $livre = $pdoStatement->fetch(PDO::FETCH_ASSOC);

            // Si le livre est trouvé, on le retourne
            if ($livre) {
                return $livre;
            } else {
                // Si le livre n'existe pas, retourner un tableau vide
                return [];
            }
        } catch (PDOException $e) {
            // Gestion des erreurs : vous pouvez journaliser ou afficher une erreur personnalisée
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    //Cette fonction permet de verifier si le livre actuelle est deja emprunter par l'utilisateur actuelle c'est a dire celui qui est en ligne 
    function verifLivreEmprunter($id_livre): array
    {
        try {
            // Connexion à la base de données
            $pdo = dbConnect();

            // Préparation et exécution de la requête SQL pour récupérer l'utilisateur actuelle 
            $pdoStatement = $pdo->prepare('SELECT * FROM emprunts WHERE statut = "actif" AND id_livre = :id_livre');
            $pdoStatement->bindParam(':id_livre', $id_livre, PDO::PARAM_INT);
            $pdoStatement->execute();

            // Récupération du résultat sous forme de tableau associatif
            $verifLivreEmprunter = $pdoStatement->fetch(PDO::FETCH_ASSOC);

            // Si le livre est trouvé, on le retourne
            if ($verifLivreEmprunter) {
                return $verifLivreEmprunter;
            } else {
                // Si le livre n'existe pas, retourner un tableau vide 
                return [];
            }
        } catch (PDOException $e) {
            // Gestion des erreurs : vous pouvez journaliser ou afficher une erreur personnalisée
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    // Cette fonction permet d'afficher les livres correspondant à une recherche de l'utilisateur
    function afficheLivreRecherche($rechercheLivre): array
    {
        try {
            // Connexion à la base de données
            $pdo = dbConnect();

            // Préparation et exécution de la requête SQL pour rechercher des livres en fonction du titre ou de la catégorie
            $pdoStatement = $pdo->prepare('
              SELECT * 
            FROM livres 
            WHERE titre LIKE :rechercheLivre 
            OR auteur LIKE :rechercheLivre 
            OR categorie LIKE :rechercheLivre
            ORDER BY titre
        ');

            // Ajout des paramètres de la recherche (le terme de recherche doit être entouré de % pour une recherche partielle)
            $pdoStatement->bindValue(':rechercheLivre', '%' . $rechercheLivre . '%', PDO::PARAM_STR);

            // Exécution de la requête
            $pdoStatement->execute();

            // Récupération des résultats sous forme de tableau associatif
            $livreRecherche = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

            return $livreRecherche;
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
    // Cette fonction permet de récupérer toutes les catégories ou une seule catégorie en fonction de l'ID
    function afficheCategorie($id = false): array
    {
        try {
            // Connexion à la base de données
            $pdo = dbConnect();

            // Si $id est fourni (différent de false), on récupère la catégorie correspondant à cet ID
            if ($id) {
                // Préparation de la requête pour récupérer une seule catégorie par son ID
                $pdoStatement = $pdo->prepare('SELECT * FROM categories WHERE id_categorie = :id');
                // Exécution de la requête avec l'ID en paramètre
                $pdoStatement->execute([':id' => $id]);
            } else {
                // Si aucun ID n'est fourni, on récupère toutes les catégories
                $pdoStatement = $pdo->prepare('SELECT * FROM categories');
                $pdoStatement->execute();
            }

            // Récupération des résultats sous forme de tableau associatif
            $categorie = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

            return $categorie;
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    ?>