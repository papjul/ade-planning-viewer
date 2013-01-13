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

# En-tête
header('Content-Type: text/html; charset=utf-8');

## Récupération de la configuration
$file = array('conf'   => file_get_contents(ROOT.'/config/constants.conf'),
              'groups' => file_get_contents(ROOT.'/config/groups.conf'));

$conf   = json_decode($file['conf']);
$groups = json_decode($file['groups']);

# On prépare l’export en iCal
list($startDay, $startMonth, $startYear) = explode('/', gmdate('d\/m\/Y', $conf->FIRST_WEEK));
list($endDay, $endMonth, $endYear) = explode('/', gmdate('d\/m\/Y', intval($conf->FIRST_WEEK + ($conf->NB_WEEKS * 7 * 24 * 3600))));
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
  <meta charset="utf-8" />

  <title>Planning IUT Info — Exporter l’agenda</title>
  <meta name="description" content="Planning IUT Info est une application en PHP pour récupérer le planning de l’ADE sur l’ENT de l’IUT Informatique d’Aix-en-Provence via une interface suivant le principe KISS" />
  <meta name="keywords" content="planning, emploi, temps, ade, ent, iut, dut, info, informatique, aix, aix-en-provence" />
  <meta name="author" content="Julien Papasian" />
  <meta name="robots" content="noindex, nofollow" />

  <link rel="stylesheet" title="Planning" type="text/css" href="static/style.css" />

  <script type="text/javascript">
  // <![CDATA[
  /* Quelques trucs en CSS pour ceux qui ont JavaScript désactivé */
  document.write('<style type="text/css">input[type="submit"] { display: none; }</style>');
  // ]]>
  </script>
</head>
<body>
  <header><h1>Planning IUT Info — Exporter l’agenda</h1></header>

  <hr />

  <div id="content">
    <p><strong>Le planning de l’IUT Info est souvent sujet à des modifications, c’est pourquoi si vous voulez avoir un agenda toujours à jour, <ins>vous devez le synchroniser régulièrement</ins>.</strong></p>

    <p>Voici ce que vous serez capable de faire à la fin de ce tutoriel :</p>

    <p class="center"><strong>Navigateur Internet (Firefox, Opera, Chrome, etc)</strong></p>

    <p class="center"><a href="img/browser.png"><img src="img/browser.min.png" alt="" /></a></p>

    <p class="center"><strong>Android</strong></p>

    <p class="center"><a href="img/android1.png"><img src="img/android1.min.png" alt="" /></a> <a href="img/android2.png"><img src="img/android2.min.png" alt="" /></a><br /><a href="img/android3.png"><img src="img/android3.min.png" alt="" /></a></p>

    <p class="center"><strong>iOS (iPhone, iPod, etc)</strong></p>

    <p class="center"><a href="img/ios.jpg"><img src="img/ios.min.jpg" alt="" /></a></p>

    <p>Si vous ne savez pas comment faire, j’ai choisi de vous présenter le service <strong>Google Calendar</strong> car il est facilement consultable sur n’importe quel navigateur mais peut aussi être accédé depuis la plupart des smartphones (Android, iOS, Windows, etc).</p>

    <p>Commencez par aller <a href="http://www.google.com/calendar">sur le site</a>, connectez-vous à votre compte Google si ce n’est pas déjà fait et cliquez sur « Ajouter par URL », comme indiqué ci-dessous :</p>

    <p class="center"><img src="img/googlecalendar1.png" alt="Image non trouvée" /></p>

    <hr />

    <p>Ajoutez ensuite l’URL correspondant au(x) groupe(s) que vous voulez suivre :</p>

    <form method="post" action="export.php#idTree">
      <?php
      # Le(s) groupe(s) concernés
      $idTree = (isset($_POST['idTree'])) ? $_POST['idTree'] : explode(',', 0);
      ?>
      <p>
        <select name="idTree[]" id="idTree" onchange="document.getElementById('submit').click();" multiple="multiple">
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
        <input type="submit" id="submit" name="submit" value="OK" />
      </p>

      <?php
      if(implode(',',$idTree) != 0)
        echo '<fieldset><legend>URL générée</legend><p id="url">'.$conf->URL_ADE.'<wbr />/custom<wbr />/modules<wbr />/plannings<wbr />/anonymous_cal.jsp?<wbr />resources='.implode(',',$idTree).'<wbr />&amp;projectId='.$conf->PROJECT_ID.'<wbr />&amp;startDay='.$startDay.'<wbr />&amp;startMonth='.$startMonth.'<wbr />&amp;startYear='.$startYear.'<wbr />&amp;endDay='.$endDay.'<wbr />&amp;endMonth='.$endMonth.'<wbr />&amp;endYear='.$endYear.'<wbr />&amp;calType=ical</p></fieldset>';
      ?>
    </form>

    <p class="center"><img src="img/googlecalendar2.png" alt="Image non trouvée" /></p>

    <hr />

    <p><strong>Consultation de l’agenda :</strong></p>

    <ul>
        <li><strong>Navigateur Internet :</strong> Cliquez ensuite sur le planning qui vient de s’ajouter dans « Autres agendas » pour consulter le planning précédemment ajouté</li>
        <li><strong>Android :</strong> Dans les Paramètres système, dans Comptes, vérifiez que la synchronisation de l’agenda de votre compte Google est active. Utilisez ensuite l’application Agenda. Dans le menu déroulant → Agendas à afficher → Cocher l’URL du planning. Dans Agendas à synchroniser, cochez à nouveau l’URL du planning. Validez par OK.</li>
        <li><strong>iOS :</strong> Suivez <a href="https://support.google.com/calendar/bin/answer.py?hl=fr&amp;answer=151674&amp;topic=13950&amp;ctx=topic">les instructions décrites ici</a>.</li>
        <li><strong>Autres smartphones :</strong> Google a mis en place un outil pour synchroniser l’agenda Google Calendar avec l’application agenda native de votre smartphone : <a href="http://www.google.com/mobile/sync/index.html">Google Sync</a>.</li>
    </ul>
  </div>
  <hr />

  <footer><p>Copyright © 2012 <a href="https://github.com/Yurienu/PlanningIUTInfo">Planning IUT Info</a></p></footer>
</body>
</html>