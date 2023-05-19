<?php

/**
 * @author saddek touati
 * fonction Page
 * pour déterminer la page en cours ainsi que son titre
 * 
 * @param aucun paramètre
 * @return string
 */
function Page()
{
    $page = '/';
    $title = 'Bienvenus';

    if (empty($_GET['page']) == false) {
        $page = $_GET['page'];
        $title = str_replace('-', ' ', $_GET['page']);
    }
    return ["page" => $page, "title" => $title];
}

/**
 * @author saddek touati
 * fonction ClassDeLien
 * pour déterminer le lien de la gage en cours
 *
 * @depends Page()
 * @param string $slug
 * @return string
 */
function ClassDeLien(String $slug)
{
    return (
        (Page()["page"] == $slug || strpos($slug, Page()["page"]) !== false
        ) ? "active" : "");
}

/**
 * @author saddek touati
 * fonction FichierSurServeur
 * pour déterminer quel script a inclure pour chaque page afin d'afficher le formulaire et le résultat de recherche
 *
 * @depends ClassDeLien
 * @global array $liensMenu
 * @param string $slug
 * @return string
 */
function FichierSurServeur()
{
    global $liensMenu;
    $fichierSurDisque = "";

    foreach ($liensMenu as $lien) {
        if (ClassDeLien($lien["slug"]) == "active") {
            $fichierSurDisque = $lien["fichier-sur-serveur"];
            break;
        }
    }

    return "include/" . $fichierSurDisque;
}

/**
 * @author saddek touati
 * fonction CalculerNoteFinale
 * pour calculer la note finale de l'étudiant(e)
 *
 * @param float $noteTp1
 * @param float $noteTp2
 * @param float $noteExamenFinal
 * @return float
 */
function CalculerNoteFinale(Float $noteTp1, Float $noteTp2, Float $noteExamenFinal)
{
    return ($noteTp1 * .2 + $noteTp2 * .4 + $noteExamenFinal * .4);
}

/**
 * @author saddek touati
 * fonction EstUnEchec
 * pour préciser c'est quoi un échec
 * 
 * @param float $note
 * @return float
 */
function EstUnEchec(Float $note)
{
    return $note < 60;
}

/**
 * @author Saddek Touati
 * fonction FormatterNote
 * pour formatter la note d'un(e) étudiant(e), exemple 90,50 au lieu de 90.5036596589658
 *
 * @param float $note
 * @return string
 */
function FormatterNote(Float $note)
{
    return number_format($note,  2, ",", "");
}

/**
 * @author Saddek Toauti
 * fonction FormatterNom
 * formatter le nom d'étudiant(e)
 *
 * @param string $prenom
 * @param string $nom
 * @return string
 */
function FormatterNom(String $prenom, String $nom)
{
    return $prenom . " " . $nom;
}

/**
 * @author Saddek Touati
 * fonction ConstruitResultat
 * pour construire le tableau finale qui va être retourné par les fonctions de recherche
 * 
 * @depends FormatterNote
 * @param float $totalNotes
 * @param array $etudiants
 * @return array
 */
function ConstruitResultat(Float $totalNotes, array $etudiants)
{
    $effectifEtudiant = count($etudiants);

    if ($effectifEtudiant > 0) {
        $moyenne = $totalNotes / $effectifEtudiant;
        return ["trouve" => true, "donnees" => ["moyenne" => FormatterNote($moyenne), "etudiants" => $etudiants]];
    } else {
        return ["trouve" => false];
    }
}

/**
 * @author Saddek Touati
 * fonction TrouverNotesTravail
 * pour rechercher la liste des étudiant(e)s par groupe et type de travail
 *
 * @depends FormatterNom
 * @global array $notesTousGroupes 
 * @param int $idGroupe
 * @param int $indexNote
 * @return array
 */
function TrouverNotesTravail(Int $idGroupe, int $indexNote)
{
    global $notesTousGroupes;
    $groupe = $notesTousGroupes[$idGroupe];
    $listeEtudiants = [];
    $totalNotes = 0;

    foreach ($groupe as $codeEtudiant => $etudiant) {
        $listeEtudiants[$codeEtudiant]["prenom-et-nom"] = FormatterNom($etudiant[0], $etudiant[1]);
        $listeEtudiants[$codeEtudiant]["note"] = $etudiant[$indexNote];
        $totalNotes += $etudiant[$indexNote];
    }

    return ConstruitResultat($totalNotes, $listeEtudiants);
}

/**
 * @author Saddek Touati
 * Fonction TrouverNotesFinales
 *
 * @depends ConstruitResultat CalculerNoteFinale EstUnEchec FormatterNote FormatterNom
 * @global array $notesTousGroupes
 * @param array $idGroupes
 * @param string $sexe
 * @param bool $echec
 * @return array
 */
function TrouverNotesFinales(array $idGroupes, String $sexe, Bool $echec)
{
    global $notesTousGroupes;
    $listeEtudiants = [];
    $totalNotes = 0;

    foreach ($idGroupes as $groupe) {

        foreach ($notesTousGroupes[$groupe] as $codeEtudiant => $notesEtudiant) {

            $noteFinale = CalculerNoteFinale($notesEtudiant[4], $notesEtudiant[5], $notesEtudiant[6]);

            // si l'usager veut seulement ceux qui ont échoué on elimine ceux qui n'ont pas échoué
            // si l'usager a précisé un sexe on élimine ceux qui ne répondent pas à son choix 
            if (($echec && EstUnEchec($noteFinale) == false) || ($sexe !== "F+M" && $notesEtudiant[2] !== $sexe)) {
                continue;
            }

            $listeEtudiants[$codeEtudiant]["prenom-et-nom"] = FormatterNom($notesEtudiant[0], $notesEtudiant[1]);
            $listeEtudiants[$codeEtudiant]["sexe"] = $notesEtudiant[2];
            $listeEtudiants[$codeEtudiant]["echec"] = EstUnEchec($noteFinale) ? "oui" : "";
            $listeEtudiants[$codeEtudiant]["note"] = FormatterNote($noteFinale);
            $totalNotes += $noteFinale;
        }
    }

    return ConstruitResultat($totalNotes, $listeEtudiants);
}

