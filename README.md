# ADE Planning Viewer

## Description

ADE Planning Viewer est une application en Javascript et PHP pour afficher le planning d’ADE Campus utilisé par de nombreuses universités en France via une interface suivant le principe KISS. Ce programme est distribué avec un exemple fonctionnel pour l’IUT Informatique d’Aix-Marseille mais peut être réutilisé par n’importe quelle formation utilisant ADE Campus dans le respect de la licence AGPL v3.0+.

ADE Planning Viewer est mis à votre disposition dans l’espoir qu’il vous sera utile sans aucune garantie.


## Démonstration

L’association Inform’Aix utilise un exemple de cette application en fonctionnement à cette adresse : http://informaix.com/planning/ (lien mort ?)


## Prérequis

PHP 5.3 ou supérieur, avec l’extension PECL Yaml.


## Installation

Téléchargez et dézippez https://github.com/papjul/ADEPlanningViewer/archive/master.zip

Si vous souhaitez pouvoir réinitialiser l’identifiant à l’aide du script reset.php, accordez les droits en écriture sur le fichier data/identifier.


## Configuration

La configuration est stockée au format YAML dans le dossier data/. Ce format de données lisible par n’importe quel éditeur de texte devrait vous être intuitif.

### Constantes (constants.yaml)
`URL_ADE` : URL de base de l’ADE version JSP.

Si vous accédez à votre planning par une URL du type http://monurl/direct/<...>, il s’agit probablement de http://monurl/jsp. Sinon, pour les versions anciennes de l’ADE, essayez http://monurl/ade. En allant sur ces URL, vous devriez être redirigé sur la page d‘accueil du planning.

Ne mettez surtout pas de / à la fin de cette URL.

Exemple : `http://ade-consult.pp.univ-amu.fr/jsp`

`PROJECT_NAME` : nom de votre projet.

Vous pouvez mettre ce que vous voulez, ça n’a pas d’importance pour le fonctionnement de l’application. Cette valeur sera utilisée pour le titre de la page.

Exemple : `Planning IUT Info`

`PROJECT_ID` : identifiant du projet.

Certaines universités utilisent un projet par année, par formation, etc. Pour connaître cette valeur, affichez un planning que vous exportez par un flux RSS ou ICalendar (générer une URL) depuis l’ADE. Regardez l’URL de votre navigateur et relevez un paramètre idProject=X ou projectId=X

Exemple : `8`

`FIRST_WEEK` : première semaine de l’emploi du temps au format timestamp.

Regardez dans la barre des semaines sur l’ADE quelle est la première semaine puis convertissez cette date au format timestamp avec un site comme http://www.timestamp.fr/. Il est important que l’heure soit positionnée à 2 heures du matin.

Exemple : `1440374400` (le 24/8/2015 à 2:00:00)

`NB_WEEKS` : nombre de semaines dans l’emploi du temps.

Vous pouvez soit choisir de compter toutes les semaines qui s’affichent sur le barre des semaines sur l’ADE, ou y aller à tâtons. Commencez avec 52 semaines puis regardez si la dernière semaine de la liste déroulante sur l’application correspond à la dernière semaine de la barre des semaines sur l’ADE.

Exemple : `52`

`ID_TREE` : ressource(s) sélectionnée(s) par défaut.

Si vous souhaitez qu’une ressource soit affichée par défaut lorsqu’on se rend sur l’application, entrez leurs identifiants séparées par des virgules. Référez-vous à la partie de configuration sur les ressources pour récupérer les identifiants des ressources. Laissez 0 si vous ne souhaitez pas que des ressources soient sélectionnées.

Exemple : `8385,8386`

`SATURDAY` : affiche ou non le samedi par défaut. `yes` pour l’afficher, `no` pour ne pas l’afficher.

Concrètement, si aucune de vos ressources ne travaille le samedi, vous mettrez cette valeur à `no`. Sinon, il est préférable de mettre `yes` pour éviter de manquer une activité.

Exemple : `yes`

`SUNDAY` : affiche ou non le dimanche par défaut. `yes` pour l’afficher, `no` pour ne pas l’afficher.

Normalement, aucune ressource ne devrait travailler le dimanche, donc la valeur sera `no`. Si vous avez des événements spéciaux, tels que des formations qui se déroulent le dimanche, vous voudrez peut-être mettre cette valeur à `yes`.

Exemple : `no`

`DISPLAY_CONF_ID` : disposition par défaut.

Disposition que vous souhaitez par défaut. Référez-vous à la partie de configuration sur les dispositions pour récupérer les identifiants des dispositions.

