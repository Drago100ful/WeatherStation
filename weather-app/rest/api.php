<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");


function getData($data)
{
    if (mysqli_num_rows($data) > 1) {
        $collection = array();
        $index = 0;
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $collection[$index] = $row;
            $index++;
        }

        return $collection;
    } elseif (mysqli_num_rows($data) > 0) {
        return mysqli_fetch_array($data);
    }

    return NULL;
}

function respond($data)
{
    if ($data != null) {
        echo json_encode($data);
    } else {
        echo "No matches";
    }
}

function getCurrentDate()
{
    return date('Y-m-d G:i:s');
}

function timeSub($difference)
{
    return date('Y-m-d G:i:s', strtotime(getCurrentDate()) - $difference);
}

function average($data) {
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
        round($temp /= $size, 1),
        round($pressure /= $size, 1),
        round($humidity /= $size, 1),
        round($altitude /= $size, 1)
    ];
}


if (isset($_GET['getTemp'])) {

    if (isset($_GET['timespan'])) {
        global $database_connection;
        $timespan = htmlspecialchars($_GET['timespan']);
        include("db.php");

        switch ($timespan) {
            case "current":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log ORDER BY log_date DESC LIMIT 1");
                respond(getData($data));
                break;
            case "30s":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log WHERE log_date BETWEEN '" . timeSub(30) . "' AND '" . getCurrentDate() . "'");
                respond(getData($data));

                break;
            case "60s":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log WHERE log_date BETWEEN '" . timeSub(60) . "' AND '" . getCurrentDate() . "'");
                respond(getData($data));
                break;
            case "5min":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log WHERE log_date BETWEEN '" . timeSub(300) . "' AND '" . getCurrentDate() . "'");
                respond(getData($data));
                break;
            case "30min":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log WHERE log_date BETWEEN '" . timeSub(1800) . "' AND '" . getCurrentDate() . "'");
                respond(getData($data));
                break;
            case "1h":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log WHERE log_date BETWEEN '" . timeSub(3600) . "' AND '" . getCurrentDate() . "'");
                $data = getData($data);

                respond(average($data));
                break;
            case "12h":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log WHERE log_date BETWEEN '" . timeSub(43200) . "' AND '" . getCurrentDate() . "'");
                $data = getData($data);

                respond(average($data));
                break;
            case "1d":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log WHERE log_date BETWEEN '" . timeSub(86400) . "' AND '" . getCurrentDate() . "'");
                $data = getData($data);

                respond(average($data));
                break;
            case "7d":
                $data = mysqli_query($database_connection, "SELECT * FROM data_log WHERE log_date BETWEEN '" . timeSub(604800) . "' AND '" . getCurrentDate() . "'");
                $data = getData($data);

                respond(average($data));
                break;
        }
        mysqli_close($database_connection);
        die();
    }

}

