<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Users</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    h2 {
      background-color: #bccad6;
      margin-bottom:10px;
      font-family: "Roboto", sans-serif;
      padding:20px;
      font-size: 30px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
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
  </style>
</head>
<body>
<?php

/**
 * Function to query information based on 
 * a parameter: in this case, user_firstname.
 *
 */

if (isset($_POST['submit'])) {
    try  {
        
        require "../config.php";
        require "../common.php";

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * 
                        FROM users
                        WHERE user_firstname = :user_firstname";

        $user_firstname = $_POST['user_firstname'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':user_firstname', $user_firstname, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<?php require "templates/header.php"; ?>
        
<?php  
if (isset($_POST['submit'])) {
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>Results</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User First Name</th>
                    <th>User Last Name</th>
                    <th>User Email Address</th>
                    <th>User Age</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo escape($row["user_id"]); ?></td>
                <td><?php echo escape($row["user_firstname"]); ?></td>
                <td><?php echo escape($row["user_lastname"]); ?></td>
                <td><?php echo escape($row["user_email"]); ?></td>
                <td><?php echo escape($row["user_age"]); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_POST['user_firstname']); ?>.</blockquote>
    <?php } 
} ?> 

<h2>Find user based on First Name</h2>

<form method="post">
    <label for="user_firstname">First Name</label>
    <input type="text" id="user_firstname" name="user_firstname">
    <div class = "button-group">
    <button class="button" type="submit" name="submit" value="View Results">View Results</button>
</div>
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
</body>
</html>