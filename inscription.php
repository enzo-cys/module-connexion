<?php
require_once __DIR__.'/config/db.php';

$errors = [];
$redirect = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Démarrer la session si nécessaire pour le traitement POST
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $login  = trim($_POST['login'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $nom    = trim($_POST['nom'] ?? '');
    $pass   = $_POST['password'] ?? '';
    $pass2  = $_POST['password_confirm'] ?? '';

    // Validations
    if ($login === '' || $prenom === '' || $nom === '' || $pass === '' || $pass2 === '') {
        $errors[] = "Tous les champs sont obligatoires.";
    }
    if ($pass !== $pass2) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (strlen($pass) < 4) {
        $errors[] = "Mot de passe trop court (min 4 caractères).";
    }

    // Unicité login
    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $errors[] = "Ce login est déjà utilisé.";
        }
    }

    if (!$errors) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare(
            "INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$login, $prenom, $nom, $hash]);
        $redirect = true;
    }
}

// Inclure le header (qui démarre la session)
require_once __DIR__.'/includes/header.php';

// Vérifier si l'utilisateur est déjà connecté ou s'il faut rediriger après inscription
if (isset($_SESSION['user'])) {
    echo '<script>window.location.href = "index.php";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=index.php"></noscript>';
    echo '<p>Redirection en cours... <a href="index.php">Cliquez ici si la redirection ne fonctionne pas</a></p>';
    require_once __DIR__.'/includes/footer.php';
    exit;
}

if ($redirect) {
    echo '<script>window.location.href = "connexion.php?inscription=ok";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=connexion.php?inscription=ok"></noscript>';
    echo '<p>Inscription réussie ! Redirection en cours... <a href="connexion.php?inscription=ok">Cliquez ici si la redirection ne fonctionne pas</a></p>';
    require_once __DIR__.'/includes/footer.php';
    exit;
}
?>
<h1>Inscription</h1>

<?php if ($errors): ?>
  <div class="alert error">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="form">
  <div class="field">
    <label for="login">Login</label>
    <input id="login" type="text" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required>
  </div>
  <div class="field">
    <label for="prenom">Prénom</label>
    <input id="prenom" type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required>
  </div>
  <div class="field">
    <label for="nom">Nom</label>
    <input id="nom" type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
  </div>
  <div class="field">
    <label for="password">Mot de passe</label>
    <input id="password" type="password" name="password" required>
  </div>
  <div class="field">
    <label for="password_confirm">Confirmer le mot de passe</label>
    <input id="password_confirm" type="password" name="password_confirm" required>
  </div>
  <button type="submit" class="btn">Créer mon compte</button>
</form>

<p>Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>

<?php require_once __DIR__.'/includes/footer.php'; ?>