<!DOCTYPE html>
<html>
<head>
<title>Products</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>

<nav class="header">
    
      <ul class="main-nav">
          <li><a href="products.php">Products</a></li>
          <li><a href="cart.php">Cart</a></li>
          <li><a href="track.php"> Track Order</a></li>
      </ul>
      
</nav>

<?php
	try{
	$dsn = "mysql:host=courses;dbname=z1892226";
	$pdo = new PDO($dsn, 'z1892226', '2002Feb20');
	
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	// Get the 5 most recently added products
	// The amounts of products to show on each page
	
$num_products_on_each_page = 5;
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
// Select products ordered by the date added
$stmt = $pdo->prepare('SELECT * FROM INVENTORY ORDER BY PRODUCTNAME DESC LIMIT ?,?');
// bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
$stmt->bindValue(1, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
$stmt->bindValue(2, $num_products_on_each_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the products from the database and return the result as an Array
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_products = $pdo->query('SELECT * FROM INVENTORY')->rowCount();

	}
	catch(PDOexception $e)
	{
		echo "Connection to Database failed: " . $e->getMessage();
	}//End Catch

?>
<div class="products content-wrapper">
    <h1>Shop Our Products:</h1>
    <p><?=$total_products?> Products</p>
    <div class="products-wrapper">
        <?php foreach ($products as $product): ?>
        <a href="product.php?page=product&PRODUCTID=<?=$product['PRODUCTID']?>" class="product">
        <img src="Photos/<?=ucwords(strtolower($product['PRODUCTNAME']))?>.jpeg" width="200" height="200" alt="<?=$product['PRODUCTNAME']?>">
        <strong><p class="name">&emsp; &emsp;<?=$product['PRODUCTNAME']?></p>
            
            <span class="price">
              <br>&emsp; &emsp; &dollar;<?=$product['PRICE']?>
                
            </span>
        </a>
        <?php endforeach; ?>
    </div>
	<div class="buttons">
        <?php if ($current_page > 1): ?>
        <a href="products.php?page=products&p=<?=$current_page-1?>">Prev</a>
        <?php endif; ?>
        <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($products)): ?>
        <a href="products.php?page=products&p=<?=$current_page+1?>">Next</a>
        <?php endif; ?>
    </div>
   
</div>
</body>
</html>
