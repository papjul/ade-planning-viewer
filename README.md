Planning IUT Info
==========

Description
-------------
L’application Planning IUT Info est une application en PHP pour récupérer le planning de l’ADE sur l’ENT de l’IUT Informatique d’Aix-en-Provence via une interface suivant le principe KISS. Elle s’inspire de l’application Java, Planning Viewer, développée par Alexis Mimran, aujourd’hui hors de fonctionnement.

Planning IUT Info est mis à votre disposition dans l’espoir qu’il vous sera utile.


Compatibilité
-------------
**Incompatible** avec les versions inférieures à 5.3 de PHP. Dernière version testée : 5.5.


Installation
-------------
Vous devez récupérer les dépendances avec Composer (le fichier composer.json est fourni). Toutes les instructions d’installation et d’utilisation sont ici selon votre système : http://getcomposer.org/doc/00-intro.md

Accordez ensuite les droits en écriture sur le dossier tmp/ pour permettre la mise en cache et sur le fichier data/identifier pour pouvoir réinitialiser l’identifiant à l’aide du script reset.php.


Démonstration
-------------
L’association Inform’Aix utilise un exemple de cette application en fonctionnement à cette adresse : http://informaix.com/planning/


Licence
-------------
Vous pouvez partager, modifier cette application, en veillant à respecter la licence Affero General Public License (AGPL) version 1 ou supérieure disponible dans le fichier LICENSE distribué avec ce logiciel.
