<?php
$afficherResultat = false;

//variable pour stocker les erreurs de données du formulaire
$erreur["id-groupe"] = "";
$erreur["travail"] = "";

if (isset($_GET["rechercher"])) {
    //validation des données provenant du formulaire
    $erreur["id-groupe"] = ValiderGet("Groupe", "id-groupe", [0, 1]);
    $erreur["travail"] = ValiderGet("Travail", "index-travail", [4, 5, 6]);

    //determine si on procede au traitement ou non
    $afficherResultat = (empty($erreur["id-groupe"]) && empty($erreur["travail"]));
}

?>

<section>
    <h1>Notes de travail</h1>

    <form>
        <input type="hidden" name="page" value="notes-travail">

        <fieldset>
            <?= $erreur["id-groupe"] ?>
            <label>Groupe</label>
            <select name="id-groupe">
                <option value="" disabled selected>choisissez</option>
                <?php
                // $notesTousGroupes vient du fichier donnees/notes.php
                foreach ($notesTousGroupes as $cle => $groupe) { ?>
                    <option value="<?= $cle ?>" <?= EstSelectione("id-groupe", $cle, false) ?>>groupe <?= $cle + 1 ?></option>
                <?php }
                ?>
            </select>
        </fieldset>

        <fieldset>
            <?= $erreur["travail"] ?>
            <label>Type de travail</label>
            <select name="index-travail">
                <option value="" disabled selected>choisissez</option>
                <?php
                // $typeDeTravail vient du fichier donnees/notes.php
                foreach ($typeDeTravail as $travail) { ?>
                    <option value="<?= $travail["index"] ?>" <?= EstSelectione("index-travail", $travail["index"], false) ?>><?= $travail["type"] ?></option>
                <?php }
                ?>
            </select>
        </fieldset>

        <fieldset>
            <input type="submit" name="rechercher" value="rechercher">
        </fieldset>

    </form>

</section>

<?php
// inclure le script pour traiter la requete de l'utilisateur si les données de formulaires sont correctes
if ($afficherResultat) require "resultat/travail.inc.php";
?>