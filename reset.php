<?php
/**
 * Planning IUT Info
 * Copyright © 2012-2014 Julien Papasian
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License
 * as published by Affero; either version 1 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Affero General Public License for more details.
 *
 * You should have received a copy of the Affero General Public
 * License along with this program. If not, see
 * <http://www.affero.org/oagpl.html>.
 */
# En-tête
header('Content-Type: text/html; charset=utf-8');

# Ce script ne peut être appelé que toutes les heures maximum pour des raisons de sécurité
if(filemtime('data/identifier') > time() - 3600)
    exit('L’identifiant de connexion a déjà été réinitialisé il y a peu de temps.');

# Initialisation de la session cURL
$ch = curl_init();

# Se connecte au portail Univ-AMU
curl_setopt($ch, CURLOPT_URL, 'http://planning.univ-amu.fr/ade/custom/modules/plannings/anonymous_cal.jsp?resources=25421&projectId=8&startDay=25&startMonth=08&startYear=2014&endDay=17&endMonth=08&endYear=2015&calType=ical');
curl_setopt($ch, CURLOPT_HEADER, true);         # Affiche les headers (pour récupérer le cookie)
curl_setopt($ch, CURLOPT_NOBODY, true);         # Affiche UNIQUEMENT les headers (pas le contenu)
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); # Affiche le contenu sous forme de string
# Récupère le cookie
$content = curl_exec($ch);
preg_match_all('|Set-Cookie: (.*);|U', $content, $results);
$cookies = implode(';', $results[1]);

# Envoie le cookie
curl_setopt($ch, CURLOPT_COOKIE, $cookies);
curl_setopt($ch, CURLOPT_HEADER, false); # Désactive l’affichage des headers
## Sélectionne une ressource
# Déroule le menu des enseignants
curl_setopt($ch, CURLOPT_URL, 'http://planning.univ-amu.fr/ade/standard/gui/tree.jsp?category=instructor&expand=false&forceLoad=false&reload=false&scroll=0');
curl_exec($ch);

# Déroule la lettre N
curl_setopt($ch, CURLOPT_URL, 'http://planning.univ-amu.fr/ade/standard/gui/tree.jsp?branchId=6270&expand=false&forceLoad=false&reload=false&scroll=0');
curl_exec($ch);

# Sélectionne Nedjar
curl_setopt($ch, CURLOPT_URL, 'http://planning.univ-amu.fr/ade/standard/gui/tree.jsp?selectId=25421&reset=false&forceLoad=true&scroll=0');
curl_exec($ch);

# Sélectionne les jours
curl_setopt($ch, CURLOPT_URL, 'http://planning.univ-amu.fr/ade/custom/modules/plannings/pianoDays.jsp');
curl_exec($ch);

# Sélectionne les semaines et récupère l’image
curl_setopt($ch, CURLOPT_NOBODY, false); # Réactive la récupération du contenu de la page
curl_setopt($ch, CURLOPT_URL, 'http://planning.univ-amu.fr/ade/custom/modules/plannings/imagemap.jsp?week=6&reset=false&width=1360&height=591');
$image = curl_exec($ch);

# Récupération de l’identifiant
preg_match('|identifier=(.*)&|U', $image, $identifier);
file_put_contents('data/identifier', $identifier[1]);
echo 'Nouvel identifiant réinitialisé&nbsp;: '.$identifier[1];

curl_close($ch);
/** EOF /**/