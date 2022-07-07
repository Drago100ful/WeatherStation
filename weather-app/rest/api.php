<?php
include "db.php";

header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");

function getData($data)
{
    if (mysqli_num_rows($data) > 1) {
        $collection = [];
        $index = 0;
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $collection[$index] = $row;
            $index++;
        }
        return $collection;
    } elseif (mysqli_num_rows($data) > 0) {
        $data = mysqli_fetch_array($data);
        return [
            "date" => $data["log_date"],
            "temp" => $data["temp"],
            "pressure" => $data["pressure"],
            "humidity" => $data["humidity"],
            "altitude" => $data["altitude"],
        ];
    }

    return null;
}

function getCurrentDate()
{
    return date("Y-m-d G:i:s");
}

function respond($data)
{
    if ($data != null) {
        echo json_encode($data);
    } else {
        echo "No matches";
    }
}

function getDataBetween($difference)
{
    global $database_connection;
    return mysqli_query(
        $database_connection,
        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
        timeSub($difference) .
        "' AND '" .
        getCurrentDate() .
        "'"
    );
}

function timeSub($difference)
{
    return date("Y-m-d G:i:s", strtotime(getCurrentDate()) - $difference);
}

function average($data): array
{
    if ($data['temp'] !== NULL) {
        $size = count($data);
        $temp = 0;
        $pressure = 0;
        $humidity = 0;
        $altitude = 0;

        for ($i = 0; $i < $size; $i++) {
            $temp += $data[$i]["temp"];
            $pressure += $data[$i]["pressure"];
            $humidity += $data[$i]["humidity"];
            $altitude += $data[$i]["altitude"];
        }

        return [
            "date" => $data[0]["log_date"],
            "temp" => round($temp /= $size, 1),
            "pressure" => round($pressure /= $size, 1),
            "humidity" => round($humidity /= $size, 1),
            "altitude" => round($altitude /= $size, 1),
        ];
    }

    return [
        "date" => null,
        "temp" => null,
        "pressure" => null,
        "humidity" => null,
        "altitude" => null,
    ];
}

if (isset($_GET["getTemp"])) {
    global $database_connection;
    if (isset($_GET["timespan"])) {
        $timespan = htmlspecialchars($_GET["timespan"]);

        switch ($timespan) {
            case "current":
                $data = mysqli_query(
                    $database_connection,
                    "SELECT * FROM data_log ORDER BY log_date DESC LIMIT 1"
                );
                respond(getData($data));
                break;
            case "5s":
                $data = getDataBetween(5);
                respond(average(getData($data)));
                break;
            case "30s":
                $data = getDataBetween(30);
                respond(average(getData($data)));

                break;
            case "60s":
                $data = getDataBetween(60);
                respond(average($data));
                break;
            case "5min":
                $data = $data = getDataBetween(300);
                respond(average($data));
                break;
            case "30min":
                $data = $data = getDataBetween(1800);
                respond(average($data));
                break;
            case "1h":
                $data = $data = getDataBetween(3600);
                $data = getData($data);

                respond(average($data));
                break;
            case "12h":
                $data = $data = getDataBetween(43200);
                $data = getData($data);

                respond(average($data));
                break;
            case "1d":
                $data = $data = getDataBetween(86400);
                $data = getData($data);

                respond(average($data));
                break;
            case "7d":
                $data = $data = getDataBetween(604800);
                $data = getData($data);

                respond(average($data));
                break;
        }
        mysqli_close($database_connection);
        die();
    }
}

if (isset($_GET["getAverage"])) {
    global $database_connection;
    if (isset($_GET["timespan"])) {
        $timespan = htmlspecialchars($_GET["timespan"]);

        switch ($timespan) {
            case "current":
                $data = mysqli_query(
                    $database_connection,
                    "SELECT * FROM data_log ORDER BY log_date DESC LIMIT 1"
                );
                respond([getData($data)]);
                break;
            case "5s":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 5; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 1);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    $data[$i] = average(getData($query));

                    $date = $pastDate;
                }
                respond($data);
                break;
            case "30s":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 3; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 10);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    $data[$i] = average(getData($query));

                    $date = $pastDate;
                }
                respond($data);
                break;
            case "60s":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 6; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 10);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    $data[$i] = average(getData($query));

                    $date = $pastDate;
                }
                respond($data);
                break;
            case "5min":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 5; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 60);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    if(getData($query) !== NULL) {
                        $data[$i] = average(getData($query));
                    }

                    $date = $pastDate;
                }
                respond($data);
                break;
            case "30min":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 6; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 300);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    if(getData($query) !== NULL) {
                        $data[$i] = average(getData($query));
                    }

                    $date = $pastDate;
                }
                respond($data);
                break;
            case "1h":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 12; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 300);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    if(getData($query) !== NULL) {
                        $data[$i] = average(getData($query));
                    }

                    $date = $pastDate;
                }
                respond($data);
                break;
            case "12h":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 12; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 3600);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    if(getData($query) !== NULL) {
                        $data[$i] = average(getData($query));
                    }

                    $date = $pastDate;
                }
                respond($data);
                break;
            case "1d":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 24; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 3600);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    if(getData($query) !== NULL) {
                        $data[$i] = average(getData($query));
                    }

                    $date = $pastDate;
                }

                respond($data);
                break;
            case "7d":
                $date = getCurrentDate();
                $data = [];
                for ($i = 0; $i < 7; $i++) {
                    $pastDate = date("Y-m-d G:i:s", strtotime($date) - 86400);
                    $query = mysqli_query(
                        $database_connection,
                        "SELECT * FROM data_log WHERE log_date BETWEEN '" .
                        $pastDate .
                        "' AND '" .
                        $date .
                        "'"
                    );
                    if(getData($query) !== NULL) {
                        $data[$i] = average(getData($query));
                    }

                    $date = $pastDate;
                }

                respond($data);

                break;
        }
        mysqli_close($database_connection);
        die();
    }
}
