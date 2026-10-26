<?php
// Création d'une ordonnance pour un patient.
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/db.php';
exiger_connexion();

// Les quantités s1 et s2 sont saisies en ml et converties en unités du distributeur.
const UNITES_PAR_ML = 270;

// Médicaments : nom du champ => libellé affiché
$medicaments = [
    's1' => 's1 (en ml)',
    's2' => 's2 (en ml)',
    'v'  => 'Vert',
    'b'  => 'Bleu',
    'r'  => 'Rouge',
    'j'  => 'Jaune',
    'c1' => 'Cachet 1',
    'c2' => 'Cachet 2',
];

/** Convertit une saisie (virgule ou point accepté) en nombre décimal. */
function lire_nombre(string $cle): float
{
    return (float) str_replace(',', '.', $_POST[$cle] ?? '0');
}

$message = '';
$succes = false;
if (isset($_POST['ajouter'])) {
    $valeurs = [
        (int) round(lire_nombre('s1') * UNITES_PAR_ML),
        (int) round(lire_nombre('s2') * UNITES_PAR_ML),
        (int) lire_nombre('v'),
        (int) lire_nombre('b'),
        (int) lire_nombre('r'),
        (int) lire_nombre('j'),
        (int) lire_nombre('c1'),
        (int) lire_nombre('c2'),
        (int) ($_POST['patient'] ?? 0),
    ];
    try {
        executer(
            'INSERT INTO ordonnance (s1, s2, v, b, r, j, c1, c2, id_personne) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            'iiiiiiiii',
            $valeurs
        );
        $succes = true;
        $message = 'Ordonnance enregistrée.';
    } catch (mysqli_sql_exception $e) {
        $message = "Enregistrement impossible : l'ordonnance n'a pas pu être créée.";
    }
}

$personnes = selectionner('SELECT id_personne, prenom, nom, statut FROM personne ORDER BY prenom');

$titre = 'Nouvelle ordonnance';
require __DIR__ . '/includes/header.php';
?>
<h1>Ajouter une nouvelle ordonnance</h1>
<?php if ($message !== ''): ?>
    <p class="alerte <?= $succes ? 'ok' : 'erreur' ?>"><?= h($message) ?></p>
<?php endif; ?>
<form method="post">
    <label for="patient">Patient</label>
    <select id="patient" name="patient" required>
        <?php foreach ($personnes as $p): ?>
            <option value="<?= h($p['id_personne']) ?>"><?= h($p['prenom'] . ' ' . $p['nom'] . ' (' . $p['statut'] . ')') ?></option>
        <?php endforeach; ?>
    </select>

    <div class="grille">
        <?php foreach ($medicaments as $cle => $libelle): ?>
            <div>
                <label for="<?= h($cle) ?>"><?= h($libelle) ?></label>
                <input type="number" id="<?= h($cle) ?>" name="<?= h($cle) ?>" min="0" step="<?= in_array($cle, ['s1', 's2'], true) ? 'any' : '1' ?>" value="0">
            </div>
        <?php endforeach; ?>
    </div>

    <button type="submit" name="ajouter">Ajouter</button>
</form>
<p><a href="menu.php">← Retour au menu</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>
