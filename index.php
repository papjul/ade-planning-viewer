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

# Activation des erreurs
if(DEBUG)
{
  error_reporting(E_ALL);
  ini_set('display_errors', true);
}

# On donne le cookie à bouffer au navigo le plus tôt possible
if(isset($_POST['idTree']))
  setcookie('idTree', implode(',', $_POST['idTree']), time() + ONE_YEAR, null, null, false, true);

## Création des groupes dans un tableau de tableaux
$groups = array('1re année'    => 0,
                  'Groupe 1A'  => 8385,
                  'Groupe 1B'  => 8386,
                  'Groupe 2A'  => 8387,
                  'Groupe 2B'  => 8388,
                  'Groupe 3A'  => 8389,
                  'Groupe 3B'  => 8390,
                  'Groupe 4A'  => 8391,
                  'Groupe 4B'  => 8392,
                  'Groupe 5A'  => 8393,
                  'Groupe 5B'  => 8394,
                '2e année'     => 0,
                  'Groupe 1A ' => 8400,
                  'Groupe 1B ' => 8401,
                  'Groupe 2A ' => 8402,
                  'Groupe 2B ' => 8403,
                  'Groupe 3A ' => 8404,
                  'Groupe 3B ' => 8405,
                  'Groupe 4A ' => 3772,
                  'Groupe 4B ' => 3773,
                'Licence Pro'  => 0,
                  'LP'         => 6445,
                'Enseignants'  => 0,
                  'BERNE Michel'         => 5156,
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
                  'MARTIN-NEVOT Mickael' => 4533,
                  'MONNET Marlène'       => 9836,
                  'NEDJAR Sebastien'     => 578,
                  'PAIN BARRE Cyril'     => 5179,
                  'RISCH Vincent'        => 5173,
                  'SLEZAK Eileen'        => 5670,
                  'VAQUIERI Josee'       => 5345,
                  'YAHI Safa'            => 6323);

## Création des associations numéro de semaine → timestamp dans un tableau
$weeks = array();
$already_selected = false;

# Boucle sur NB_WEEKS semaines
$timestamp = FIRST_WEEK;
for($i = 0; $i < NB_WEEKS; ++$i)
{
  $weeks[$i] = $timestamp;

  # Semaine suivante (seulement 6 jours pour l’instant pour capter la semaine courante au dimanche)
  $timestamp += 6 * ONE_DAY;

  # S’il s’agit de la semaine courante, on note la valeur pour plus tard
  if(!$already_selected && $timestamp > time())
  {
    $current_week = $i;
    $already_selected = true;
  }

  # Semaine suivante (ajout du jour manquant pour l’itération suivante)
  $timestamp += ONE_DAY;
}

# Les dimensions
$dimensions = array(320 => 240, 640 => 480, 800 => 600, 1024 => 768, 1280 => 720, 1366 => 768, 1600 => 1024, 1920 => 1080);

### On commence à noter les paramètres qui seront nécessaires pour la génération de l’image
# On utilise aléatoirement un des identifier à notre disponibilité
$file_identifier = file(URL_IDENTIFIER);
$rand = rand(0, count($file_identifier) - 1);
$identifier = $file_identifier[$rand];

# La semaine à afficher
$idPianoWeek = isset($_POST['idPianoWeek']) ? intval($_POST['idPianoWeek']) : $current_week;

# Les jours de la semaine
$saturday = SATURDAY;
$sunday = SUNDAY;
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
else $idTree = explode(',', ID_TREE);

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

