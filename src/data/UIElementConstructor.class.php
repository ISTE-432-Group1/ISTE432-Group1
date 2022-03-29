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
        function buildTable($select) {
            if(count($select) > 0) {
                $html = "<table>\n<tr>";
                foreach ($select[0] AS $columnName => $value) {
                    $html .= "<th>" . $columnName . "</th>";
                }
                $html .= "</tr>\n";
                foreach ($select AS $row) {
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
        
        // this is the only function in this class currently in use. I'll delete the others if we settle on this design.
        function buildInteractiveTable($select, $describe) {
            $html = "<table>\n<tr>";
            $inputfields = "<tr>";
            foreach ($describe AS $value) {
                $html .= "<th>" . $value['Field'];
                if(!empty($value['Key'])) {
                    $html .= " ({$value['Key']})";
                }
                $html .= "</th>";
                $inputfields .= "<td><input type='text' name='" . $value['Field'] . "' placeholder='" . $value['Type'];
                if ($value['Null'] == "NO") {
                    $inputfields .= " (required)";
                }
                $inputfields .= "'>";
            }
            $html .= "</tr>\n" . $inputfields . "<td><button onclick='insert()'>Insert</button></td></tr>\n";
            foreach ($select AS $row) {
                $html .= "<tr>";
                foreach ($row AS $value) {
                    $html .= "<td>" . $value . "</td>";
                }
                $html .= "<td><button onclick='update()'>Update</button></td><td><button onclick='delete'>Delete</button</td></tr>\n";
            }
            $html .= "</table>\n";
            if(count($select) == 0) {
                $html .= "<h3>(No data for this table)</h3>";
            }
            return $html;
        }

        // this isn't really needed anymore but I'm keeping it until we settle on a UI for inserts
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

        // this is a proof of concept and has never been used except for in prototypes
        function buildDeleteForm($table, $columns) {
            $html = "<h3>Delete data from table in bulk</h3>\n<form action='./ExceptionTestUI.php' method='POST'>\n<input type='hidden' name='#tableInUse' value='$table'>";
            $html .= "<label for='Where Clause'>Enter WHERE condition for the delete</label><br />\n<span>WHERE </span><input type='text id='condition' name='condition' placeholder='Example: id < 5'><span>;</span><br />\n";
            $html .= "<input type='submit' value='delete'>\n</form>";
            return $html;
        }

    }