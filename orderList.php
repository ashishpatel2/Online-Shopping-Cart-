<?php

try {
    $username = 'z1892226';
    $password = '2002Feb20';
    $dsn = "mysql:host=courses;dbname=z1892226";
    $pdo = new PDO($dsn, $username, $password);
}
catch(PDOexception $e) {
    echo "Connection to database failed: " . $e->getMessage();
}
if(isset($_POST['update']))
    {
        $stmt=$pdo->prepare("UPDATE ORDERS SET STATUS=? WHERE  TRACKINGID=?");
        $stmt->execute(array($_POST["status"],$_POST["id"]));

    }

function draw_table($rows)
{
    echo "<table border=1 cellspacing=1 cellpadding=10>";
    echo "<tr>";
    foreach($rows[0] as $key => $item) 
    {
        echo "<th>$key</th>";
    }
    echo "</tr>";
    
    foreach($rows as $row)
    {
        echo "<tr>";
        foreach($row as $item)
        {
            echo "<td>$item</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
    
    echo "<html><body>";
    
    $rs = $pdo->query("SELECT * FROM ORDERS WHERE STATUS = 'Processing';");
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p style=font-size:30px;><b>Processing Orders:</b></p>";
    draw_table($rows);
    
    $rs = $pdo->query("SELECT * FROM ORDERS WHERE STATUS = 'Shipped';");
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p style=font-size:30px;><b>Shipped Orders:</b></p>";
    draw_table($rows);
    
    $rs = $pdo->query("SELECT * FROM ORDERS WHERE STATUS = 'Fulfilled';");
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p style=font-size:30px;><b>Fulfilled Orders:</b></p>";
    draw_table($rows);

    
    
    echo "</body></html>";

?>

<html>
<head>
<title>orderList</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style.css" rel="stylesheet" type="text/css">
</head><body>
<form action="orderList.php" method="post">
<strong><p>Update Order:</p><br>

Tracking ID: <input type="text" name="id" placeholder="Ex. 1"><br><br>

<label for="status">Change Order Status to :</label>

<select name="status" >
  <option value="Processing">Processing</option>
  <option value="Shipped">Shipped</option>
  <option value="Fulfilled">Fulfilled</option>
</select>
<br><br>
<input type="submit" name="update" value="Update">
</form>
    
</body>
</html>
