<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Group Details</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    h2 {
      background-color: #bccad6;
      margin-bottom: 10px;
      font-family: "Roboto", sans-serif;
      padding: 20px;
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
    echo "Group ID not found in the URL.";
    exit;
}
?>

<?php
try {
    require "../config.php";
    require "../common.php";

    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * FROM fb_group WHERE fbgroup_id = :fbgroup_id";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':fbgroup_id', $id, PDO::PARAM_INT);
    $statement->execute();
    $row = $statement->fetch();

    // Fetch users in the group
    $sql = "SELECT u.* FROM users u INNER JOIN group_users gu ON u.user_id = gu.user_id WHERE gu.fb_groupid = :fbgroup_id";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':fbgroup_id', $id, PDO::PARAM_INT);
    $statement->execute();
    $usersInGroup = $statement->fetchAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<h2>Group Details</h2>
<?php if (is_array($row)) : ?>
  <table>
    <tr>
      <th>Group ID:</th>
      <td><?php echo escape($row['fbgroup_id']); ?></td>
    </tr>
    <tr>
      <th>Group Name:</th>
      <td><?php echo $row['fbgroup_name']; ?></td>
    </tr>
  </table>

  <h2>Users in the Group</h2>
  <?php if (count($usersInGroup) > 0) : ?>
    <table>
      <tr>
        <th>User ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <!-- Add more user details columns as needed -->
      </tr>
      <?php foreach ($usersInGroup as $user) : ?>
        <tr>
          <td><?php echo escape($user['user_id']); ?></td>
          <td><?php echo $user['user_firstname']; ?></td>
          <td><?php echo $user['user_lastname']; ?></td>
          <!-- Add more user details columns as needed -->
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else : ?>
    <p>No users found in this group.</p>
  <?php endif; ?>

  <div class="button-group">
    <a class="button" href="editGroup.php?fbgroup_id=<?php echo escape($row['fbgroup_id']); ?>">Edit Group</a>
  </div>
<?php else : ?>
  <p>No data found for the provided ID.</p>
<?php endif; ?>

<br><a href="showGroups.php">Go Back</a>

</body>
</html>
