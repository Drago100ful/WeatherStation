#!/bin/sh

if [[ $(/usr/bin/id -u) -ne 0 ]]; then
    echo "Not running as root"
    exit
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