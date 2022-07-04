#!/usr/bin/python3

# GeneralDeps
import time
import datetime

# Sensor
import board
import adafruit_dht
import Adafruit_BMP.BMP085 as BMP085

# SensorInit
import mysql.connector

dhtDevice = adafruit_dht.DHT11(board.D4)
sensor = BMP085.BMP085()

# DB
database = mysql.connector.connect(
    host="localhost",
    user="sensor_unit",
    password="=@{yR5s5?e<Xf§)%"
)
cursor = database.cursor()
sql = "INSERT INTO data_log (log_date, temp, pressure, altitude, humidity) VALUES (%s, %d, %d, %d, %d)"

while True:
    try:
        humidity = dhtDevice.humidity

        vals = (datetime.datetime.now(), sensor.read_temperature(), sensor.read_pressure() / 100, sensor.read_altitude(), humidity)
        cursor.execute(sql, vals)

        database.commit()

    except RuntimeError as error:
        # Errors happen fairly often, DHT's are hard to read, just keep going
        print(error.args[0])
        time.sleep(2.0)
        continue

    time.sleep(2.0)
