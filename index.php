<?php
//inclusion des données et des fonctions utilisées partout dans le site

//les liens des pages
require("donnees/liens.php");
//les notes des étudiants
require("donnees/notes.php");
//les fonction que j'utilise
require("fonctions/fonctions.php");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title><?= Page()['title'] ?></title>
</head>

<body>

    <header>
        <h3>TP - Introduction à la programmation Web</h3>
        <?php if (Page()["page"] != "/") { ?>
            <nav>
                <?php
                foreach ($liensMenu as $cle => $lien) {
                    if ($lien["slug"] == "/") continue; ?>
                    <a href="<?= $lien["slug"] ?>" class="<?= ClassDeLien($lien["slug"]) ?>"><?= $lien["text"] ?></a>
                <?php } ?>
            </nav>
        <?php } ?>
    </header>

    <main>
        <?php
        require(FichierSurServeur());
        ?>
    </main>

</body>

</html>