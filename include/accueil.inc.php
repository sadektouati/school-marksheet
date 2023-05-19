 <section class="lien-accueil">
     <?php
        foreach ($liensMenu as $cle => $lien) {
            if ($lien["slug"] == "/") continue; ?>
         <a href="<?= $lien["slug"] ?>" class="<?= ClassDeLien($lien["slug"]) ?>"><?= $lien["text"] ?></a>
     <?php } ?>
 </section>