# On prépare l’URL de l’image
$img_src = (implode(',', $idTree) != 0) ? URL_ADE.'/imageEt?identifier='.$identifier.'&amp;projectId='.PROJECT_ID.'&amp;idPianoWeek='.$idPianoWeek.'&amp;idPianoDay='.$idPianoDay.'&amp;idTree='.implode(',', $idTree).'&amp;width='.$width.'&amp;height='.$height.'&amp;lunchName=REPAS&amp;displayMode=1057855&amp;showLoad=false&amp;ttl='.time().'000&amp;displayConfId='.$displayConfId : 'img/bgExpertBlanc.gif';
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

  <link rel="alternate" type="application/rss+xml" title="Flux RSS des <?= NB_DAYS_RSS ?> jours à venir" href="<?php echo URL_ADE, '/rss?projectId=', PROJECT_ID, '&amp;resources=', $idTree, '&amp;nbDays=', NB_DAYS_RSS; ?>" />
  <link rel="stylesheet" title="Planning" type="text/css" href="style.css" />

  <script type="text/javascript">
  // <![CDATA[
  /* Quelques trucs en CSS pour ceux qui ont JavaScript désactivé */
  document.write('<style type="text/css"><?= !DEBUG ? 'input[type="submit"] { display: none; } ' : '' ?>input[type="button"].week { display: inline; }</style>');
  // ]]>
  </script>
