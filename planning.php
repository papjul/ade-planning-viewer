<?php
/** 
 * Planning IUT Info
 * Copyright © 2012 Julien Papasian
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

### Initialisation
define('ROOT', dirname( __FILE__ ));
require_once ROOT.'/init.php';   # Sécurité principalement
require_once ROOT.'/config.php'; # Configuration

# On donne le cookie à bouffer au navigo le plus tôt possible
if(isset($_POST['submit']))
    setcookie('idTree', $_POST['idTree'], time() + ONE_YEAR, null, null, false, true);

## Création des groupes dans un tableau de tableaux
$groups = array('1re année'   => array('1re année (tous)' => '8385%2C8386%2C8387%2C8388%2C8389%2C8390%2C8391%2C8392%2C8393%2C8394',
                                        'Groupe 1' => '8385%2C8386',
                                        'Groupe 2' => '8387%2C8388',
                                        'Groupe 3' => '8389%2C8390',
                                        'Groupe 4' => '8391%2C8392',
                                        'Groupe 5' => '8393%2C8394'),
                '2e année'    => array('2e année (tous)' => '8400%2C8401%2C8402%2C8403%2C8404%2C8405%2C3772%2C3773',
                                        'Groupe 1' => '8400%2C8401',
                                        'Groupe 2' => '8402%2C8403',
                                        'Groupe 3' => '8404%2C8405',
                                        'Groupe 4' => '3772%2C3773'),
                'Licence Pro' => array('LP' => '6445'),
                'Tous'        => array('Toutes années' => '8385%2C8386%2C8387%2C8388%2C8389%2C8390%2C8391%2C8392%2C8393%2C8394%2C8400%2C8401%2C8402%2C8403%2C8404%2C8405%2C3772%2C3773%2C6445'));

## Création des associations numéro de semaine → timestamp dans un tableau
$weeks = array();
$alreadySelected = false;

# Boucle sur NB_WEEKS semaines
$timestamp = FIRST_WEEK;
for($i = 0; $i < NB_WEEKS; ++$i)
{
    $weeks[$i] = $timestamp;

    # Semaine suivante (seulement 6 jours pour l’instant pour capter la semaine courante au dimanche)
    $timestamp += 6 * ONE_DAY;

    # S’il s’agit de la semaine courante, on note la valeur pour plus tard
    if(!$alreadySelected && $timestamp > time())
    {
        $currentWeek = $i;
        $alreadySelected = true;
    }

    # Semaine suivante (ajout du jour manquant pour l’itération suivante)
    $timestamp += ONE_DAY;
}

# On commence à noter les paramètres qui seront nécessaires pour la génération de l’image
/** On génère un nombre aléatoire pour déterminer quel
 * identifiant de session sera utilisé.
 * Obligatoire car si un identifiant est trop utilisé,
 * il est visiblement bloqué par l’ADE.
 */
$rand = rand(0,1);

# L’identifiant de session
if($rand == 0) $identifier = '28b225b964f22b085de4b704b5885ded';
else $identifier = '8ba834238410a5a92ecb1729024b7871';

# La semaine à afficher
$idPianoWeek = isset($_POST['idPianoWeek']) ? intval($_POST['idPianoWeek']) : $currentWeek;

# Les jours de la semaine
$saturday = SATURDAY;
$sunday = SUNDAY;
if(isset($_POST['submit']))
{
    $saturday = (isset($_POST['saturday']) ? 'yes' : 'no');
    $sunday = (isset($_POST['sunday']) ? 'yes' : 'no');
}
$idPianoDay = '0%2C1%2C2%2C3%2C4'.($saturday == 'yes' ? '%2C5' : '').''.($sunday == 'yes' ? '%2C6' : '');

# Le(s) groupe(s) concernés
$idTree = (isset($_POST['idTree']) ? $_POST['idTree'] : ((isset($_COOKIE['idTree'])) ? $_COOKIE['idTree'] : ID_TREE));

