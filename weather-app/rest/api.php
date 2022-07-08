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
            if(isset($row["log_date"])) {
                $collection[$index] = $row;
                $index++;
            }
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

return [];
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
    if(isset($data)) {
        $count = count($data);
        $temp = 0;
        $humid = 0;
        $press = 0;
        $alt = 0;

        for($i = 0; $i < $count; $i++) {
            $temp += $data[$i]['temp'];
            $humid += $data[$i]['humidity'];
            $press += $data[$i]['pressure'];
            $alt += $data[$i]['altitude'];
        }

        return [
          "date" => $data[0]["log_date"],
          "temp" => $temp/$count,
          "pressure" => $press/$count,
          "humidity" => $humid/$count,
          "altitude" => $alt/$count
        ];
    }

    return [];
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
                $query = getDataBetween(5);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/3;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);
                break;
            case "30s":
                $query = getDataBetween(30);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/5;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);

                break;
            case "60s":
                $query = getDataBetween(60);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/10;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);
                break;
            case "5min":
                $query = getDataBetween(300);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/5;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);
                break;
            case "30min":
                $query = getDataBetween(1800);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/5;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);
                break;
            case "1h":
                $query = getDataBetween(3600);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/6;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);
                break;
            case "12h":
                $query = getDataBetween(43200);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/12;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);
                break;
            case "1d":
                $query = getDataBetween(86400);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/24;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);
                break;
            case "7d":
                $query = getDataBetween(604800);
                $queryResult = getData($query);
                $count = count($queryResult);
                $chunksize = (int) $count/7;

                $chunks = array_chunk($queryResult, $chunksize);

                $data = [];

                for($i = 0; $i < count($chunks); $i++) {
                    $data[] = average($chunks[$i]);
                }

                respond($data);
                break;
        }
        mysqli_close($database_connection);
        die();
    }
}
