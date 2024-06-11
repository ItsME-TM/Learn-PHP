<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Group</title>
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
if (isset($_GET['fbgroup_id'])) {
    $id = $_GET['fbgroup_id'];
} else {
    echo "Group ID not found in the URL.";
    exit;
}

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

    // Fetch users not in the group
    $sql = "SELECT u.* FROM users u
            WHERE u.user_id NOT IN (SELECT gu.user_id FROM group_users gu WHERE gu.fb_groupid = :fbgroup_id)";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':fbgroup_id', $id, PDO::PARAM_INT);
    $statement->execute();
    $usersNotInGroup = $statement->fetchAll();

    // Handle form submissions
    if (isset($_POST['edit'])) {
        // Handle group name edit
        $fbgroup_name = $_POST['fbgroup_name'];

        try {
            $sql = "UPDATE fb_group SET fbgroup_name = :fbgroup_name WHERE fbgroup_id = :fbgroup_id";
            $statement = $connection->prepare($sql);
            $statement->bindParam(':fbgroup_name', $fbgroup_name, PDO::PARAM_STR);
            $statement->bindParam(':fbgroup_id', $id, PDO::PARAM_INT);
            $statement->execute();
        } catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }

        // Handle adding users to the group
        if (isset($_POST['selected_users'])) {
            $selected_users = $_POST['selected_users'];

            foreach ($selected_users as $user_id) {
                try {
                    $sql = "INSERT INTO group_users (user_id, fb_groupid) VALUES (:user_id, :fbgroup_id)";
                    $statement = $connection->prepare($sql);
                    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $statement->bindParam(':fbgroup_id', $id, PDO::PARAM_INT);
                    $statement->execute();
                } catch(PDOException $error) {
                    echo $sql . "<br>" . $error->getMessage();
                }
            }
        }

        // Handle removing users from the group
        if (isset($_POST['selected_users_remove'])) {
            $selected_users_remove = $_POST['selected_users_remove'];

            foreach ($selected_users_remove as $user_id) {
                try {
                    $sql = "DELETE FROM group_users WHERE user_id = :user_id AND fb_groupid = :fbgroup_id";
                    $statement = $connection->prepare($sql);
                    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $statement->bindParam(':fbgroup_id', $id, PDO::PARAM_INT);
                    $statement->execute();
                } catch(PDOException $error) {
                    echo $sql . "<br>" . $error->getMessage();
                }
            }
        }

        // Redirect to the viewgroup.php page after editing
        header("Location: viewGroups.php?id=$id");
        exit;
    }
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<h2>Edit Group</h2>
<form method="post">
    <label for="fbgroup_name">FaceBook Group Name</label>
    <input type="text" name="fbgroup_name" id="fbgroup_name" value="<?php echo $row['fbgroup_name']; ?>">
    
    <!-- Multi-select dropdown for adding users -->
    <label for="selected_users">Add Users to the Group</label>
    <select multiple name="selected_users[]" id="selected_users">
        <?php
        foreach ($usersNotInGroup as $user) {
            echo "<option value='" . $user['user_id'] . "'>" . $user['user_firstname'] . " " . $user['user_lastname'] . "</option>";
        }
        ?>
    </select>

    <!-- Multi-select dropdown for removing users -->
    <label for="selected_users_remove">Remove Users from the Group</label>
    <select multiple name="selected_users_remove[]" id="selected_users_remove">
        <?php
        foreach ($usersInGroup as $user) {
            echo "<option value='" . $user['user_id'] . "'>" . $user['user_firstname'] . " " . $user['user_lastname'] . "</option>";
        }
        ?>
    </select>
    
    <div class="button-group">
        <button type="submit" class="button" name="edit" value="Edit">Save Changes</button>
    </div>
</form>

<br><a href="showGroups.php">Go Back</a>

</body>
</html>
