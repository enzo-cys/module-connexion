<?php
require_once __DIR__.'/includes/header.php';
?>
<section class="banner">
  <div class="banner-inner">
    <h1 class="banner-title">
      Leeloo Dallas <span>Multipass&nbsp;!</span>
    </h1>
    <p class="banner-text">
      Bienvenue sur le module de connexion. Ce site permet : inscription, connexion,
      modification du profil et espace d’administration.
    </p>

    <?php if (!isset($_SESSION['user'])): ?>
      <div class="banner-actions">
        <a class="btn" href="inscription.php">Créer un compte</a>
        <a class="btn secondary" href="connexion.php">Se connecter</a>
      </div>
    <?php else: ?>
      <div class="card success-lite glass-lite">
        Connecté en tant que <strong><?= htmlspecialchars($_SESSION['user']['login']); ?></strong>.
        <?php if ($_SESSION['user']['login'] === 'admin'): ?>
          (Administrateur)
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="features">
  <h2>Fonctionnalités</h2>
  <div class="feature-grid">
    <div class="feature"><h3>Inscription</h3><p>Créer un compte utilisateur.</p></div>
    <div class="feature"><h3>Connexion</h3><p>Accéder à votre espace.</p></div>
    <div class="feature"><h3>Profil</h3><p>Modifier vos informations.</p></div>
    <div class="feature"><h3>Administration</h3><p>Liste des utilisateurs (admin).</p></div>
  </div>
</section>

<section>
  <h2>Code source</h2>
  <p><a class="btn" target="_blank" rel="noopener" href="https://github.com/enzo-cys/module-connexion">Voir le dépôt</a></p>
</section>

<?php
require_once __DIR__.'/includes/footer.php';