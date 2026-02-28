<?php
if (session_status() == PHP_SESSION_NONE) session_start();

include_once 'database.php';

if (!isset($_SESSION['staff_num']) || $_SESSION['user_level'] !== 'Admin') {
    echo "<script>alert('You do not have permission to perform this action.');</script>";
    echo "<script>window.location.href='customers.php';</script>";
    exit();
}

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create new customer
if (isset($_POST['create'])) {
    $cid    = $_POST['cid'];
    $fname  = $_POST['fname'];
    $lname  = $_POST['lname'];
    $gender = $_POST['gender'];
    $phone  = $_POST['phone'];

    try {
        $stmt = $conn->prepare("INSERT INTO tbl_customers_a199441_pt2
            (fld_customer_num, fld_customer_fname, fld_customer_lname, fld_customer_gender, fld_customer_phone)
            VALUES (:cid,:fname,:lname,:gender,:phone)");
        $stmt->bindParam(':cid', $cid);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        header("Location: customers.php?created=" . time());
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Update customer, including changing ID
if (isset($_POST['update'])) {
    $old_cid = $_POST['old_cid']; // original ID
    $cid     = $_POST['cid'];     // new ID
    $fname   = $_POST['fname'];
    $lname   = $_POST['lname'];
    $gender  = $_POST['gender'];
    $phone   = $_POST['phone'];

    try {
        $stmt = $conn->prepare("UPDATE tbl_customers_a199441_pt2
            SET fld_customer_num = :cid,
                fld_customer_fname = :fname,
                fld_customer_lname = :lname,
                fld_customer_gender = :gender,
                fld_customer_phone = :phone
            WHERE fld_customer_num = :old_cid");
        $stmt->bindParam(':cid', $cid);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':old_cid', $old_cid);
        $stmt->execute();
        header("Location: customers.php?updated=" . time());
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete customer
if (isset($_GET['delete'])) {
    $cid = $_GET['delete'];
    try {
        $stmt = $conn->prepare("DELETE FROM tbl_customers_a199441_pt2 WHERE fld_customer_num = :cid");
        $stmt->bindParam(':cid', $cid);
        $stmt->execute();
        header("Location: customers.php?deleted=" . time());
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch customer for edit
if (isset($_GET['edit'])) {
    $cid = $_GET['edit'];
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_customers_a199441_pt2 WHERE fld_customer_num = :cid");
        $stmt->bindParam(':cid', $cid);
        $stmt->execute();
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// DO NOT close $conn here
?>
