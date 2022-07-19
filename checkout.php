<!DOCTYPE html>
<html>
<head>
<title>checkout</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>

<nav class="header">
    
        
    <h1>Welcome to Checkout</h1>
      <ul class="main-nav">
          <li><a href="cart.php">Cart</a></li>
      </ul>
      
	
</nav>
</body>

<?php

try{
    
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$dsn = "mysql:host=courses;dbname=z1892226";
$pdo = new PDO($dsn, 'z1892226', '2002Feb20');

$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0.00;
$shippingtotal = 10.00;

$array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
$stmt = $pdo->prepare('SELECT * FROM INVENTORY WHERE PRODUCTID IN (' . $array_to_question_marks . ')');
$stmt->execute(array_keys($products_in_cart));
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {

if (isset($_POST['full_name'], $_POST['address'], $_POST['phone'], $_POST['card'])) {
    $stmt=$pdo->prepare('INSERT INTO ORDERS
    (PHONE,FULLNAME,CREDITCARD,ADDRESS,STATUS) VALUES (?,?,?,?,"Processing")');
    $stmt->execute(array($_POST['phone'],$_POST['full_name'], $_POST['card'],$_POST['address'] ));
    foreach ($products_in_cart as $id => $quantity) {
        // Update product quantity in the products table
        $stmt = $pdo->prepare('UPDATE INVENTORY SET QUANTITY = QUANTITY - ? WHERE QUANTITY > 0 AND PRODUCTID = ?');
        $stmt->execute(array($quantity, $id));
    }
}
else
{
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) 
    {
        echo "There was an error placing your order, please try again.";
    }
}

}
catch (Exception $e)
{
    echo $e->getMessage();
}
if(isset($_POST["checkout"]))
{
    unset($_SESSION['cart']);
}
foreach ($products as $product) {
    $subtotal += (float)$product['PRICE'] * (int)$products_in_cart[$product['PRODUCTID']];
}
$tax=($subtotal+$shippingtotal)*0.0825;

}
catch(PDOexception $e)
{
    echo "Connection to Database failed: " . $e->getMessage();
}

?>

<?php if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) { ?>
    <div class="checkout content-wrapper">
    <form action="" method="post">
        <h2>Shipping Details</h2>
        <div class="row1">
                    Full Name: <input type="text" name="full_name" placeholder="John Adams" required><br><br>
                    Address: <input type="text" name="address" placeholder="907 Lincoln Higway, Dekalb, IL" required><br><br>
                    Phone: <input type="text" name="phone" placeholder="1234567899" required><br><br>
                    Payment Card: <input type="text" name="card" placeholder="123123123" required><br><br>
        </div>  
        <table>
        
        </table>
        <span><strong>Subtotal: &dollar; </span><span><?=number_format($subtotal,2)?></span><br>
        <span><strong>Shipping Cost: &dollar; </span><span><?=number_format($shippingtotal,2)?></span><br>
        <span><strong>Tax: &dollar; </span><span><?=number_format($tax,2)?></span><br>



        <div class="total">
                    <span><strong>Total: &dollar; </span><span><?=number_format($subtotal+$shippingtotal+$tax,2)?></span>
                </div>

                <div class="buttons">
                    <input type="submit" name="checkout" value="Place Order">
                </div>

        
    </form>
</div>
</html>
<?php } else {?>
<?php
header("Location: placeorder.php")
?>
</div>

<?php } ?>
