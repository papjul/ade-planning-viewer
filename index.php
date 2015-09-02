<?php

/**
 * Planning IUT Info
 * Copyright © 2012-2015 Julien Papasian
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License
 * as published by Affero; either version 3 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Affero General Public License for more details.
 *
 * You should have received a copy of the Affero General Public
 * License along with this program. If not, see
 * <https://www.gnu.org/licenses/agpl-3.0.html>.
 */
### Initialisation
define('ROOT', dirname(__FILE__));

# En-tête
header('Content-Type: text/html; charset=utf-8');

## Récupération de la configuration
$file = array('conf' => file_get_contents(ROOT . '/data/constants.json'),
    'resources' => file_get_contents(ROOT . '/data/resources.json'),
    'dimensions' => file_get_contents(ROOT . '/data/dimensions.json'));

$conf = json_decode($file['conf'], true);
$resources = json_decode($file['resources'], true);
$dimensions = json_decode($file['dimensions'], true);
$file['identifier'] = file(ROOT . '/data/identifier', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

# Récupération du cookie
if (isset($_COOKIE[$conf['COOKIE_NAME']])) {
    $perconf = json_decode($_COOKIE[$conf['COOKIE_NAME']], true);
}

# Enregistrement des données POST en cookie
if (isset($_POST['idPianoWeek'])) {
    $perconf = array('idTree' => isset($_POST['idTree']) ? $_POST['idTree'] : 0,
        'idPianoWeek' => $_POST['idPianoWeek'],
        'saturday' => isset($_POST['saturday']) ? 'yes' : 'no',
        'sunday' => isset($_POST['sunday']) ? 'yes' : 'no',
        'displayConfId' => $_POST['displayConfId'],
        'width' => $_POST['width']);

    setcookie($conf['COOKIE_NAME'], json_encode($perconf), time() + 365 * 24 * 3600, '/', null, false, true);
}

## Création des associations numéro de semaine → timestamp dans un tableau
$weeks = array();
$already_selected = false;

# Boucle sur NB_WEEKS semaines
$timestamp = $conf['FIRST_WEEK'];
for ($i = 0; $i < $conf['NB_WEEKS']; ++$i) {
    $weeks[$i] = gmdate('d\/m\/Y', $timestamp);

    # Semaine suivante (seulement 6 jours pour l’instant pour capter la semaine courante au dimanche)
    $timestamp += 6 * 24 * 3600;

    # S’il s’agit de la semaine courante, on note la valeur pour plus tard
    if (!$already_selected && $timestamp > time()) {
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
$sunday = isset($perconf) ? $perconf['sunday'] : $conf['SUNDAY'];
$idPianoDay = '0,1,2,3,4' . ($saturday == 'yes' ? ',5' : '') . '' . ($sunday == 'yes' ? ',6' : '');

# Le(s) groupe(s) concernés
$idTree = (isset($perconf)) ? $perconf['idTree'] : explode(',', $conf['ID_TREE']);

# Les dimensions
$width = isset($perconf) ? intval($perconf['width']) : $conf['WIDTH'];

if (isset($dimensions[$width])) {
    $height = $dimensions[$width];
} else {
    $width = $conf['WIDTH'];
    $height = $conf['HEIGHT'];
}

# Le format (horizontal/vertical)
$displayConfId = isset($perconf) ? intval($perconf['displayConfId']) : $conf['DISPLAY_CONF_ID'];

# On prépare l’export en iCal
list($startDay, $startMonth, $startYear) = explode('/', gmdate('d\/m\/Y', $conf['FIRST_WEEK']));
list($endDay, $endMonth, $endYear) = explode('/', gmdate('d\/m\/Y', intval($conf['FIRST_WEEK'] + ($conf['NB_WEEKS'] * 7 * 24 * 3600))));

# On prépare l’URL de l’image
$img_src = (implode(',', $idTree) != 0) ? $conf['URL_ADE'] . '/imageEt?identifier=' . $identifier . '&amp;projectId=' . $conf['PROJECT_ID'] . '&amp;idPianoWeek=' . $idPianoWeek . '&amp;idPianoDay=' . $idPianoDay . '&amp;idTree=' . implode(',', $idTree) . '&amp;width=' . $width . '&amp;height=' . $height . '&amp;lunchName=REPAS&amp;displayMode=1057855&amp;showLoad=false&amp;ttl=' . time() . '000&amp;displayConfId=' . $displayConfId : 'img/bgAdeBlanc.png';

### Préparation du template
require_once('view/main.phtml');

/** EOF /**/