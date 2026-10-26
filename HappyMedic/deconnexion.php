<?php
// Ferme la session et revient à la page de connexion.
require __DIR__ . '/includes/auth.php';
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
