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
$reset = $planning->getReset();

# En production, ce script ne peut être rappelé qu’après un certain temps pour des raisons de sécurité
if (!$conf['DEBUG'] && filemtime('data/identifier') > time() - $conf['RESET_LIMIT']) {
    exit('L’identifiant de connexion a déjà été réinitialisé il y a peu de temps.');
}

# Initialisation de la session cURL
$ch = curl_init();

# Ouvre une connexion anonyme
curl_setopt($ch, CURLOPT_URL, $conf['URL_ADE'] . '/custom/modules/plannings/anonymous_cal.jsp');
curl_setopt($ch, CURLOPT_HEADER, true);         # Affiche les headers (pour récupérer le cookie)
curl_setopt($ch, CURLOPT_NOBODY, true);         # Affiche UNIQUEMENT les headers (pas le contenu)
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); # Affiche le contenu sous forme de string
#
# Récupère le cookie
$content = curl_exec($ch);

if (!$content) {
    throw new Exception('Impossible de charger la page de calendrier anonyme.');
}

preg_match_all("/set-cookie: (.*)\\r/U", $content, $results);
$cookies = implode(';', $results[1]);

# Envoie le cookie
curl_setopt($ch, CURLOPT_COOKIE, $cookies);
//curl_setopt($ch, CURLOPT_HEADER, false); # Désactive l’affichage des headers

# Sélectionne le projet
curl_setopt($ch, CURLOPT_URL, $conf['URL_ADE'] . '/standard/gui/interface.jsp?projectId=' . $conf['PROJECT_ID']);
$page_projet = curl_exec($ch);
if (!$page_projet) {
    throw new Exception('Impossible de charger la page du projet '.$conf['PROJECT_ID'].'.');
}

# Ouvre la catégorie
curl_setopt($ch, CURLOPT_URL, $conf['URL_ADE'] . '/standard/gui/tree.jsp?category=' . $reset['category']);
$page_category = curl_exec($ch);
if (!$page_category) {
    throw new Exception('Impossible de charger la page de la catégorie dans l’arborescence.');
}

# Déroule les différentes branches
foreach ($reset['branches'] as $branch) {
    curl_setopt($ch, CURLOPT_URL, $conf['URL_ADE'] . '/standard/gui/tree.jsp?branchId=' . $branch);
    $page_branch = curl_exec($ch);
    if (!$page_branch) {
        throw new Exception('Impossible de charger la page de la branche '.$branch.' dans l’arborescence.');
    }
}

# Sélectionne la ressource finale
curl_setopt($ch, CURLOPT_URL, $conf['URL_ADE'] . '/standard/gui/tree.jsp?selectId=' . $reset['resource']);
$page_resource = curl_exec($ch);
if (!$page_resource) {
    throw new Exception('Impossible de charger la page de la ressource '.$reset['resource'].' dans l’arborescence.');
}

# Charge les jours
curl_setopt($ch, CURLOPT_URL, $conf['URL_ADE'] . '/custom/modules/plannings/pianoDays.jsp');
$page_days = curl_exec($ch);
if (!$page_days) {
    throw new Exception('Impossible de charger la page des jours.');
}

# Charge les semaines
curl_setopt($ch, CURLOPT_URL, $conf['URL_ADE'] . '/custom/modules/plannings/pianoWeeks.jsp');
$page_weeks = curl_exec($ch);
if (!$page_weeks) {
    throw new Exception('Impossible de charger la page des semaines.');
}

# Récupère l’image
curl_setopt($ch, CURLOPT_NOBODY, false); # Réactive la récupération du contenu de la page
curl_setopt($ch, CURLOPT_URL, $conf['URL_ADE'] . '/custom/modules/plannings/imagemap.jsp');
$image = curl_exec($ch);

curl_close($ch);

if (!$image) {
    throw new Exception('Impossible de charger la page de l’image.');
}

# Récupération de l’identifiant
preg_match('|identifier=(.+)&|U', $image, $identifier);
if (isset($identifier[1])) {
    file_put_contents('data/identifier', $identifier[1]);
    echo $identifier[1];
} else {
    throw new Exception('Impossible de récupérer un identifiant.');
}

/** EOF /**/
