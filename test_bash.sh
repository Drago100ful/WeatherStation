#!/bin/sh

ROOTUID="0"

if [ "$(id -u)" -ne "$ROOTUID" ] ; then
    echo "This script must be executed with root privileges."
    exit 1
fi

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