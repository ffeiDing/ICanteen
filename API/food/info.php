<?php

/*
* API: /food/info.php?canteen=xx&floor=xx
	   /food/info.php?canteen=xx&floor=xx&window=xx
* HTTP Method: GET
* Parameters:
	* canteen: canteen_name (String) not null
	* floor: floor (int) not null
	* window: window_name (String) (allow null)

* 422 No such canteen or floor or window

* 200 OK: return JSON:
	* if window is null: return window_list
	* else: return food_list
*/

// Connect to database
$servername = "127.0.0.1";
$username = "root";
$password = "123456";
$dbname = "food";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn -> connect_error) {
	// fatal error
	die("Connection failed: " . $conn -> connect_error);
}

$canteen_name = $_GET["canteen"];
$floor = (int)$_GET["floor"];

$list = array();
$response = array();

if (!array_key_exists("window", $_GET) || $_GET["window"] === null) {

	$conn -> query("SET CHARACTER SET 'utf8'");
	$sql = "SELECT DISTINCT window_name FROM food WHERE canteen_name='$canteen_name' AND floor=$floor";

	$result = $conn -> query($sql);

	if ($result -> num_rows === 0) {
		// No such canteen or floor or window
		header($_SERVER["SERVER_PROTOCOL"] . " 422 No such canteen or floor or window");
		exit(1);
	}

	while ($row = $result -> fetch_assoc()) {
		array_push($list, $row["window_name"]);
	}
	$response["window_name"] = $list;
	$response["success"] = true;
}

else {
	$window_name = $_GET["window"];
	$conn -> query("SET CHARACTER SET 'utf8'");
	$sql = "SELECT id, service_time, food_name, price, taste, score, tot FROM food WHERE canteen_name='$canteen_name' AND floor=$floor AND window_name='$window_name'";

	$result = $conn -> query($sql);

	if ($result -> num_rows === 0) {
		// No such canteen or floor or window
		header($_SERVER["SERVER_PROTOCOL"] . " 422 No such canteen or floor or window");
		exit(1);
	}

	while ($row = $result -> fetch_assoc()) {
		array_push($list, $row);
	}

	$response["foodlist"] = $list;
	$response["success"] = true;

}


http_response_code(200);
header("Content-type: application/json");
echo json_encode($response);
$conn -> close();

?>