<section>

    <h2>Résultat de recherche : </h2>
    <?php
    $resulat = trouverNotesTravail($_GET["id-groupe"], $_GET["index-travail"]);
    // si la fonction trouve des étudiants qui répondent au critères de la rquete de l'utilisateur

    if ($resulat['trouve']) { ?>

        <table>

            <tr>
                <th>Prénom et Nom</th>
                <th>Note</th>
            </tr>

            <?php
            foreach ($resulat['donnees']['etudiants'] as $code => $etudiant) { ?>
                <tr>
                    <td><?= $etudiant["prenom-et-nom"]; ?></td>
                    <td><?= $etudiant["note"]; ?></td>
                </tr>
            <?php } ?>

            <tr>
                <th>moyenne de groupe</th>
                <td><?= $resulat['donnees']["moyenne"] ?></td>
            </tr>

        </table>
    <?php
    } else {
        // si la fonction NE TROUVE PAS des étudiants qui répondent au critères de la rquete de l'utilisateur
    ?>
        <span>Aucun résultat (sauf erreur)</span>
    <?php } ?>
</section>