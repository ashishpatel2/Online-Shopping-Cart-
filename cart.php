<!DOCTYPE html>
<html>
<head>
<title>cart</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>

<nav class="header">
    
        

      <ul class="main-nav">
          <li><a href="products.php">Products</a></li>
          <li><a href="cart.php">Cart</a></li>
      </ul>
      
	
</nav>

</body>

<?php

$dsn = "mysql:host=courses;dbname=z1892226";
$pdo = new PDO($dsn, 'z1892226', '2002Feb20');

$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

try{
    
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    
// If the user clicked the add to cart button on the product page we can check for the form data
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    // Prepare the SQL statement, we basically are checking if the product exists in our databaser
    $stmt = $pdo->prepare('SELECT * FROM INVENTORY WHERE PRODUCTID = ?');
    $stmt->execute([$_POST['product_id']]);
    // Fetch the product from the database and return the result as an Array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    if ($product && $quantity > 0) {
        // Product exists in database, now we can create/update the session variable for the cart
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                // Product exists in cart so just update the quanity
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // Product is not in cart so add it
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // There are no products in cart, this will add the first product to cart
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
    header('location: index.php?page=cart');
    exit;
   
}

// Empty the cart
if (isset($_POST['emptycart']) && isset($_SESSION['cart'])) {
    // Remove all products from the shopping cart
    unset($_SESSION['cart']);
    header('location: index.php?page=cart');
    exit;
   
}
// Remove product from cart
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    // Remove the product from the shopping cart
    unset($_SESSION['cart'][$_GET['remove']]);
}
// Update product quantities in cart if the user clicks the "Update" button 
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
    // Loop through the post data so we can update the quantities for every product in cart
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $pid = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            // Always do checks and validation
            if (is_numeric($pid) && isset($_SESSION['cart'][$pid]) && $quantity > 0) {
                // Update new quantity
                $_SESSION['cart'][$pid] = $quantity;
            }
        }
    }
  
    
    header('location: index.php?page=cart');
    exit;
 
}
  if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
        header('location: index.php?page=checkout');
        exit;
    }


$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;
// If there are products in cart
if ($products_in_cart) {
    // There are products in the cart so we need to select those products from the database
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $pdo->prepare('SELECT * FROM INVENTORY WHERE PRODUCTID IN (' . $array_to_question_marks . ')');
    $stmt->execute(array_keys($products_in_cart));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Calculate the subtotal
    foreach ($products as $product) {
        $subtotal += (float)$product['PRICE'] * (int)$products_in_cart[$product['PRODUCTID']];
    }
}
}

catch(PDOexception $e)
{
    echo "Connection to Database failed: " . $e->getMessage();
}//End Catch
?>



<div class="cart content-wrapper">
    <h1>Shopping Cart</h1>
    <form action="cart.php" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">You have no products added in your Shopping Cart</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="img">
                        <a href="product.php?page=product&PRODUCTID<?=$product['PRODUCTID']?>">
                            <img src="Photos/<?=ucwords(strtolower($product['PRODUCTNAME']))?>.jpeg" width="50" height="50" alt="<?=$product['PRODUCTNAME']?>">
                        </a>
                    </td>
                    <td>
                        <a href="product.php?page=product&PRODUCTID=<?=$product['PRODUCTID']?>"><?=$product['PRODUCTNAME']?></a>
                        <br>
                        <a href="index.php?page=cart&remove=<?=$product['PRODUCTID']?>" class="remove">Remove</a>
                    </td>
                    <td class="price">&dollar;<?=$product['PRICE']?></td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$product['PRODUCTID']?>" value="<?=$products_in_cart[$product['PRODUCTID']]?>" min="1" max="<?=$product['QUANTITY']?>" placeholder="Quantity" required>
                    </td>
                    <td class="price">&dollar;<?=$product['PRICE'] * $products_in_cart[$product['PRODUCTID']]?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal</span>
            <span class="price">&dollar;<?=$subtotal?></span>
        </div>
        <div class="buttons">
        <input type="submit" value="Update" name="update">
        <input type="submit" value="Empty Cart" name="emptycart" >
        <input type="submit" value="Checkout" name="checkout" >
        </div>
    </form>
    <div class="buttons">
    <form action="products.php" method="post">
    <input type="submit" value="Continue Shopping" name="cont">
                </form>
     </div>
     
</div>
</html>
