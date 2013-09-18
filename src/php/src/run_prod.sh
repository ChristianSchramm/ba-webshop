#!/bin/bash

case $1 in
  "prod")
  	app/console cache:clear --env=prod
  	app/console assets:install  --symlink --env=prod
  	app/console assetic:dump --env=prod
  ;;
  "dev") 
  	app/console cache:clear
  	app/console assets:install --symlink
  	app/console assetic:dump 
  ;;
  *)
  	app/console cache:clear
  	app/console assets:install --symlink
  	app/console assetic:dump 

  	app/console cache:clear --env=prod
  	app/console assets:install  --symlink --env=prod
  	app/console assetic:dump --env=prod
  ;;
esac
