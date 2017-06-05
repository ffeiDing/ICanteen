<?php
/*
### get user info

* API: /user/info.php
* HTTP Method: GET
* Parameters:
	* name: (String)

* 422 No such user

* 200 OK: return JSON:
	* name: String
	* realName: String
	* phone: 13 digits String
	* email: String

*/



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

$name = $_GET["name"];


// insert and check
$conn -> query("SET CHARACTER SET 'utf8'");
$sql = "SELECT name, realName, phone, email FROM user WHERE name='$name'";

$result = $conn -> query($sql);

if ($result -> num_rows === 0) {
	// No such user
	header($_SERVER["SERVER_PROTOCOL"] . " 422 No such user");
	exit(1);
}

$response = $result -> fetch_assoc();

http_response_code(200);
header("Content-type: application/json");

echo json_encode($response);

$conn -> close();




?>