# Les dimensions
$width = isset($_POST['width']) ? intval($_POST['width']) : WIDTH;
switch($width)
{
    case 320:
        $height = 240;
        break;

    case 640:
        $height = 480;
        break;

    case 800:
        $height = 600;
        break;

    case 1024:
        $height = 768;
        break;

    case 1366:
        $height = 768;
        break;

    case 1600:
        $height = 1024;
        break;

    case 1920:
        $height = 1080;
        break;

    default;
        $width = 1000;
        $height = 500;
}

# Le format (horizontal/vertical)
$displayConfId = isset($_POST['displayConfId']) ? intval($_POST['displayConfId']) : DISPLAY_CONF_ID;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-style-type" content="text/css" />
        <meta name="title" content="Planning IUT" />
        <meta name="abstract" content="Planning" />
        <meta name="description" content="Planning de l’IUT Informatique d’Aix-en-Provence" />
        <meta name="keywords" content="planning" />
        <meta name="owner" content="Julien Papasian" />
        <meta name="author" content="Julien Papasian" />
        <meta http-equiv="content-language" content="french" />
        <meta name="robots" content="noindex,nofollow" />
        
        <title>Planning IUT Info</title>
        <?php
        # Flux RSS
        echo '<link rel="alternate" type="application/rss+xml" title="Flux RSS des '.NB_DAYS_RSS.' jours à venir" href="'.URL_ADE.'/rss?projectId='.PROJECT_ID.'&amp;resources='.$idTree.'&amp;nbDays='.NB_DAYS_RSS.'" />';
        ?>
        <link title="Planning" type="text/css" rel="stylesheet" href="style.css" />

        <script type="text/javascript">
        /* Quelques trucs en CSS pour ceux qui ont JavaScript désactivé */
        document.write('<style type="text/css">#submit { display: none; } .buttonWeek { display: inline; }</style>');
        </script>
    </head>
    <body>
        <h2 class="centre">Planning IUT Info</h2>

        <!-- Les scripts -->
        <script type="text/javascript">
        /* Permet de passer automatiquement la taille du planning à 1920x1080 en cas de sélection de tous les groupes */
        function checkWidth(formulaire) {
            if(formulaire.form.elements['idTree'].options[formulaire.form.elements['idTree'].selectedIndex].value == '<?php echo $groups['Tous']['Toutes années']; ?>') document.getElementById("width").selectedIndex = 7;

            document.getElementById('submit').click();
        }

        /* Bouton Semaine précédente */
        function goPreviousWeek(formulaire) {
            document.getElementById("idPianoWeek").selectedIndex = parseInt(formulaire.form.elements['idPianoWeek'].options[formulaire.form.elements['idPianoWeek'].selectedIndex].value) - 1;

            document.getElementById('submit').click();
        }

        /* Bouton Semaine suivante */
        function goNextWeek(formulaire) {
            document.getElementById("idPianoWeek").selectedIndex = parseInt(formulaire.form.elements['idPianoWeek'].options[formulaire.form.elements['idPianoWeek'].selectedIndex].value) + 1;

            document.getElementById('submit').click();
        }
        </script>

        <form id="myform" method="post" action="planning.php">
            <!-- Le groupe -->
            <table><tbody><tr><td colspan="3"><select name="idTree" id="idTree" onchange="javascript:checkWidth(this);">
            <?php
            foreach($groups as $kInitLoop => $vInitLoop)
            {
                echo '<optgroup label="'.$kInitLoop.'">';

                foreach($vInitLoop as $kLoop => $vLoop)
                {
                    echo '<option value="'.$vLoop.'"'.($idTree == $vLoop ? SELECTED : '').'>'.$kLoop.'</option>';
                }

                echo '</optgroup>';
            }
            ?>
            </select></td></tr>
            <tr>
            <?php
            # On affiche les flèches de navigation vers les semaines précédentes et suivantes, si possible
            if($idPianoWeek > 0)
            {
                ?>
                <td><input type="button" name="previous_week" id="previous_week" class="buttonWeek" value="Semaine précédente" onclick="javascript:goPreviousWeek(this);" /></td>
                <?php
            }
            ?>
            <td><select name="idPianoWeek" id="idPianoWeek" onchange="document.getElementById('submit').click();">
                <?php
                # Boucle sur NB_WEEKS semaines
                for($i = 0; $i < NB_WEEKS; ++$i)
                {
                    echo '<option value="'.$i.'"'.(($idPianoWeek == $i) ? SELECTED : '').'>Semaine du '.gmdate('d\/m\/Y', $weeks[$i]).'</option>';

                    # Semaine suivante
                    $timestamp += ONE_WEEK;
                }
                ?>
            </select></td>
            <?php
            if($idPianoWeek < NB_WEEKS - 1)
            {
                ?>
                <td><input type="button" name="next_week" id="next_week" class="buttonWeek" value="Semaine suivante" onclick="javascript:goNextWeek(this);" /></td>
                <?php
            }

            echo '</tr></tbody></table>';

            # On affiche l’image
            echo '<p class="centre"><img src="'.URL_ADE.'/imageEt?identifier='.$identifier.'&amp;projectId='.PROJECT_ID.'&amp;idPianoWeek='.$idPianoWeek.'&amp;idPianoDay='.$idPianoDay.'&amp;idTree='.$idTree.'&amp;width='.$width.'&amp;height='.$height.'&amp;lunchName=REPAS&amp;displayMode=1057855&amp;showLoad=false&amp;ttl='.time().'000&amp;displayConfId='.$displayConfId.'" alt="Erreur d’affichage du planning" /></p>';
            ?>
            <p class="centre"><input type="checkbox" name="saturday" id="saturday" value="yes" onchange="document.getElementById('submit').click();"<?php echo ($saturday == 'yes') ? ' checked="checked"' : ''; ?> /><label for="saturday"> Samedi</label> <input type="checkbox" name="sunday" id="sunday" value="yes" onchange="document.getElementById('submit').click();"<?php echo ($sunday == 'yes') ? ' checked="checked"' : ''; ?> /><label for="sunday"> Dimanche</label>
            <br />
            <select id="displayConfId" name="displayConfId" onchange="document.getElementById('submit').click();">
                <option value="41"<?php echo ($displayConfId == 41) ? SELECTED : ''; ?>>Horizontal</option>
                <option value="8"<?php echo ($displayConfId == 8) ? SELECTED : ''; ?>>Vertical</option>
            </select><select id="width" name="width" onchange="document.getElementById('submit').click();">
                <option value="320"<?php echo ($width == 320) ? SELECTED : ''; ?>>320x240</option>
                <option value="640"<?php echo ($width == 640) ? SELECTED : ''; ?>>640x480</option>
                <option value="800"<?php echo ($width == 800) ? SELECTED : ''; ?>>800x600</option>
                <option value="1000"<?php echo ($width == 1000) ? SELECTED : ''; ?>>1000x500 (par défaut)</option>
                <option value="1024"<?php echo ($width == 1024) ? SELECTED : ''; ?>>1024x768</option>
                <option value="1366"<?php echo ($width == 1366) ? SELECTED : ''; ?>>1366x768</option>
                <option value="1600"<?php echo ($width == 1600) ? SELECTED : ''; ?>>1600x1024</option>
                <option value="1920"<?php echo ($width == 1920) ? SELECTED : ''; ?>>1920x1080</option>
            </select></p>

            <p class="centre"><input type="submit" id="submit" name="submit" value="Récupérer le planning" /></p>
        </form>

        <p>&nbsp;</p>

        <p class="centre">Copyright © 2012 <a href="https://github.com/Yurienu/PlanningIUTInfo">Planning IUT Info</a></p>
    </body>
</html>