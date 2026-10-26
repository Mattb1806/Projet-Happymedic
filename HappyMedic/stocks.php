<?php
// Consultation et mise à jour des stocks de médicaments.
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/db.php';
exiger_connexion();

$message = '';
$succes = false;
if (isset($_POST['valider'])) {
    $nom = $_POST['nom'] ?? '';
    $quantite = filter_var($_POST['quantite'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

    if ($quantite === false) {
        $message = 'La quantité doit être un nombre entier positif ou nul.';
    } else {
        try {
            executer('UPDATE stock SET quantite = ? WHERE nom = ?', 'is', [$quantite, $nom]);
            $succes = true;
            $message = 'Stock de « ' . $nom . ' » mis à jour : ' . $quantite . '.';
        } catch (mysqli_sql_exception $e) {
            $message = 'Mise à jour impossible.';
        }
    }
}

$stocks = selectionner('SELECT nom, quantite FROM stock ORDER BY nom');

$titre = 'Stocks';
require __DIR__ . '/includes/header.php';
?>
<h1>Gérer les stocks</h1>
<?php if ($message !== ''): ?>
    <p class="alerte <?= $succes ? 'ok' : 'erreur' ?>"><?= h($message) ?></p>
<?php endif; ?>

<?php if (!$stocks): ?>
    <p>Aucun médicament enregistré.</p>
<?php else: ?>
    <table>
        <thead><tr><th>Médicament</th><th>Quantité en stock</th></tr></thead>
        <tbody>
        <?php foreach ($stocks as $s): ?>
            <tr><td><?= h($s['nom']) ?></td><td><?= h($s['quantite']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post">
        <label for="nom">Médicament</label>
        <select id="nom" name="nom" required>
            <?php foreach ($stocks as $s): ?>
                <option value="<?= h($s['nom']) ?>"><?= h($s['nom']) ?> (<?= h($s['quantite']) ?>)</option>
            <?php endforeach; ?>
        </select>

        <label for="quantite">Nouvelle quantité</label>
        <input type="number" id="quantite" name="quantite" min="0" step="1" required>

        <button type="submit" name="valider">Valider</button>
    </form>
<?php endif; ?>
<p><a href="menu.php">← Retour au menu</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>
