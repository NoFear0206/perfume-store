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
if ($_SESSION['user_level'] !== 'Admin') {
    echo "<script>alert('You do not have the right to access this page.');</script>";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

include_once 'staffs_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Perfume Store : Staffs</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Arial; }
h2 { text-align:center; margin-top:20px; }
.form-container { width:450px; margin:auto; }
.table-container { width:95%; margin:auto; margin-top:25px; }
.btn-group { display:flex; gap:5px; }
</style>
</head>
<body>

<?php include_once 'nav_bar.php'; ?>

<div class="container">

<h2><?php echo isset($editrow) ? 'Edit Staff' : 'Create New Staff'; ?></h2>

<div class="form-container">
<form action="staffs.php" method="post" class="form-horizontal">

<div class="form-group">
<label class="col-sm-3 control-label">Staff ID</label>
<div class="col-sm-9">
<?php if(isset($editrow)) { ?>
    <input type="text" name="sid" class="form-control" value="<?php echo $editrow['fld_staff_num']; ?>" required>
    <input type="hidden" name="old_sid" value="<?php echo $editrow['fld_staff_num']; ?>">
<?php } else { ?>
    <input type="text" name="sid" class="form-control" placeholder="Staff ID" required>
<?php } ?>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">First Name</label>
<div class="col-sm-9">
<input name="fname" type="text" class="form-control" placeholder="First Name" value="<?php if(isset($editrow)) echo $editrow['fld_staff_fname']; ?>" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">Last Name</label>
<div class="col-sm-9">
<input name="lname" type="text" class="form-control" placeholder="Last Name" value="<?php if(isset($editrow)) echo $editrow['fld_staff_lname']; ?>" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">Gender</label>
<div class="col-sm-9">
<label class="radio-inline">
<input type="radio" name="gender" value="Male" <?php if(isset($editrow) && $editrow['fld_staff_gender']=="Male") echo "checked"; ?> required> Male
</label>
<label class="radio-inline">
<input type="radio" name="gender" value="Female" <?php if(isset($editrow) && $editrow['fld_staff_gender']=="Female") echo "checked"; ?>> Female
</label>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">Phone Number</label>
<div class="col-sm-9">
<input name="phone" type="text" class="form-control" placeholder="Phone Number" value="<?php if(isset($editrow)) echo $editrow['fld_staff_phone']; ?>" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">Email</label>
<div class="col-sm-9">
<input name="email" type="email" class="form-control" placeholder="Email Address" value="<?php if(isset($editrow)) echo $editrow['fld_staff_email']; ?>" required>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">Password</label>
<div class="col-sm-9">
<input type="password" name="password" class="form-control" placeholder="<?php echo isset($editrow) ? 'Leave blank to keep current password' : 'Password'; ?>" <?php echo isset($editrow) ? '' : 'required'; ?>>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label">User Level</label>
<div class="col-sm-9">
<select name="user_level" class="form-control" required>
<option value="">--Select--</option>
<option value="Admin" <?php if(isset($editrow) && $editrow['fld_staff_user_level']=="Admin") echo "selected"; ?>>Admin</option>
<option value="Non-admin" <?php if(isset($editrow) && $editrow['fld_staff_user_level']=="Non-admin") echo "selected"; ?>>Non-admin</option>
</select>
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-3 col-sm-9">
<?php if(isset($editrow)) { ?>
    <button type="submit" name="update" class="btn btn-warning">✎ Update</button>
<?php } else { ?>
    <button type="submit" name="create" class="btn btn-success">➕ Create</button>
<?php } ?>
<button type="reset" class="btn btn-default">🩹 Clear</button>
</div>
</div>

</form>
</div>

<hr>

<h3 style="text-align:left;">Staff List</h3>

<div class="table-container">
<table class="table table-bordered table-striped">
<thead>
<tr>
<th>Staff ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Gender</th>
<th>Phone Number</th>
<th>Email</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
$per_page = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $per_page;

try {
    $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a199441_pt2 LIMIT $start_from, $per_page");
    $stmt->execute();
    $result = $stmt->fetchAll();
} catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}

foreach($result as $readrow) {
?>
<tr>
<td><?php echo $readrow['fld_staff_num']; ?></td>
<td><?php echo $readrow['fld_staff_fname']; ?></td>
<td><?php echo $readrow['fld_staff_lname']; ?></td>
<td><?php echo $readrow['fld_staff_gender']; ?></td>
<td><?php echo $readrow['fld_staff_phone']; ?></td>
<td><?php echo $readrow['fld_staff_email']; ?></td>
<td>
<?php if ($_SESSION['user_level'] === 'Admin') { ?>
<a href="staffs.php?edit=<?php echo $readrow['fld_staff_num']; ?>" class="btn btn-warning btn-xs">Edit</a>
<a href="staffs.php?delete=<?php echo $readrow['fld_staff_num']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to delete this staff?');">Delete</a>
<?php } else { ?>
<span class="text-muted">No Permission</span>
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>

<nav aria-label="Page navigation" class="text-center">
<ul class="pagination">
<?php
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_staffs_a199441_pt2");
    $stmt->execute();
    $total_records = $stmt->fetchColumn();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$total_pages = ceil($total_records / $per_page);
for ($i=1; $i <= $total_pages; $i++) {
    echo '<li';
    if($i==$page) echo ' class="active"';
    echo '><a href="staffs.php?page='.$i.'">'.$i.'</a></li>';
}
?>
</ul>
</nav>

</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
