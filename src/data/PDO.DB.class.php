<?php

    require_once "./validations.php";

    class DB {

        // attr
        private $dbh;

        //------------------------- CONNECT TO DB ------------------------------

        // constructor
        function __construct() {
            try {
                $this -> dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']};dbname={$_SERVER['DB']}", $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);
            } catch(PDOException $pdoe) {
                echo $pdoe -> getMessage();
                die();
            }
        }

        //---------------------------- CREATE -----------------------------------

        // insert into db
        function genericInsert($table, $columns, $values) {

            try {

                // make sure table/columns exists
                if (DB::columnsAreValid($table, $columns)) {

                    // function for error checking gets called here
                    errorChecking($table, $columns, $values);
                    exit;

                    // build query string
                    $queryBeginning = "INSERT INTO $table (";
                    $queryEnd = ") VALUES (";

                    for ($i = 0; $i < min(count($columns), count($values)); $i++) {
                        $queryBeginning .= $columns[$i] . ", ";
                        $queryEnd .= ":value" . $i . ", ";
                    }

                    $queryString = rtrim($queryBeginning, ", ") . rtrim($queryEnd, ", ") . ")";

                    // prepare statement
                    $stmt = $this -> dbh -> prepare($queryString);

                    // bind params
                    for ($i = 0; $i < min(count($columns), count($values)); $i++) {
                        $stmt -> bindParam(":value" . $i, $values[$i]);
                    }

                    // execute statement
                    $stmt -> execute();

                } else {

                    //table/columns don't exist
                    throw new Exception("Error with insert, couldn't find table or columns.");

                }

                return $this -> dbh -> lastInsertId();

            } catch(Exception $e) {
                echo $e -> getMessage();
                return -1;
                die();
            }
        }

        //------------------------------ READ -----------------------------------

        // generic fetch statement, get all columns/records from specified table if it exists
        function genericFetch($table) {
            try {
                $data = [];
                
                // make sure table exists
                if (DB::tableIsValid($table)) {

                    // prepare statement
                    $stmt = $this -> dbh -> prepare("SELECT * FROM $table");

                    // execute
                    $stmt -> execute();

                    // fetch result
                    $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table");

                }

                return $data;

            } catch(Exception $e) {
                echo $e -> getMessage();
                return [];
                die();
            }
        }

        //-------------------------------- UPDATE -----------------------------------

        // update rows in db
        function genericUpdate($table, $columns, $values, $condition) {
            try {

                // make sure table and columns exist
                if (DB::columnsAreValid($columns)) {

                    // build query string
                    $queryBeginning = "UPDATE $table SET ";

                    for ($i = 0; $i < min(count($columns), count($values)); $i++) {
                        $queryBeginning .= $columns[$i] . " = :value" . $i . ", ";
                    }

                    $queryString = rtrim($queryBeginning, ", ") . " WHERE " . $condition;

                    // prepare statement
                    $stmt = $this -> dbh -> prepare($queryString);

                    // bind params
                    for ($i = 0; $i < min(count($columns), count($values)); $i++) {
                        $stmt -> bindParam(":value" . $i, $values[$i]);
                    }

                    // execute statement
                    $stmt -> execute();

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table or columns");

                }

                return $stmt -> rowCount();

            } catch(Exception $e) {
                echo $e -> getMessage();
                return -1;
                die();
            }
        }
        
        //-------------------------------- DELETE -----------------------------------

        // delete rows from db
        function genericDelete($table, $condition) {
            try {

                // make sure table exists
                if (DB::tableIsValid($table)) {

                    // build query string
                    $queryString = "DELETE FROM $table WHERE " . $condition;

                    // prepare statement
                    $stmt = $this -> dbh -> prepare($queryString);

                    // execute statement
                    $stmt -> execute();

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table");

                }

                return $stmt -> rowCount();

            } catch(Exception $e) {
                echo $e -> getMessage();
                return -1;
                die();
            }
        }

        //--------------------------------- META ------------------------------------

        // list out tables
        function showTables() {
            try {
                $data = [];
                
                // prepare statement
                $stmt = $this -> dbh -> prepare("SHOW TABLES");

                // execute
                $stmt -> execute();

                // fetch result
                $data = $stmt -> fetchAll();
                
                return $data;

            } catch(Exception $e) {
                echo $e -> getMessage();
                return [];
                die();
            }
        }

        // describe table
        function describe($table) {
            try {
                $data = [];
                
                // make sure table exists
                if (DB::tableIsValid($table)) {

                    // prepare statement
                    $stmt = $this -> dbh -> prepare("DESCRIBE $table");

                    // execute
                    $stmt -> execute();

                    // fetch result
                    $data = $stmt -> fetchAll();

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table");
                    
                }

                return $data;

            } catch(Exception $e) {
                echo $e -> getMessage();
                return [];
                die();
            }
        }

        //-------- VALIDATION (for items that can't be bound as a parameter) ----------

        // validate that table exists
        function tableIsValid($table) {

            // set flag to false by default
            $isValid = false;

            // look through tables, check if the table specified is among them
            foreach (DB::showTables() AS $listing) {

                // check each table
                $isValid = ($listing[0] == $table);
                
                // if you find the table, jump out of the loop
                if ($isValid) break;
            }

            // return the flag
            return $isValid;
        }

        // validate that columns exist 
        function columnsAreValid($table, $columns) {
            
            try {

                // set flag to true by default; if one column fails, the whole thing fails
                $isValid = true;

                // confirm the columns belong to a real table
                if (DB::tableIsValid($table)) {

                    // check each column against the table's described columns
                    foreach ($columns AS $column) {

                        // flag to make sure each column exists
                        $columnExists = false;

                        // find each column among the listed columns, all must be found
                        foreach (DB::describe($table) AS $listed) {

                            // compare the inputed column name to the listed column name
                            $columnExists = ($column == $listed[0]);

                            // when you find the column, jump out of the loop
                            if ($columnExists) break;
                        }

                        // check of the column was found
                        $isValid = $columnExists;

                        // if it wasn't found, validation has failed
                        if (!$isValid) break;
                    }

                    return $isValid;

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table/columns");
                }

                return $isValid;
            } catch(Exception $e) {
                echo $e -> getMessage();
                return [];
                die();
            }
        }
    }


    function errorChecking($table, $columns, $values){
        // error checking here!
        var_dump($values);

        // [DONE] `agreement`, 
        if($table == "agreement"){
            // `agreementTypeID`, --> not provided by user
            // `agreementTypeName`, --> $values[0]
            $agreementTypeName = trim($values[0]);
            if($agreementTypeName == "" || strlen($agreementTypeName) > 255 || sqlMetaChars($agreementTypeName) || sqlInjection($agreementTypeName) || sqlInjectionUnion($agreementTypeName) || sqlInjectionSelect($agreementTypeName) || sqlInjectionInsert($agreementTypeName) || sqlInjectionDelete($agreementTypeName) || sqlInjectionUpdate($agreementTypeName) || sqlInjectionDrop($agreementTypeName) || crossSiteScripting($agreementTypeName) || crossSiteScriptingImg($agreementTypeName)){
                return "Error: agreementTypeName entered not valid.";
            }
            // `agreementTypeNote` --> $values[1]
            $agreementTypeNote = trim($values[1]);
            if(strlen($agreementTypeNote) > 255 || sqlMetaChars($agreementTypeNote) || sqlInjection($agreementTypeNote) || sqlInjectionUnion($agreementTypeNote) || sqlInjectionSelect($agreementTypeNote) || sqlInjectionInsert($agreementTypeNote) || sqlInjectionDelete($agreementTypeNote) || sqlInjectionUpdate($agreementTypeNote) || sqlInjectionDrop($agreementTypeNote) || crossSiteScripting($agreementTypeNote) || crossSiteScriptingImg($agreementTypeNote)){
                return "Error: agreementTypeNote entered not valid.";
            }
        }
        // [DONE] `author`, 
        if($table == "author"){
            // `namedPersonID`, --> $values[0]
            $namedPersonID = trim($values[0]);
            if(!(integer(intval($namedPersonID)) && intval($namedPersonID) > 0) || $namedPersonID == ""){
                return "Error: namedPersonID entered not valid.";
            }
            // `bookID` --> $values[1]
            $bookID = trim($values[1]);
            if(!(integer(intval($bookID)) && intval($bookID) > 0) || $bookID == ""){
                return "Error: bookID entered not valid.";
            }
        }
        // [DONE] `book`, 
        if($table == "book"){
            // `bookDescriptor`,
            $bookDescriptor = trim($values[0]);
            if(sqlMetaChars($bookDescriptor) || sqlInjection($bookDescriptor) || sqlInjectionUnion($bookDescriptor) || sqlInjectionSelect($bookDescriptor) || sqlInjectionInsert($bookDescriptor) || sqlInjectionDelete($bookDescriptor) || sqlInjectionUpdate($bookDescriptor) || sqlInjectionDrop($bookDescriptor) || crossSiteScripting($bookDescriptor) || crossSiteScriptingImg($bookDescriptor)){
                return "Error: bookDescriptor entered not valid.";
            }
            // `bookNote`
            $bookNote = trim($values[1]);
            if(sqlMetaChars($bookNote) || sqlInjection($bookNote) || sqlInjectionUnion($bookNote) || sqlInjectionSelect($bookNote) || sqlInjectionInsert($bookNote) || sqlInjectionDelete($bookNote) || sqlInjectionUpdate($bookNote) || sqlInjectionDrop($bookNote) || crossSiteScripting($bookNote) || crossSiteScriptingImg($bookNote)){
                return "Error: bookNote entered not valid.";
            }
        }
        // [~~YUCK!~~] `book_edition`,
        if($table == "book_edition"){}
        // [DONE] `book_subject`,
        if($table == "book_subject`"){
            // `bookID`,
            $bookID = trim($values[0]);
            if(!(integer(intval($bookID)) && intval($bookID) > 0) || $bookID == ""){
                return "Error: bookID entered not valid.";
            }
            // `subjectID`,
            $subjectID = trim($values[1]);
            if(!(alphabetic($subjectID)) || $subjectID == "" || strlen($subjectID) > 3 || sqlMetaChars($subjectID) || sqlInjection($subjectID) || sqlInjectionUnion($subjectID) || sqlInjectionSelect($subjectID) || sqlInjectionInsert($subjectID) || sqlInjectionDelete($subjectID) || sqlInjectionUpdate($subjectID) || sqlInjectionDrop($subjectID) || crossSiteScripting($subjectID) || crossSiteScriptingImg($subjectID)){
                return "Error: subjectID entered not valid.";
            }
            // `bookSubjectNote`
            $bookSubjectNote = trim($values[2]);
            if(sqlMetaChars($bookSubjectNote) || sqlInjection($bookSubjectNote) || sqlInjectionUnion($bookSubjectNote) || sqlInjectionSelect($bookSubjectNote) || sqlInjectionInsert($bookSubjectNote) || sqlInjectionDelete($bookSubjectNote) || sqlInjectionUpdate($bookSubjectNote) || sqlInjectionDrop($bookSubjectNote) || crossSiteScripting($bookSubjectNote) || crossSiteScriptingImg($bookSubjectNote)){
                return "Error: bookSubjectNote entered not valid.";
            }
        }
        // [DONE] `book_type`,
        if($table == "book_type`"){
            // `bookID`,
            $bookID = trim($values[0]);
            if(integer(intval($bookID)) && intval($bookID) > 0){
                return "Error: bookID entered not valid.";
            }
            // `typeID`,
            $typeID = trim($values[1]);
            if(!(alphabetic($typeID)) || $typeID == "" || strlen($typeID) > 3 || sqlMetaChars($typeID) || sqlInjection($typeID) || sqlInjectionUnion($typeID) || sqlInjectionSelect($typeID) || sqlInjectionInsert($typeID) || sqlInjectionDelete($typeID) || sqlInjectionUpdate($typeID) || sqlInjectionDrop($typeID) || crossSiteScripting($typeID) || crossSiteScriptingImg($typeID)){
                return "Error: typeID entered not valid.";
            }
            // `bookTypeNote`
            $bookTypeNote = trim($values[2]);
            if(sqlMetaChars($bookTypeNote) || sqlInjection($bookTypeNote) || sqlInjectionUnion($bookTypeNote) || sqlInjectionSelect($bookTypeNote) || sqlInjectionInsert($bookTypeNote) || sqlInjectionDelete($bookTypeNote) || sqlInjectionUpdate($bookTypeNote) || sqlInjectionDrop($bookTypeNote) || crossSiteScripting($bookTypeNote) || crossSiteScriptingImg($bookTypeNote)){
                return "Error: bookTypeNote entered not valid.";
            }
        }
        // [DONE] `edition`,
        if($table == "edition`"){
            // `editionString`,
            $editionString = trim($values[1]);
            if($editionString == "" || sqlMetaChars($editionString) || sqlInjection($editionString) || sqlInjectionUnion($editionString) || sqlInjectionSelect($editionString) || sqlInjectionInsert($editionString) || sqlInjectionDelete($editionString) || sqlInjectionUpdate($editionString) || sqlInjectionDrop($editionString) || crossSiteScripting($editionString) || crossSiteScriptingImg($editionString)){
                return "Error: editionString entered not valid.";
            }
        }
        // [DONE] `editor`,
        if($table == "editor`"){
            // `namedPersonID`, --> $values[0]
            $namedPersonID = trim($values[0]);
            if(!(integer(intval($namedPersonID)) && intval($namedPersonID) > 0) || $namedPersonID == ""){
                return "Error: namedPersonID entered not valid.";
            }
            // bookID
            $bookID = trim($values[1]);
            if(!(integer(intval($bookID)) && intval($bookID) > 0) || $bookID == ""){
                return "Error: bookID entered not valid.";
            }
        }
        // [DONE] `format`,
        if($table == "format"){
            // `formatID`, --> $values[0]
            $formatID = trim($values[0]);
            if(!(integer(intval($formatID)) && intval($formatID) > 0) || $formatID == ""){
                return "Error: formatID entered not valid.";
            }
            // `formatName` --> $values[1]
            $formatName = trim($values[1]);
            if($formatName == "" || strlen($formatName) > 255 || sqlMetaChars($formatName) || sqlInjection($formatName) || sqlInjectionUnion($formatName) || sqlInjectionSelect($formatName) || sqlInjectionInsert($formatName) || sqlInjectionDelete($formatName) || sqlInjectionUpdate($formatName) || sqlInjectionDrop($formatName) || crossSiteScripting($formatName) || crossSiteScriptingImg($formatName)){
                return "Error: formatName entered not valid.";
            }
        }
        // [] `named_person`,
        if($table == "named_person`"){}
        // [] `publisher`,
        if($table == "publisher`"){}
        // [] `subject`,
        if($table == "subject`"){}
        // [] `title`,
        if($table == "title`"){}
        // [] `translator`,
        if($table == "translator`"){}
        // [] `type`
        if($table == "type"){}

        // end of error checking
    }

// don't close php tag