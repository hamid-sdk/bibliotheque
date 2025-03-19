<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bibliothèque - <?php echo $titrePage ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="<?= URL_ASSETS; ?>css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
  <header>
    <!-- Barre de Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top  navbar-dark bg-dark">
      <div class="container-fluid">
        <!-- Logo et nom du site -->
        <a class="navbar-brand" href="index.php?p=accueil">
          <img src="<?= URL_ASSETS; ?>images/logoBookAddict.png" alt="Logo" width="100" height="80" class="d-inline-block align-text-top">
        </a>

        <!-- Bouton pour écran réduit -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Liens de navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link <?= ($_GET['p'] ?? 'accueil') === 'accueil' ? 'active' : '' ?>" href="index.php?p=accueil">Accueil</a></li>
            <li class="nav-item"><a class="nav-link <?= ($_GET['p'] ?? 'livres') === 'livres' ? 'active' : '' ?>" href="index.php?p=livres">Livres</a></li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">Filtres</a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li>
                  <a class="dropdown-item <?= ($_GET['p'] ?? 'nouveauxLivres') === 'nouveauxLivres' ? 'active' : '' ?> " href="index.php?p=nouveauxLivres">Nouveautés</a>
                </li>
                <li>
                  <a class="dropdown-item <?= ($_GET['p'] ?? 'categories') === 'categories' ? 'active' : '' ?> " href="index.php?p=categories">Categories</a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- Barre de recherche -->
          <form class="d-flex me-3" role="search" action="index.php?p=rechercheLivre" method="post">
            <input class="form-control me-2" name="rechercheLivre" type="search" placeholder="Rechercher" aria-label="Rechercher">
            <button class="btn btn-outline-light" name="bouton" type="submit">Rechercher</button>
          </form>

          <!-- Options utilisateur -->
          <div class="d-flex flex-wrap gap-2 mt-1 mt-sm-0 justify-content-center justify-content-sm-end">
            <!-- si l'utilisateur n'est pas connecter il ne peux pas acceder a certaines options  -->
            <?php if (isset($user)): ?>
              <a href="index.php?p=emprunt" class="btn btn-warning position-relative">
                Emprunts
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $_SESSION["nombreEmprunts"] ?></span>
              </a>
              <a href="index.php?p=reservation" class="btn btn-warning position-relative">
                Réservations
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $_SESSION["nombreReserves"] ?></span>
              </a>
              <a href="index.php?p=messages" class="btn btn-warning position-relative">
                Messages
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $_SESSION["nombreMessages"] ?></span>
              </a>
            <?php endif; ?>
            <?php  if (isset($user) && $userRole == 'admin'):  ?>
              <a href="index.php?p=administrateur" class="btn btn-outline-primary">Dashboard</a>
            <?php endif; ?>

            <!-- si l'utilisateur est connecter normalement il doit voir le bouton profil et celui de la deconnexion -->
            <?php if (isset($user)): ?>
              <!-- Profil et déconnexion -->
              <a href="index.php?p=profil" class="btn btn-outline-primary">Profil</a>
              <a href="index.php?p=deconnexion" class="btn btn-outline-danger">Déconnexion</a>
            <?php else: ?>
              <!-- Connexion ou Inscription en fonction de la page actuelle -->
              <?php if (isset($_GET['p']) && $_GET['p'] === 'connexion'): ?>
                <!-- Afficher seulement le bouton Inscription -->
                <a href="index.php?p=inscription" class="btn btn-outline-primary">Inscription</a>
              <?php else: ?>
                <!-- Si l'utilisateur n'est pas sur la page de connexion ou d'inscription, afficher les deux -->
                <a href="index.php?p=connexion" class="btn btn-outline-primary">Connexion</a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main class="container  flex-grow-1" style="margin-top: 150px;margin-bottom: 50px;">
    <!-- Contenu principal -->
    <?php echo $contenue ?>
  </main>

  <footer class="bg-dark text-white text-center py-3 mt-5 mt-auto" >
    <p class="mb-0">&copy; 2024 Bibliothèque. Tous droits réservés.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>