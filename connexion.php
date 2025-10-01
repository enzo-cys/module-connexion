<?php
require_once __DIR__.'/config/db.php';

$error = null;
$redirect = false;

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Démarrer la session si nécessaire pour le traitement POST
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $login = trim($_POST['login'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'     => $user['id'],
            'login'  => $user['login'],
            'prenom' => $user['prenom'],
            'nom'    => $user['nom']
        ];
        $redirect = true;
    } else {
        $error = "Identifiants invalides.";
    }
}

// Inclure le header (qui démarre la session)
require_once __DIR__.'/includes/header.php';

// Vérifier si l'utilisateur est déjà connecté ou s'il faut rediriger
if (isset($_SESSION['user']) || $redirect) {
    echo '<script>window.location.href = "index.php";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=index.php"></noscript>';
    echo '<p>Redirection en cours... <a href="index.php">Cliquez ici si la redirection ne fonctionne pas</a></p>';
    require_once __DIR__.'/includes/footer.php';
    exit;
}
?>
<h1>Connexion</h1>

<?php if (isset($_GET['inscription'])): ?>
  <div class="alert success">Inscription réussie. Vous pouvez vous connecter.</div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert error"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" class="form">
  <div class="field">
    <label for="login">Login</label>
    <input id="login" type="text" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required>
  </div>
  <div class="field">
    <label for="password">Mot de passe</label>
    <input id="password" type="password" name="password" required>
  </div>
  <button type="submit" class="btn">Se connecter</button>
</form>

<p>Pas de compte ? <a href="inscription.php">Créer un compte</a></p>

<?php require_once __DIR__.'/includes/footer.php'; ?>