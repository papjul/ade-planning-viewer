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
$groups = array('1re année'   => array('1re année (tous)' => '8385,8386,8387,8388,8389,8390,8391,8392,8393,8394',
                                        'Groupe 1' => '8385,8386',
                                        'Groupe 2' => '8387,8388',
                                        'Groupe 3' => '8389,8390',
                                        'Groupe 4' => '8391,8392',
                                        'Groupe 5' => '8393,8394'),
                '2e année'    => array('2e année (tous)' => '8400,8401,8402,8403,8404,8405,3772,3773',
                                        'Groupe 1' => '8400,8401',
                                        'Groupe 2' => '8402,8403',
                                        'Groupe 3' => '8404,8405',
                                        'Groupe 4' => '3772,3773'),
                'Licence Pro' => array('LP' => '6445'),
                'Tous'        => array('Toutes années' => '8385,8386,8387,8388,8389,8390,8391,8392,8393,8394,8400,8401,8402,8403,8404,8405,3772,3773,6445'),
                'Enseignants' => array('BERNE Michel'         => 5156,
                                       'BOITARD Didier'       => 5581,
                                       'BONHOMME Christian'   => 5115,
                                       'BROCHE Martine'       => 5579,
                                       'CACCHIA Marie claude' => 5419,
                                       'CASALI Alain'         => 321,
                                       'CICCHETTI Rosine'     => 254,
                                       'DRAGUT Andreea'       => 5639,
                                       'GAITAN Patricia'      => 5204,
                                       'KIAN Yavar'           => 1236,
                                       'LAKHAL Lotfi'         => 144,
                                       'LANKESTER Robert'     => 5351,
                                       'LAPORTE Marc'         => 5570,
                                       'MONNET Marlène'       => 9836,
                                       'NEDJAR Sebastien'     => 578,
                                       'PAIN BARRE Cyril'     => 5179,
                                       'RISCH Vincent'        => 5173,
                                       'SLEZAK Eileen'        => 5670,
                                       'VAQUIERI Josee'       => 5345,
                                       'YAHI Safa'            => 6323));

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

# Les dimensions
$dimensions = array(320 => 240, 640 => 480, 800 => 600, 1024 => 768, 1280 => 720, 1366 => 768, 1600 => 1024, 1920 => 1080);

### On commence à noter les paramètres qui seront nécessaires pour la génération de l’image
# On utilise aléatoirement un des identifier à notre disponibilité
$fileIdentifier = file(URL_IDENTIFIER);
$rand = rand(0, count($fileIdentifier) - 1);
$identifier = $fileIdentifier[$rand];

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
$idPianoDay = '0,1,2,3,4'.($saturday == 'yes' ? ',5' : '').''.($sunday == 'yes' ? ',6' : '');

# Le(s) groupe(s) concernés
$idTree = (isset($_POST['idTree']) ? $_POST['idTree'] : ((isset($_COOKIE['idTree'])) ? $_COOKIE['idTree'] : ID_TREE));

# Les dimensions
$width = isset($_POST['width']) ? intval($_POST['width']) : WIDTH;

if(array_key_exists($width, $dimensions))
    $height = $dimensions[$width];

else
{
    $width = WIDTH;
    $height = HEIGHT;
}

