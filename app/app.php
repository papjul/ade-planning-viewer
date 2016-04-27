<?php

/**
 * This file is part of ADE Planning Viewer.
 * Copyright © 2012-2016 Julien Papasian
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
if (!defined('ROOT')) {
    exit();
}

# En-tête
header('Content-Type: text/html; charset=utf-8');

# Protège contre les injections PHP des fichiers YAML
if (function_exists('yaml_parse_file')) {
    if (ini_get('yaml.decode_php')) {
        ini_set('yaml.decode_php', false);
    }
}

require_once(ROOT . '/src/Planning.class.php');
