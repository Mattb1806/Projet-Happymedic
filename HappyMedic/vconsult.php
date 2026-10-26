<?php
// Point d'accès utilisé par le distributeur (programme Java) et par consultation.php.
// Reçoit le prénom du patient dans le champ POST « cb » et renvoie son ordonnance en JSON.
require __DIR__ . '/includes/db.php';

header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json; charset=utf-8');

$prenom = $_POST['cb'] ?? '';
$ordonnance = null;

try {
    $personnes = selectionner('SELECT id_personne FROM personne WHERE prenom = ? LIMIT 1', 's', [$prenom]);
    if ($personnes) {
        $lignes = selectionner('SELECT * FROM ordonnance WHERE id_personne = ? LIMIT 1', 'i', [$personnes[0]['id_personne']]);
        $ordonnance = $lignes[0] ?? null;
    }
} catch (mysqli_sql_exception $e) {
    $ordonnance = null;
}

if ($ordonnance === null) {
    http_response_code(404);
    echo json_encode(['erreur' => 'Aucune ordonnance trouvée pour ce prénom.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Même format que la version d'origine : les huit quantités puis un message.
echo json_encode([
    's1'  => (int) $ordonnance['s1'],
    's2'  => (int) $ordonnance['s2'],
    'v'   => (int) $ordonnance['v'],
    'b'   => (int) $ordonnance['b'],
    'r'   => (int) $ordonnance['r'],
    'j'   => (int) $ordonnance['j'],
    'c1'  => (int) $ordonnance['c1'],
    'c2'  => (int) $ordonnance['c2'],
    'msg' => 'id personne = ' . $ordonnance['id_personne'],
]);
