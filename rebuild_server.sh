#!/bin/sh

ROOTUID="0"

if [ "$(id -u)" -ne "$ROOTUID" ] ; then
    echo "This script must be executed with root privileges."
    exit 1
fi

echo "IP-Adresse des Hosts: "

read hostip

prfx="{\"host-address\": \""
sufx="\"}"
concat=$prfx$hostip$sufx

clear

echo "OK! \n"
sudo echo "$concat" > ./weather-app/src/assets/config.json
cd ./weather-app/
npm install
npm run build


sudo rm -rf /var/www/html
sudo mkdir /var/www/html
sudo cp -a ./rest/. /var/www/html
sudo cp -a ./dist/. /var/www/html