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
session_start();
$dsn = "mysql:host=courses;dbname=z1892226";
$pdo = new PDO($dsn, 'z1892226', '2002Feb20');

$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$subtotal=0.00;
try{
    if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) 
    {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
     
        $getuser=$pdo->prepare("SELECT USERID FROM USER WHERE USERNAME= ?");
        $getuser->execute(array('ASHISH012'));
        $stmt=$pdo->prepare("INSERT INTO CARTADDITION( USERID,PRODUCTID,AMOUNT) VALUES (?,?,?)");
        $stmt->execute(array($_POST["product_id"],$_POST["quantity"]));
    
   
   
    $produ=$pdo->prepare("SELECT * FROM INVENTORY WHERE PRODUCTID= ?");
    $produ->execute([$_POST['product_id']]);
    $products=$produ->fetchAll(PDO::FETCH_ASSOC);

    
    $subtotal=0.00;
    foreach ($products as $product) {
        $subtotal += (float)$product['PRICE'] * (int)$_POST["quantity"];
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
                        <a href="product.php?page=product&PRODUCTID=<?=$product['PRODUCTID']?>">
                            <img src="Photos/<?=$product['img']?>" width="50" height="50" alt="<?=$product['PRODUCTID']?>">
                        </a>
                    </td>
                    <
                    
                    <td>
                        <a href="cart.php?page=product&id=<?=$product['PRODUCTID']?>"><?=$product['PRODUCTNAME']?></a>
                        <br>
                        <a href="cart.php?page=cart&remove=<?=$product['PRODUCTID']?>" class="remove">Remove</a>
                    </td>
                    <td class="price">&dollar;<?=$product['PRICE']?></td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$product['PRODUCTID']?>" value="<?=$products_in_cart[$product['PRODUCTID']]?>" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
                    </td>
                    <td class="price">&dollar;<?=$product['PRICE'] *$_POST['product_id']?></td>
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
            <input type="submit" value="Place Order" name="placeorder">
        </div>
    </form>
</div>


</html>