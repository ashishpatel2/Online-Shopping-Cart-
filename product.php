<!DOCTYPE html>
<html>
<head>
<title>product</title>
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

<?php
try{

	$dsn = "mysql:host=courses;dbname=z1892226";
	$pdo = new PDO($dsn, 'z1892226', '2002Feb20');
	
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['PRODUCTID'])) {
    // Prepare statement and execute, prevents SQL injection
    $stmt = $pdo->prepare('SELECT * FROM INVENTORY WHERE PRODUCTID = ?');
    $stmt->execute([$_GET['PRODUCTID']]);
    // Fetch the product from the database and return the result as an Array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

}
}
catch(PDOexception $e)
	{
		echo "Connection to Database failed: " . $e->getMessage();
	}//End Catch
?>

   <div class="product content-wrapper">
   <img src="Photos/<?=ucwords(strtolower($product['PRODUCTNAME']))?>.jpeg" width="500" height="500" alt="<?=$product['PRODUCTNAME']?>">
    <h6></h6>
    <div>
        <h1 class="name"><?=$product['PRODUCTNAME']?></h1>
        <br>
        <span class="price">
            &dollar;<?=$product['PRICE']?>
          
        </span>
        <form action="http://students.cs.niu.edu/~z1892226/466groupproject/cart.php" method="post">
            <input type="number" name="quantity" value="1" min="1" max="<?=$product['QUANTITY']?>" placeholder="Quantity" required>
            <input type="hidden" name="product_id" value="<?=$product['PRODUCTID']?>">
            <input type="hidden" name="product_price" value="<?=$product['PRICE']?>">
            <input type="hidden" name="product_name" value="<?=$product['PRODUCTNAME']?>">
            <input type="submit" value="Add To Cart">
        </form>
   
    </div>
</div>


</body>
</html>
