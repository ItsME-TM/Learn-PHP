<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recently Joined</title>
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
      background-color: #8d9db6;
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

try {
        
    require "../config.php";
    require "../common.php";

    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * 
            FROM users
            ORDER BY user_id DESC
            LIMIT 3";

    $statement = $connection->prepare($sql);
    $statement->execute();

    $result = $statement->fetchAll();
} catch (PDOException $error) {
    echo $error->getMessage();
}

?>
<?php require "templates/header.php"; ?>
<?php if ($result && $statement->rowCount() > 0) { ?>
    <h2>Recently Joined Users</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>User First Name</th>
                <th>User Last Name</th>
                <th>User Email Address</th>
                <th>User Age</th>
                <th>Action</th>
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
                    <td><a href="view.php?id=<?php echo escape($row[0]); ?>">View</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <style>
        table {
            border-collapse: collapse; 
        }
        th, td {
            padding: 8px; 
        }
    </style>
<?php } else { ?>
    <blockquote>No results found.</blockquote>
<?php } ?>
<br><a href="index.php">Go Back</a>

</body>
</html>