<?php

session_start();

try {
    $username = 'z1892226';
    $password = '2002Feb20';
    $dsn = "mysql:host=courses;dbname=z1892226";
    $pdo = new PDO($dsn, $username, $password);
}
catch(PDOexception $e) {
    echo "Connection to database failed: " . $e->getMessage();
}

if ( !isset($_POST['EMPNAME'], $_POST['PASSPHRASE']) ) 
{
	exit('Please fill both the username and passphrase fields!');
}

    $rs = $pdo->prepare("SELECT * FROM EMPLOYEES WHERE EMPNAME = :us AND PASSPHRASE = :ps;");
    $rs->execute(array(":us" => $_POST["EMPNAME"], ":ps" => $_POST["PASSPHRASE"]));
    
    if(!$rs) { echo "Error in query"; die(); }
    
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($rows) > 0)
    {
        echo "<p style=font-size:25px;><b>Welcome back, " . $_POST["EMPNAME"] . ".</b></p>";
        
        echo "<p style=font-size:20px;>Click here to view the inventory:</p>";
		echo "<form method=POST action=inventoryList.php>";
		echo "<input type=submit value='Inventory'/>";
		echo "</form>";

		echo "<p style=font-size:20px;>Click here to view the order list:</p>";
		echo "<form method=POST action=orderList.php>";
		echo "<input type=submit value='Orders'/>";
		echo "</form>";
    }
    else
    {
        echo "<p style=font-size:30px;><b>Incorrect login!</b></p>";
    }

?>
