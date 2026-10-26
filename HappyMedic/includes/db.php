<?php
// Connexion unique à la base de données et petites fonctions d'accès.

$fichierConfig = __DIR__ . '/config.php';
if (!is_file($fichierConfig)) {
    http_response_code(500);
    exit('Fichier includes/config.php manquant : copiez includes/config.example.php en config.php.');
}
require $fichierConfig;

// Les erreurs SQL deviennent des exceptions (mysqli_sql_exception) que les pages peuvent attraper.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/** Renvoie la connexion MySQL (créée une seule fois par requête). */
function db(): mysqli
{
    static $connexion = null;
    if ($connexion === null) {
        try {
            $connexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $connexion->set_charset('utf8');
        } catch (mysqli_sql_exception $e) {
            http_response_code(500);
            exit('Connexion à la base impossible : vérifiez que MySQL est démarré et le fichier includes/config.php.');
        }
    }
    return $connexion;
}

/** SELECT préparé : renvoie toutes les lignes sous forme de tableaux associatifs. */
function selectionner(string $sql, string $types = '', array $parametres = []): array
{
    $requete = db()->prepare($sql);
    if ($types !== '') {
        $requete->bind_param($types, ...$parametres);
    }
    $requete->execute();
    $lignes = $requete->get_result()->fetch_all(MYSQLI_ASSOC);
    $requete->close();
    return $lignes;
}

/** INSERT / UPDATE / DELETE préparé : renvoie le nombre de lignes modifiées. */
function executer(string $sql, string $types = '', array $parametres = []): int
{
    $requete = db()->prepare($sql);
    if ($types !== '') {
        $requete->bind_param($types, ...$parametres);
    }
    $requete->execute();
    $modifiees = $requete->affected_rows;
    $requete->close();
    return $modifiees;
}
