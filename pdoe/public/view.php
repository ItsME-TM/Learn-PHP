<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Details</title>
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
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID not found in the URL.";
    exit;
}
?>

<?php
try  {
    require "../config.php";
    require "../common.php";

    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM users WHERE user_id = :user_id";

    $statement = $connection->prepare($sql);
    $statement->bindParam(':user_id', $id, PDO::PARAM_INT); 
    $statement->execute();
    $row = $statement->fetch();
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<?php if (is_array($row)) : ?>
  <h2>User Details</h2>
  <table>
    <tr>
      <th>ID:</th>
      <td><?php echo escape($row['user_id']); ?></td>
    </tr>
    <tr>
      <th>First Name:</th>
      <td><?php echo $row['user_firstname']; ?></td>
    </tr>
    <tr>
      <th>Last Name:</th>
      <td><?php echo $row['user_lastname']; ?></td>
    </tr>
    <tr>
      <th>Email:</th>
      <td><?php echo $row['user_email']; ?></td>
    </tr>
    <tr>
      <th>Age:</th>
      <td><?php echo $row['user_age']; ?></td>
    </tr>
    <tr>
      <th>Birthday:</th>
      <td><?php echo $row['user_birthday']; ?></td>
    </tr>
  </table>

  <div class="button-group">
    <a class="button" href="editUser.php?user_id=<?php echo escape($row['user_id']); ?>">Edit User</a>
  </div>
<?php else : ?>
  <p>No data found for the provided ID.</p>
<?php endif; ?>

<br><a href="home.php">Go Back</a>

<?php require "templates/footer.php"; ?>
</body>
</html>
