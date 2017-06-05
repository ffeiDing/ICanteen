<?php

/*
### recommend food

* API: /food/info.php?money=xx&canteen=xx&floor=xx
	   /food/info.php?money=xx&canteen=xx&floor=xx&window=xx
* HTTP Method: GET
* Parameters:
	* money: Integer
	* canteen: canteen_name (String) not null
	* floor: floor (int) not null
	* window: window_name (String) (allow null)

* 422 No such canteen or floor or window

* 200 OK: return JSON:
	* if window is null: random a window
	* else: return food_list (including window list)
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


$conn -> query("SET CHARACTER SET 'utf8'");

if (!array_key_exists("window", $_GET) || $_GET["window"] === null) {

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

	$length = count($list);
	while (true) {
		$x = rand(0, $length - 1);
		if ($list[$x] !== "水果") {
			$window_name = $list[$x];
			break;
		}
	}
}

else {
	$window_name = $_GET["window"];
}




$sql = "SELECT id, price, score FROM food WHERE canteen_name='$canteen_name' AND floor=$floor AND window_name='$window_name'";

$result = $conn -> query($sql);

if ($result -> num_rows === 0) {
	// No such canteen or floor or window
	header($_SERVER["SERVER_PROTOCOL"] . " 422 No such canteen or floor or window");
	exit(1);
}


$food = array();

while ($row = $result -> fetch_assoc()) {
	array_push($food, array((int)($row["id"]), (int)((double)($row["price"]) * 100), (double)($row["score"])));
}



// DP
$n = count($food);
$money = (int)($_GET["money"]) * 100;

$rand_keys = shuffle($food);

for ($i = 0; $i < $n; $i++)
	for ($j = 0; $j <= $money; $j++) {
		$dym[$i][$j] = 0.0;
		$cho[$i][$j] = false;
	}


for ($j = $food[0][1]; $j <= $money; $j++) {
	$dym[0][$j] = $food[0][2];
	$cho[0][$j] = true;
}




for ($i = 1; $i < $n; $i++)
	for ($j = 0; $j <= $money; $j++) {
		$dym[$i][$j] = $dym[$i - 1][$j];
		$cho[$i][$j] = false;

		if ($j >= $food[$i][1]) {
			if ($dym[$i][$j] <= $dym[$i - 1][$j - $food[$i][1]] + $food[$i][2]) {
				$dym[$i][$j] = $dym[$i - 1][$j - $food[$i][1]] + $food[$i][2];
				$cho[$i][$j] = true;
			}
		}
	}

$ans = array();

for ($i = $n - 1, $j = $money; $i >= 0; $i--) {
	if ($cho[$i][$j]) {
		array_push($ans, $food[$i][0]);
		$j = $j - $food[$i][1];
	}
}

$response = array();
$list = array();


foreach ($ans as $food_id) {
	$sql = "SELECT id, window_name, service_time, food_name, price, taste, score, tot FROM food WHERE id=$food_id";
	$result = $conn -> query($sql);
	if ($result === false) {
		die("Database Query Error");
	}
	array_push($list, $result -> fetch_assoc());
}

$response["foodlist"] = $list;
$response["success"] = true;

http_response_code(200);
header("Content-type: application/json");
echo json_encode($response);
$conn -> close();

?>