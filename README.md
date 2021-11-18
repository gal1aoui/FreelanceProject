# Symfony-Mercure-FreelanceProject
1- git clone https://github.com/gal1aoui/Symfony-Mercure-FreelanceProject.git

2- cd Sym*

3- composer install

4- create db after configuring url of db in .env file

5- start migrating tables by -> php bin/console make:migration

6- start migrating with db -> php bin/console doctrine:schema:update --force

7- cd mercure

8- $env:MERCURE_PUBLISHER_JWT_KEY='!ChangeMe!'; $env:MERCURE_SUBSCRIBER_JWT_KEY='!ChangeMe!'; $env:SERVER_NAME=3000; .\mercure.exe run -config Caddyfile.dev

9- cd..

10- symfony serv // Or // symfony server:start 

11- npm run watch

and Done :) !!!
