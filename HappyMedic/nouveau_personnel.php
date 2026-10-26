<?php
// Création d'un compte pour un membre du personnel (ou un patient).
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
    $statut = $_POST['statut'] ?? '';

    if ($prenom === '' || $nom === '' || $email === '' || $mdp === '') {
        $message = 'Tous les champs sont obligatoires.';
    } elseif (!in_array($statut, STATUTS, true)) {
        $message = 'Statut invalide.';
    } elseif (creer_personne($prenom, $nom, $email, $mdp, $statut)) {
        $succes = true;
        $message = 'Compte ' . $prenom . ' ' . $nom . ' (' . $statut . ') créé.';
    } else {
        $message = 'Enregistrement impossible : cet e-mail existe peut-être déjà.';
    }
}

$titre = 'Gérer le personnel';
require __DIR__ . '/includes/header.php';
?>
<h1>Ajouter un membre du personnel</h1>
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

    <label for="statut">Statut</label>
    <select id="statut" name="statut" required>
        <option value="">-- choisir --</option>
        <?php foreach (STATUTS as $s): ?>
            <option value="<?= h($s) ?>"><?= h($s) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" name="enregistrer">Enregistrer</button>
</form>
<p><a href="menu.php">← Retour au menu</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>
