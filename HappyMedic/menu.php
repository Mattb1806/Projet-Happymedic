<?php
// Menu principal : les liens affichés dépendent du statut de l'utilisateur.
require __DIR__ . '/includes/auth.php';
exiger_connexion();

$titre = 'Menu';
require __DIR__ . '/includes/header.php';
?>
<h1>Bonjour <?= h($_SESSION['utilisateur_prenom'] . ' ' . $_SESSION['utilisateur_nom']) ?></h1>
<p>Votre statut : <strong><?= h($_SESSION['utilisateur_statut']) ?></strong></p>

<ul class="menu">
    <?php if (statut_parmi('manitou')): ?>
        <li><a href="nouveau_personnel.php">Gérer le personnel</a></li>
    <?php endif; ?>
    <?php if (statut_parmi('secretaire', 'manitou', 'infirmier', 'medecin')): ?>
        <li><a href="nouveau_patient.php">Ajouter un nouveau patient</a></li>
        <li><a href="stocks.php">Gérer les stocks</a></li>
    <?php endif; ?>
    <?php if (statut_parmi('infirmier', 'medecin', 'patient')): ?>
        <li><a href="consultation.php">Consulter une ordonnance</a></li>
    <?php endif; ?>
    <?php if (statut_parmi('medecin')): ?>
        <li><a href="nouvelle_ordonnance.php">Ajouter une nouvelle ordonnance</a></li>
    <?php endif; ?>
    <li><a href="http://localhost/phpmyadmin/">Base de données (phpMyAdmin)</a></li>
</ul>
<?php require __DIR__ . '/includes/footer.php'; ?>
