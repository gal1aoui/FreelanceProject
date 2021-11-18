# Symfony-Mercure-FreelanceProject
create db after configuring url of db in .env file
start migrating tables by -> php bin/console make:migration
start migrating with db -> php bin/console doctrine:schema:update --force

1- git clone https://github.com/gal1aoui/Symfony-Mercure-FreelanceProject.git

2- cd Sym*

3- composer install

4- cd mercure

5- $env:MERCURE_PUBLISHER_JWT_KEY='!ChangeMe!'; $env:MERCURE_SUBSCRIBER_JWT_KEY='!ChangeMe!'; $env:SERVER_NAME=3000; .\mercure.exe run -config Caddyfile.dev

6- cd..

7- symfony serv // Or // symfony server:start 

8- npm run watch

and Done :) !!!
