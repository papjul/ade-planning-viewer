# Planning IUT Info

## Description

L’application Planning IUT Info est une application en Javascript et PHP pour récupérer le planning de l’ADE sur l’ENT de l’IUT Informatique d’Aix-en-Provence via une interface suivant le principe KISS. Elle s’inspire de l’application Java, Planning Viewer, développée par Alexis Mimran, aujourd’hui hors de fonctionnement.

Planning IUT Info est mis à votre disposition dans l’espoir qu’il vous sera utile sans aucune garantie.


## Prérequis

PHP 5.3 ou supérieur.


## Installation

Téléchargez et dézippez https://github.com/papjul/PlanningIUTInfo/archive/master.zip

Si vous souhaitez pouvoir réinitialiser l’identifiant à l’aide du script reset.php, accordez les droits en écriture sur le fichier data/identifier.


## Configuration

La configuration est stockée au format Json dans le dossier data/.

### Constantes (constants.json)
### Dimensions (dimensions.json)
### Identifiant (identifier)
### Ressources (resources.json)


## Thème Bootstrap

Bien que l’application soit conçue pour fonctionner sans dépendances externes, vous pouvez utiliser Bootstrap pour rendre l‘application plus esthétique.

### Avec Composer (recommandé)
Vous devez récupérer la dépendance à Bootstrap avec Composer (le fichier composer.json est fourni). Toutes les instructions d’installation et d’utilisation sont ici selon votre système : http://getcomposer.org/doc/00-intro.md

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
