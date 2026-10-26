<?php
// Inscription d'un nouveau patient.
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/personnes.php';
exiger_connexion();

$message = '';
$succes = false;
if (isset($_POST['enregistrer'])) {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mdp = $_POST['mdp'] ?? '';

    if ($prenom === '' || $nom === '' || $email === '' || $mdp === '') {
        $message = 'Tous les champs sont obligatoires.';
    } elseif (creer_personne($prenom, $nom, $email, $mdp, 'patient')) {
        $succes = true;
        $message = 'Patient ' . $prenom . ' ' . $nom . ' enregistré.';
    } else {
        $message = 'Enregistrement impossible : cet e-mail existe peut-être déjà.';
    }
}

$titre = 'Nouveau patient';
require __DIR__ . '/includes/header.php';
?>
<h1>Ajouter un nouveau patient</h1>
<?php if ($message !== ''): ?>
    <p class="alerte <?= $succes ? 'ok' : 'erreur' ?>"><?= h($message) ?></p>
<?php endif; ?>
<form method="post">
    <label for="prenom">Prénom</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" required>

    <label for="email">Adresse e-mail</label>
    <input type="text" id="email" name="email" required>

    <label for="mdp">Mot de passe</label>
    <input type="password" id="mdp" name="mdp" autocomplete="new-password" required>

    <button type="submit" name="enregistrer">Enregistrer</button>
</form>
<p><a href="menu.php">← Retour au menu</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>
