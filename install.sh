#!/bin/sh
sudo apt-get update
sudo apt install python3-pip -y
sudo apt-get install libgpiod2 -y

pip3 install Adafruit-BMP
pip3 install adafruit-circuitpython-dht
pip3 install mysql-connector-python
sudo raspi-config nonint do_i2c 0

sudo apt-get install nginx -y
sudo apt-get install php7.4 php7.4-fpm php7.4-mysql -y

sudo rm /etc/nginx/sites-available/default
sudo cp ./installer/default /etc/nginx/sites-available/
sudo chmod -R 777 /var/www/html

sudo apt-get install mariadb-server -y

sudo mysql -e "
  CREATE DATABASE weather_data CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
  use weather_data;
  CREATE TABLE data_log(log_date DATETIME NOT NULL, temp INT NOT NULL, pressure INT NOT NULL, altitude INT NOT NULL, humidity INT NOT NULL, PRIMARY KEY (log_date));
  CREATE USER 'sensor_unit'@'localhost' IDENTIFIED BY '7uu3qWSPkm00RXBY';
  GRANT INSERT ON data_log TO 'sensor_unit'@'localhost';
  CREATE USER 'server_api'@'localhost' IDENTIFIED BY 'tscNGpxGegDgVTgA';
  GRANT ALL PRIVILEGES ON data_log TO 'server_api'@'localhost';
  FLUSH PRIVILEGES;
  "
