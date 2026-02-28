<!--
Matric Number: A199441
Name: JIN YANRAN
-->
<?php
include_once 'database.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perfume Store : Product Details</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial; }
    .well img { max-width: 100%; height: auto; }
    .panel-body { font-size: 14px; }
  </style>
</head>
<body>

<?php include_once 'nav_bar.php'; ?>

<?php
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT * FROM tbl_products_a199441_pt2 WHERE fld_product_num = :pid");
  $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
  $pid = isset($_GET['pid']) ? $_GET['pid'] : '';
  $stmt->execute();
  $readrow = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
  $readrow = null;
}
$conn = null;
?>

<div class="container-fluid">
  <?php if ($readrow) { ?>
  <div class="row">

    <!-- Product Image -->
    <div class="col-xs-12 col-sm-5 col-sm-offset-1 col-md-4 col-md-offset-2 well text-center">
      <?php if ($readrow['fld_product_image'] == "" ) {
        echo "No image";
      } else { ?>
        <img src="products/<?php echo rawurlencode($readrow['fld_product_image']); ?>" class="img-responsive" alt="<?php echo htmlspecialchars($readrow['fld_product_name']); ?>">
      <?php } ?>
    </div>

    <!-- Product Info -->
    <div class="col-xs-12 col-sm-5 col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading"><strong>Product Details</strong></div>
        <div class="panel-body">
          Below are specifications of this perfume.
        </div>
        <table class="table">
          <tr>
            <td><strong>Product ID</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_num']); ?></td>
          </tr>
          <tr>
            <td><strong>Perfume Name</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_name']); ?></td>
          </tr>
          <tr>
            <td><strong>Brand</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_brand']); ?></td>
          </tr>
          <tr>
            <td><strong>Fragrance Type</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_type']); ?></td>
          </tr>
          <tr>
            <td><strong>Volume (ml)</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_volume']); ?></td>
          </tr>
          <tr>
            <td><strong>Price (RM)</strong></td>
            <td>RM <?php echo number_format($readrow['fld_product_price'],2); ?></td>
          </tr>
          <tr>
            <td><strong>Quantity Available</strong></td>
            <td><?php echo htmlspecialchars($readrow['fld_product_quantity']); ?></td>
          </tr>
        </table>
        <div class="panel-footer text-center">
          <a href="products.php" class="btn btn-primary btn-sm">Back to Products</a>
        </div>
      </div>
    </div>

  </div>
  <?php } else { ?>
    <div class="alert alert-warning text-center" role="alert">
      Product not found. <a href="products.php" class="btn btn-default btn-sm">Back to Products</a>
    </div>
  <?php } ?>
</div>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>
