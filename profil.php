<?php
require_once __DIR__.'/config/db.php';

$success = null;
$errors  = [];

// Inclure le header (qui démarre la session)
require_once __DIR__.'/includes/header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo '<script>window.location.href = "connexion.php";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=connexion.php"></noscript>';
    echo '<p>Redirection en cours... <a href="connexion.php">Cliquez ici si la redirection ne fonctionne pas</a></p>';
    require_once __DIR__.'/includes/footer.php';
    exit;
}

// Récupération utilisateur actuel
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$current = $stmt->fetch();
if (!$current) {
    session_destroy();
    echo '<script>window.location.href = "connexion.php";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=connexion.php"></noscript>';
    echo '<p>Session invalide. Redirection en cours... <a href="connexion.php">Cliquez ici si la redirection ne fonctionne pas</a></p>';
    require_once __DIR__.'/includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login  = trim($_POST['login'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $nom    = trim($_POST['nom'] ?? '');
    $pass   = $_POST['password'] ?? '';
    $pass2  = $_POST['password_confirm'] ?? '';

    if ($login === '' || $prenom === '' || $nom === '') {
        $errors[] = "Champs obligatoires manquants.";
    }

    if ($login !== $current['login']) {
        $st = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ? AND id != ?");
        $st->execute([$login, $current['id']]);
        if ($st->fetch()) {
            $errors[] = "Ce login est déjà utilisé.";
        }
    }

    $passwordClause = "";
    $params = [$login, $prenom, $nom, $current['id']];

    if ($pass !== '' || $pass2 !== '') {
        if ($pass !== $pass2) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        } elseif (strlen($pass) < 4) {
            $errors[] = "Mot de passe trop court.";
        } else {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $passwordClause = ", password = ?";
            $params = [$login, $prenom, $nom, $hash, $current['id']];
        }
    }

    if (!$errors) {
        $sql = "UPDATE utilisateurs SET login = ?, prenom = ?, nom = ? $passwordClause WHERE id = ?";
        $upd = $pdo->prepare($sql);
        $upd->execute($params);

        $_SESSION['user']['login']  = $login;
        $_SESSION['user']['prenom'] = $prenom;
        $_SESSION['user']['nom']    = $nom;

        $success = "Profil mis à jour.";
        $stmt->execute([$_SESSION['user']['id']]);
        $current = $stmt->fetch();
    }
}

require_once __DIR__.'/includes/header.php';
?>
<h1>Mon profil</h1>

<?php if ($success): ?>
  <div class="alert success"><?= htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($errors): ?>
  <div class="alert error">
    <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<form method="post" class="form">
  <div class="field">
    <label for="login">Login</label>
    <input id="login" type="text" name="login" value="<?= htmlspecialchars($current['login']); ?>" required>
  </div>
  <div class="field">
    <label for="prenom">Prénom</label>
    <input id="prenom" type="text" name="prenom" value="<?= htmlspecialchars($current['prenom']); ?>" required>
  </div>
  <div class="field">
    <label for="nom">Nom</label>
    <input id="nom" type="text" name="nom" value="<?= htmlspecialchars($current['nom']); ?>" required>
  </div>
  <div class="field">
    <label for="password">Nouveau mot de passe (laisser vide si inchangé)</label>
    <input id="password" type="password" name="password">
  </div>
  <div class="field">
    <label for="password_confirm">Confirmer mot de passe</label>
    <input id="password_confirm" type="password" name="password_confirm">
  </div>
  <button type="submit" class="btn">Mettre à jour</button>
</form>

<?php require_once __DIR__.'/includes/footer.php'; ?>