<?php
/*
### Register

* API: /user/register.php
* HTTP Method: POST
* Parameters:
	* name: (String)
	* password: (String)
	* realName: (utf8 String)
	* phone: (String 11 digits)
	* email: (email format)

* 400 Bad Request: Happen when the request is from GET.

* 422 Duplicate Name

* 200 OK: Return JSON, including:
  	* _id: need sending everytime
  	* name: need sending everytime 
  	* token: need sending everytime
  	* success: true
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
$realName = $_POST["realName"];
$phone = $_POST["phone"];
$email = $_POST["email"];

$response = array();

// insert and check
$conn -> query("SET CHARACTER SET 'utf8'");
$conn -> query("SET NAMES 'utf8'");

$sql = "INSERT INTO user (name, password, realName, phone, email) VALUES ('$name', '$passwd', '$realName', '$phone', '$email')";

if ($conn -> query($sql) === false) {
	// Duplicate Name
	header($_SERVER["SERVER_PROTOCOL"] . " 422 Duplicate Name");
	exit(1);
}

// successful insert
$payload = array();
$payload["_id"] = time();
$payload["name"] = $name;
$token = JWT::encode($payload, "ILoveYou");

http_response_code(200);
header("Content-type: application/json");
$response["_id"] = $payload["_id"];
$response["name"] = $payload["name"];
$response["token"] = $token;
$response["success"] = true;
echo json_encode($response);


$conn -> close();


?>
