<!--
Matric Number: A99441
Name: JIN YANRAN
-->
<?php
session_start(); 
include_once 'database.php';

if (!isset($_SESSION['user_level'])) {
    header("Location: login.php");
    exit();
}

$user_level = $_SESSION['user_level'];
if ($user_level != 'Admin' && $user_level != 'Non-admin') {
    echo "<script>alert('Invalid user level');</script>";
    exit();
}

$oid = isset($_GET['oid']) ? $_GET['oid'] : '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT o.fld_order_num, o.fld_order_date,
        s.fld_staff_fname, s.fld_staff_lname, s.fld_staff_email,
        c.fld_customer_fname, c.fld_customer_lname
        FROM tbl_orders_a199441 o
        JOIN tbl_staffs_a199441 s ON o.fld_staff_num = s.fld_staff_num
        JOIN tbl_customers_a199441 c ON o.fld_customer_num = c.fld_customer_num
        WHERE o.fld_order_num = :oid");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

   
    $stmt = $conn->prepare("SELECT od.fld_order_detail_num, od.fld_order_detail_quantity,
        p.fld_product_name, p.fld_product_price
        FROM tbl_orders_details_a199441 od
        JOIN tbl_products_a199441 p ON od.fld_product_num = p.fld_product_num
        WHERE od.fld_order_num = :oid");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $stmt->execute();
    $details = $stmt->fetchAll();

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice - My Perfume Store</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { margin:20px; }
    .panel-heading h4 { margin:0; }
  </style>
</head>
<body>

<div class="container">

  <!-- Header -->
  <div class="row">
    <div class="col-xs-6 text-center">
      <img src="logo.png" width="60%" height="60%">
    </div>
    <div class="col-xs-6 text-right">
      <h1>INVOICE</h1>
      <?php if($order){ ?>
      <h5>Order: <?php echo $order['fld_order_num']; ?></h5>
      <h5>Date: <?php echo $order['fld_order_date']; ?></h5>
      <?php } ?>
    </div>
  </div>
  <hr>

  <!-- From & To Panels -->
  <div class="row">
    <div class="col-xs-5">
      <div class="panel panel-default">
        <div class="panel-heading"><h4>From: My Perfume Store Sdn. Bhd.</h4></div>
        <div class="panel-body">
          <p>
            123 Aroma Street<br>
            Fragrance City<br>
            50200 Kuala Lumpur<br>
            Malaysia
          </p>
        </div>
      </div>
    </div>
    <div class="col-xs-5 col-xs-offset-2 text-right">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>To: <?php if($order) echo $order['fld_customer_fname']." ".$order['fld_customer_lname']; ?></h4>
        </div>
        <div class="panel-body">
          <p>
            Address 1 <br>
            Address 2 <br>
            Postcode City <br>
            State <br>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Products Table -->
  <table class="table table-bordered">
    <tr style="background:#f2f2f2;">
      <th>No</th>
      <th>Product</th>
      <th class="text-right">Quantity</th>
      <th class="text-right">Price(RM)/Unit</th>
      <th class="text-right">Total(RM)</th>
    </tr>
    <?php
    $grandtotal = 0;
    $counter = 1;
    if($details){
        foreach($details as $row){
            $subtotal = $row['fld_product_price'] * $row['fld_order_detail_quantity'];
            $grandtotal += $subtotal;
            echo "<tr>
                    <td>".$counter."</td>
                    <td>".$row['fld_product_name']."</td>
                    <td class='text-right'>".$row['fld_order_detail_quantity']."</td>
                    <td class='text-right'>".$row['fld_product_price']."</td>
                    <td class='text-right'>".$subtotal."</td>
                  </tr>";
            $counter++;
        }
    }
    ?>
    <tr>
      <td colspan="4" class="text-right"><b>Grand Total</b></td>
      <td class="text-right"><?php echo $grandtotal; ?></td>
    </tr>
  </table>

  <!-- Bank & Contact Details -->
  <div class="row">
    <div class="col-xs-5">
      <div class="panel panel-default">
        <div class="panel-heading"><h4>Bank Details</h4></div>
        <div class="panel-body">
          <p>Your Name</p>
          <p>Bank Name</p>
          <p>SWIFT:</p>
          <p>Account Number:</p>
          <p>IBAN:</p>
        </div>
      </div>
    </div>
    <div class="col-xs-7">
      <div class="panel panel-default">
        <div class="panel-heading"><h4>Contact Details</h4></div>
        <div class="panel-body">
          <?php if($order){ ?>
          <p>Staff: <?php echo $order['fld_staff_fname']." ".$order['fld_staff_lname']; ?></p>
          <p>Email: <?php echo $order['fld_staff_email']; ?></p>
          <?php } ?>
          <p><br></p>
          <p>Computer-generated invoice. No signature is required.</p>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
