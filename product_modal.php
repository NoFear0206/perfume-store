<?php
include_once 'database.php';

if (!isset($_GET['pid'])) {
  echo "Invalid product.";
  exit();
}

$pid = $_GET['pid'];

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT * FROM tbl_products_a199441_pt2 WHERE fld_product_num = :pid");
  $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
  $stmt->execute();
  $product = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error loading product.";
  exit();
}

if (!$product) {
  echo "Product not found.";
  exit();
}
?>

<div class="text-center">

  <!-- Product Image -->
  <?php if ($product['fld_product_image'] != "") { ?>
    <img src="products/<?php echo htmlspecialchars($product['fld_product_image']); ?>"
         class="img-responsive img-thumbnail center-block"
         style="max-height:220px; margin-bottom:15px;">
  <?php } else { ?>
    <p>No image available</p>
  <?php } ?>

  <!-- Product Info Table -->
  <table class="table table-bordered table-condensed" style="max-width:420px; margin:auto;">
    <tr>
      <th width="45%">Product ID</th>
      <td><?php echo htmlspecialchars($product['fld_product_num']); ?></td>
    </tr>
    <tr>
      <th>Perfume Name</th>
      <td><?php echo htmlspecialchars($product['fld_product_name']); ?></td>
    </tr>
    <tr>
      <th>Brand</th>
      <td><?php echo htmlspecialchars($product['fld_product_brand']); ?></td>
    </tr>
    <tr>
      <th>Fragrance Type</th>
      <td><?php echo htmlspecialchars($product['fld_product_condition']); ?></td>
    </tr>
    <tr>
      <th>Volume</th>
      <td><?php echo htmlspecialchars($product['fld_product_volume']); ?> ml</td>
    </tr>
    <tr>
      <th>Price</th>
      <td>RM <?php echo number_format($product['fld_product_price'], 2); ?></td>
    </tr>
    <tr>
      <th>Quantity</th>
      <td><?php echo htmlspecialchars($product['fld_product_quantity']); ?></td>
    </tr>
  </table>

</div>
