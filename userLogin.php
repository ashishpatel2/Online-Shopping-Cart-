<?php

session_start();

try 
{
    $username = 'z1892226';
    $password = '2002Feb20';
    $dsn = "mysql:host=courses;dbname=z1892226";
    $pdo = new PDO($dsn, $username, $password);
}
catch(PDOexception $e) 
{
    echo "Connection to database failed: " . $e->getMessage();
}

if ( !isset($_POST['USERNAME'], $_POST['PASSPHRASE']) ) 
{
	exit('Please fill both the username and passphrase fields!');
}

    $rs = $pdo->prepare("SELECT * FROM USER WHERE USERNAME = :us AND PASSPHRASE = :ps;");
    $rs->execute(array(":us" => $_POST["USERNAME"], ":ps" => $_POST["PASSPHRASE"]));
    
    if(!$rs) { echo "Error in query"; die(); }
    
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    

    if (count($rows) > 0)
    {
    echo "<p style=font-size:15px;><b>Welcome back: " . $_POST["USERNAME"] . "</b></p>";
    header("Location: products.php");
    exit();
    }
    else
    {
        echo "<p style=font-size:30px;><b>Incorrect login!</b></p>";
    }

	$rs->close();

?>