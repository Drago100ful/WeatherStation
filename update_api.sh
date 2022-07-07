#!/bin/sh

ROOTUID="0"

if [ "$(id -u)" -ne "$ROOTUID" ] ; then
    echo "This script must be executed with root privileges."
    exit 1
fi

sudo rm -rf /var/www/html/api.php
sudo rm -rf /var/www/html/db.php
cd ./weather-app/
sudo cp -a ./rest/. /var/www/html
sudo cp -a ./dist/. /var/www/html