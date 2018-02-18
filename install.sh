#!/usr/bin/env bash

echo 'get composer...' 

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

php composer.phar install
npm install
npm run build

php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:create

php bin/console cache:clear

# End
cd -