</head>
<body>
  <header><h1>Planning IUT Info</h1></header>

  <form id="planning" name="myform" method="post" action="index.php">
    <table>
      <tbody>
        <tr>
          <td colspan="3">
            <select name="idTree[]" id="idTree" onchange="check_groups()" multiple="multiple">
              <?php
              $first_optgroup = true;
              foreach($groups as $kLoop => $vLoop)
              {
                if($vLoop != 0)
                  echo '<option value="', $vLoop, '"', (in_array($vLoop, $idTree)) ? SELECTED : '', '>', $kLoop, '</option>';

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
            <?= ($idPianoWeek > 0) ? '<input type="button" name="previous_week" id="previous_week" class="week" value="&lt;&lt;" onclick="go_previous_week()" />' : '&nbsp;' ?>
          </td>

          <td>
            <select name="idPianoWeek" id="idPianoWeek" onchange="submit_form()">
              <?php
              # Boucle sur NB_WEEKS semaines
              for($i = 0; $i < NB_WEEKS; ++$i)
              {
                echo '<option value="', $i, '"', ($idPianoWeek == $i) ? SELECTED : '', '>Semaine du ', gmdate('d\/m\/Y', $weeks[$i]), '</option>';
                $timestamp += ONE_WEEK;
              }
              ?>
            </select>
          </td>

          <td>
            <?= ($idPianoWeek < NB_WEEKS - 1) ? '<input type="button" name="next_week" id="next_week" class="week" value="&gt;&gt;" onclick="go_next_week()" />' : '&nbsp;' ?>
          </td>
        </tr>
      </tbody>
    </table>

    <p><a href="export.php" title="Exporter le planning au format iCalendar ICS/VCS"><strong>Exporter l’agenda</strong></a></p>

    <p><img id="img_planning" src="<?= $img_src ?>" alt="Serveur inaccessible ou mise à jour requise" /></p>

    <p><a id="href_planning" href="<?= $img_src ?>">Télécharger l’image</a></p>

    <hr />

    <p>
      <input type="checkbox" name="saturday" id="saturday" value="yes" onchange="submit_form()"<?= ($saturday == 'yes') ? ' checked="checked"' : '' ?> /><label for="saturday"> Samedi</label>
      <input type="checkbox" name="sunday" id="sunday" value="yes" onchange="submit_form()"<?= ($sunday == 'yes') ? ' checked="checked"' : '' ?> /><label for="sunday"> Dimanche</label>
    </p>

    <p>
      <select id="displayConfId" name="displayConfId" onchange="submit_form()">
        <option value="41"<?= ($displayConfId == 41) ? SELECTED : '' ?>>Horizontal</option>
        <option value="8"<?= ($displayConfId == 8) ? SELECTED : '' ?>>Vertical</option>
      </select>
      <select id="width" name="width" onchange="submit_form()">
        <?php
        echo '<option value="', WIDTH, '" ', ($width == WIDTH) ? SELECTED : '', '>', WIDTH, ' x ', HEIGHT, ' (par défaut)</option>';

        foreach($dimensions as $dWidth => $dHeight)
          echo '<option value="', $dWidth, '" ', ($width == $dWidth) ? SELECTED : '', '>', $dWidth, ' x ', $dHeight, '</option>';
        ?>
      </select>
    </p>

    <p><input type="submit" id="submit" name="submit" value="Récupérer le planning" /></p>
  </form>

  <p class="center"><em>L’image peut mettre un certain temps à s’actualiser en fonction de votre connexion</em></p>
  <p class="center"><em>Vous pouvez sélectionner plusieurs groupes à la fois en maintenant Ctrl ou Maj dans la liste</em></p>

  <hr />

  <footer><p>Copyright © 2012 <a href="https://github.com/Yurienu/PlanningIUTInfo">Planning IUT Info</a></p></footer>

  <!-- Les scripts -->
  <script type="text/javascript">
  // <![CDATA[
  /* Renvoie un array contenant les éléments sélectionnés d’un select multiple */
  function getMultipleSelectById(selectId) {
    var elmt = document.getElementById(selectId);
    var values = new Array();
    var j = 0;

    for(var i = 0; i < elmt.options.length; ++i)
    {
      if(elmt.options[i].selected == true)
      {
        values[j] = elmt.options[i].value;
        ++j;
      }
    }

    return values;
  }

  /* Envoyer le formulaire */
  function submit_form() {
    var dimensions = new Array();
    <?php
    echo "dimensions[".WIDTH."] = ".HEIGHT.";\n";

    foreach($dimensions as $dWidth => $dHeight)
      echo "dimensions[".$dWidth."] = ".$dHeight.";\n";
    ?>

    var idPianoWeek   = document.getElementById('idPianoWeek').value;
    var idPianoDay    = "0,1,2,3,4";
    var idTree        = getMultipleSelectById('idTree');
    var width         = document.getElementById('width').value;
    var height        = dimensions[parseInt(width)];
    var displayConfId = document.getElementById('displayConfId').value;

    if(document.getElementById('saturday').checked == true) idPianoDay += ",5";
    if(document.getElementById('sunday').checked == true) idPianoDay += ",6";

    if(idTree != '') {
      var url = "<?= URL_ADE ?>/imageEt?identifier=<?= $identifier ?>\&projectId=<?= PROJECT_ID ?>\&idPianoWeek=" + idPianoWeek + "\&idPianoDay=" + idPianoDay + "\&idTree=" + idTree + "\&width=" + width + "\&height=" + height + "\&lunchName=REPAS\&displayMode=1057855\&showLoad=false\&ttl=<?= time() ?>000\&displayConfId=" + displayConfId + "";
    }
    else {
      var url = "img/bgExpertBlanc.gif";
    }

    document.getElementById('img_planning').src = url;
    document.getElementById('href_planning').href = url;
  }

  /* Permet d’envoyer en cookie le nouveau groupe sélectionné */
  function check_groups() {
    var idTree = getMultipleSelectById('idTree');

    if(idTree != '') {
      var date = new Date();
      date.setTime(date.getTime() + <?= ONE_YEAR ?>);
      var expires = "; expires=" + date.toGMTString();

      document.cookie = "idTree="+idTree+expires+"; path=/";
    }

    submit_form();
  }

  /* Bouton Semaine précédente */
  function go_previous_week() {
    document.getElementById('idPianoWeek').selectedIndex = parseInt(document.getElementById('idPianoWeek').selectedIndex) - 1;
    submit_form();
  }

  /* Bouton Semaine suivante */
  function go_next_week() {
    document.getElementById('idPianoWeek').selectedIndex = parseInt(document.getElementById('idPianoWeek').selectedIndex) + 1;
    submit_form();
  }
  // ]]>
  </script>
</body>
</html>