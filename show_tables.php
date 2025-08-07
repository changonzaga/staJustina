<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $result = $db->query('SHOW TABLES');
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>