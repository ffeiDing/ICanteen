<?php
/*
### Update Info

* API: /user/info.php
* HTTP Method: POST
* Parameters:
	* _id: Integer
	* name: String
	* old_password: String
	* new_password: String
	* token: String

* 401 Not Login: Happen when 'token' is tampered.

* 422 Wrong Password: Happen when 'old_password' is wrong.

* 200 OK: Return None. '_id' and token are still available.

*/
require "/home/appserver/firebase/vendor/autoload.php";
use \Firebase\JWT\JWT;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
	$old_passwd = $_POST["old_password"];
	// Check name and passwd
	$sql = "SELECT * FROM userInfo WHERE name = '$name' AND password = '$old_passwd'";
	$result = $conn -> query($sql);
	if ($result -> num_rows == 0) {
		// Wrong Name or Password
		header($_SERVER["SERVER_PROTOCOL"] . " 422 Wrong Password");
		exit(1);
	}

	// Check token
	$payload = array();
	$payload["_id"] = (int)$_POST["_id"];
	$payload["name"] = $_POST["name"];
	$token = JWT::encode($payload, "ILoveYou");

	if ($_POST["token"] != $token) {
		header($_SERVER["SERVER_PROTOCOL"] . " 401 Not Login");
		exit(1);
	}

	// Updata info
	$new_passwd = $_POST["new_password"];
	$sql = "UPDATE userInfo SET password = '$new_passwd' WHERE name = '$name'";
	$conn -> query($sql);

	http_response_code(200);
}
?>