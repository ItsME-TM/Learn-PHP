<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceBook Group</title>
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
            padding-left: 20px;
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
        main {
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
// Fetch and display users from the 'users' table
require "../config.php";
require "../common.php";

try {
    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT user_id, user_firstname, user_lastname FROM users";
    $result = $connection->query($sql);

    // Create an array to keep track of selected user IDs
    $selected_user_ids = isset($_POST['selected_users']) ? $_POST['selected_users'] : [];
    $error_message = "";
    ?>

    <?php require "templates/header.php"; ?>

    <?php
    if (isset($_POST['submit'])) {
        // Handle form submission after fetching users
        try  {
            $connection = new PDO($dsn, $username, $password, $options);

            // Create the new group
            $new_group = array(
                "fbgroup_name"  => $_POST['fbgroup_name']
            );

            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                "fb_group",
                implode(", ", array_keys($new_group)),
                ":" . implode(", :", array_keys($new_group))
            );

            $statement = $connection->prepare($sql);
            $statement->execute($new_group);

            // Get the ID of the newly created group
            $group_id = $connection->lastInsertId();

            // Insert selected users into group_users table
            if (!empty($selected_user_ids)) {
                foreach ($selected_user_ids as $user_id) {
                    $sql = "INSERT INTO group_users (user_id, fb_groupid) VALUES (:user_id, :group_id)";
                    $statement = $connection->prepare($sql);
                    $statement->execute(array(':user_id' => $user_id, ':group_id' => $group_id));
                }
            }
        } catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
        ?>

        <?php if ($statement) { ?>
            <blockquote><?php echo $_POST['fbgroup_name']; ?> successfully added.</blockquote>
        <?php } ?>
    <?php } ?>

    <link rel="stylesheet" href="css/style.css">
    <header>Add a Group</header>
    <form method="post">
        <label for="fbgroup_name">FaceBook Group Name</label>
        <input type="text" name="fbgroup_name" id="fbgroup_name">
        
        <!-- Multi-select dropdown for selecting users -->
        <label for="selected_users">Select Users</label>
        <select multiple name="selected_users[]" id="selected_users">
            <?php
            foreach ($result as $row) {
                $user_id = $row['user_id'];
                $user_firstname = $row['user_firstname'];
                $user_lastname = $row['user_lastname'];

                // Check if the user is selected
                $selected = in_array($user_id, $selected_user_ids) ? 'selected' : '';

                echo "<option value='" . $user_id . "' $selected>" . $user_firstname . " " . $user_lastname . "</option>";
            }
            ?>
        </select>
        
        <div class="button-group">
            <button type="submit" class="button" name="submit" value="Submit">Submit</button>
        </div>
    </form>
    <br><a href="fb_groups.php">Back to home</a>

    <?php require "templates/footer.php"; ?>
    <?php
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>
</body>
</html>
