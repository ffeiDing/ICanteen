<?php
/*

### add food comments

* API: /food/comments.php
	   
* HTTP Method: POST
* Parameters:
	* food_id: Integer
	* name: who adds comments (String)
	* _id: Integer (for login status check)
	* token: String (for login status check)
	* content: content of comments
	* score: Integer (0-5)


* 422 No such food_id or user

* 401 Not Login: Happen when 'token' is tampered.

* 200 OK:
	{success:true}

### get food comments

* API: /food/comments.php?food_id=xx
	   
* HTTP Method: GET
* Parameters:
	* food_id: Integer


* 422 No such food_id

* 200 OK: return JSON
	{comments:[{user_name: "xxx", post_date: "xxx", content: "xxx", score: "x"}], success:true}

*/
require "/home/appserver/firebase/vendor/autoload.php";
use \Firebase\JWT\JWT;

$servername = "127.0.0.1";
$username = "root";
$password = "123456";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn -> connect_error) {
	// fatal error
	die("Connection failed: " . $conn -> connect_error);
}


$conn -> query("SET CHARACTER SET 'utf8'");
$conn -> query("SET NAMES 'utf8'");

$response = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	

	// Read input
	$food_id = (int)$_POST["food_id"];
	$name = $_POST["name"];
	$_id = (int)$_POST["_id"];
	$content = $_POST["content"];
	$score = $_POST["score"];

	$sql = "SELECT name FROM users.user WHERE name='$name'";
	$result = $conn -> query($sql);
	if ($result -> num_rows === 0) {
		// No such user
		header($_SERVER["SERVER_PROTOCOL"] . " 422 No such user");
		exit(1);
	}

	$sql = "SELECT score, tot FROM food.food WHERE id=$food_id";
	$result = $conn -> query($sql);
	if ($result -> num_rows === 0) {
		// No such food_id
		header($_SERVER["SERVER_PROTOCOL"] . " 422 No such food_id");
		exit(1);
	}

	$row = $result -> fetch_assoc();
	$newTot = (int)$row["tot"] + 1;
	$newScore = ((double)$row["score"] * (int)$row["tot"] + $score) / $newTot;

	$sql = "UPDATE food.food SET score=$newScore, tot=$newTot WHERE id=$food_id";
	if ($conn -> query($sql) === false) {
		die("Database Update Error");
	}

	// Check token
	$payload = array();
	$payload["_id"] = $_id;
	$payload["name"] = $name;
	$token = JWT::encode($payload, "ILoveYou");

	if ($_POST["token"] !== $token) {
		header($_SERVER["SERVER_PROTOCOL"] . " 401 Not Login");
		exit(1);
	}

	$sql = "INSERT INTO food.comments (food_id, user_name, content, score) VALUES ($food_id, '$name', '$content', '$score')";


	if ($conn -> query($sql) === false) {
		die("Database Insert Error");
	}

	$response["success"] = true;


} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
	// Read input
	$food_id = (int)$_GET["food_id"];

	$sql = "SELECT id FROM food.food WHERE id=$food_id";
	$result = $conn -> query($sql);
	if ($result -> num_rows === 0) {
		// No such food_id
		header($_SERVER["SERVER_PROTOCOL"] . " 422 No such food_id");
		exit(1);
	}

	$sql = "SELECT user_name, post_date, content, score FROM food.comments WHERE food_id=$food_id ORDER BY post_date DESC";

	$list = array();
	$result = $conn -> query($sql);

	while ($row = $result -> fetch_assoc()) {
		array_push($list, $row);
	}

	$response["comments"] = $list;
	$response["success"] = true;
}

http_response_code(200);
header("Content-type: application/json");
echo json_encode($response);
$conn -> close();
?>