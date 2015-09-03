# ADE Planning Viewer

## Description

ADE Planning Viewer est une application en Javascript et PHP pour afficher le planning d’ADE Campus utilisé par de nombreuses universités en France via une interface suivant le principe KISS. Ce programme est distribué avec un exemple fonctionnel pour l’IUT Informatique d’Aix-Marseille mais peut être réutilisé par n’importe quelle formation utilisant ADE Campus dans le respect de la licence AGPL v3.0+.

ADE Planning Viewer est mis à votre disposition dans l’espoir qu’il vous sera utile sans aucune garantie.


## Prérequis

PHP 5.3 ou supérieur, avec l’extension PECL Yaml.


## Installation

Téléchargez et dézippez https://github.com/papjul/ADEPlanningViewer/archive/master.zip

Si vous souhaitez pouvoir réinitialiser l’identifiant à l’aide du script reset.php, accordez les droits en écriture sur le fichier data/identifier.


## Configuration

La configuration est stockée au format YAML dans le dossier data/. Ce format de données lisible par n’importe quel éditeur de texte devrait vous être intuitif.

### Constantes (constants.yaml)
### Ressources (resources.yaml)
### Identifiant (reset.yaml et identifier)
### Affichages (displays.yaml)
### Dimensions (dimensions.yaml)

## Thème Bootstrap

Bien que l’application soit conçue pour fonctionner sans dépendances externes, vous pouvez utiliser Bootstrap pour rendre l‘application plus esthétique.

### Avec Composer (recommandé)
Suivez les instructions d’installation fournies ici : http://getcomposer.org/doc/00-intro.md

Puis initialisez le project avec la commande :
```
composer install
```

Pour mettre à jour Boostrap, vous n’aurez qu’à lancer la commande :
```
composer update
```

Dans le fichier constants.json, veuillez mettre la variable `USE_BOOTSTRAP` à `true` et la variable `USE_BOOTSTRAP_CDN` à `false`.

### Manuellement
Téléchargez Bootstrap sur http://getbootstrap.com/getting-started#download et placez le fichier bootstrap.min.css dans le dossier à créer vendor/twitter/bootstrap/dist/css/

Pour mettre à jour Bootstrap, vous devrez retourner télécharger Bootstrap sur le site officiel à chaque fois.

Dans le fichier constants.json, veuillez mettre la variable `USE_BOOTSTRAP` à `true` et la variable `USE_BOOTSTRAP_CDN` à `false`.

### Avec un CDN (déconseillé)

Dans le fichier constants.json, veuillez mettre la variable `USE_BOOTSTRAP` à `true` et la variable `USE_BOOTSTRAP_CDN` à `true`.


## Démonstration

L’association Inform’Aix utilise un exemple de cette application en fonctionnement à cette adresse : http://informaix.com/planning/ (lien mort ?)


## Licence

Vous pouvez partager, modifier cette application, en veillant à respecter la licence Affero General Public License (AGPL) version 3 ou supérieure disponible dans le fichier LICENSE distribué avec ce logiciel.
