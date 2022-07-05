#!/bin/sh

cd ./weather-app/
clear
echo "IP-Adresse des Hosts: "

read hostip

prfx = '{"host-address": "'
sufx = '"}'
concat = $prfx + $hostip + $sufx

sudo echo "$concat" > ./weather-app/src/assets/config.json
npm run install
npm run build

