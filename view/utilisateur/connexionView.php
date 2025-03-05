<?php ob_start(); // Démarre l'enregistrement de la page 
?>
<!-- Grand titre de la page -->
<div class="container text-center my-5">
    <h1>Connexion à votre compte</h1>
    <p class="lead">Veuillez entrer vos informations pour vous connecter.</p>
</div>
<!-- Formulaire de Connexion -->
<form action="index.php?p=connexion" method="post">
    <div class="container col-md-6">
        <!-- Affichage de l'erreur générale de connexion (si elle existe) -->
        <?php if (isset($erreurs['connexion'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($erreurs['connexion']); ?></div>
        <?php endif; ?>
        <!-- Affichage des erreurs liées à l'emprunt d'un livre -->
        <?php if (isset($_SESSION['emprunt'])): ?>
            <div class="alert alert-warning">
                <?php
                echo htmlspecialchars($_SESSION['emprunt']);
                // Supprimer l'erreur après affichage
                unset($_SESSION['emprunt']);
                ?>
            </div>
        <?php endif; ?>
        <!-- Champ pour l'email -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php echo isset($erreurs['email']) ? 'is-invalid' : ''; ?>" name="email" id="email" placeholder="Entrez votre email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
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

        <input type="submit" class="btn btn-dark rounded-3 w-100 py-2" value="Connexion" name="bouton">
    </div>
</form>

<?php
$contenue = ob_get_clean(); // Copie le contenu enregistré dans la variable $contenue
$titrePage = "Connexion";
require("./view/template/templateAccueil.php");
?>