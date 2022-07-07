#!/bin/sh

ROOTUID="0"

if [ "$(id -u)" -ne "$ROOTUID" ] ; then
    echo "This script must be executed with root privileges."
    exit 1
fi

sudo systemctl stop nginx

echo "IP-Adresse des Hosts: "

read hostip

prfx="{\"host-address\": \""
sufx="\"}"
concat=$prfx$hostip$sufx

echo "Writing Config"
sudo echo "$concat" > ./weather-app/src/assets/config.json
cd ./weather-app/
echo "Building Webapp"
npm install
npm run build

echo "Copying build"
sudo rm -rf /var/www/html
sudo mkdir /var/www/html
sudo cp -a ./rest/. /var/www/html
sudo cp -a ./dist/. /var/www/html

echo "Restarting nginx"
sudo systemctl restart nginx