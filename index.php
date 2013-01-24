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

# Force la désactivation des guillemets magiques
if(get_magic_quotes_gpc())
{
  $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
  while(list($key, $val) = each($process))
  {
    foreach($val as $k => $v)
    {
      unset($process[$key][$k]);
      if(is_array($v))
      {
        $process[$key][stripslashes($k)] = $v;
        $process[] = &$process[$key][stripslashes($k)];
      }
      else
        $process[$key][stripslashes($k)] = stripslashes($v);
    }
  }
  unset($process);
}

# En-tête
header('Content-Type: text/html; charset=utf-8');

## Récupération de la configuration
$file = array('conf'       => file_get_contents(ROOT.'/config/constants.json'),
              'ressources' => file_get_contents(ROOT.'/config/ressources.json'),
              'dimensions' => file_get_contents(ROOT.'/config/dimensions.json'));

$conf       = json_decode($file['conf'], true);
$ressources = json_decode($file['ressources'], true);
$dimensions = json_decode($file['dimensions'], true);
$file['identifier'] = file($conf['URL_IDENTIFIER'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

# Récupération du cookie
if(isset($_COOKIE[$conf['COOKIE_NAME']]))
  $perconf = json_decode($_COOKIE[$conf['COOKIE_NAME']], true);

# Enregistrement des données POST en cookie
if(isset($_POST['idPianoWeek']))
{
  $perconf = array('idTree'        => isset($_POST['idTree']) ? $_POST['idTree'] : 0,
                   'idPianoWeek'   => $_POST['idPianoWeek'],
                   'saturday'      => isset($_POST['saturday']) ? 'yes' : 'no',
                   'sunday'        => isset($_POST['sunday']) ? 'yes' : 'no',
                   'displayConfId' => $_POST['displayConfId'],
                   'width'         => $_POST['width']);

  setcookie($conf['COOKIE_NAME'], json_encode($perconf), time() + 365 * 24 * 3600, '/', null, false, true);
}

## Création des associations numéro de semaine → timestamp dans un tableau
$weeks = array();
$already_selected = false;

# Boucle sur NB_WEEKS semaines
$timestamp = $conf['FIRST_WEEK'];
for($i = 0; $i < $conf['NB_WEEKS']; ++$i)
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
$idPianoWeek = isset($perconf) ? intval($perconf['idPianoWeek']) : $current_week;

# Les jours de la semaine
$saturday = isset($perconf) ? $perconf['saturday'] : $conf['SATURDAY'];
$sunday   = isset($perconf) ? $perconf['sunday']   : $conf['SUNDAY'];
$idPianoDay = '0,1,2,3,4'.($saturday == 'yes' ? ',5' : '').''.($sunday == 'yes' ? ',6' : '');

# Le(s) groupe(s) concernés
$idTree = (isset($perconf)) ? $perconf['idTree'] : explode(',', $conf['ID_TREE']);

# Les dimensions
$width = isset($perconf) ? intval($perconf['width']) : $conf['WIDTH'];

if(isset($dimensions[$width]))
  $height = $dimensions[$width];

else
{
  $width  = $conf['WIDTH'];
  $height = $conf['HEIGHT'];
}

# Le format (horizontal/vertical)
$displayConfId = isset($perconf) ? intval($perconf['displayConfId']) : $conf['DISPLAY_CONF_ID'];

# On prépare l’export en iCal
list($startDay, $startMonth, $startYear) = explode('/', gmdate('d\/m\/Y', $conf['FIRST_WEEK']));
list($endDay, $endMonth, $endYear) = explode('/', gmdate('d\/m\/Y', intval($conf['FIRST_WEEK'] + ($conf['NB_WEEKS'] * 7 * 24 * 3600))));

# On prépare l’URL de l’image
$img_src = (implode(',', $idTree) != 0) ? $conf['URL_ADE'].'/imageEt?identifier='.$identifier.'&amp;projectId='.$conf['PROJECT_ID'].'&amp;idPianoWeek='.$idPianoWeek.'&amp;idPianoDay='.$idPianoDay.'&amp;idTree='.implode(',', $idTree).'&amp;width='.$width.'&amp;height='.$height.'&amp;lunchName=REPAS&amp;displayMode=1057855&amp;showLoad=false&amp;ttl='.time().'000&amp;displayConfId='.$displayConfId : 'img/bgExpertBlanc.gif';
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

  <?= (implode(',', $idTree) != 0) ? '<link rel="alternate" type="application/rss+xml" title="Flux RSS des '.$conf['NB_DAYS_RSS'].' jours à venir" href="'.$conf['URL_ADE'].'/rss?projectId='.$conf['PROJECT_ID'].'&amp;resources='.implode(',', $idTree).'&amp;nbDays='.$conf['NB_DAYS_RSS'].'" />' : '' ?>
  <link rel="stylesheet" title="Planning" type="text/css" href="static/style.css" />

  <script type="text/javascript">
  // <![CDATA[
  /* Quelques trucs en CSS pour ceux qui ont JavaScript désactivé */
  document.write('<style type="text/css">');
  document.write('  input[type="submit"] { display: none; }');
  document.write('  button.week { display: inline; }');
  document.write('  #url { display: none; }');
  <?= (implode(',',$idTree) != 0) ? 'document.write(\'  #genbutton { display: inline; }\');' : '' ?>
  document.write('</style>');
  // ]]>
  </script>
</head>
<body>
  <header><h1>Planning IUT Info</h1></header>

  <hr />

  <form id="planning" method="post" action="index.php">
    <table class="selectors">
      <tbody>
        <tr>
          <td colspan="3">
            <select name="idTree[]" id="idTree" multiple="multiple">
              <?php
              $first_optgroup = true;
              foreach($ressources as $kLoop => $vLoop)
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
              for($i = 0; $i < $conf['NB_WEEKS']; ++$i)
              {
                echo '<option value="'.$i.'"'.(($idPianoWeek == $i) ? ' selected="selected"' : '').'>Semaine du '.gmdate('d\/m\/Y', $weeks[$i]).'</option>';
                $timestamp += 7 * 24 * 3600;
              }
              ?>
            </select>
          </td>

          <td>
            <?= ($idPianoWeek < $conf['NB_WEEKS'] - 1) ? '<button id="next_week" class="week">&gt;&gt;</button>' : '&nbsp;' ?>
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
        foreach($dimensions as $dWidth => $dHeight)
          echo '<option value="'.$dWidth.'"'.(($width == $dWidth) ? ' selected="selected"' : '').'>'.$dWidth.' x '.$dHeight.''.(($dWidth == $conf['WIDTH'] && $dHeight == $conf['HEIGHT']) ? ' (par défaut)' : '').'</option>';
        ?>
      </select>
    </p>

    <p><input type="submit" name="submit" value="Récupérer le planning" /><button id="genbutton">Exporter en iCal</button></p>

    <?php
    echo '<fieldset id="url"><legend>URL d’export du calendrier au format iCal</legend><p>'.$conf['URL_ADE'].'<wbr />/custom<wbr />/modules<wbr />/plannings<wbr />/anonymous_cal.jsp?<wbr />resources=<span id="resources">'.implode(',',$idTree).'</span><wbr />&amp;projectId='.$conf['PROJECT_ID'].'<wbr />&amp;startDay='.$startDay.'<wbr />&amp;startMonth='.$startMonth.'<wbr />&amp;startYear='.$startYear.'<wbr />&amp;endDay='.$endDay.'<wbr />&amp;endMonth='.$endMonth.'<wbr />&amp;endYear='.$endYear.'<wbr />&amp;calType=ical</p></fieldset>';
    ?>
  </form>

  <hr />

  <footer><p>Copyright © 2012-<?= date('Y') ?> <a href="https://github.com/Yurienu/PlanningIUTInfo">Planning IUT Info</a></p></footer>

  <script type="text/javascript">
  // <![CDATA[
  var conf       = <?= json_encode($conf) ?>;
  var dimensions = <?= json_encode($dimensions) ?>;
  var identifier = '<?= $identifier ?>';
  // ]]>
  </script>
  <script type="text/javascript" src="static/form.js"></script>
  <script type="text/javascript" src="static/listeners.js"></script>
</body>
</html>