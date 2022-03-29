<?php
    require_once('./PDO.DB.class.php');
    require_once('./UIElementConstructor.class.php');

    $dbh = new DB();
    $elementConstructor = new UIElementConstructor();
?>
<html>
    <head>
        <title>Generic DB access UI</title>
    </head>
    <body>
        <?php
            // have the user select a table from the dropdown
            echo $elementConstructor -> createTableSelect($dbh -> showTables());
            // testing read method
            if(isset($_POST['table'])) {
                echo "<h2>Table: {$_POST['table']}</h2>";
                $select = $dbh -> genericFetch($_POST['table']);
                $describe = $dbh -> describe($_POST['table']);
                echo $elementConstructor -> buildInteractiveTable($select, $describe);
                // echo var_dump($dbh -> describe($_POST['table']));
            } 
        ?>
    </body>
</html>