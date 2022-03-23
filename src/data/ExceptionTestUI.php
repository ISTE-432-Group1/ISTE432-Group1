<html>
    <head>
        <title>Generic DB access UI</title>
    </head>
    <body>
        <?php

            require_once('./PDO.DB.class.php');

            $dbh = new DB();

                // testing read method
                echo "<h2>Read test</h2>";
                // $data = $dbh -> genericFetch(TABLE NAME);
                // var_dump($data);

                // testing create method
                echo "<h2>Create test</h2>";
                // $id = $dbh -> genericInsert(TABLE NAME, [COLUMN NAMES], [VALUES]);
                // echo $id;

                // testing update method
                echo "<h2>Update test</h2>";
                // $rowsAffected = $dbh -> genericUpdate(TABLE NAME, [COLUMN NAMES], [VALUES], CONDITION);
                // echo $rowsAffected;

                // testing delete method
                echo "<h2>Delete test</h2>";
                // $rowsAffected = $dbh -> genericDelete(TABLE NAME, CONDITION);
                // echo $rowsAffected;

                // check results of crud ops
                echo "<h2>Changed table results</h2>";
                // $data = $dbh -> genericFetch(TABLE NAME);
                // var_dump($data);

                // testing meta operations
                echo "<h2>Meta operations</h2>";

                echo "<h3>Show tables</h3>";
                // $data = $dbh -> showTables();
                // var_dump($data);

                echo "<h3>Describe a table</h3>";
                // $data = $dbh -> describe(TABLE NAME);
                // var_dump($data);
        ?>
    </body>
</html>