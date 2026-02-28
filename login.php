<?php
session_start();
if (isset($_SESSION['staff_num'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Staff Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }
    .login-container {
      width: 360px;
      margin: 100px auto;
      background-color: #ffffff;
      border-radius: 6px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .login-container img {
      width: 80px;
      margin: 20px 0;
    }
    .login-container h3 {
      margin-bottom: 25px;
    }
    .login-container .btn {
      width: 100%;
    }
    .footer-text {
      margin-top: 20px;
      font-size: 12px;
      color: #888;
    }
  </style>
</head>
<body>

<div class="login-container panel panel-default">
  <div class="panel-body">

    <img src="logo.png" alt="Logo">
    <h3>Staff Login</h3>

    <form method="post" action="login_process.php">

      <div class="form-group">
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-user"></span>
          </span>
          <input type="text" name="staff_num" class="form-control" placeholder="Staff ID" required>
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-lock"></span>
          </span>
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
      </div>

      <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>

    <?php if (isset($_GET['error'])) { ?>
      <div class="alert alert-danger" style="margin-top:15px;">
        <?php echo $_GET['error']; ?>
      </div>
    <?php } ?>

    <div class="footer-text">
      Perfume Store Management System
    </div>

  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>
