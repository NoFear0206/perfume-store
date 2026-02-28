<?php 
include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create: Add product to an order
if (isset($_POST['addproduct'])) {
    if ($_SESSION['user_level'] != 'Admin' && $_SESSION['user_level'] != 'Non-admin') {
        exit("You do not have permission to add products to order.");
    }

    try {
        $stmt = $conn->prepare("SELECT fld_order_detail_num FROM tbl_orders_details_a199441 
                                ORDER BY fld_order_detail_num DESC LIMIT 1");
        $stmt->execute();
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        $num = $last ? intval(substr($last['fld_order_detail_num'], 2)) + 1 : 1;
        $did = 'OD' . str_pad($num, 3, '0', STR_PAD_LEFT); 

        $oid = $_POST['oid'];      
        $pid = $_POST['pid'];
        $quantity = $_POST['quantity'];

        $stmt2 = $conn->prepare("SELECT fld_product_name, fld_product_price, fld_product_brand
                                 FROM tbl_products_a199441_pt2 
                                 WHERE fld_product_num = :pid");
        $stmt2->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt2->execute();
        $product = $stmt2->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("INSERT INTO tbl_orders_details_a199441(
            fld_order_detail_num,
            fld_order_num, 
            fld_product_num, 
            fld_order_detail_quantity,
            fld_product_name_snapshot,
            fld_product_price_snapshot,
            fld_product_brand_snapshot
        ) VALUES(:did, :oid, :pid, :quantity, :name, :price, :brand)");

        $stmt->bindParam(':did', $did);
        $stmt->bindParam(':oid', $oid);
        $stmt->bindParam(':pid', $pid);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':name', $product['fld_product_name']);
        $stmt->bindParam(':price', $product['fld_product_price']);
        $stmt->bindParam(':brand', $product['fld_product_brand']);

        $stmt->execute();

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $_GET['oid'] = $oid;
}

// Delete: Remove product from an order
if (isset($_GET['delete'])) {

    try {
        $stmt = $conn->prepare("DELETE FROM tbl_orders_details_a199441 WHERE fld_order_detail_num = :did");
        $stmt->bindParam(':did', $did, PDO::PARAM_STR);
        $did = $_GET['delete'];
        $stmt->execute();

        header("Location: orders_details.php?oid=" . $_GET['oid']);
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>
