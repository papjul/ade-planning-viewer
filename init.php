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

## Quelques fonctions de sécurité en vrac
# Protection contre les register_globals
# Doit être effectué avant que des globals soient traités par le code
if(ini_get('register_globals'))
{
    if(isset($_REQUEST['GLOBALS']))
        exit('<a href="http://www.hardened-php.net/globals-problem">$GLOBALS overwrite vulnerability</a>');

    $verboten = array('GLOBALS', '_SERVER', 'HTTP_SERVER_VARS', '_GET', 'HTTP_GET_VARS',
                      '_POST', 'HTTP_POST_VARS', '_COOKIE', 'HTTP_COOKIE_VARS', '_FILES',
                      'HTTP_POST_FILES', '_ENV', 'HTTP_ENV_VARS', '_REQUEST', '_SESSION',
                      'HTTP_SESSION_VARS');

    foreach($_REQUEST as $name => $value)
    {
        if(in_array($name, $verboten))
        {
            header('HTTP/1.x 500 Internal Server Error');
            echo 'register_globals security paranoia: trying to overwrite superglobals, aborting.';
            exit(-1);
        }

        unset($GLOBALS[$name]);
    }
}

# Désactive le content sniffing d’IE8. Les autres navigateurs devraient ignorer cette ligne
header('X-Content-Type-Options: nosniff');

# Désactive les magic quotes si utilisation de PHP 5.3
@ini_set('magic_quotes_runtime', 0);

# Protection contre les injections SQL (UNION) et XSS/CSS
$query_string = strtolower(rawurldecode($_SERVER['QUERY_STRING']));
$bad_string   = array('%20union%20', '/*', '*/union/*', '+union+', 'load_file',
                      'outfile', 'document.cookie', 'onmouse', '<script', '<iframe',
                      '<applet', '<meta', '<style', '<form', '<img',
                      '<body', '<link', '..', 'http://', '%3C%3F');

foreach($bad_string as $string_value)
    if(strpos($query_string, $string_value))
        exit('Unauthorised value in query string');

unset($query_string, $bad_string, $string_value);

header('Content-Type: text/html; charset=utf-8');

# Autorise les inclusions
define('SAFE', true);
/** EOF /**/ 