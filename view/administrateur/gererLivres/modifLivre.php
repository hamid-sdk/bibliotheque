<?php ob_start(); // Démarre l'enregistrement de la page 
?>
<h1 class="text-center display-4 fw-bold text-primary my-4">Modifier un livre</h1>

<!-- Fil d'Ariane (Breadcrumb) avec Bootstrap amélioré -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-3 rounded-3 shadow-sm">
        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-primary fw-bold">Accueil</a></li>
        <li class="breadcrumb-item"><a href="index.php?p=administrateur" class="text-decoration-none text-primary fw-bold">Tableau de Bord Administrateur</a></li>
        <li class="breadcrumb-item"><a href="index.php?p=administrateur/gererLivres" class="text-decoration-none text-primary fw-bold">Gerer les livres</a></li>
        <li class="breadcrumb-item active text-secondary" aria-current="page">Modifier un livre </li>
    </ol>
</nav>

<!-- Formulaire de modification -->
<form action="index.php?p=administrateur/gererLivres/modifierLivres&idLivre=<?php echo htmlspecialchars($livre['id_livre']); ?>" method="post" enctype="multipart/form-data">
    <div class="container col-md-6">
        <!-- Champ pour le titre -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php echo isset($erreurs['titre']) ? 'is-invalid' : ''; ?>" name="titre" id="titre" placeholder="Titre du livre" value="<?php echo isset($livre['titre']) ? htmlspecialchars($livre['titre']) : ''; ?>">
            <label for="titre" class="form-label">Titre</label>
            <?php if (isset($erreurs['titre'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['titre']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour l'auteur -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php echo isset($erreurs['auteur']) ? 'is-invalid' : ''; ?>" name="auteur" id="auteur" placeholder="Auteur du livre" value="<?php echo isset($livre['auteur']) ? htmlspecialchars($livre['auteur']) : ''; ?>">
            <label for="auteur" class="form-label">Auteur</label>
            <?php if (isset($erreurs['auteur'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['auteur']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour la catégorie -->
        <div class="form-floating mb-3">
            <select class="form-select <?php echo isset($erreurs['categorie']) ? 'is-invalid' : ''; ?>" name="categorie" id="categorie">
                <option value="" disabled>Choisir une catégorie</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?php echo htmlspecialchars($categorie['id_categorie']); ?>"
                        <?php echo (isset($livre['categorie']) && $livre['categorie'] == $categorie['id_categorie']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categorie['nom_categorie']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="categorie" class="form-label">Catégorie</label>
            <?php if (isset($erreurs['categorie'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['categorie']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour l'ISBN -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php echo isset($erreurs['isbn']) ? 'is-invalid' : ''; ?>" name="isbn" id="isbn" placeholder="ISBN du livre" value="<?php echo isset($livre['isbn']) ? htmlspecialchars($livre['isbn']) : ''; ?>">
            <label for="isbn" class="form-label">ISBN</label>
            <?php if (isset($erreurs['isbn'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['isbn']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour le statut -->
        <div class="form-floating mb-3">
            <select class="form-select <?php echo isset($erreurs['statut']) ? 'is-invalid' : ''; ?>" name="statut" id="statut">
                <option value="disponible" <?php echo isset($livre['statut']) && $livre['statut'] === 'disponible' ? 'selected' : ''; ?>>Disponible</option>
                <option value="emprunte" <?php echo isset($livre['statut']) && $livre['statut'] === 'emprunte' ? 'selected' : ''; ?>>Indisponible</option>
            </select>
            <label for="statut" class="form-label">Statut</label>
            <?php if (isset($erreurs['statut'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['statut']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour la description -->
        <div class="form-floating mb-3">
            <textarea class="form-control <?php echo isset($erreurs['description']) ? 'is-invalid' : ''; ?>" name="description" id="description" placeholder="Description du livre" style="height: 120px;"><?php echo isset($livre['description']) ? htmlspecialchars($livre['description']) : ''; ?></textarea>
            <label for="description" class="form-label">Description</label>
            <?php if (isset($erreurs['description'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['description']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour l'image -->
        <div class="mb-3">
            <label for="image" class="form-label">Image du livre</label>
            <input type="file" class="form-control <?php echo isset($erreurs['image']) ? 'is-invalid' : ''; ?>" name="image" id="image">
            <?php if (isset($erreurs['image'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['image']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Bouton de soumission -->
        <input type="submit" class="btn btn-dark rounded-3 w-100 py-2" value="Modifier le livre" name="bouton">
    </div>
</form>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Modifier un livre";
require("./view/template/templateAccueil.php");
?>