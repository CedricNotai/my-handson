# handson-2023-1-equipe-4-perso

## Récupérer le repo et le faire tourner sur votre machine
### En local 
- git clone
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- npm install
- npm run dev
- php artisan serve
- aller surlocalhost:8000

### Avec l'image Docker en utilisant Sail
- git clone
- docker run --rm -u "${id -u}:${id -g}"  -v "${pwd}:/var/www/html"  -w /var/www/html  laravelsail/php81-composer:latest  composer install --ignore-platform-reqs 
- si erreur (bad substitution, remplacer les accolades par des parenthèses
- si erreur, essayer avec un terminal Powershell
- ./vendor/bin/sail up -d

S'il y a des erreurs à ce stade, c'est peut être parce que le terminal n'est pas adapté :
- quitter VSCode
- ouvrir un terminal Windows
- wsl --install -d Ubuntu-20.04
- wsl --set-default Ubuntu-20.04
- rouvrir VSCode en mode administrateur
- ouvrir le terminal sur VSCode en WSL
- relancer ./vendor/bin/sail up -d
- ./vendor/bin/sail php artisan key:generate
- ./vendor/bin/sail npm install
- ./vendor/bin/sail npm run dev

Le projet tourne sur Docker.
N'oubliez pas d'adapter le .env en fonction de votre base de données locale.
