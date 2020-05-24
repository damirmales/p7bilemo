
##Clone the project

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/96e99bc8b0484136860644b310977ba0)](https://app.codacy.com/manual/d.males/p7bilemo?utm_source=github.com&utm_medium=referral&utm_content=damirmales/p7bilemo&utm_campaign=Badge_Grade_Dashboard)

On github https://github.com/damirmales/p7bilemo Download the zip file of the project p7bilemo-master.zip 
install it then go to the root of the project

Install composer as it’s needed to take care about depencies.
With your command line launch a composer update 

Configure the the .env File to access your database, add a name of your futur database
see section doctrine/doctrine-bundle 

##Setup the Database
Start your local server, then go in the project folder do in command line
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
##Generate the SSH keys with JWT
First create a jwt folder into the config directory,then in command line 
```
generate the first key
openssl genrsa -out config/jwt/private.pem -aes256 4096
you will be asked for a pass phrase : note it carefully
generate the second key
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
Report the pass phrase into the lexik/jwt-authentication-bundle section of the .env file

##Test the API with Postman
See this tutorial to handle the software if necessary

<<https://openclassrooms.com/fr/courses/4668056-construisez-des-microservices/5123020-testez-votre-api-grace-a-postman>>

##Documentation
Available at your  localhost:8000/doc
