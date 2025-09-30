<?php
require_once __DIR__.'/config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['login'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    echo "<p>Accès refusé. <a href='index.php'>Retour</a></p>";
    exit;
}

$stmt = $pdo->query("SELECT id, login, prenom, nom FROM utilisateurs ORDER BY id ASC");
$users = $stmt->fetchAll();

require_once __DIR__.'/includes/header.php';
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