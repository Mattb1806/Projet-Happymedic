<?php
// En-tête commun. La page définit $titre avant de l'inclure.
$titre = $titre ?? 'HappyMedic';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($titre) ?> - HappyMedic</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="barre">
    <a class="logo" href="menu.php">HappyMedic</a>
    <?php if (isset($_SESSION['utilisateur_email'])): ?>
        <nav>
            <span><?= h($_SESSION['utilisateur_prenom'] . ' ' . $_SESSION['utilisateur_nom']) ?> (<?= h($_SESSION['utilisateur_statut']) ?>)</span>
            <a href="menu.php">Menu</a>
            <a href="deconnexion.php">Déconnexion</a>
        </nav>
    <?php endif; ?>
</header>
<main class="carte">
