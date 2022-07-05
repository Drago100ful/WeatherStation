#!/bin/sh

sudo apt-get install npm -y
clear
echo "IP-Adresse des Hosts: "

read hostip

prfx="{\"host-address\": \""
sufx="\"}"
concat=$prfx$hostip$sufx

sudo echo "$concat" > ./weather-app/src/assets/config.json
cd ./weather-app/
npm install
npm run build

sudo rm -rf /var/www/html
sudo mkdir /var/www/html
sudo cp -a ./dist/. /var/www/html

