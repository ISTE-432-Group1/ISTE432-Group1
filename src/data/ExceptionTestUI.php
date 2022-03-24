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
                $data = $dbh -> genericFetch($_POST['table']);
                echo $elementConstructor -> buildTable($data);
                $data = $dbh -> describe($_POST['table']);
                echo $elementConstructor -> buildInsertForm($_POST['table'], $data);
            } else if (isset($_POST['#tableInUse'])) {
                $tableName = $_POST['#tableInUse'];
                unset($_POST['#tableInUse']);
                $columns = [];
                $values = [];
                foreach($_POST AS $column => $value) {
                    $columns[] = $column;
                    $values[] = $value;
                }

                echo " Row inserted: {$dbh -> genericInsert($tableName, $columns, $values)}";
            }
        ?>
    </body>
</html>