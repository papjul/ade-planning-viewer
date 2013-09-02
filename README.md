Planning IUT Info
==========

Description
-------------
L’application Planning IUT Info est une application en PHP pour récupérer le planning de l’ADE sur l’ENT de l’IUT Informatique d’Aix-en-Provence via une interface suivant le principe KISS. Elle s’inspire de l’application Java, Planning Viewer, développée par Alexis Mimran, aujourd’hui hors de fonctionnement.
Planning IUT Info est mis à votre disposition dans l’espoir qu’il vous sera utile.


Compatibilité
-------------
**Testé** sous *PHP 5.3* et *PHP 5.4*.


Installation
-------------
Vous devez récupérer la dernière version de [RainTPL 3](https://github.com/rainphp/raintpl3/tags) et déposer le dossier library/ à la racine du projet.

Accordez ensuite les droits en écriture sur le dossier tmp/ et le tour est joué ;)


Si vous souhaitez bénéficier du design de Bootstrap, téléchargez-en [la dernière version](http://twitter.github.com/bootstrap/) et placer les fichiers bootstrap.min.css et bootstrap-responsive.min.css dans le dossier static/

Sinon, pensez à enlever les balises <link non-nécessaires dans le fichier tpl/layout.head.html


Démonstration
-------------
Un exemple de cette application en fonctionnement, intégré à un site web, est disponible à [cette adresse](http://amenysta.net/iut)


Licence
-------------
Vous pouvez partager, modifier cette application, en veillant à respecter la licence GNU GPLv2+ disponible dans le fichier LICENSE distribué avec ce logiciel.
