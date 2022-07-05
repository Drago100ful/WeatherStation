#!/bin/sh
sudo mkdir $HOME/WeatherStation/
sudo cp ./readout.py $HOME/WeatherStation/
sudo echo $HOME
sudo echo "
[Unit]
Description=Starts Sensor Readout
After=multi-user.target

[Service]
Type=simple
ExecStart=$HOME/WeatherStation/readout.py#
Restart=on-abort

[Install]
WantedBy=multi-user.target
" > /lib/systemd/system/sensor_readout.service