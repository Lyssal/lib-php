# Librairie PHP générale de Lyssal

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4e86aafd-eadd-4fc7-8433-da8f3605db49/small.png)](https://insight.sensiolabs.com/projects/4e86aafd-eadd-4fc7-8433-da8f3605db49)


## Chaine

Permet de traiter des chaînes de caractères.

* `supprimeAccents()` : Supprime les accents de la chaîne de caractères
* `minifie($separateur, $toutEnMinuscule)` : Simplifie la chaîne de caractères (pour une URL par exemple)


## Csv

Permet de traiter des fichiers CSV.

* `importe()` : Lit le fichier CSV
* `exporte()` : Télécharge le fichier CSV généré


## Fichier

Permet de traiter des fichiers.

* `move($nouveauChemin, $remplaceSiExistant)` : Déplace le fichier
* `copy($chemin, $remplaceSiExistant)` : Copie le fichier
* `setNomMinifie($nouveauNom, $separateur, $toutEnMinuscule, $longueurMaximale, $remplaceSiExistant)` : Minifie le nom du fichier
* `getCheminLibre($fichier, $separateur)` : Retourne un chemin libre (aucun fichier existant) pour ce fichier


## Image

Permet de traiter des images (étend Fichier).

* `redimensionne($nouvelleLargeur, $nouvelleHauteur, $conserveProportions)` : Redimensionne l'image
