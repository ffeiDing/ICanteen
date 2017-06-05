## Server IP:
120.25.232.98:9999

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
  	* success: true



### Update password

* API: /user/chpw.php
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




### get user info

* API: /user/info.php?name=xx
* HTTP Method: GET
* Parameters:
	* name: (String)

* 422 No such user

* 200 OK: return JSON:
	* name: String
	* realName: String
	* phone: 13 digits String
	* email: String



id: int(32)
canteen_name: String (100)
floor: int(4)
window_name: String (100)
service_time: String (100)
food_name: String (100)
price: decimal
taste: String (100)


create table user (
name VARCHAR(100) PRIMARY KEY,
password VARCHAR(100) NOT NULL,
realName VARCHAR(100) NOT NULL,
phone CHAR(11) NOT NULL,
email VARCHAR(100) NOT NULL);

ALTER DATABASE users CHARACTER SET utf8 COLLATE utf8_unicode_ci;

create table food (
id INT(32) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
canteen_name VARCHAR(100) NOT NULL,
floor INT(4) UNSIGNED NOT NULL,
window_name VARCHAR(100) NOT NULL,
service_time VARCHAR(100) NOT NULL,
food_name VARCHAR(100) NOT NULL,
price DECIMAL(5,2) NOT NULL,
taste VARCHAR(100) NOT NULL,
score DECIMAL(5,1) NOT NULL);


load data infile '/var/lib/mysql-files/data.csv'
into table food
fields terminated by ','
lines terminated by '\n'
ignore 1 rows
(canteen_name, floor, window_name, service_time, food_name, price, taste);

### get food info

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
	* else: return food_list (including score and tot(当前评价人数))


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


create table food.comments (
id INT(32) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
food_id INT(32) NOT NULL,
user_name VARCHAR(100) NOT NULL,
post_date TIMESTAMP NOT NULL,
content TEXT NOT NULL,
score INT(32) NOT NULL);


### get food comments

* API: /food/comments.php?food_id=xx
	   
* HTTP Method: GET
* Parameters:
	* food_id: Integer


* 422 No such food_id

* 200 OK: return JSON
	{comments:[{user_name: "xxx", post_date: "xxx", content: "xxx", score: "x"}], success:true}



### recommend food

* API: /food/info.php?canteen=xx&floor=xx
	   /food/info.php?canteen=xx&floor=xx&window=xx
* HTTP Method: GET
* Parameters:
	* canteen: canteen_name (String) not null
	* floor: floor (int) not null
	* window: window_name (String) (allow null)

* 422 No such canteen or floor or window

* 200 OK: return JSON:
	* if window is null: random a window
	* return food_list (including window list)