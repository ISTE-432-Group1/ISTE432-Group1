<?php

    class UIElementConstructor {

        // This method builds select lists based on the return value of the "show tables" pdo
        function createTableSelect($tables) {
            $html = "<form action='./ExceptionTestUI.php' method='POST'>\n<select name='table'>\n";
            $html .="<option value=''>Select a table</option>\n";
            foreach($tables AS $table) {
                $html .= "<option value='" . $table[0] . "'>" . $table[0] . "</option>\n"; 
            }
            $html .= "</select>\n<input type='submit' value='query table'>\n</form>\n";
            return $html;
        }

        // This method builds html tables based on the return value of the "generic fetch" pdo method
        function buildTable($tableData) {
            if(count($tableData) > 0) {
                $html = "<table>\n<tr>";
                foreach ($tableData[0] AS $key => $value) {
                    $html .= "<th>" . $key . "</th>";
                }
                $html .= "</tr>\n";
                foreach ($tableData AS $row) {
                    $html .= "<tr>";
                    foreach ($row AS $value) {
                        $html .= "<td>" . $value . "</td>";
                    }
                    $html .= "</tr\n>";
                }
                $html .= "</table>\n";
                return $html;
            } else {
                return "<p>No data for this table</p>";
            }
        }

        function buildInsertForm($table, $data) {
            $html = "<h3>Insert data into table</h3>\n<form action='./ExceptionTestUI.php' method='POST'>\n<input type='hidden' name='#tableInUse' value='$table'>";
            foreach($data AS $column) {
                if (!(strpos($column['Extra'], 'auto_increment') !== false)) {
                    $html .= "<label for='" . $column[0] . "'>" . $column[0] . "</label>\n<input type='text id='" . $column[0] . "' name='" . $column[0] . "'><br />\n";
                }
            }
            $html .= "<input type='submit' value='insert'>\n</form>";
            return $html;
        }

        // UPDATE FORM - builds form of updates containing column names
        // table - data is modifying 
        // columns - column names being inserted
        // value - values in columns named by 
        // to fill field automatically, use atrribute called placeholder='' (single quotes bc you're in a string with double    quotes)
        function buildUpdateForm($table, $columns, $value) {
            $html = "<h3>Update table data</h3>\n<form action='./ExceptionTestUI.php' method='POST'>\n<input type='hidden' name='#tableInUse' value='$table'>";
            foreach($columns AS $column) {
                if (!(strpos($column['Extra'], 'auto_increment') !== false)) {
                    // name ($column[0]) controls the key
                    $html .= "<label for='" . $column[0] . "'>" . $column[0] . "</label>\n<input type='text id='" . $column[0] . "' name='" . $column[0] . "'value='" . $value[0] . "'><br />\n";
                }
            }
            $html .= "<input type='submit' value='insert'>\n</form>";
            return $html;
        }

    }