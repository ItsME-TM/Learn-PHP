<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit user</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 10px;
    }
    header {
      background-color: #bccad6;
      color: #3e4444;
      padding: 10px 0;
      padding-left:20px;
      font-size: 30px;
      color: #000000;
    }
    nav ul {
      list-style: none;
      margin-top: 30px;
      padding-left: 10px;
    }
    nav ul li {
      margin-bottom: 30px;
      margin-top: 20px;
    }
    nav ul li a {
      text-decoration: none;
      color: #222;
      font-weight: bold;
    }
    nav ul li a:hover {
      color: #0056b3;
    }
    .button-group {
      margin-top: 10px;
    }
    .button {
      display: inline-block;
      padding: 3px 6px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
    }
    .button:hover {
      background-color: #0056b3;
    }
    main{
	flex: 1;
    }
    footer {
      background-color: #f2f2f2;
      padding: 20px 0;
      text-align: center;
    }
  </style>
</head>
<body>
<?php

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);

    $users = [
      "user_id" => $_POST['user_id'],
      "user_firstname" => $_POST['user_firstname'],
      "user_lastname" => $_POST['user_lastname'],
      "user_age" => $_POST['user_age'],
      "user_email"=> $_POST['user_email'],
      "user_birthday" => $_POST['user_birthday']
    ];

    $sql = "UPDATE users 
            SET 
            user_firstname = :user_firstname, 
            user_lastname = :user_lastname, 
            user_age = :user_age,
            user_email = :user_email,
            user_birthday = :user_birthday 
            WHERE user_id = :user_id";
  
    $statement = $connection->prepare($sql);
    $statement->execute($users);
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}
  
if (isset($_GET['user_id'])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);
    $user_id = $_GET['user_id'];
    $sql = "SELECT user_firstname, user_lastname, user_age, user_email, user_birthday FROM users WHERE user_id = :user_id";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    
    $user = $statement->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
} else {
  echo "Oops!";
  exit;
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) : ?>
  <blockquote><?php echo escape($_POST['user_firstname']); ?> successfully updated.</blockquote>
<?php endif; ?>
<link rel="stylesheet" href="css/style.css">
<header>Edit User Information</header>

<form method="post" style="margin: 20px;">
  <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
  <label for="user_firstname">First Name</label>
  <input type="text" name="user_firstname" value="<?php echo escape($user['user_firstname']); ?>"><br>
  <label for="user_lastname">Last Name</label>
  <input type="text" name="user_lastname" value="<?php echo escape($user['user_lastname']); ?>"><br>
  <label for="user_age">Age</label>
  <input type="text" name="user_age" value="<?php echo escape($user['user_age']); ?>"><br>
  <label for="user_email">Email</label>
  <input type="text" name="user_email" value="<?php echo $user['user_email']; ?>"><br>
  <label for="user_birthday">Birthday</label>
  <input type="date" name="user_birthday" value="<?php echo escape($user['user_birthday']); ?>"><br>
  <input type="hidden" name="user_id" value="<?php echo escape($user_id); ?>">
<div class = "button-group">
  <button type="submit" class="button" name="submit" value="Save">save</button>
</div>
</form>

<a href="home.php">Go Back</a>

<?php require "templates/footer.php"; ?>
</body>
</html>