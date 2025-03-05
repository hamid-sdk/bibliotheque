<?php ob_start(); // Démarre l'enregistrement de la page ?>
<!-- Grand titre de la page -->
<div class="container text-center my-5">
    <h1>Inscription</h1>
    <p class="lead">Créez un compte pour accéder à nos services.</p>
</div>
<form action="index.php?p=inscription" method="post">
    <div class="container col-md-6">
        <!-- Si des erreurs existent lors de l'inscription, elles seront affichées sous chaque champ -->
        
        <!-- Champ pour le nom -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php echo isset($erreurs['nom']) ? 'is-invalid' : ''; ?>" name="nom" id="nom" placeholder="Entrez votre nom" value="<?php echo isset($nom) ? htmlspecialchars($nom) : ''; ?>">
            <label for="nom" class="form-label">Nom</label>
            <!-- Affichage de l'erreur pour le nom -->
            <?php if (isset($erreurs['nom'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['nom']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour l'email -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php echo isset($erreurs['email']) ? 'is-invalid' : ''; ?>" name="email" id="email" placeholder="Entrez votre mail" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            <label for="email" class="form-label">Email</label>
            <!-- Affichage de l'erreur pour l'email -->
            <?php if (isset($erreurs['email'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['email']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour le mot de passe -->
        <div class="form-floating mb-3">
            <input type="password" class="form-control <?php echo isset($erreurs['mot_de_passe']) ? 'is-invalid' : ''; ?>" name="mot_de_passe" id="mot_de_passe" placeholder="Entrez votre mot de passe">
            <label for="mot_de_passe" class="form-label">Mot de passe</label>
            <!-- Affichage de l'erreur pour le mot de passe -->
            <?php if (isset($erreurs['mot_de_passe'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['mot_de_passe']); ?></div>
            <?php endif; ?>
        </div>

        <!-- Champ pour confirmer le mot de passe -->
        <div class="form-floating mb-3">
            <input type="password" class="form-control <?php echo isset($erreurs['confirm_mot_de_passe']) ? 'is-invalid' : ''; ?>" name="confirm_mot_de_passe" id="confirm_mot_de_passe" placeholder="Veuillez confirmez votre mot de passe">
            <label for="confirm_mot_de_passe" class="form-label">Confirmation de votre mot de passe</label>
            <!-- Affichage de l'erreur pour la confirmation du mot de passe -->
            <?php if (isset($erreurs['confirm_mot_de_passe'])): ?>
                <div class="invalid-feedback"><?php echo htmlspecialchars($erreurs['confirm_mot_de_passe']); ?></div>
            <?php endif; ?>
        </div>

        <input type="submit" class="btn btn-dark rounded-3 w-100 py-2" value="Inscription" name="bouton">
    </div>
</form>
<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Inscription";
require("./view/template/templateAccueil.php");
?>