/**
 * @author Saddek Touati
 * fonction TrouverEtudiant
 * pour trouver un étudiant par son code
 * 
 * @depends FormatterNote CalculerNoteFinale
 * @global array $notesTousGroupes
 * @param string $codeRecherche
 * @return array
 */
function TrouverEtudiant(String $codeRecherche)
{
    global $notesTousGroupes;

    $resultat["trouve"] = false;
    foreach ($notesTousGroupes as $etudiants) {
        foreach ($etudiants as $code => $donnees) {
            if (mb_strtoupper($code) == mb_strtoupper($codeRecherche)) {
                $resultat["trouve"] = true;
                $resultat["notes"]["tp1"] = $donnees[4];
                $resultat["notes"]["tp2"] = $donnees[5];
                $resultat["notes"]["examen"] = $donnees[6];
                $resultat["notes"]["finale"] = FormatterNote(CalculerNoteFinale($donnees[4], $donnees[5], $donnees[6]));
                break;
            }
        }
    }
    return $resultat;
}

/**
 * @author Saddek Touati
 * fonction ErreurEnHtml
 * pour formatter le message d'erreur à afficher en html
 *
 * @param string $text1
 * @param string $text2
 * @param bool $requis
 * @return string
 */
function ErreurEnHTML(String $text1, String $text2, Bool $requis = false)
{
    if ($requis) {
        return "<span>le champ $text1 est requis.</span>";
    }
    return "<span>le champ $text1 doit être un de: $text2 .</span>";
}

/**
 * @author Saddek Touati
 * Fonction ValiderGet
 * pour la validation des données de formulaire, qui doivent être parmi des listes
 * 
 * @depends ErreurEnHTML
 * @param string $nomDeChamp
 * @param string $cleDeChamp
 * @param array $tableauDeDonnes
 * @param bool $requis par defuat TRUE
 * @return string
 */

function ValiderGet(String $nomDeChamp, String $cleDeChamp, array $tableauDeDonnes, Bool $requis = true)
{
    if (isset($_GET[$cleDeChamp]) == false) {
        if ($requis) return ErreurEnHTML($nomDeChamp, "", true);
    } else {

        // Si valeur de $_GET[$cleDeChamp] est PAS un tableau, et que la valeur exists dans le tableau $tableauDeDonnes
        if (is_array($_GET[$cleDeChamp]) == false && in_array($_GET[$cleDeChamp], $tableauDeDonnes) === false) {
            return ErreurEnHTML($nomDeChamp, implode(', ', $tableauDeDonnes));
        }

        // Si valeur de $_GET[$cleDeChamp] est un tableau, et que TOUTES SES valeurs existent dans le tableau $tableauDeDonnes
        if (is_array($_GET[$cleDeChamp])) {
            foreach ($_GET[$cleDeChamp] as $key => $valeur) {
                if (in_array($valeur, $tableauDeDonnes) === false) {
                    return ErreurEnHTML($nomDeChamp, implode(', ', $tableauDeDonnes));
                }
            }
        }
    }
    return "";
}

/**
 * @author Saddek Touati
 * fonction ValiderRegex
 * pour la validation des données en form de text libre de formulaire qui sont provenu par la méthode GET
 *
 * @depends ErreurEnHTML
 * @param string $nomDeChamp
 * @param string $cleDeChamp
 * @param string $pattern
 * @param bool $requis
 * @return string
 */
function ValiderRegex(String $nomDeChamp, String $cleDeChamp, String $pattern, Bool $requis = true)
{
    if (empty($_GET[$cleDeChamp])) {
        if ($requis) return ErreurEnHTML($nomDeChamp, "", true);
    } else {
        //déterminer si la valeur dans $_GET[$cleDeChamp] conforme à l'expression regulière $pattern
        if (preg_match($pattern, $_GET[$cleDeChamp]) == false) {
            return "<span>Code erroné. Entrez <em>4 lettres suivi par 6 chiffres</em></span>";
        }
    }
    return "";
}

/**
 * @author Saddek Touati
 * fonction EstSelectione
 * pour déterminer si une clé est présente dans la requete et que sa valeur est égale à une données
 *
 * @param string $cleDeChamp
 * @param string $valeur
 * @param bool $checkBox
 * @return string
 */
function EstSelectione(String $cleDeChamp, String $valeur, Bool $checkBox = true)
{
    if (isset($_GET[$cleDeChamp])) {

        // Si valeur de $_GET[$cleDeChamp] n'est PAS un tableau, et que $valeur egale $_GET[$cleDeChamp]
        if (is_array($_GET[$cleDeChamp]) == false) {
            return $_GET[$cleDeChamp] == $valeur ? ($checkBox ? "checked" : "selected") : "";
        }
        // Si valeur de $_GET[$cleDeChamp] est un tableau, et que $valeur exists dans le tableau $_GET[$cleDeChamp]
        return in_array($valeur, $_GET[$cleDeChamp]) ? ($checkBox ? "checked" : "selected") : "";
    }
    return "";
}
