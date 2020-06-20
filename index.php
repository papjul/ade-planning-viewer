<?php

/**
 * This file is part of ADE Planning Viewer.
 * Copyright © 2012-2020 Julien Papasian
 *
 * ADE Planning Viewer is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License
 * as published by Affero; either version 3 of the License, or (at
 * your option) any later version.
 *
 * ADE Planning Viewer is distributed in the hope that it will be useful,
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

require_once(ROOT . '/app/app.php');

$planning = new Planning();

## Récupération de la configuration
$conf = $planning->getConf();
$resources = $planning->getResources();
$displays = $planning->getDisplays();
$dimensions = $planning->getDimensions();

# Récupération de la configuration personnalisée
$custom_conf = $planning->getCustomConf();

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
$identifier = $planning->getIdentifier();

# La semaine à afficher
$idPianoWeek = !is_null($custom_conf) ? intval($custom_conf['idPianoWeek']) : $current_week;

# Les jours de la semaine
$saturday = !is_null($custom_conf) ? $custom_conf['saturday'] : $conf['SATURDAY'];
$sunday = !is_null($custom_conf) ? $custom_conf['sunday'] : $conf['SUNDAY'];
$idPianoDay = '0,1,2,3,4' . ($saturday == 'yes' ? ',5' : '') . '' . ($sunday == 'yes' ? ',6' : '');

# Le(s) groupe(s) concernés
$idTree = (!is_null($custom_conf)) ? $custom_conf['idTree'] : explode(',', $conf['ID_TREE']);

# Les dimensions
$width = !is_null($custom_conf) ? intval($custom_conf['width']) : $conf['WIDTH'];

if (isset($dimensions[$width])) {
    $height = $dimensions[$width];
} else {
    $width = $conf['WIDTH'];
    $height = $conf['HEIGHT'];
}

# Le format (horizontal/vertical)
$displayConfId = !is_null($custom_conf) ? intval($custom_conf['displayConfId']) : $conf['DISPLAY_CONF_ID'];

# On prépare l’export en iCal
list($startDay, $startMonth, $startYear) = explode('/', gmdate('d\/m\/Y', $conf['FIRST_WEEK']));
list($endDay, $endMonth, $endYear) = explode('/', gmdate('d\/m\/Y', intval($conf['FIRST_WEEK'] + ($conf['NB_WEEKS'] * 7 * 24 * 3600))));

# On prépare l’URL de l’image
$img_src = (implode(',', $idTree) != 0) ? $conf['URL_ADE'] . '/imageEt?identifier=' . $identifier . '&amp;projectId=' . $conf['PROJECT_ID'] . '&amp;idPianoWeek=' . $idPianoWeek . '&amp;idPianoDay=' . $idPianoDay . '&amp;idTree=' . implode(',', $idTree) . '&amp;width=' . $width . '&amp;height=' . $height . '&amp;lunchName=REPAS&amp;displayMode=1057855&amp;showLoad=false&amp;ttl=' . time() . '000&amp;displayConfId=' . $displayConfId : 'img/bgAdeBlanc.png';

### Préparation du template
require_once('view/main.phtml');

/** EOF /**/
