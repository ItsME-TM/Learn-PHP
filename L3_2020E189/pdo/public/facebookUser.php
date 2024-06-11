<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FaceBook User</title>
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
      font-size: 40px;
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

if (isset($_POST['submit'])) {
    require "../config.php";
    require "../common.php";

    try  {
        $connection = new PDO($dsn, $username, $password, $options);
        
        $new_user = array(
            "user_firstname" => $_POST['user_firstname'],
            "user_lastname"  => $_POST['user_lastname'],
            "user_email"     => $_POST['user_email'],
            "user_age"       => $_POST['user_age'],
            "user_birthday"  => $_POST['user_birthday']
        );

        $sql = sprintf(
                "INSERT INTO %s (%s) values (%s)",
                "users",
                implode(", ", array_keys($new_user)),
                ":" . implode(", :", array_keys($new_user))
        );
        
        $statement = $connection->prepare($sql);
        $statement->execute($new_user);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) { ?>
    <blockquote><?php echo $_POST['user_firstname']; ?> successfully added.</blockquote>
<?php } ?>
<link rel="stylesheet" href="css/style.css">
<header>Add a user</header>

<form method="post">
    <label for="user_firstname">First Name</label>
    <input type="text" name="user_firstname" id="user_firstname">
    <label for="user_lastname">Last Name</label>
    <input type="text" name="user_lastname" id="user_lastname">
    <label for="user_birthday">Birthday</label>
    <input type="date" name="user_birthday" id="user_birthday">
    <label for="user_email">Email Address</label>
    <input type="text" name="user_email" id="user_email">
    <label for="user_age">Age</label>
    <input type="text" name="user_age" id="user_age">
    <div class = "button-group">
    <button type="submit" class= "button" name="submit" value="Submit">submit</button>
</div>
</form>

<br><a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
</body>
</html>