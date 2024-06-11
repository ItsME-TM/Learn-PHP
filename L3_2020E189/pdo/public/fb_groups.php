<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FaceBook Groups</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 10px;
    }
    header {
      background-color: #bccad6;
      color: #3e4444;
      padding: 1px 0;
      padding-left:20px;
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
<?php require "templates/header.php"; ?>
  <header>
    <h1>FaceBook Groups</h1>
  </header>
  <nav>
    <ul>
      <li><a href="addgroup.php">Add a FaceBook Group</a></li>
      <li><a href="showGroups.php">FaceBook Groups</a></li>   
    </ul>
  </nav>
</body>
</html>