# Le format (horizontal/vertical)
$displayConfId = isset($_POST['displayConfId']) ? intval($_POST['displayConfId']) : DISPLAY_CONF_ID;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-style-type" content="text/css" />
        <meta name="title" content="Planning IUT Info" />
        <meta name="abstract" content="Planning de l’IUT Informatique d’Aix-en-Provence" />
        <meta name="description" content="Planning IUT Info est une application en PHP pour récupérer le planning de l’ADE sur l’ENT de l’IUT Informatique d’Aix-en-Provence via une interface suivant le principe KISS" />
        <meta name="keywords" content="planning, emploi, temps, ade, ent, iut, dut, info, informatique, aix, aix-en-provence" />
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
        <h2>Planning IUT Info</h2>

        <!-- Les scripts -->
        <script type="text/javascript">
        /* Permet de passer automatiquement la taille du planning à 1920x1080 en cas de sélection de tous les groupes */
        function checkWidth(formulaire) {
            if(formulaire.form.elements['idTree'].options[formulaire.form.elements['idTree'].selectedIndex].value == '<?php echo $groups['Tous']['Toutes années']; ?>') document.getElementById("width").selectedIndex = 8;

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

        <form id="myform" method="post" action="index.php">
            <!-- Le groupe -->
            <table><tbody><tr><td colspan="3"><select name="idTree" id="idTree" onchange="javascript:checkWidth(this);">
            <?php
            foreach($groups as $kInitLoop => $vInitLoop)
            {
                echo '<optgroup label="'.$kInitLoop.'">';

                foreach($vInitLoop as $kLoop => $vLoop)
                    echo '<option value="'.$vLoop.'"'.($idTree == $vLoop ? SELECTED : '').'>'.$kLoop.'</option>';

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
                <td><input type="button" name="previous_week" id="previous_week" class="buttonWeek" value="&lt;&lt;" onclick="javascript:goPreviousWeek(this);" /></td>
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
                <td><input type="button" name="next_week" id="next_week" class="buttonWeek" value="&gt;&gt;" onclick="javascript:goNextWeek(this);" /></td>
                <?php
            }

            echo '</tr></tbody></table>';

            # On prépare l’export en iCal
            list($startDay, $startMonth, $startYear) = explode('/', gmdate('d\/m\/Y', $weeks[$idPianoWeek]));
            list($endDay, $endMonth, $endYear) = explode('/', gmdate('d\/m\/Y', intval($weeks[$idPianoWeek] + 6 * ONE_DAY)));

            # On prépare l’URL de l’image
            $imageSrc = URL_ADE.'/imageEt?identifier='.$identifier.'&amp;projectId='.PROJECT_ID.'&amp;idPianoWeek='.$idPianoWeek.'&amp;idPianoDay='.$idPianoDay.'&amp;idTree='.$idTree.'&amp;width='.$width.'&amp;height='.$height.'&amp;lunchName=REPAS&amp;displayMode=1057855&amp;showLoad=false&amp;ttl='.time().'000&amp;displayConfId='.$displayConfId;

            # Et on affiche les liens et l’image
            echo '<p><a href="'.URL_ADE.'/custom/modules/plannings/anonymous_cal.jsp?resources='.$idTree.'&amp;projectId='.PROJECT_ID.'&amp;startDay='.$startDay.'&amp;startMonth='.$startMonth.'&amp;startYear='.$startYear.'&amp;endDay='.$endDay.'&amp;endMonth='.$endMonth.'&amp;endYear='.$endYear.'&amp;calType=ical" title="Exporter au format iCalendar ICS/VCS"><strong>Exporter au format iCal</strong></a> / <a href="'.$imageSrc.'">Télécharger l’image</a><br /><img src="'.$imageSrc.'" alt="Serveur non-accessible ou mise à jour requise" /></p>';
            ?>
            <p><input type="checkbox" name="saturday" id="saturday" value="yes" onchange="document.getElementById('submit').click();"<?php echo ($saturday == 'yes') ? ' checked="checked"' : ''; ?> /><label for="saturday"> Samedi</label> <input type="checkbox" name="sunday" id="sunday" value="yes" onchange="document.getElementById('submit').click();"<?php echo ($sunday == 'yes') ? ' checked="checked"' : ''; ?> /><label for="sunday"> Dimanche</label>
            <br />
            <select id="displayConfId" name="displayConfId" onchange="document.getElementById('submit').click();">
                <option value="41"<?php echo ($displayConfId == 41) ? SELECTED : ''; ?>>Horizontal</option>
                <option value="8"<?php echo ($displayConfId == 8) ? SELECTED : ''; ?>>Vertical</option>
            </select>

            <select id="width" name="width" onchange="document.getElementById('submit').click();">
                <?php
                echo '<option value="'.WIDTH.'" '.(($width == WIDTH) ? SELECTED : '').'>'.WIDTH.' x '.HEIGHT.' (par défaut)</option>';

                foreach($dimensions as $dWidth => $dHeight)
                    echo '<option value="'.$dWidth.'" '.(($width == $dWidth) ? SELECTED : '').'>'.$dWidth.' x '.$dHeight.'</option>';
                ?>
            </select></p>

            <p><input type="submit" id="submit" name="submit" value="Récupérer le planning" /></p>
        </form>

        <p>&nbsp;</p>

        <p>Copyright © 2012 <a href="https://github.com/Yurienu/PlanningIUTInfo">Planning IUT Info</a></p>
    </body>
</html>