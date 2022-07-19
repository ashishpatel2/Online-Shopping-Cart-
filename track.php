<?php
    echo "Tracking Order Page";
	try{
	$dsn = "mysql:host=courses;dbname=z1892226";
	$pdo = new PDO($dsn, 'z1892226', '2002Feb20');
	
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


        $stmt=$pdo->prepare("SELECT * FROM ORDERS WHERE PHONE=?");
        $stmt->execute(array($_POST["phone"]));
        $orders=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
    
    
}
catch(PDOexception $e)
	{
		echo "Connection to Database failed: " . $e->getMessage();
	}//End Catch

?>

<html>
<head>
<title>Track Order</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
    <body>
        <form action="" method="post">
        Enter Phone Number: <input type="text" name="phone" placeholder="123-456-7899"><br><br>
        <input type="submit" name="find" value="Track Order">

        </form> 
        
        <?php foreach ($orders as $order): ?>

         <strong> <p>Tracking Id: </p><strong><span> <?=$order['TRACKINGID']?></span>
        <p>Full Name: </p><span><strong><?=$order['FULLNAME']?></span>
        <p>Status: </p><span><strong><?=$order['STATUS']?></span>
        <p>Shipping Adress: </p><span><strong><?=$order['ADDRESS']?></span>

        <?php endforeach; ?>
        <br>
        <form action="products.php" method="post">
            <input type="submit" value="Continue Shopping" name="cont">
        </form>


    </body>
</html>