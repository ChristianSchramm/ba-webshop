#!/bin/bash
echo "###########################"
echo "run testsi with test config"
echo "###########################"


app/console doctrine:database:drop --force --env=test
app/console doctrine:database:create --env=test
app/console doctrine:schema:update --force --env=test

echo "Y" | app/console doctrine:fixtures:load   --env=test 

bin/phpunit -c app/

