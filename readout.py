# GeneralDeps
import time
import datetime
import csv

# Sensor
import board
import adafruit_dht
import Adafruit_BMP.BMP085 as BMP085

# SensorInit
dhtDevice = adafruit_dht.DHT11(board.D4)
sensor = BMP085.BMP085()

# CSVInit
def write_csv(data):
    with open('./weather_log.csv', 'a') as outfile:
        writer = csv.writer(outfile)
        writer.writerow(data)


while True:
    try:
        humidity = dhtDevice.humidity

        write_csv([
            datetime.datetime.now(),
            sensor.read_temperature(),
            sensor.read_pressure(),
            sensor.read_altitude(),
            humidity
        ])

    except RuntimeError as error:
        # Errors happen fairly often, DHT's are hard to read, just keep going
        print(error.args[0])
        time.sleep(2.0)
        continue

    time.sleep(2.0)
