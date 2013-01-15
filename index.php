<?php
/** 
 * Planning IUT Info
 * Copyright © 2012-2013 Julien Papasian
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

# En-tête
header('Content-Type: text/html; charset=utf-8');

## Récupération de la configuration
$file = array('conf'       => file_get_contents(ROOT.'/config/constants.conf'),
              'groups'     => file_get_contents(ROOT.'/config/groups.conf'),
              'dimensions' => file_get_contents(ROOT.'/config/dimensions.conf'));

$conf       = json_decode($file['conf']);
$groups     = json_decode($file['groups']);
$dimensions = json_decode($file['dimensions']);
$file['identifier'] = file($conf->URL_IDENTIFIER, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

# On donne le cookie à bouffer au navigo le plus tôt possible
if(isset($_POST['idTree']))
  setcookie('idTree', implode(',', $_POST['idTree']), time() + 365 * 24 * 3600, null, null, false, true);

## Création des associations numéro de semaine → timestamp dans un tableau
$weeks = array();
$already_selected = false;

# Boucle sur NB_WEEKS semaines
$timestamp = $conf->FIRST_WEEK;
for($i = 0; $i < $conf->NB_WEEKS; ++$i)
{
  $weeks[$i] = $timestamp;

  # Semaine suivante (seulement 6 jours pour l’instant pour capter la semaine courante au dimanche)
  $timestamp += 6 * 24 * 3600;

  # S’il s’agit de la semaine courante, on note la valeur pour plus tard
  if(!$already_selected && $timestamp > time())
  {
    $current_week = $i;
    $already_selected = true;
  }

  # Semaine suivante (ajout du jour manquant pour l’itération suivante)
  $timestamp += 24 * 3600;
}

### On commence à noter les paramètres qui seront nécessaires pour la génération de l’image
# On utilise aléatoirement un des identifier à notre disponibilité
$identifier = $file['identifier'][rand(0, count($file['identifier']) - 1)];

# La semaine à afficher
$idPianoWeek = isset($_POST['idPianoWeek']) ? intval($_POST['idPianoWeek']) : $current_week;

# Les jours de la semaine
$saturday = $conf->SATURDAY;
$sunday   = $conf->SUNDAY;
if(isset($_POST['submit']))
{
  $saturday = isset($_POST['saturday']) ? 'yes' : 'no';
  $sunday = isset($_POST['sunday']) ? 'yes' : 'no';
}
$idPianoDay = '0,1,2,3,4'.($saturday == 'yes' ? ',5' : '').''.($sunday == 'yes' ? ',6' : '');

# Le(s) groupe(s) concernés
$idTree = array();
if(isset($_POST['idTree'])) $idTree = $_POST['idTree'];
elseif(isset($_COOKIE['idTree'])) $idTree = explode(',', $_COOKIE['idTree']);
else $idTree = explode(',', $conf->ID_TREE);

# Les dimensions
$width = isset($_POST['width']) ? intval($_POST['width']) : $conf->WIDTH;

if(array_key_exists($width, $dimensions))
  $height = $dimensions[$width];

else
{
  $width  = $conf->WIDTH;
  $height = $conf->HEIGHT;
}

# Le format (horizontal/vertical)
$displayConfId = isset($_POST['displayConfId']) ? intval($_POST['displayConfId']) : $conf->DISPLAY_CONF_ID;

# On prépare l’URL de l’image
$img_src = (implode(',', $idTree) != 0) ? $conf->URL_ADE.'/imageEt?identifier='.$identifier.'&amp;projectId='.$conf->PROJECT_ID.'&amp;idPianoWeek='.$idPianoWeek.'&amp;idPianoDay='.$idPianoDay.'&amp;idTree='.implode(',', $idTree).'&amp;width='.$width.'&amp;height='.$height.'&amp;lunchName=REPAS&amp;displayMode=1057855&amp;showLoad=false&amp;ttl='.time().'000&amp;displayConfId='.$displayConfId : 'img/bgExpertBlanc.gif';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
  <meta charset="utf-8" />

  <title>Planning IUT Info</title>
  <meta name="description" content="Planning IUT Info est une application en PHP pour récupérer le planning de l’ADE sur l’ENT de l’IUT Informatique d’Aix-en-Provence via une interface suivant le principe KISS" />
  <meta name="keywords" content="planning, emploi, temps, ade, ent, iut, dut, info, informatique, aix, aix-en-provence" />
  <meta name="author" content="Julien Papasian" />
  <meta name="robots" content="noindex, nofollow" />

  <?= (implode(',', $idTree) != 0) ? '<link rel="alternate" type="application/rss+xml" title="Flux RSS des '.$conf->NB_DAYS_RSS.' jours à venir" href="'.$conf->URL_ADE.'/rss?projectId='.$conf->PROJECT_ID.'&amp;resources='.implode(',', $idTree).'&amp;nbDays='.$conf->NB_DAYS_RSS.'" />' : '' ?>
  <link rel="stylesheet" title="Planning" type="text/css" href="static/style.css" />

  <script type="text/javascript">
  // <![CDATA[
  /* Quelques trucs en CSS pour ceux qui ont JavaScript désactivé */
  document.write('<style type="text/css">input[type="submit"] { display: none; } button.week { display: inline; }</style>');
  // ]]>
  </script>
