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
                $html = "<table>\n<tr id='tableheaders'>";
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
                for ($i = 0; $i < count($select); $i++) {
                    $html .= "<tr id='$i'>";
                    foreach ($select[$i] AS $value) {
                        $html .= "<td>" . $value . "</td>";
                    }
                    if(true) {
                        $condition = UIElementConstructor::buildConditionalStatement($select[$i], $describe);
                        $html .= UIElementConstructor::buildUpdateForm($table, $condition, $i);
                    }
                    if(true) {
                        $condition = UIElementConstructor::buildConditionalStatement($select[$i], $describe);
                        $html .= UIElementConstructor::buildDeleteForm($table, $condition);
                    }
                    $html .= "</tr>\n";
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

        // This builds the delete form if they have permission to delete items
        function buildDeleteForm($table, $condition) {
            $html = "<td><button onclick='del(\"$table\", \"$condition\")'>Delete</button</td>";
            return $html;
        }

        // This builds the update form if they have permission to delete items
        function buildUpdateForm($table, $condition, $rowId) {
            $html = "<td><button onclick='update(\"$table\", \"$condition\", $rowId)'>Update</button></td>";
            return $html;
        }

        //this is for building the conditional statement on which the rows are updated or deleted
        function buildConditionalStatement($row, $describe) {
            $condition = [];
            foreach ($row AS $key => $value) {
                foreach ($describe AS $field) {
                    if($field['Field'] == $key) {
                        if($field['Key'] == "PRI" && (strpos($field['Type'], "char") == false || strpos($field['Type'], "text") == false)) {
                            $condition[] = "$key = $value";
                        } else if ($field['Key'] == "PRI") {
                            $condition[] = "$key = '$value'";
                        }
                    }
                }
            }
            return implode(" AND ", $condition);
        }
    }