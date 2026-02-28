<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ----------------- EDIT: Fetch order data -----------------
if (isset($_GET['edit'])) {
    $oid = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM tbl_orders_a199441 WHERE fld_order_num = :oid");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $stmt->execute();
    $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ----------------- CREATE: Add new order -----------------
if (isset($_POST['create'])) {
    if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'Admin') {
        exit("You do not have permission to create orders.");
    }

    if (isset($_POST['sid'], $_POST['cid'])) {

        $date = date('Ymd');

        $stmt = $conn->prepare("
            SELECT fld_order_num 
            FROM tbl_orders_a199441
            WHERE fld_order_num LIKE :today
            ORDER BY fld_order_num DESC
            LIMIT 1
        ");
        $today = "O".$date."%";
        $stmt->bindParam(':today', $today, PDO::PARAM_STR);
        $stmt->execute();
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($last) {
            $lastNum = intval(substr($last['fld_order_num'], -3)) + 1;
        } else {
            $lastNum = 1;
        }

        $oid = "O".$date.str_pad($lastNum, 3, '0', STR_PAD_LEFT);

        $sid = $_POST['sid'];
        $cid = $_POST['cid'];

        $stmt = $conn->prepare("
            INSERT INTO tbl_orders_a199441
            (fld_order_num, fld_staff_num, fld_customer_num)
            VALUES(:oid, :sid, :cid)
        ");
        $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
        $stmt->execute();
    }
}


// ----------------- UPDATE: Edit existing order -----------------
if (isset($_POST['update'])) {
    if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'Admin') {
        exit("You do not have permission to update orders.");
    }

    if (isset($_POST['oid'], $_POST['sid'], $_POST['cid'])) {
        $oid = $_POST['oid'];
        $sid = $_POST['sid'];
        $cid = $_POST['cid'];

        $stmt = $conn->prepare("UPDATE tbl_orders_a199441 
                                SET fld_staff_num = :sid, fld_customer_num = :cid 
                                WHERE fld_order_num = :oid");
        $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
        $stmt->execute();
    }
}

// ----------------- DELETE: Remove order -----------------
if (isset($_GET['delete'])) {
    if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'Admin') {
        echo "<script>alert('You do not have the right to delete orders.');</script>";
        echo "<script>window.location.href='orders.php';</script>";
        exit();
    }

    $oid = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tbl_orders_a199441 WHERE fld_order_num = :oid");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $stmt->execute();

    header("Location: orders.php");
    exit();
}

// ----------------- ADD PRODUCT to order -----------------
if (isset($_POST['addproduct'])) {
    if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'Admin') {
        exit("You do not have permission to add product.");
    }

    if (isset($_POST['oid'], $_POST['pid'], $_POST['quantity'])) {
        $did = uniqid('D', true);
        $oid = $_POST['oid'];
        $pid = $_POST['pid'];
        $quantity = $_POST['quantity'];

        $stmt = $conn->prepare("INSERT INTO tbl_orders_details_a199441(
            fld_order_detail_num, fld_order_num, fld_product_num, fld_order_detail_quantity
        ) VALUES(:did, :oid, :pid, :quantity)");

        $stmt->bindParam(':did', $did, PDO::PARAM_STR);
        $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
    }
}

// ----------------- DELETE PRODUCT from order -----------------
if (isset($_GET['deleteproduct'])) {
    if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'Admin') {
        $oid = isset($_GET['oid']) ? $_GET['oid'] : '';
        echo "<script>alert('You do not have the right to delete order details.');</script>";
        echo "<script>window.location.href='orders_details.php?oid=".$oid."';</script>";
        exit();
    }

    if (isset($_GET['delete'], $_GET['oid'])) {
        $did = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM tbl_orders_details_a199441 WHERE fld_order_detail_num = :did");
        $stmt->bindParam(':did', $did, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: orders_details.php?oid=" . $_GET['oid']);
        exit();
    }
}


$conn = null;
?>
