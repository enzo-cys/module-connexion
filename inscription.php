<?php
require_once __DIR__.'/config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        header("Location: connexion.php?inscription=ok");
        exit;
    }
}

require_once __DIR__.'/includes/header.php';
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