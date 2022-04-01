<?php

    class UIElementConstructor {

        // This method builds select lists based on the return value of the "show tables" pdo
        function createTableSelect($tables) {
            $html = "<form action='./ExceptionTestUI.php' method='GET'>\n<select name='table'>\n";
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
        function buildInteractiveTable($table, $select, $describe) {
            if(true) {
                $html = "<table>\n<tr>";
                foreach ($describe AS $value) {
                    $html .= "<th>" . $value['Field'];
                    if(!empty($value['Key'])) {
                        $html .= " ({$value['Key']})";
                    }
                    $html .= "</th>";
                }
                $html .= "</tr>\n";
                if(true) {
                    $html .= UIElementConstructor::buildInsertForm($table, $describe);
                }
                foreach ($select AS $row) {
                    $html .= "<tr>";
                    foreach ($row AS $value) {
                        $html .= "<td>" . $value . "</td>";
                    }
                    $html .= "<td><button onclick='update()'>Update</button></td><td><button onclick='del()'>Delete</button</td></tr>\n";
                }
                $html .= "</table>\n";
                if(count($select) == 0) {
                    $html .= "<h3>(No data for this table)</h3>";
                }
                return $html;
            } else {
                return "<h3>Error: Permission denied</h3>";
            }
        }

        // this isn't really needed anymore but I'm keeping it until we settle on a UI for inserts
        function buildInsertForm($table, $describe) {
            $inputfields = "<tr><form action='ExceptionTestUI.php' method='POST'>
                <input type='hidden' name='operation' value='insert' />
                <input type='hidden' name='table' value='$table' />";
            foreach ($describe AS $value) {
                if ($value['Extra'] == "auto_increment") {
                    $inputfields .= "<td>(auto-generated)</td>";
                } else {
                    $inputfields .= "<td><input type='text' name='" . $value['Field'] . "' placeholder='" . $value['Type'];
                    if ($value['Null'] == "NO") {
                        $inputfields .= " (not null)";
                    }
                    $inputfields .= "' /></td>";
                }
            }
            return $inputfields . "<td><input type='submit' value='Insert' /></td></form></tr>\n";
        }

        // this is a proof of concept and has never been used except for in prototypes
        function buildDeleteForm($table, $columns) {
            $html = "<h3>Delete data from table in bulk</h3>\n<form action='./ExceptionTestUI.php' method='POST'>\n<input type='hidden' name='#tableInUse' value='$table'>";
            $html .= "<label for='Where Clause'>Enter WHERE condition for the delete</label><br />\n<span>WHERE </span><input type='text id='condition' name='condition' placeholder='Example: id < 5'><span>;</span><br />\n";
            $html .= "<input type='submit' value='delete'>\n</form>";
            return $html;
        }

    }