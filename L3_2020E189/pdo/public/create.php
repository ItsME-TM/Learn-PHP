<?php

/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

  try  {
    $connection = new PDO($dsn, $username, $password, $options);
    
    $new_user = array(
      "firstname" => $_POST['firstname'],
      "lastname"  => $_POST['lastname'],
      "email"     => $_POST['email'],
      "age"       => $_POST['age'],
      "location"  => $_POST['location'],
      "nic"       => $_POST['nic']
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

  <?php if (isset($_POST['submit']) && $statement) : ?>
    <blockquote><?php echo escape($_POST['firstname']); ?> successfully added.</blockquote>
  <?php endif; ?>
  <div style="padding: 2px; text-align: left;">
  <h2 style = "color: #333;">Add a user</h2>
  </div>

  <style>
    .form-group {
        display: block;
        margin-bottom: 10px;
    }

    label {
        display: inline-block;
        width: 120px;
        text-align: right;
        margin-right: 10px;
    }

    input[type="text"] {
        width: 200px;
    }
</style>

  <form method="post">  
<input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">

<div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" id="firstname">
    </div>

    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" id="lastname">
    </div>

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="text" name="email" id="email">
    </div>

    <div class="form-group">
        <label for="location">Location</label>
        <input type="text" name="location" id="location">
    </div>

    <div class="form-group">
        <label for="age">Age</label>
        <input type="text" name="age" id="age">
    </div>

    <div class="form-group">
        <label for="nic">NIC</label>
        <input type="text" name="nic" id="nic">
    </div>

    <div class="form-group">
        <input type="submit" name="submit" value="Submit">
    </div>
</form>
  <a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