Exemple : `53`

`WIDTH` : largeur par défaut de l‘image en pixels.

Une largeur plus grande peut permettre l’affichage de plus d’éléments sur les événements. Cette valeur doit exister dans le fichier de configuration des dimensions.

Exemple : `1000`

`HEIGHT` : hauteur par défaut de l‘image en pixels.

Une hauteur plus grande peut permettre l’affichage de plus d’éléments sur les événements. Cette valeur doit exister dans le fichier de configuration des dimensions.

Exemple : `500`

`NB_DAYS_RSS` : nombre de jours à prendre en compte dans les flux RSS.

Indique le nombre de jours que le flux RSS propose. Si vous ne savez pas ce que c’est, 15 jours conviendront à vos utilisateurs s’ils comptent s’en servir.

Exemple : `15`

`COOKIE_NAME` : nom du cookie.

Vous pouvez personnaliser le nom du cookie si vous utilisez plusieurs instances du planning pour éviter les conflits.

`USE_BOOTSTRAP` : `true` pour utiliser le thème Bootstrap, `false` sinon.

Se référer à la section Thème.

Exemple : `true`

`USE_BOOTSTRAP_CDN` : `true` pour utiliser un CDN pour afficher le thème Bootstrap, `false` sinon.

Se référer à la section Thème. `USE_BOOTSTRAP` doit valoir `true` pour fonctionner.

Exemple : `false`

### Ressources (resources.yaml)
Les ressources sont les entités pouvant avoir un emploi du temps. Cela peut être un étudiant, une formation, une classe, un groupe, une option, un enseignant, une salle (pour connaître sa disponibilité), etc.

Les ressources doivent être regroupées par catégorie, sans sous-catégorie. Chaque ressource faisant partie d’une catégorie doit être indentée par une tabulation en début de ligne. Le format d’une ligne de ressource est `<identifiant>: <nom>`. Le nom n’a aucune influence sur le fonctionnement de l’application.

Pour récupérer l’identifiant d’une ressource, vous devez vous rendre dans l’ADE et dérouler l’arbre des ressources jusqu’à votre ressource.

Si vous utilisez ADE Direct (URL contenant direct/), vous devrez utiliser un inspecteur d’élément (tel que Firebug) sur la ressource pour trouver son identifiant (par exemple `<div id="Direct Planning Tree_<id>">`).

Si vous utilisez ADE JSP (URL_ADE du fichier de configuration), passez la souris sur la ressource et vous allez voir un lien de la forme `javascript:check(<id>, true|false)`.

Il est préférable de ne pas récupérer les identifiants des parents. Si vous prenez l’identifiant d’une formation IUT Info, par exemple, cela prendra tous les emplois du temps qui en découlent (1re année, 2e année, ainsi que les groupes de chaque année), sans distinction. Il sera ainsi impossible de distinguer l’emploi du temps du Groupe 2 en 1re année de celui du Groupe 1 en 2e année. Il vaut donc mieux créer autant de lignes que de ressources. Vous aurez ensuite la possibilité d’afficher toutes les 1re année et 2e année d’un coup dans la liste des ressources sur l’application en maintenant Ctrl et en cliquant sur chaque ressource.

Exemple :
```
1re année:
    8385: Groupe 1A
    8386: Groupe 1B
    8387: Groupe 2A
    8388: Groupe 2B
2e année:
    8400: Groupe 1A
    8401: Groupe 1B
```

### Identifiant (reset.yaml et identifier)

### Dispositions (displays.yaml)

### Dimensions (dimensions.yaml)

Les dimensions sont les tailles proposées à l’utilisateur. Généralement, les tailles fournies dans le fichier par défaut vous suffiront mais vous pouvez aussi les personnaliser selon le format `<largeur>: <hauteur>`. Attention, petite contrainte cependant, vous ne pouvez pas avoir deux largeurs identiques pour plusieurs hauteurs (unicité de la clé).

Il ne semble pas y avoir de limites de dimensions pour la taille d’une image mais évitez d’utiliser plusieurs dizaines de milliers de pixels, ça n’a aucun intérêt et la génération d’une telle image consommera beaucoup de ressources serveur.

Exemple :
```
1024: 768
1280: 720
1366: 768
1600: 1024
1920: 1080
```

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


## Licence

Vous pouvez partager, modifier cette application, en veillant à respecter la licence Affero General Public License (AGPL) version 3 ou supérieure disponible dans le fichier LICENSE distribué avec ce logiciel.
