<?php
// Consultation de l'ordonnance d'un patient (interroge vconsult.php).
require __DIR__ . '/includes/auth.php';
exiger_connexion();

$titre = 'Consultation';
require __DIR__ . '/includes/header.php';
?>
<h1>Consulter une ordonnance</h1>
<form id="formulaire">
    <label for="cb">Prénom du patient</label>
    <input type="text" id="cb" name="cb" required autofocus>
    <button type="submit">Consulter</button>
</form>
<div id="resultat"></div>
<p><a href="menu.php">← Retour au menu</a></p>

<script>
const LIBELLES = {
    s1: "s1", s2: "s2", v: "Vert", b: "Bleu", r: "Rouge", j: "Jaune", c1: "Cachet 1", c2: "Cachet 2"
};

document.getElementById("formulaire").addEventListener("submit", async (evenement) => {
    evenement.preventDefault();
    const zone = document.getElementById("resultat");
    zone.replaceChildren();

    try {
        const reponse = await fetch("vconsult.php", { method: "POST", body: new FormData(evenement.target) });
        const donnees = await reponse.json();

        if (donnees.erreur) {
            const alerte = document.createElement("p");
            alerte.className = "alerte erreur";
            alerte.textContent = donnees.erreur;
            zone.appendChild(alerte);
            return;
        }

        const tableau = document.createElement("table");
        for (const [cle, libelle] of Object.entries(LIBELLES)) {
            const ligne = tableau.insertRow();
            ligne.insertCell().textContent = libelle;
            ligne.insertCell().textContent = donnees[cle];
        }
        zone.appendChild(tableau);
    } catch (erreur) {
        const alerte = document.createElement("p");
        alerte.className = "alerte erreur";
        alerte.textContent = "Impossible de récupérer l'ordonnance.";
        zone.appendChild(alerte);
    }
});
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
