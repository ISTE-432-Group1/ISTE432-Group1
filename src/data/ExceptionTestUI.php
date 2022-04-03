<?php
    require_once('./PDO.DB.class.php');
    require_once('./UIElementConstructor.class.php');

    $dbh = new DB();
    $elementConstructor = new UIElementConstructor();
?>
<html>
    <head>
        <title>Generic DB access UI</title>
        <link rel="stylesheet" href="style.css">
        <script src="../js/tablefunctions.js"></script>
    </head>
    <body>
        <?php
            // have the user select a table from the dropdown
            echo $elementConstructor -> createTableSelect($dbh -> showTables());
            if(isset($_POST['operation'])) {
                switch($_POST['operation']) {
                    case "insert":
                        $columns = [];
                        $values = [];
                        for($i = 2; $i < count($_POST); $i++) {
                            $columns[] = array_keys($_POST)[$i];
                            $values[] = $_POST[array_keys($_POST)[$i]];
                        }
                        $success = $dbh -> genericInsert($_POST['table'], $columns, $values);
                        header("Location: ExceptionTestUI.php?table={$_POST['table']}");
                    break;
                    case "update":
                        // code for updating here
                    break;
                    case "delete":
                        $rowsAffected = $dbh -> genericDelete($_POST['table'], $_POST['values']);
                        header("Location: ExceptionTestUI.php?table={$_POST['table']}");
                    break;
                }
            }
            // testing read method
            if(isset($_GET['table'])) {
                echo "<h2>Table: {$_GET['table']}</h2>";
                $select = $dbh -> genericFetch($_GET['table']);
                $describe = $dbh -> describe($_GET['table']);
                echo $elementConstructor -> buildInteractiveTable($_GET['table'], $select, $describe);
            } 
        ?>
    </body>
</html>
