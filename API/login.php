<?php
/*
### Login

* API: /user/login.php
* HTTP Method: POST
* Parameters:
	* name: username (String)
	* password: password (String)

* 400 Bad Request: Happen when the request is from GET.

* 422 Wrong Name or Password

* 200 OK: Return JSON, including:
  	* _id: need sending everytime
  	* name: need sending everytime 
  	* token: need sending everytime
*/
require "/home/appserver/firebase/vendor/autoload.php";
use \Firebase\JWT\JWT;

// Request must be from POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	http_response_code(400);
	echo "<p>Please request via POST</p>";
	exit(1);
}


// Connect to database
$servername = "127.0.0.1";
$username = "root";
$password = "123456";
$dbname = "users";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn -> connect_error) {
	// fatal error
	die("Connection failed: " . $conn -> connect_error);
}


// Read input
$name = $_POST["name"];
$passwd = $_POST["password"];

$response = array();

// check name and passwd
$sql = "SELECT * FROM userInfo WHERE name = '$name' AND password = '$passwd'";
$result = $conn -> query($sql);
if ($result -> num_rows == 0) {
	// Wrong Name or Password
	header($_SERVER["SERVER_PROTOCOL"] . " 422 Wrong Name or Password");
	exit(1);
}

// successful login
$payload = array();
$payload["_id"] = time();
$payload["name"] = $name;
$token = JWT::encode($payload, "ILoveYou");

http_response_code(200);
header("Content-type: application/json");
$response["_id"] = $payload["_id"];
$response["name"] = $payload["name"];
$response["token"] = $token;
echo json_encode($response);


$conn -> close();


?>
