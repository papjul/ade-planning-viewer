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

if(!defined('SAFE')) exit('Nothing to do here');

### Configuration
# URL de l’ADE sans le / final
define('URL_ADE', 'http://planning.univmed.fr/ade');

# Id du projet (12 pour 2010/2011, 10 pour 2011/2012, 22 pour 2012/2013, etc)
define('PROJECT_ID', 22);

# Timestamp de la première semaine de l’année au format GMT
define('FIRST_WEEK', 1344816000);

# Nombre de semaines du projet de l’année
define('NB_WEEKS', 54);

# Samedi et dimanche
define('SATURDAY', 'yes');
define('SUNDAY', 'no');

# Affichage par défaut (41 : vertical, 8 : horizontal)
define('DISPLAY_CONF_ID', 41);

# Longueur par défaut (valeur existante ou sera automatiquement remplacée par 1000)
define('WIDTH', 1000);

# Nombre de jours à afficher dans le flux RSS
define('NB_DAYS_RSS', 15);

### Constantes de dates
define('ONE_MINUTE', 60);
define('ONE_HOUR', 60 * ONE_MINUTE);
define('ONE_DAY', 24 * ONE_HOUR);
define('ONE_WEEK', 7 * ONE_DAY);
define('ONE_YEAR', 365 * ONE_DAY);
/** EOF /**/