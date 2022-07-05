<?php

$database_connection = mysqli_connect(
    "localhost",
    "server_api",
    "tscNGpxGegDgVTgA",
    "weather_data"
);

if (mysqli_errno($database_connection)) {
    echo "Issue connecting to database: " . mysqli_connect_error();
    die();
}
