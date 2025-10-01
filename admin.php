<?php
require_once __DIR__.'/config/db.php';

// Inclure le header (qui démarre la session)
require_once __DIR__.'/includes/header.php';

// Vérification des droits d'accès
if (!isset($_SESSION['user']) || $_SESSION['user']['login'] !== 'admin') {
    http_response_code(403);
    echo "<h1>Accès refusé</h1>";
    echo "<p>Vous devez être administrateur pour accéder à cette page.</p>";
    echo "<p><a href='index.php'>Retour à l'accueil</a></p>";
    require_once __DIR__.'/includes/footer.php';
    exit;
}

$stmt = $pdo->query("SELECT id, login, prenom, nom FROM utilisateurs ORDER BY id ASC");
$users = $stmt->fetchAll();
?>
<h1>Administration</h1>
<p>Liste complète des utilisateurs inscrits.</p>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Login</th>
      <th>Prénom</th>
      <th>Nom</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= $u['id']; ?></td>
        <td><?= htmlspecialchars($u['login']); ?></td>
        <td><?= htmlspecialchars($u['prenom']); ?></td>
        <td><?= htmlspecialchars($u['nom']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php require_once __DIR__.'/includes/footer.php'; ?>