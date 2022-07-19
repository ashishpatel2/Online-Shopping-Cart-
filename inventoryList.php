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
    
    $rs = $pdo->query("SELECT * FROM INVENTORY");
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<html><body>";
    
    echo "<p style=font-size:30px;><b>Inventory list:</b></p>";
    draw_table($rows);
    
    echo "</body></html>";

?>