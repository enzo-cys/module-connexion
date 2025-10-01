<?php
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Déconnexion - Module Connexion</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="icon" type="image/png" href="assets/img/multipass.png">
<script>window.location.href = "index.php";</script>
<noscript><meta http-equiv="refresh" content="0;url=index.php"></noscript>
</head>
<body>
<p>Déconnexion en cours... <a href="index.php">Cliquez ici si la redirection ne fonctionne pas</a></p>
</body>
</html>