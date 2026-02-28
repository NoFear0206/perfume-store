<?php
session_start();
require_once 'database.php';

if (!isset($_POST['login'])) {
    header("Location: login.php");
    exit();
}

$staff_num = $_POST['staff_num'];
$password  = md5($_POST['password']);

$stmt = $conn->prepare(
    "SELECT fld_staff_num, fld_staff_fname, fld_staff_lname, fld_staff_user_level
     FROM tbl_staffs_a199441_pt2
     WHERE fld_staff_num = :staff_num
     AND fld_staff_password = :password"
);

$stmt->bindParam(':staff_num', $staff_num);
$stmt->bindParam(':password', $password);
$stmt->execute();

$staff = $stmt->fetch(PDO::FETCH_ASSOC);

if ($staff) {

    $_SESSION['staff_num']  = $staff['fld_staff_num'];
    $_SESSION['staff_name'] = $staff['fld_staff_fname'].' '.$staff['fld_staff_lname'];
    $_SESSION['user_level'] = $staff['fld_staff_user_level'];

    header("Location: index.php");
    exit();

} else {
    header("Location: login.php?error=Invalid Staff ID or Password");
    exit();
}
