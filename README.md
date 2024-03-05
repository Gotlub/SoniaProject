# Project de stage Spanc 
## _Communauté de communes des Pays de L'Aigle_

[![CDC](https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Logo_cdc_des_Pays_lAigle.jpg/280px-Logo_cdc_des_Pays_lAigle.jpg)](https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Logo_cdc_des_Pays_lAigle.jpg/280px-Logo_cdc_des_Pays_lAigle.jpg)


SoniaProject est un petit logiciel de gestion a destination de la SPANC de la CDC des Pays de L'Aigle.

- Il a été realisé en Symfony
- Et NodeJS ([webpack encore](https://symfony.com/doc/current/frontend/encore/installation.html))


## Installation

SoniaProject nécessite [Node.js](https://nodejs.org/) v10+ et [php>=8.2](https://www.php.net/releases/8.2/fr.php)
            

Installer le project avec composer.

```sh
cd SoniaProject
composer install
```

Puis nodeJs

```sh
npm install
npm run watch
```

[Structure de la base de données avec trigger](https://github.com/Gotlub/SoniaProject/blob/main/documents/StructureBDDpourTest.sql)  
[![tables](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/mcd.png)](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/mcd.png)
[Exemple d'un des script python pour l'importation des données ](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/finalscriptExemple.py)  
  
  
## Description du projet
   
[![project](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/filtres.jpg)](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/filtres.jpg)

Le projet permet de trier, filtrer et exporter les rendez-vous renseignés dans la BDD.
Il utilise le bundle knp_paginator pour la pagination et pour trier les différents champs.
Les filtres ont été réalisés sans bundle pour plus de modularité.


[Utilisation du bundle ux_autocomplete : ](https://symfony.com/bundles/ux-autocomplete/current/index.html)
[![autoComplete](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/autoComplete.jpg)](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/autoComplete.jpg)

[Export au formal Xlsx avec php PhpSpreadsheet : ](https://phpspreadsheet.readthedocs.io/en/latest/)
[![PhpSpreadsheet](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/Xlsx.jpg)](https://raw.githubusercontent.com/Gotlub/SoniaProject/main/documents/Xlsx.jpg)
