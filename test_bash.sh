#!/bin/sh
sudo mkdir /usr/bin/WeatherStation/
sudo cp ./readout.py /usr/bin/WeatherStation/

sudo echo "
[Unit]
Description=Starts Sensor Readout
After=multi-user.target

[Service]
Type=simple
ExecStart=/usr/bin/WeatherStation/readout.py
Restart=on-abort

[Install]
WantedBy=multi-user.target
" > /lib/systemd/system/sensor_readout.service