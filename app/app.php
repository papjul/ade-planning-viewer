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
if (!defined('ROOT')) {
    exit();
}

# En-tête
header('Content-Type: text/html; charset=utf-8');

# Prérequis
if (!function_exists('yaml_parse_file')) {
    throw new Exception('Cette application nécessite l’installation de l’extension PECL Yaml pour PHP.');
}

# Protège contre les injections PHP des fichiers YAML
if (ini_get('yaml.decode_php')) {
    ini_set('yaml.decode_php', false);
}

require_once(ROOT . '/src/Planning.class.php');
