Planning IUT Info
==========

**DÉPRÉCIÉ. Depuis l’année 2015/2016, le système de gestion de planning a été changé. Merci de vous référer à http://ade-consult.pp.univ-amu.fr/direct/myplanning.jsp**

**Le code ne sera pas mis à jour avec cette version pour trois raisons :**

1. **Je ne suis plus à l’IUT**
2. **Le système n’utilise plus des images mais des ensembles de blocs div complétés par des requêtes adressées et retournées par un serveur Java, soit une manière de fonctionner totalement différente de l’ancien système (une simple image avec des paramètres GET)**
3. **La sécurité du système a été renforcée avec des jetons de vérification par POST qui réduit la faisabilité (l’ancien système utilisait un jeton commun à tous par GET)**

Description
-------------
L’application Planning IUT Info est une application en PHP pour récupérer le planning de l’ADE sur l’ENT de l’IUT Informatique d’Aix-en-Provence via une interface suivant le principe KISS. Elle s’inspire de l’application Java, Planning Viewer, développée par Alexis Mimran, aujourd’hui hors de fonctionnement.

Planning IUT Info est mis à votre disposition dans l’espoir qu’il vous sera utile.


Compatibilité
-------------
**Incompatible** avec les versions inférieures à 5.3 de PHP (vous ne devriez pas utiliser de versions inférieures à 5.4 de toute façon). Dernière version testée : 5.6.


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
