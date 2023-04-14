# Hands On Project - Équipe 4

## Pitch
The Hands On Project est un projet libre de développement web/mobile full stack de l’IT-Akademy, se déroulant sur trois semaines entre le 2 janvier 2023 et le 3 février 2023.

Le thème du projet est la sobriété numérique. Nous avions pour consigne de travailler sur une application ayant un impact positif sur la sobriété numérique.

Nous avons donc décidé de développer une application permettant de trouver les lieux de recyclage de matériel électrique et électronique hors d'usage à proximité, ainsi que les magasins d’électronique susceptibles de réparer ou de conseiller sur la réparation d’un appareil.

L'application est disponible sur https://handson-equipe-4.me/ 

## Stack
Les technologies et outils employés dans notre projet sont :
- PHP 8.1
- Laravel ^8.1
- Laravel Sail
- SASS
- Bootstrap
- MySQL
- phpmyadmin
- npm et Laravel Vite
- composer
- Figma pour les maquettes : https://www.figma.com/file/Hzy5AN1WIr6ze9QrnmDOQP/Maquette-Hands-on-project?node-id=0%3A1&t=TwS6yYVFYpYAKxAX-1 
- Nom de domaine handson-equipe-4.me (NameCheap)
- certificat SSL (NameCheap)
- hébergement Cloud : Digital OCean
- Emails : Mailgun

## API utilisées :
- Geolocation
- OpenCageData
- Opendatasoft
- Geoapify : Places et Routing

## Organisation du travail :
- Daily rapide tous les matins
- Discord de projet pour la communication et le partage d'informations
- Drive partagé : copie du sujet et docs divers - https://drive.google.com/drive/folders/1B6eYNOLyxWrgpm3caJiT7PCY-5CbB--l 
- Trello pour le suivi des tâches : https://trello.com/b/t5aw7CaU/kanban 

## Scénario
- j’arrive sur la page d’accueil
- je suis invité à m’inscrire ou à me connecter
- une fois connecté, je peux me géolocaliser
- j’arrive sur la page de résultats 
- pour chaque résultat, je peux voir les détails disponibles (adresse, numéro de téléphone, site web, horaires d’ouvertures…)
- je peux également calculer l’itinéraire vers un des résultats
- 3 modes de déplacement : à pied, en vélo ou en voiture
- j’ai la possibilité d’éditer mon profil (nom, adresse mail, mot de passe)

## Équipe de développeurs
- Corentin BARNERON
- Dursun BASLI
- Cédric CAM 
- Michel LERUYET
- Sara ZOUIOULA

## Demo

## Documentation utile
- Laravel https://laravel.com/
- API Geoapify Routing : https://apidocs.geoapify.com/docs/routing/#routing 
- API Geoapify Places : https://apidocs.geoapify.com/docs/places/#about
- API Geolocation : https://developer.mozilla.org/fr/docs/Web/API/Geolocation_API 
- API Opencage Geocoding : https://opencagedata.com/api 
- API Opendatasoft : https://public.opendatasoft.com/api/v2/console

## Axes de développement :
- amélioration de la fonctionnalité d’édition du mot de passe
- calcul de l’empreinte carbone de l’itinéraire choisi
- profil utilisateur enrichi avec moyen de déplacement préféré
