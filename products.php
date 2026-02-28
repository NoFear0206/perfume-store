<!--
Matric Number: A199441
Name: JIN YANRAN
-->
<?php
session_start();
if (!isset($_SESSION['staff_num'])) {
    header("Location: login.php");
    exit();
}
include_once 'products_crud.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perfume Store : Products</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap.min.css">

  <style>
    body { font-family: Arial; }
    .form-container { width:450px; margin:auto; }
    table.form-table td { padding:8px; }
    h2 { text-align:center; margin-top:20px; }
    table { width:90%; margin:auto; margin-top:25px; }
    .fragrance-column { display:flex; flex-direction:column; gap:4px; line-height:18px; }

    .modal-auto {
      width: auto;
      max-width: 520px;
      margin: 30px auto;
    }
    .modal-body {
      max-height: 70vh;
      overflow-y: auto;
    }
  </style>
</head>
<body>

<?php include_once 'nav_bar.php'; ?>

<div class="container">

<?php if ($_SESSION['user_level'] == 'Admin') { ?>
<h2>Create New Perfume</h2>

<div class="form-container">
<form action="products.php" method="post">
<table class="form-table">
<tr>
  <td>Product ID</td>
  <td><input name="pid" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_num']; ?>" required></td>
</tr>
<tr>
  <td>Perfume Name</td>
  <td><input name="name" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_name']; ?>" required></td>
</tr>
<tr>
  <td>Price (RM)</td>
  <td><input name="price" type="number" step="0.01" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_price']; ?>" required></td>
</tr>
<tr>
  <td>Brand</td>
  <td>
    <select name="brand" required>
      <option value="">Select Brand</option>
      <?php
      $brands = ["Dior","Chanel","Gucci","YSL","Jo Malone","Burberry","Versace","Tom Ford","Marc Jacobs","Givenchy"];
      foreach($brands as $b){
        $selected = (isset($_GET['edit']) && $editrow['fld_product_brand']==$b) ? "selected" : "";
        echo "<option value='$b' $selected>$b</option>";
      }
      ?>
    </select>
  </td>
</tr>
<tr>
  <td>Fragrance Type</td>
  <td class="fragrance-column">
    <?php
    $types = ["Floral","Woody","Fresh","Oriental"];
    foreach($types as $t){
      $checked = (isset($_GET['edit']) && $editrow['fld_product_condition']==$t) ? "checked" : "";
      echo "<label><input type='radio' name='cond' value='$t' $checked required> $t</label>";
    }
    ?>
  </td>
</tr>
<tr>
  <td>Volume (ml)</td>
  <td>
    <select name="volume" required>
      <option value="">Select Volume</option>
      <option value="30" <?php if(isset($_GET['edit']) && $editrow['fld_product_volume']=="30") echo "selected"; ?>>30</option>
      <option value="50" <?php if(isset($_GET['edit']) && $editrow['fld_product_volume']=="50") echo "selected"; ?>>50</option>
      <option value="100" <?php if(isset($_GET['edit']) && $editrow['fld_product_volume']=="100") echo "selected"; ?>>100</option>
    </select>
  </td>
</tr>
<tr>
  <td>Quantity</td>
  <td><input name="quantity" type="number" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_quantity']; ?>" required></td>
</tr>
<tr>
  <td></td>
  <td>
    <?php if(isset($_GET['edit'])){ ?>
      <input type="hidden" name="oldpid" value="<?php echo $editrow['fld_product_num']; ?>">
      <button type="submit" name="update" class="btn btn-warning">Update</button>
    <?php } else { ?>
      <button type="submit" name="create" class="btn btn-success">Create</button>
    <?php } ?>
    <button type="reset" class="btn btn-default">Clear</button>
  </td>
</tr>
</table>
</form>
</div>
<?php } ?>

<hr>

<h3 style="margin-left:5%">Products List</h3>

<table id="productsTable" class="table table-striped table-bordered">
<thead>
<tr>
  <th>Product ID</th>
  <th>Name</th>
  <th>Price (RM)</th>
  <th>Brand</th>
  <th>Action</th>
</tr>
</thead>
<tbody>
<?php
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare("SELECT * FROM tbl_products_a199441_pt2");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach($rows as $r){
?>
<tr>
<td><?php echo $r['fld_product_num']; ?></td>
<td><?php echo $r['fld_product_name']; ?></td>
<td><?php echo $r['fld_product_price']; ?></td>
<td><?php echo $r['fld_product_brand']; ?></td>
<td>
<button class="btn btn-info btn-xs view-details" data-id="<?php echo $r['fld_product_num']; ?>">Details</button>
<?php if($_SESSION['user_level']=='Admin'){ ?>
<a href="products.php?edit=<?php echo $r['fld_product_num']; ?>" class="btn btn-warning btn-xs">Edit</a>
<a href="products.php?delete=<?php echo $r['fld_product_num']; ?>" class="btn btn-danger btn-xs"
onclick="return confirm('Delete this product?');">Delete</a>
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>

<div class="modal fade" id="productModal">
  <div class="modal-dialog modal-auto">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Product Details</h4>
      </div>
      <div class="modal-body" id="modal-content">
        <div class="text-center">Loading...</div>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function(){

  var table = $('#productsTable').DataTable({
    order: [[1,'asc']],
    lengthMenu: [[5,10,20,30,-1],[5,10,20,30,"All"]],
    dom: 'Blfrtip',
    buttons: [
      {
        extend: 'excelHtml5',
        text: 'Export to Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });

  $(document).on('click', '.view-details', function () {
    var pid = $(this).data('id');
    $('#modal-content').html('<div class="text-center">Loading...</div>');
    $('#productModal').modal('show');
    $('#modal-content').load('product_modal.php?pid=' + pid);
  });

});
</script>


</body>
</html>
