CREATE DATABASE test;

use test;

CREATE TABLE users (
	user_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	user_firstname VARCHAR(30) NOT NULL,
	user_lastname VARCHAR(30) NOT NULL,
	user_email VARCHAR(50) NOT NULL,
	user_age INT(3)
);