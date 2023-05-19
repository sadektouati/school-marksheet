<section>
    <h2>Notes de l'étudiant avec code : <?= $_GET["code"] ?></h2>
    <?php
    $resulat = TrouverEtudiant($_GET["code"]);

    // si la fonction trouve des étudiants qui répondent au critères de la rquete de l'utilisateur
    if ($resulat['trouve']) { ?>
        <table>
            <tr>
                <th>TP1</th>
                <th>TP2</th>
                <th>Examen</th>
                <th>Note finale</th>
            </tr>
            <tr>
                <td><?= $resulat["notes"]["tp1"]; ?></td>
                <td><?= $resulat["notes"]["tp2"]; ?></td>
                <td><?= $resulat["notes"]["examen"]; ?></td>
                <td><?= $resulat["notes"]["finale"]; ?></td>
            </tr>

        </table>
    <?php
    } else {
        // si la fonction NE TROUVE PAS des étudiants qui répondent au critères de la rquete de l'utilisateur
    ?>
        <div>Aucun étudiant trouvé avec le code: <em><?= $_GET["code"] ?></em></div>
    <?php } ?>

</section>