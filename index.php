<!--
Matric Number: A99441
Name: JIN YANRAN
-->
<?php
session_start();
if (!isset($_SESSION['staff_num'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Perfume Store - Home</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <style>
    body {
      background-color: #f5f5f5;
    }
    .home-container {
      margin-top: 80px;
    }
    .welcome-panel {
      border-radius: 6px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .welcome-panel h2 {
      margin-top: 0;
    }
    .system-info {
      margin-top: 15px;
      font-size: 15px;
      line-height: 1.8;
    }
    .logo-box {
      text-align: center;
      margin-bottom: 20px;
    }
    .logo-box img {
      width: 120px;
      opacity: 0.9;
    }
  </style>
</head>
<body>

<?php include_once 'nav_bar.php'; ?>

<div class="container home-container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">

      <div class="panel panel-default welcome-panel">
        <div class="panel-body">

          <div class="logo-box">
            <img src="logo.png" alt="Logo">
          </div>

          <h2>Welcome</h2>

          <div class="system-info">
            <p>
              Welcome to the <strong>Perfume Store Management System</strong>.
            </p>
            <p>
              Owner: <strong>JIN YANRAN (A199441)</strong>
            </p>
            <p>
              This system includes the following modules:
            </p>
            <ul>
              <li>Products Management</li>
              <li>Customers Management</li>
              <li>Staffs Management</li>
              <li>Orders Management</li>
            </ul>
            <p>
              Please use the navigation menu above to access the system features.
            </p>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>
