<?php
// Session, protection des pages et fonctions utilitaires communes.
session_start();

/** Échappe un texte avant de l'afficher dans une page HTML. */
function h($texte): string
{
    return htmlspecialchars((string) $texte, ENT_QUOTES, 'UTF-8');
}

/** Redirige vers la page de connexion si personne n'est connecté. */
function exiger_connexion(): void
{
    if (!isset($_SESSION['utilisateur_email'])) {
        header('Location: index.php');
        exit;
    }
}

/** Vrai si l'utilisateur connecté a l'un des statuts indiqués. */
function statut_parmi(string ...$statuts): bool
{
    return in_array($_SESSION['utilisateur_statut'] ?? '', $statuts, true);
}
