<?php ob_start(); ?>
<h1 class="text-center my-4"><?= isset($user) && $user["role"] == 'admin'  ? 'Consulter les messages' : 'Mes messages' ?></h1>
<!-- Affichage des messages -->
<div class="container my-5">
    <?php if (!empty($messages)): ?>
        <!-- Affichage des messages reçus -->
        <div class="row">
            <?php foreach ($messages as $message): ?>
                <div class="col-12 mb-4">
                    <!-- Card pour chaque message -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <span class="font-weight-bold"><?= isset($user) && $user["role"] == 'admin' ? 'Envoyé à: ' . $message["nom"] . ' ' . $message["prenom"] : 'De: Hamid ADENLE' ?></span>
                            <span class="text-muted"><?= date('d/m/Y H:i', strtotime($message['date_envoi'])); ?></span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars($message['contenu'])); ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between ">
                            <a href="index.php?p=detailLivre&idLivre=<?= $message["id_livre"] ?>" class="btn btn-sm btn-outline-primary"> Détails</a>
                            <?php if (isset($user) && $user["role"] != 'admin'): ?>
                                <a href="index.php?p=retournerLivre&idEmprunt=<?= $message['id_livre'] ?>" class="btn btn-sm btn-outline-danger">Retourner</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Message si aucun message n'est disponible -->
        <div class="alert alert-info text-center" role="alert">
            Vous n'avez aucun message pour le moment.
        </div>
    <?php endif; ?>
</div>

<?php
$contenue = ob_get_clean();
$titrePage = "Mes Messages";
require("./view/template/templateAccueil.php");
?>