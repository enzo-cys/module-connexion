<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLogged = isset($_SESSION['user']);
$isAdmin  = $isLogged && $_SESSION['user']['login'] === 'admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Module Connexion</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="icon" type="image/png" href="assets/img/multipass.png">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <nav class="nav">
    <a class="brand" href="index.php">ModuleConnexion</a>
    <div class="nav-links">
      <a href="index.php">Accueil</a>
      <?php if (!$isLogged): ?>
        <a href="inscription.php">Inscription</a>
        <a href="connexion.php">Connexion</a>
      <?php else: ?>
        <a href="profil.php">Profil</a>
        <?php if ($isAdmin): ?><a href="admin.php">Administration</a><?php endif; ?>
        <a href="logout.php">Déconnexion</a>
      <?php endif; ?>
      <a target="_blank" rel="noopener" href="https://github.com/enzo-cys/module-connexion">Repo GitHub</a>
    </div>
  </nav>
</header>
<main class="container">