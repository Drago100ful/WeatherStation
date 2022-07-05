#!/bin/sh

if [[ $EUID -ne 0 ]]; then
    echo "$0 is not running as root. Try using sudo."
    exit 2
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