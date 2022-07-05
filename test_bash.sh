#!/bin/sh
sudo mkdir /home/$USER/WeatherStation/
sudo cp ./readout.py /home/$USER/WeatherStation/

sudo echo "
[Unit]
Description=Starts Sensor Readout
After=multi-user.target

[Service]
Type=simple
ExecStart=/home/$USER/WeatherStation/readout.py#
Restart=on-abort

[Install]
WantedBy=multi-user.target
" > /lib/systemd/system/sensor_readout.service