<?php ob_start(); ?>
<h1 class="text-center my-4">Profil Utilisateur</h1>

<?php if (isset($erreurs['modification'])): ?>
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <!-- Icône Bootstrap -->
        <div>
            <?= $erreurs['modification']; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="container">
    <div class="row justify-content-center">
        <!-- Section Profil -->
        <div class="col-md-4 mb-4">
            <div class="card shadow text-center">
                <div class="card-body d-flex flex-column align-items-center">
                    <?php if (!empty($user['photo'])): ?>
                        <img src="<?= $user['photo'] ?>"
                            alt="Photo de profil"
                            class="rounded-circle img-fluid mb-3"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <div class="d-flex justify-content-center align-items-center rounded-circle bg-primary text-white mb-3"
                            style="width: 150px; height: 150px; font-size: 2rem;">
                            <?= strtoupper($user['prenom'][0]) . strtoupper($user['nom'][0]); ?>
                        </div>
                    <?php endif; ?>
                    <h3 class="card-title"><?= htmlspecialchars($user['prenom'] . " " . $user['nom']); ?></h3>
                    <p class="text-muted"><?= htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
        </div>

        <!-- Section Informations -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?p=profil" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom"
                                value="<?= htmlspecialchars($user['nom']); ?>">
                            <?php if (isset($erreurs['nom'])): ?>
                                <div class="text-danger"><?= $erreurs['nom']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                value="<?= htmlspecialchars($user['prenom']); ?>">
                            <?php if (isset($erreurs['prenom'])): ?>
                                <div class="text-danger"><?= $erreurs['prenom']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($user['email']); ?>">
                            <?php if (isset($erreurs['email'])): ?>
                                <div class="text-danger"><?= $erreurs['email']; ?></div>
                            <?php endif; ?>
                        </div>  
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo de profil</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <?php if (isset($erreurs['photo'])): ?>
                                <div class="text-danger"><?= $erreurs['photo']; ?></div>
                            <?php endif; ?>
                        </div>


                        <button type="submit" name="bouton" class="btn btn-primary w-100">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$contenue = ob_get_clean();
$titrePage = "Profil Utilisateur";
require("./view/template/templateAccueil.php");
?>