<?php
// Création d'une personne (patient ou membre du personnel).
require_once __DIR__ . '/db.php';

const STATUTS = ['manitou', 'secretaire', 'infirmier', 'medecin', 'patient'];

/** Ajoute une personne. Renvoie false si l'e-mail existe déjà ou si l'insertion échoue. */
function creer_personne(string $prenom, string $nom, string $email, string $mdp, string $statut): bool
{
    try {
        executer(
            'INSERT INTO personne (prenom, nom, email, mdp, statut) VALUES (?, ?, ?, ?, ?)',
            'sssss',
            [$prenom, $nom, $email, $mdp, $statut]
        );
        return true;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}
