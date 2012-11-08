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

if(!isset($_SESSION['idTree'])) exit('Groupe non sélectionné');
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
        
        <title>Planning IUT Info — Exporter l’agenda</title>
        <link title="Planning" type="text/css" rel="stylesheet" href="style.css" />
    </head>
    <body>
        <h2>Planning IUT Info — Exporter l’agenda</h2>

        <p>&nbsp;</p>

        <p><strong>Le planning de l’IUT Info est souvent sujet à des modifications, c’est pourquoi si vous voulez avoir un agenda toujours à jour, <ins>vous devez le synchroniser régulièrement</ins>.</strong></p>

        <p>Si vous ne savez pas comment faire, j’ai choisi de vous présenter le service <strong>Google Calendar</strong> car il est facilement consultable sur n’importe quel navigateur mais peut aussi être accédé depuis la plupart des smartphones (Android, iOS, Windows, etc).</p>

        <p>Commencez par aller <a href="http://www.google.com/calendar">sur le site</a>, connectez-vous à votre compte Google si ce n’est pas déjà fait et cliquez sur « Ajouter par URL », comme indiqué ci-dessous :</p>

        <p class="centre"><img src="googlecalendar.png" alt="Image non trouvée" /></p>

        <p>Ajoutez ensuite l’URL suivante (il s’agit de l’agenda sur toute l’année du groupe que vous consultiez juste avant de venir sur cette page) :</p>

        <?php
        # On prépare l’export en iCal
        list($startDay, $startMonth, $startYear) = explode('/', gmdate('d\/m\/Y', FIRST_WEEK));
        list($endDay, $endMonth, $endYear) = explode('/', gmdate('d\/m\/Y', intval(FIRST_WEEK + (NB_WEEKS * ONE_WEEK))));

        echo '<pre>'.URL_ADE.'/custom/modules/plannings/anonymous_cal.jsp?resources='.$_SESSION['idTree'].'&amp;projectId='.PROJECT_ID.'&amp;startDay='.$startDay.'&amp;startMonth='.$startMonth.'&amp;startYear='.$startYear.'&amp;endDay='.$endDay.'&amp;endMonth='.$endMonth.'&amp;endYear='.$endYear.'&amp;calType=ical</pre>';
        ?>

        <p><strong>Consultation de l’agenda :</strong></p>

        <ul>
            <li><strong>Navigateur Internet :</strong> Cliquez ensuite sur le planning qui vient de s’ajouter dans « Autres agendas » pour consulter le planning précédemment ajouté</li>
            <li><strong>Android :</strong> Dans les Paramètres système, dans Comptes, vérifiez que la synchronisation de l’agenda de votre compte Google est active. Utilisez ensuite l’application Agenda.</li>
            <li><strong>iOS :</strong> Suivez <a href="https://support.google.com/calendar/bin/answer.py?hl=fr&amp;answer=151674&amp;topic=13950&amp;ctx=topic">les instructions décrites ici</a>.</li>
            <li><strong>Autres smartphones :</strong> Google a mis en place un outil pour synchroniser l’agenda Google Calendar avec l’application agenda native de votre smartphone : <a href="http://www.google.com/mobile/sync/index.html">Google Sync</a>.</li>
        </ul>

        <p>&nbsp;</p>

        <p class="centre">Copyright © 2012 <a href="https://github.com/Yurienu/PlanningIUTInfo">Planning IUT Info</a></p>
    </body>
</html>