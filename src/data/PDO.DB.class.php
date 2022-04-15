<?php

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
                    $statement = errorChecking($table, $columns, $values);
                    if(substr($statement, 0, 6) == "Error:"){
                        echo $statement;
                        exit;
                    }

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
                    return false;
                }

                return true;

            } catch(Exception $e) {
                echo $e -> getMessage();
                return false;
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
                if (DB::columnsAreValid($table, $columns)) {

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
        
        //--------- CONFIRM LOGIN ------------
        function confirmLogin($user){
            $data = array();
            try{
                $stmt = $this->dbh->prepare("SELECT userID, username, password, roleID FROM users WHERE username = :user");
                $stmt->bindParam(":user", $user, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll();
            }catch(PDOException $pe){
                echo $pe->getMessage();
                return $data;
            }
        } // confirm login
    }

    function errorChecking($table, $columns, $values){
        // error checking here!
        // var_dump($values);

        // [DONE] `agreement`, 
        if($table == "agreement"){
            // `agreementTypeName`, --> $values[0]
            $agreementTypeName = trim($values[0]);
            if(stringNotEmpty255($agreementTypeName)){
                return "Error: agreementTypeName entered not valid.";
            }
            // `agreementTypeNote` --> $values[1]
            $agreementTypeNote = trim($values[1]);
            if(string255($agreementTypeNote)){
                return "Error: agreementTypeNote entered not valid.";
            }
        }
        // [DONE] `author`, 
        if($table == "author"){
            // `namedPersonID`, --> $values[0]
            $namedPersonID = trim($values[0]);
            if(integerNotEmpty0($namedPersonID)){
                return "Error: namedPersonID entered not valid.";
            }
            // `bookID` --> $values[1]
            $bookID = trim($values[1]);
            if(integerNotEmpty0($bookID)){
                return "Error: bookID entered not valid.";
            }
        }
        // [DONE] `book`, 
        if($table == "book"){
            // `bookDescriptor`,
            $bookDescriptor = trim($values[0]);
            if(stringText($bookDescriptor)){
                return "Error: bookDescriptor entered not valid.";
            }
            // `bookNote`
            $bookNote = trim($values[1]);
            if(stringText($bookNote)){
                return "Error: bookNote entered not valid.";
            }
        }
        // [DONE] `book_edition`,
        if($table == "book_edition"){
            // `bookID`, --> [0]
            $bookID = trim($values[0]);
            if(integerNotEmpty0($bookID)){
                return "Error: bookID entered not valid.";
            }
            // `editionID`, --> [1]
            $editionID = trim($values[1]);
            if(integerNotEmpty0($editionID)){
                return "Error: editionID entered not valid.";
            }
            // `publisherID`, --> [2]
            $publisherID = trim($values[2]);
            if(integerNotEmpty0($publisherID)){
                return "Error: publisherID entered not valid.";
            }
            // `titleID`, --> [3]
            $titleID = trim($values[3]);
            if(integerNotEmpty0($titleID)){
                return "Error: titleID entered not valid.";
            }
            // `formatID`, --> [4]
            $formatID = trim($values[3]);
            if(integerNotEmpty0($formatID)){
                return "Error: formatID entered not valid.";
            }
            // `year`, --> [5]
            $year = trim($values[5]);
            if($year == ""){
                // pass
            }else if(integer0($year)){
                return "Error: year entered not valid.";
            }
            // `numberPages`, --> [6]
            $numberPages = trim($values[6]);
            if($numberPages == ""){
                // pass
            }else if(integer0($numberPages)){
                return "Error: numberPages entered not valid.";
            }
            // `numberVolumes`, --> [7]
            $numberVolumes = trim($values[7]);
            if($numberVolumes == ""){
                // pass
            }else if(integer0($numberVolumes)){
                return "Error: numberVolumes entered not valid.";
            }
            // `agreementTypeID`, --> [8]
            $agreementTypeID = trim($values[8]);
            if(integerNotEmpty0($agreementTypeID)){
                return "Error: agreementTypeID entered not valid.";
            }
            // `salePrice`, --> [9]
            $salePrice = trim($values[9]);
            $salePrice = trim($salePrice, "-");
            $salePrice = trim($salePrice, "-");
            if($salePrice == ""){
                // pass
            }else if(notDecimal($salePrice)){
                return "Error: salePrice entered not valid.";
            }
            // `paymentAgreedAmount`, --> [10]
            $paymentAgreedAmount = trim($values[10]);
            $paymentAgreedAmount = trim($paymentAgreedAmount, "-");
            $paymentAgreedAmount = trim($paymentAgreedAmount, "-");
            if($paymentAgreedAmount == ""){
                // pass
            }else if(notDecimal($paymentAgreedAmount)){
                return "Error: paymentAgreedAmount entered not valid.";
            }
            // `illustrationsPayment`, --> [11]
            $illustrationsPayment = trim($values[11]);
            $illustrationsPayment = trim($illustrationsPayment, "-");
            $illustrationsPayment = trim($illustrationsPayment, "-");
            if($illustrationsPayment == ""){
                // pass
            }else if(notDecimal($illustrationsPayment)){
                return "Error: illustrationsPayment entered not valid.";
            }
            // `copiesSold`, --> [12]
            $copiesSold = trim($values[12]);
            if($copiesSold == ""){
                // pass
            }else if(integer0($copiesSold)){
                return "Error: copiesSold entered not valid.";
            }
            // `copiesRemaining`, --> [13]
            $copiesRemaining = trim($values[13]);
            if($copiesRemaining == ""){
                // pass
            }else if(integer0($copiesRemaining)){
                return "Error: copiesRemaining entered not valid.";
            }
            // `profitLoss`, --> [14]
            $profitLoss = trim($values[14]);
            $profitLoss = trim($profitLoss, "-");
            $profitLoss = trim($profitLoss, "-");
            if($profitLoss == ""){
                // pass
            }else if(notDecimal($profitLoss)){
                return "Error: profitLoss entered not valid.";
            }
            // `proceedsAuthor`, --> [15]
            $proceedsAuthor = trim($values[15]);
            $proceedsAuthor = trim($proceedsAuthor, "-");
            $proceedsAuthor = trim($proceedsAuthor, "-");
            if($proceedsAuthor == ""){
                // pass
            }else if(notDecimal($proceedsAuthor)){
                return "Error: proceedsAuthor entered not valid.";
            }
            // `formatNote`,  --> [16]
            $formatNote = trim($values[16]);
            if(stringText($formatNote)){
                return "Error: formatNote entered not valid.";
            }
        }
        // [DONE] `book_subject`,
        if($table == "book_subject"){
            // `bookID`,
            $bookID = trim($values[0]);
            if(integerNotEmpty0($bookID)){
                return "Error: bookID entered not valid.";
            }
            // `subjectID`,
            $subjectID = trim($values[1]);
            if(alphabeticID($subjectID)){
                return "Error: subjectID entered not valid.";
            }
            // `bookSubjectNote`
            $bookSubjectNote = trim($values[2]);
            if(stringText($bookSubjectNote)){
                return "Error: bookSubjectNote entered not valid.";
            }
        }
        // [DONE] `book_type`,
        if($table == "book_type"){
            // `bookID`,
            $bookID = trim($values[0]);
            if(integerNotEmpty0($bookID)){
                return "Error: bookID entered not valid.";
            }
            // `typeID`,
            $typeID = trim($values[1]);
            if(alphabeticID($typeID)){
                return "Error: typeID entered not valid.";
            }
            // `bookTypeNote`
            $bookTypeNote = trim($values[2]);
            if(stringText($bookTypeNote)){
                return "Error: bookTypeNote entered not valid.";
            }
        }
        // [DONE] `edition`,
        if($table == "edition"){
            // `editionString`,
            $editionString = trim($values[1]);
            if(stringText($editionString)){
                return "Error: editionString entered not valid.";
            }
        }
        // [DONE] `editor`,
        if($table == "editor"){
            // `namedPersonID`, --> $values[0]
            $namedPersonID = trim($values[0]);
            if(integerNotEmpty0($namedPersonID)){
                return "Error: namedPersonID entered not valid.";
            }
            // bookID
            $bookID = trim($values[1]);
            if(integerNotEmpty0($bookID)){
                return "Error: bookID entered not valid.";
            }
        }
        // [DONE] `format`,
        if($table == "format"){
            // `formatName` --> $values[0]
            $formatName = trim($values[0]);
            if(stringNotEmpty255($formatName)){
                return "Error: formatName entered not valid.";
            }
        }
        // [DONE] `named_person`,
        if($table == "named_person"){
            // `fname`,
            $fname = trim($values[0]);
            if(string255($fname)){
                return "Error: fname entered not valid.";
            }
            // `lname`,
            $lname = trim($values[1]);
            if(string255($lname)){
                return "Error: lname entered not valid.";
            }
            // `nobilityTitle`,
            $nobilityTitle = trim($values[2]);
            if(string255($nobilityTitle)){
                return "Error: nobilityTitle entered not valid.";
            }
            // `lifeYears`,
            $lifeYears = trim($values[3]);
            if($lifeYears == ""){
                // pass
            }else if(integer0($lifeYears)){
                return "Error: lifeYears entered not valid.";
            }
            // `personNote`
            $personNote = trim($values[4]);
            if(stringText($personNote)){
                return "Error: personNote entered not valid.";
            }

            if($fname == "" && $lname == ""){
                return "Error: fname and lname cannot both be blank.";
            }
        }
        // [DONE] `publisher`,
        if($table == "publisher"){
            // `publisherName`,
            $publisherName = trim($values[0]);
            if(string255($publisherName)){
                return "Error: publisherName entered not valid.";
            }
            // `publisherLocation`
            $publisherLocation = trim($values[1]);
            if(string255($publisherLocation)){
                return "Error: publisherLocation entered not valid.";
            }

            if($publisherName == "" && $publisherLocation == ""){
                return "Error: publisherName and publisherLocation cannot both be blank.";
            }
        }
        // [DONE] `subject`,
        if($table == "subject"){
            // `subjectID`,
            $subjectID = trim($values[0]);
            if(alphabeticID($subjectID)){
                return "Error: subjectID entered not valid.";
            }
            // `subjectDescription`
            $subjectDescription = trim($values[1]);
            if($subjectDescription == "" || stringText($subjectDescription)){
                return "Error: subjectDescription entered not valid.";
            }
        }
        // [DONE] `title`,
        if($table == "title"){
            // `titleString`
            $titleString = trim($values[0]);
            if($titleString == "" || stringText($titleString)){
                return "Error: titleString entered not valid.";
            }
        }
        // [DONE] `translator`,
        if($table == "translator"){
            // `namedPersonID`, --> $values[0]
            $namedPersonID = trim($values[0]);
            if(integerNotEmpty0($namedPersonID)){
                return "Error: namedPersonID entered not valid.";
            }
            // `bookID` --> $values[1]
            $bookID = trim($values[1]);
            if(integerNotEmpty0($bookID)){
                return "Error: bookID entered not valid.";
            }
        }
        // [DONE] `type`
        if($table == "type"){
            // `typeID`,
            $typeID = trim($values[0]);
            if(alphabeticID($typeID)){
                return "Error: typeID entered not valid.";
            }
            // `typeDescription`
            $typeDescription = trim($values[0]);
            if($typeDescription == "" || stringText($typeDescription)){
                return "Error: typeDescription entered not valid.";
            }
        }
        // end of error checking
        return "Valid statement.";
    }

    // error checking compression
    function stringNotEmpty255($str){
        if ($str == "" || string255($str)){
            return true;
        }else{
            return false;
        }
    }

    function string255($str){
        if (strlen($str) > 255 || stringText($str)){
            return true;
        }else{
            return false;
        }
    }

    function stringText($str){
        if(sqlMetaChars($str) || sqlInjection($str) || sqlInjectionUnion($str) || sqlInjectionSelect($str) || sqlInjectionInsert($str) || sqlInjectionDelete($str) || sqlInjectionUpdate($str) || sqlInjectionDrop($str) || crossSiteScripting($str) || crossSiteScriptingImg($str)){
            return true;
        }else{
            return false;
        }
    }

    function integerNotEmpty0($str){
        if(!(integer(intval($str)) && intval($str) > 0) || $str == ""){
            return true;
        }else{
            return false;
        }
    }

    function integer0($str){
        if(!(integer(intval($str)) && intval($str) > 0)){
            return true;
        }else{
            return false;
        }
    }

    function notDecimal($str){
        if(!(decimal($str))){
            return true;
        }else{
            return false;
        }
    }

    function alphabeticID($str){
        if(!(alphabetic($str)) || $str == "" || strlen($str) > 3 || stringText($str)){
            return true;
        }else{
            return false;
        }
    }

// don't close php tag
