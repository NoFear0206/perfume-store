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

include_once 'orders_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perfume Store : Orders</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial; }
    h2 { text-align:center; margin-top:20px; }
    .form-container { width:500px; margin:auto; }
    .table-container { width:95%; margin:auto; margin-top:25px; }
  </style>
</head>
<body>

<?php include_once 'nav_bar.php'; ?>

<div class="container">

  <h2>Create New Order</h2>

  <div class="form-container">
    <form action="orders.php" method="post" class="form-horizontal">

      <div class="form-group">
        <label class="col-sm-3 control-label">Order ID</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" value="Auto Generated" readonly>
        </div>
      </div>


      <div class="form-group">
        <label class="col-sm-3 control-label">Staff</label>
        <div class="col-sm-9">
          <select name="sid" class="form-control" required>
            <option value="">-- Please Select Staff --</option>
            <?php
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a199441_pt2");
              $stmt->execute();
              $staffs = $stmt->fetchAll();
            } catch(PDOException $e){
              echo "Error: " . $e->getMessage();
            }

            foreach ($staffs as $staff) {
              $selected = (isset($_GET['edit']) && $editrow['fld_staff_num'] == $staff['fld_staff_num']) ? "selected" : "";
              echo "<option value='{$staff['fld_staff_num']}' $selected>
                      {$staff['fld_staff_fname']} {$staff['fld_staff_lname']}
                    </option>";
            }
            ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">Customer</label>
        <div class="col-sm-9">
          <select name="cid" class="form-control" required>
            <option value="">-- Please Select Customer --</option>
            <?php
            try {
              $stmt = $conn->prepare("SELECT * FROM tbl_customers_a199441_pt2");
              $stmt->execute();
              $customers = $stmt->fetchAll();
            } catch(PDOException $e){
              echo "Error: " . $e->getMessage();
            }

            foreach ($customers as $cust) {
              $selected = (isset($_GET['edit']) && $editrow['fld_customer_num'] == $cust['fld_customer_num']) ? "selected" : "";
              echo "<option value='{$cust['fld_customer_num']}' $selected>
                      {$cust['fld_customer_fname']} {$cust['fld_customer_lname']}
                    </option>";
            }

            $conn = null;
            ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <?php if (isset($_GET['edit'])) { ?>
            <?php if ($_SESSION['user_level'] == 'Admin') { ?>
              <button type="submit" name="update" class="btn btn-warning">Update</button>
            <?php } ?>
          <?php } else { ?>
            <?php if ($_SESSION['user_level'] == 'Admin') { ?>
              <button type="submit" name="create" class="btn btn-success">Create</button>
            <?php } ?>
          <?php } ?>
          <button type="reset" class="btn btn-default">Clear</button>
        </div>
      </div>

    </form>
  </div>

  <hr>

  <h3>Order List</h3>

  <div class="table-container">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Staff</th>
          <th>Customer</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php
        $per_page = 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start_from = ($page - 1) * $per_page;

        try {
          $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $sql = "
            SELECT o.fld_order_num,
                   s.fld_staff_fname, s.fld_staff_lname,
                   c.fld_customer_fname, c.fld_customer_lname
            FROM tbl_orders_a199441 o
            JOIN tbl_staffs_a199441_pt2 s
              ON o.fld_staff_num = s.fld_staff_num
            JOIN tbl_customers_a199441_pt2 c
              ON o.fld_customer_num = c.fld_customer_num
            LIMIT $start_from, $per_page
          ";

          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $orders = $stmt->fetchAll();
        } catch(PDOException $e){
          echo "Error: " . $e->getMessage();
        }

        foreach ($orders as $order) {
          echo "<tr>
                  <td>{$order['fld_order_num']}</td>
                  <td>{$order['fld_staff_fname']} {$order['fld_staff_lname']}</td>
                  <td>{$order['fld_customer_fname']} {$order['fld_customer_lname']}</td>
                  <td>
                    <a href='orders_details.php?oid={$order['fld_order_num']}' class='btn btn-info btn-xs'>Details</a>";
          if ($_SESSION['user_level'] == 'Admin') {
            echo "<a href='orders.php?edit={$order['fld_order_num']}' class='btn btn-warning btn-xs'>Edit</a>
                  <a href='orders.php?delete={$order['fld_order_num']}' class='btn btn-danger btn-xs'
                     onclick=\"return confirm('Are you sure to delete this order?');\">Delete</a>";
          }
          echo "</td></tr>";
        }
        try {
          $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_orders_a199441");
          $stmt->execute();
          $total_records = $stmt->fetchColumn();
        } catch(PDOException $e){
          echo "Error: " . $e->getMessage();
        }

        $total_pages = ceil($total_records / $per_page);

        echo '<tr><td colspan="4" class="text-center">';
        echo '<nav aria-label="Page navigation">';
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $total_pages; $i++) {
          $active = ($page == $i) ? 'class="active"' : '';
          echo "<li $active><a href='orders.php?page=$i'>$i</a></li>";
        }
        echo '</ul>';
        echo '</nav>';
        echo '</td></tr>';
        $conn = null;
        ?>
      </tbody>
    </table>
  </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>
</html>
