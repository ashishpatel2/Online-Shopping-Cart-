<?php

    $dsn = "mysql:host=courses;dbname=z1892226";
    $pdo = new PDO($dsn, 'z1892226', '2002Feb20');

    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    echo '<body style="background-color:grey">';
    echo "<font size = '20' face = 'Times New Roman'>";
    echo "<div align='center'><br><br><br>Thank you! your order has been placed<br></div>";
    ?>

<html>
<head>
<title>Order Placed</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style.css" rel="stylesheet" type="text/css">
</head><body>
    <div class="buttons" style="text-align:right">
        <form action="products.php" method="post">
        <input type="submit" value="Continue Shopping" name="cont">
        </form>
    </div>

</body>
</html>