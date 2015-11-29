<?php

/**
 * This file is part of ADE Planning Viewer.
 * Copyright © 2012-2015 Julien Papasian
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

/**
 * Classe de gestion d’un planning
 */
class Planning {

    protected $conf;
    protected $resources;
    protected $displays;
    protected $dimensions;
    protected $custom_conf;
    protected $reset;
    protected $yaml;

    /**
     * Initialise les variables requises
     */
    public function __construct() {
        $this->conf = $this->getYaml('constants');
        $this->setCustomConf();
    }

    /**
     * Retourne un fichier YAML sous forme d’array
     * @param string $file
     * @return array
     * @throws Exception
     */
    protected function getYaml($file) {
        if (!file_exists(ROOT . '/data/' . $file . '.yaml')) {
            throw new Exception('Le fichier ' . $file . '.yaml n’a pas pu être trouvé dans le dossier de configuration. Vérifiez son existence et les droits de lecture.');
        }

        // PECL Yaml
        if (function_exists('yaml_parse_file')) {
            return yaml_parse_file(ROOT . '/data/' . $file . '.yaml');
        }

        // Symfony YAML
        if (!class_exists('Symfony\Component\Yaml\Parser')) {
            require_once ROOT . '/vendor/autoload.php';
        }
        if (!($this->yaml instanceof \Symfony\Component\Yaml\Parser)) {
            $this->yaml = new \Symfony\Component\Yaml\Parser();
        }

        try {
            return $this->yaml->parse(file_get_contents(ROOT . '/data/' . $file . '.yaml'));
        } catch (ParseException $e) {
            printf("Impossible de parser le fichier YAML. Détails de l’erreur : %s", $e->getMessage());
        }
    }

    /**
     * Définit la configuration personnalisée si elle existe
     */
    protected function setCustomConf() {
        # Récupération du cookie
        if (isset($_COOKIE[$this->conf['COOKIE_NAME']])) {
            $this->custom_conf = json_decode($_COOKIE[$this->conf['COOKIE_NAME']], true);
        }

        # Enregistrement des données POST en cookie
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->custom_conf = array('idTree' => isset($_POST['idTree']) ? $_POST['idTree'] : 0,
                'idPianoWeek' => isset($_POST['idPianoWeek']) ? $_POST['idPianoWeek'] : 0,
                'saturday' => isset($_POST['saturday']) ? 'yes' : 'no',
                'sunday' => isset($_POST['sunday']) ? 'yes' : 'no',
                'displayConfId' => $_POST['displayConfId'],
                'width' => $_POST['width']);

            setcookie($this->conf['COOKIE_NAME'], json_encode($perconf), time() + 365 * 24 * 3600, '/', null, false, true);
        }
    }

    /**
     * Retourne la configuration
     * @return array
     */
    public function getConf() {
        return $this->conf;
    }

    /**
     * Retourne les ressources en les initialisant au préalable si nécessaire
     * @return array
     */
    public function getResources() {
        if (!is_array($this->resources)) {
            $this->resources = $this->getYaml('resources');
        }
        return $this->resources;
    }

    /**
     * Retourne les affichages disponibles
     * @return array
     */
    public function getDisplays() {
        if (!is_array($this->displays)) {
            $this->displays = $this->getYaml('displays');
        }
        return $this->displays;
    }

    /**
     * Retourne les dimensions disponibles
     * @return array
     */
    public function getDimensions() {
        if (!is_array($this->dimensions)) {
            $this->dimensions = $this->getYaml('dimensions');
        }
        return $this->dimensions;
    }

    /**
     * Retourne les informations permettant de réinitialiser un identifiant
     * @return array
     */
    public function getReset() {
        if (!is_array($this->reset)) {
            $this->reset = $this->getYaml('reset');
        }
        return $this->reset;
    }

    /**
     * Retourne un identifier aléatoire parmi ceux disponibles
     * @return string
     * @throws Exception
     */
    public function getIdentifier() {
        if (!file_exists(ROOT . '/data/identifier')) {
            throw new Exception('Le fichier identifier n’a pas pu être trouvé dans le dossier de configuration. Vérifiez son existence et les droits de lecture.');
        }
        $identifiers = file(ROOT . '/data/identifier', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return $identifiers[rand(0, count($identifiers) - 1)];
    }

    /**
     * Retourne la configuration personnalisée
     * @return array
     */
    public function getCustomConf() {
        return $this->custom_conf;
    }

}
