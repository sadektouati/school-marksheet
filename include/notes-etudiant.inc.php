<?php

$afficherResultat = false;

//variable pour stocker les erreurs de données du formulaire
$erreur["code"] = "";

if (isset($_GET["rechercher"])) {
    //validation des données provenant du formulaire
    $erreur["code"] = ValiderRegex("Code", "code",  '/^[[:alpha:]]{4}\d{6}$/iu');

    //determine si on procede au traitement ou non
    $afficherResultat = empty($erreur["code"]);
}
?>

<section>

    <h1>Notes étudiant</h1>

    <form>
        <input type="hidden" name="page" value="notes-etudiant">

        <fieldset>
            <?= $erreur["code"] ?>
            <label for="code">Code</label>
            <input type="text" name="code" value="<?= (isset($_GET['code']) ? $_GET['code'] : '') ?>" id="code" autofocus autocomplete="off" autocapitalize="off" placeholder="4 lettres suivi par 6 chiffres">
        </fieldset>

        <fieldset>
            <input type="submit" name="rechercher" value="rechercher">
        </fieldset>

    </form>

</section>

<?php
// inclure le script pour traiter la requete de l'utilisateur si les données de formulaires sont correctes
if ($afficherResultat) require "resultat/etudiant.php";
?>