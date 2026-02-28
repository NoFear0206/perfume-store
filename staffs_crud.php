<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['staff_num']) || $_SESSION['user_level'] !== 'Admin') {
    echo "<script>alert('You do not have permission to perform this action.');</script>";
    echo "<script>window.location.href='staffs.php';</script>";
    exit();
}

include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create new staff
if (isset($_POST['create'])) {
    $sid        = $_POST['sid'];
    $fname      = $_POST['fname'];
    $lname      = $_POST['lname'];
    $gender     = $_POST['gender'];
    $phone      = $_POST['phone'];
    $email      = $_POST['email'];
    $password   = md5($_POST['password']);
    $user_level = $_POST['user_level'];

    try {
        $stmt = $conn->prepare("INSERT INTO tbl_staffs_a199441_pt2
            (fld_staff_num,fld_staff_fname,fld_staff_lname,fld_staff_gender,fld_staff_phone,fld_staff_email,fld_staff_password,fld_staff_user_level)
            VALUES (:sid,:fname,:lname,:gender,:phone,:email,:password,:user_level)");

        $stmt->bindParam(':sid', $sid);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':user_level', $user_level);

        $stmt->execute();
        header("Location: staffs.php?created=" . time());
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Update staff
if (isset($_POST['update'])) {
    $old_sid    = $_POST['old_sid'];
    $sid        = $_POST['sid'];
    $fname      = $_POST['fname'];
    $lname      = $_POST['lname'];
    $gender     = $_POST['gender'];
    $phone      = $_POST['phone'];
    $email      = $_POST['email'];
    $password   = !empty($_POST['password']) ? md5($_POST['password']) : null;
    $user_level = $_POST['user_level'];

    try {
        if ($password) {
            $stmt = $conn->prepare("UPDATE tbl_staffs_a199441_pt2
                SET fld_staff_num=:sid,
                    fld_staff_fname=:fname,
                    fld_staff_lname=:lname,
                    fld_staff_gender=:gender,
                    fld_staff_phone=:phone,
                    fld_staff_email=:email,
                    fld_staff_password=:password,
                    fld_staff_user_level=:user_level
                WHERE fld_staff_num=:old_sid");
            $stmt->bindParam(':password', $password);
        } else {
            $stmt = $conn->prepare("UPDATE tbl_staffs_a199441_pt2
                SET fld_staff_num=:sid,
                    fld_staff_fname=:fname,
                    fld_staff_lname=:lname,
                    fld_staff_gender=:gender,
                    fld_staff_phone=:phone,
                    fld_staff_email=:email,
                    fld_staff_user_level=:user_level
                WHERE fld_staff_num=:old_sid");
        }

        $stmt->bindParam(':sid', $sid);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_level', $user_level);
        $stmt->bindParam(':old_sid', $old_sid);

        $stmt->execute();
        header("Location: staffs.php?updated=" . time());
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete staff
if (isset($_GET['delete'])) {
    $sid = $_GET['delete'];
    try {
        $stmt = $conn->prepare("DELETE FROM tbl_staffs_a199441_pt2 WHERE fld_staff_num=:sid");
        $stmt->bindParam(':sid', $sid);
        $stmt->execute();
        header("Location: staffs.php?deleted=" . time());
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch staff for edit
if (isset($_GET['edit'])) {
    $sid = $_GET['edit'];
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a199441_pt2 WHERE fld_staff_num=:sid");
        $stmt->bindParam(':sid', $sid);
        $stmt->execute();
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// DO NOT CLOSE $conn
?>
