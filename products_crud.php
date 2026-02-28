<?php

if (!isset($_SESSION['staff_num'])) {
  header("Location: login.php");
  exit();
}

include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// CREATE
if (isset($_POST['create'])) {
  if ($_SESSION['user_level'] != 'Admin') {
    echo "<script>alert('You do not have the right to create product');</script>";
    echo "<script>window.location.href='products.php';</script>";
    exit();
  }

  try {
    $stmt = $conn->prepare("INSERT INTO tbl_products_a199441_pt2(
      fld_product_num, fld_product_name, fld_product_price,
      fld_product_brand, fld_product_condition, fld_product_volume, fld_product_quantity
    ) VALUES (:pid, :name, :price, :brand, :cond, :volume, :quantity)");

    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
    $stmt->bindParam(':cond', $cond, PDO::PARAM_STR);
    $stmt->bindParam(':volume', $volume, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);

    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $brand = $_POST['brand'];
    $cond = $_POST['cond'];
    $volume = $_POST['volume'];
    $quantity = $_POST['quantity'];

    $stmt->execute();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

// UPDATE
if (isset($_POST['update'])) {
  if ($_SESSION['user_level'] != 'Admin') {
    echo "<script>alert('You do not have the right to update product');</script>";
    echo "<script>window.location.href='products.php';</script>";
    exit();
  }

  try {
    $stmt = $conn->prepare("UPDATE tbl_products_a199441_pt2 SET
      fld_product_num = :pid,
      fld_product_name = :name,
      fld_product_price = :price,
      fld_product_brand = :brand,
      fld_product_condition = :cond,
      fld_product_volume = :volume,
      fld_product_quantity = :quantity
      WHERE fld_product_num = :oldpid");

    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
    $stmt->bindParam(':cond', $cond, PDO::PARAM_STR);
    $stmt->bindParam(':volume', $volume, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':oldpid', $oldpid, PDO::PARAM_STR);

    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $brand = $_POST['brand'];
    $cond = $_POST['cond'];
    $volume = $_POST['volume'];
    $quantity = $_POST['quantity'];
    $oldpid = $_POST['oldpid'];

    $stmt->execute();

    header("Location: products.php");
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

// DELETE
if (isset($_GET['delete'])) {
  if ($_SESSION['user_level'] != 'Admin') {
    echo "<script>alert('You do not have the right to delete product');</script>";
    echo "<script>window.location.href='products.php';</script>";
    exit();
  }

  try {
    $stmt = $conn->prepare("DELETE FROM tbl_products_a199441_pt2 WHERE fld_product_num = :pid");

    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $pid = $_GET['delete'];

    $stmt->execute();

    header("Location: products.php");
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

// EDIT
if (isset($_GET['edit'])) {

  try {
    $stmt = $conn->prepare("SELECT * FROM tbl_products_a199441_pt2 WHERE fld_product_num = :pid");

    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $pid = $_GET['edit'];

    $stmt->execute();

    $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

$conn = null;
?>
