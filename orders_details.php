<?php

/*
Matric Number: A199441
Name: JIN YANRAN
*/
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once 'orders_details_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perfume Store : Order Details & Invoice</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <style>
    .error-msg { color: red; font-size: 0.9em; display: none; }
  </style>
</head>
<body>

<?php include_once 'nav_bar.php'; ?>

<?php
$oid = isset($_GET['oid']) ? $_GET['oid'] : '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $stmt = $conn->prepare("
        SELECT o.fld_order_num, o.fld_order_date, 
               s.fld_staff_fname, s.fld_staff_lname,
               c.fld_customer_fname, c.fld_customer_lname
        FROM tbl_orders_a199441 o
        JOIN tbl_staffs_a199441_pt2 s ON o.fld_staff_num = s.fld_staff_num
        JOIN tbl_customers_a199441_pt2 c ON o.fld_customer_num = c.fld_customer_num
        WHERE o.fld_order_num = :oid
    ");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $stmt->execute();
    $readrow = $stmt->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container">
  <?php if($readrow) { ?>

  <!-- Order Details -->
  <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading"><strong>Order Details</strong></div>
        <div class="panel-body">
          <p>Order ID: <?php echo $readrow['fld_order_num']; ?></p>
          <p>Order Date: <?php echo $readrow['fld_order_date']; ?></p>
          <p>Staff: <?php echo $readrow['fld_staff_fname']." ".$readrow['fld_staff_lname']; ?></p>
          <p>Customer: <?php echo $readrow['fld_customer_fname']." ".$readrow['fld_customer_lname']; ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Product Form -->
  <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <div class="page-header">
        <h2>Add a Product</h2>
      </div>
      <form action="orders_details.php" method="post" class="form-horizontal" name="frmorder" id="forder" onsubmit="return validateForm()">
        <div class="form-group">
          <label for="prd" class="col-sm-3 control-label">Product</label>
          <div class="col-sm-9">
            <select name="pid" class="form-control" id="prd">
              <option value="">Please select</option>
              <?php
              try {
                  $stmt = $conn->prepare("SELECT * FROM tbl_products_a199441_pt2");
                  $stmt->execute();
                  $products = $stmt->fetchAll();
                  foreach($products as $productrow) {
                      echo "<option value='".$productrow['fld_product_num']."'>".$productrow['fld_product_brand']." ".$productrow['fld_product_name']."</option>";
                  }
              } catch(PDOException $e) {
                  echo "Error: " . $e->getMessage();
              }
              ?>
            </select>
            <span id="pid-error" class="error-msg">Please select a product.</span>
          </div>
        </div>

        <div class="form-group">
          <label for="qty" class="col-sm-3 control-label">Quantity</label>
          <div class="col-sm-9">
            <input name="quantity" type="number" class="form-control" id="qty" min="1">
            <span id="qty-error" class="error-msg">Please enter quantity (greater than 0).</span>
          </div>
        </div>

        <input name="oid" type="hidden" value="<?php echo $oid; ?>">

        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
            <button class="btn btn-default" type="submit" name="addproduct">
              <span class="glyphicon glyphicon-plus"></span> Add Product
            </button>
            <button class="btn btn-default" type="reset" onclick="clearErrors()">Clear</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Products in This Order -->
  <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <div class="page-header">
        <h2>Products in This Order</h2>
      </div>
      <table class="table table-striped table-bordered">
        <tr>
          <th>Order Detail ID</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Action</th>
        </tr>
        <?php
        try {
        $stmt = $conn->prepare("
            SELECT od.fld_order_detail_num, od.fld_order_detail_quantity, p.fld_product_name, p.fld_product_brand
            FROM tbl_orders_details_a199441 od
            JOIN tbl_products_a199441_pt2 p
              ON od.fld_product_num = p.fld_product_num
            WHERE od.fld_order_num = :oid
        ");
        $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
        $stmt->execute();
        $details = $stmt->fetchAll();
        foreach($details as $detailrow) {
            echo "<tr>
                    <td>".$detailrow['fld_order_detail_num']."</td>
                    <td>".$detailrow['fld_product_brand']." ".$detailrow['fld_product_name']."</td>
                    <td>".$detailrow['fld_order_detail_quantity']."</td>
                    <td>
                      <a href='orders_details.php?delete=".$detailrow['fld_order_detail_num']."&oid=".$oid."' class='btn btn-danger btn-xs' onclick=\"return confirm('Are you sure to delete?');\">Delete</a>
                    </td>
                  </tr>";
        }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
      </table>
    </div>
  </div>

  <!-- Generate Invoice Button -->
  <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <a href="invoice.php?oid=<?php echo $oid; ?>" target="_blank" class="btn btn-primary btn-lg btn-block">Generate Invoice</a>
    </div>
  </div>
  <br>

  <?php } else { ?>
    <div class="row">
      <div class="col-xs-12 text-center">
        <p><b>No order found. Please select from <a href='orders.php'>Orders</a>.</b></p>
      </div>
    </div>
  <?php } ?>

</div>

<script type="text/javascript">
function validateForm() {
    var pid = document.forms["frmorder"]["pid"].value;
    var qty = document.forms["frmorder"]["quantity"].value;

    var valid = true;

    if (pid == "") {
        document.getElementById('pid-error').style.display = 'block';
        valid = false;
    } else {
        document.getElementById('pid-error').style.display = 'none';
    }

    if (qty == "" || qty <= 0) {
        document.getElementById('qty-error').style.display = 'block';
        valid = false;
    } else {
        document.getElementById('qty-error').style.display = 'none';
    }

    return valid;
}

function clearErrors() {
    document.getElementById('pid-error').style.display = 'none';
    document.getElementById('qty-error').style.display = 'none';
}
</script>

<!-- jQuery & Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>
