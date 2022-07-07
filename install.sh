#!/bin/sh
ROOTUID="0"

if [ "$(id -u)" -ne "$ROOTUID" ] ; then
    echo "This script must be executed with root privileges."
    exit 1
fi
echo "Updating and Installing Updates..."
sudo apt-get update -qq
sudo apt-get upgrade -qq
echo " OK!\n"
sudo apt-get install python3-pip -qq
sudo apt-get install libgpiod2 -qq
sudo apt-get install npm -qq

pip3 install Adafruit-BMP --quiet
pip3 install adafruit-circuitpython-dht --quiet
pip3 install mysql-connector-python --quiet
sudo raspi-config nonint do_i2c 0

sudo apt-get install nginx -qq
sudo apt-get install php7.4 php7.4-fpm php7.4-mysql -qq

sudo rm /etc/nginx/sites-available/default
sudo cp ./installer/default /etc/nginx/sites-available/
sudo chmod -R 777 /var/www/html

sudo apt-get install mariadb-server -qq

sudo mysql -e "
  CREATE DATABASE weather_data CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
  use weather_data;
  CREATE TABLE data_log(log_date DATETIME NOT NULL, temp FLOAT NOT NULL, pressure SMALLINT NOT NULL, altitude SMALLINT NOT NULL, humidity TINYINT NOT NULL, PRIMARY KEY (log_date));
  CREATE USER 'sensor_unit'@'localhost' IDENTIFIED BY '7uu3qWSPkm00RXBY';
  GRANT INSERT ON data_log TO 'sensor_unit'@'localhost';
  CREATE USER 'server_api'@'localhost' IDENTIFIED BY 'tscNGpxGegDgVTgA';
  GRANT ALL PRIVILEGES ON data_log TO 'server_api'@'localhost';
  FLUSH PRIVILEGES;
  "

clear
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
cd ..

sudo mkdir /usr/bin/WeatherStation/
sudo cp ./readout.py /usr/bin/WeatherStation/

sudo echo "[Unit]
Description=Starts Sensor Readout
After=multi-user.target

[Service]
Type=simple
ExecStart=/usr/bin/WeatherStation/readout.py
Restart=on-abort

[Install]
WantedBy=multi-user.target
" > /lib/systemd/system/sensor_readout.service

sudo systemctl enable sensor_readout.service
sudo systemctl start sensor_readout
