<section>
    <h2>Résultat de recherche : </h2>
    <?php
    $resulat = TrouverNotesFinales($_GET["id-groupe"], $_GET["sexe"], isset($_GET["en-echec-seulement"]));
    // si la fonction trouve des étudiants qui répondent au critères de la rquete de l'utilisateur
    if ($resulat['trouve']) { ?>

        <table>
            <tr>
                <th>Identifiant</th>
                <th>Prénom et Nom</th>
                <th>Sexe</th>
                <th>Note</th>
                <th>Èchec</th>
            </tr>

            <?php
            foreach ($resulat['donnees']['etudiants'] as $code => $etudiant) { ?>
                <tr>
                    <td><?= $code; ?></td>
                    <td><?= $etudiant["prenom-et-nom"]; ?></td>
                    <td><?= $etudiant["sexe"]; ?></td>
                    <td><?= $etudiant["note"]; ?></td>
                    <td><?= $etudiant["echec"]; ?></td>
                </tr>
            <?php } ?>

            <tr>
                <th colspan="3">Moyenne de groupe</th>
                <td colspan="2"><?= $resulat['donnees']["moyenne"] ?></td>
            </tr>

        </table>
    <?php } else {
        // si la fonction NE TROUVE PAS des étudiants qui répondent au critères de la rquete de l'utilisateur
    ?>
        <span>Aucun résultat (sauf erreure)</span>
    <?php } ?>
</section>