</head>
<body>
  <header><h1>Planning IUT Info</h1></header>

  <hr />

  <form id="planning" method="post" action="index.php">
    <p><a href="export.php" title="Exporter le planning au format iCalendar ICS/VCS"><strong>Exporter l’agenda</strong></a></p>

    <table class="selectors">
      <tbody>
        <tr>
          <td colspan="3">
            <select name="idTree[]" id="idTree" multiple="multiple">
              <?php
              $first_optgroup = true;
              foreach($groups as $kLoop => $vLoop)
              {
                if($vLoop != 0)
                  echo '<option value="'.$vLoop.'"'.((in_array($vLoop, $idTree)) ? ' selected="selected"' : '').'>'.$kLoop.'</option>';

                else
                {
                  echo (!$first_optgroup ? '</optgroup>' : '').'<optgroup label="'.$kLoop.'">';
                  $first_optgroup = false;
                }
              }
              echo '</optgroup>';
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <?= ($idPianoWeek > 0) ? '<button id="previous_week" class="week">&lt;&lt;</button>' : '&nbsp;' ?>
          </td>

          <td>
            <select name="idPianoWeek" id="idPianoWeek">
              <?php
              # Boucle sur NB_WEEKS semaines
              for($i = 0; $i < $conf->NB_WEEKS; ++$i)
              {
                echo '<option value="'.$i.'"'.(($idPianoWeek == $i) ? ' selected="selected"' : '').'>Semaine du '.gmdate('d\/m\/Y', $weeks[$i]).'</option>';
                $timestamp += 7 * 24 * 3600;
              }
              ?>
            </select>
          </td>

          <td>
            <?= ($idPianoWeek < $conf->NB_WEEKS - 1) ? '<button id="next_week" class="week">&gt;&gt;</button>' : '&nbsp;' ?>
          </td>
        </tr>
      </tbody>
    </table>

    <hr />
    <p><img id="img_planning" src="<?= $img_src ?>" alt="Serveur inaccessible ou mise à jour requise" /></p>
    <hr />

    <p>
      <input type="checkbox" name="saturday" id="saturday" value="yes"<?= ($saturday == 'yes') ? ' checked="checked"' : '' ?> /><label for="saturday"> Samedi</label>
      <input type="checkbox" name="sunday" id="sunday" value="yes"<?= ($sunday == 'yes') ? ' checked="checked"' : '' ?> /><label for="sunday"> Dimanche</label>
    </p>

    <p>
      <select id="displayConfId" name="displayConfId">
        <option value="41"<?= ($displayConfId == 41) ? ' selected="selected"' : '' ?>>Horizontal</option>
        <option value="8"<?= ($displayConfId == 8) ? ' selected="selected"' : '' ?>>Vertical</option>
      </select>
      <select id="width" name="width">
        <?php
        echo '<option value="'.$conf->WIDTH.'"'.(($width == $conf->WIDTH) ? ' selected="selected"' : '').'>'.$conf->WIDTH.' x '.$conf->HEIGHT.' (par défaut)</option>';

        foreach($dimensions as $dWidth => $dHeight)
          echo '<option value="'.$dWidth.'"'.(($width == $dWidth) ? ' selected="selected"' : '').'>'.$dWidth.' x '.$dHeight.'</option>';
        ?>
      </select>
    </p>

    <p><input type="submit" name="submit" value="Récupérer le planning" /></p>
  </form>

  <hr />

  <footer><p>Copyright © 2012-<?= date('Y') ?> <a href="https://github.com/Yurienu/PlanningIUTInfo">Planning IUT Info</a></p></footer>

  <script type="text/javascript">
  // <![CDATA[
  var conf       = <?= $file['conf'] ?>;
  var groups     = <?= $file['groups'] ?>;
  var dimensions = <?= $file['dimensions'] ?>;
  var identifier = '<?= $identifier ?>';
  dimensions[<?= $conf->WIDTH ?>] = <?= $conf->HEIGHT ?>;
  // ]]>
  </script>
  <script type="text/javascript" src="static/form.js"></script>
  <script type="text/javascript" src="static/listeners.js"></script>
</body>
</html>