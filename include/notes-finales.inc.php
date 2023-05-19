<?php
$afficherResultat = false;

//variable pour stocker les erreurs de données du formulaire
$erreur["Id-groupe"] = "";
$erreur["sexe"] = "";
$erreur["echec-seulement"] = "";
if (isset($_GET["rechercher"])) {

    //validation des données provenant du formulaire
    $erreur["Id-groupe"] = ValiderGet("Groupe", "id-groupe", [0, 1]);
    $erreur["sexe"] = ValiderGet("Sexe", "sexe", ["F", "M", "F+M"]);
    $erreur["echec-seulement"] = ValiderGet("Èchec", "en-echec-seulement", ["vrai"], false);

    //determine si on procede au traitement ou non
    $afficherResultat = (empty($erreur["Id-groupe"]) && empty($erreur["sexe"]) && empty($erreur["echec-seulement"]));
} ?>

<section>
    <h1>Notes finales</h1>

    <form>
        <input type="hidden" name="page" value="notes-finales">

        <fieldset>
            <?= $erreur["Id-groupe"] ?>
            <label>Groupe</label>
            <select name="id-groupe[]" multiple>
                <option value="" disabled selected>choisissez un groupe</option>
                <?php

                // $notesTousGroupes vient du fichier donnees/notes.php
                foreach ($notesTousGroupes as $cle => $groupe) { ?>
                    <option value="<?= $cle ?>" <?= EstSelectione("id-groupe", $cle, false) ?>>groupe <?= $cle + 1 ?></option>
                <?php }
                ?>
            </select>
        </fieldset>

        <fieldset>
            <?= $erreur["echec-seulement"] ?>
            <input type="checkbox" name="en-echec-seulement" value="vrai" id="en-echec-seulement" <?= EstSelectione("en-echec-seulement", "vrai") ?>>
            <label for="en-echec-seulement">Étudiant en echec seulement</label>
        </fieldset>

        <fieldset>
            <?= $erreur["sexe"] ?>
            <input type="radio" name="sexe" id="F" value="F" <?= EstSelectione("sexe", "F") ?>><label for="F">Femmes</label>
            <input type="radio" name="sexe" id="M" value="M" <?= EstSelectione("sexe", "M") ?>><label for="M">Hommes</label>
            <input type="radio" name="sexe" id="F+M" value="F+M" <?= EstSelectione("sexe", "F+M") ?>><label for="F+M">Tous</label>
        </fieldset>

        <fieldset>
            <input type="submit" name="rechercher" value="rechercher">
        </fieldset>

    </form>

</section>

<?php
// inclure le script pour traiter la requete de l'utilisateur si les données de formulaires sont correctes
if ($afficherResultat) require "resultat/finales.inc.php";
?>