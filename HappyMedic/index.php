<?php
// Page de connexion.
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/db.php';

if (isset($_SESSION['utilisateur_email'])) {
    header('Location: menu.php');
    exit;
}

$erreur = '';
if (isset($_POST['connexion'])) {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    try {
        $lignes = selectionner('SELECT prenom, nom, email, mdp, statut FROM personne WHERE email = ?', 's', [$email]);
    } catch (mysqli_sql_exception $e) {
        $lignes = [];
    }

    if ($lignes && hash_equals((string) $lignes[0]['mdp'], (string) $mdp)) {
        session_regenerate_id(true);
        $_SESSION['utilisateur_email'] = $lignes[0]['email'];
        $_SESSION['utilisateur_prenom'] = $lignes[0]['prenom'];
        $_SESSION['utilisateur_nom'] = $lignes[0]['nom'];
        $_SESSION['utilisateur_statut'] = $lignes[0]['statut'];
        header('Location: menu.php');
        exit;
    }
    $erreur = 'E-mail ou mot de passe incorrect.';
}

$titre = 'Connexion';
require __DIR__ . '/includes/header.php';
?>
<h1>Connexion</h1>
<?php if ($erreur !== ''): ?>
    <p class="alerte erreur"><?= h($erreur) ?></p>
<?php endif; ?>
<form method="post">
    <label for="email">Adresse e-mail</label>
    <input type="text" id="email" name="email" required autofocus>

    <label for="mdp">Mot de passe</label>
    <input type="password" id="mdp" name="mdp" required>

    <button type="submit" name="connexion">Se connecter</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
