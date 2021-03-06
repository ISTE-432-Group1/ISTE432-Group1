<?php

class DB {

    private $dbh;

    function __construct(){
        try{
            $this->dbh = new PDO("mysql:host=localhost;dbname=ISTE432", "root", "root");
        }catch(PDOException $pe){
            echo $pe->getMessage();
            die("<br>Bad Database");
        } // catch
    } // constructor

    //**************** */ PUBLISHER START [DONE] //******************* */
    function publisherHandler($publisherField){
        // clean the field
        // foreach publisher, check if exists
            // if yes, return array of publisherIDs
            // if no, create publisherIDs
                // return array of publisherIDs

        $idsToReturn = array();

        // cleaning
        $publisherField = trim($publisherField);
        if($publisherField == "" || substr($publisherField, 0, 1) == "?"){
            return [ -1 ];
        }else{
            // more cleaning
            $publisherField = str_replace("\"", "", $publisherField);
            $publisherFieldArr = explode(";", $publisherField);
            foreach($publisherFieldArr as $publisherInfo){
                // var_dump($publisherInfo);
                $publisherInfo = trim($publisherInfo);
                $publisherInfoArr = explode(":", $publisherInfo);
                // trim replace trim -- more cleaning
                $publisherInfoArr[0] = trim(str_replace("?", "",trim($publisherInfoArr[0])));
                if(count($publisherInfoArr) < 2){
                    // check to see if location with null name exists
                    // if yes
                        // append to idsToReturn
                    // if no
                        // create publisher
                        // append to idsToReturn

                    $id = $this->getPublisherID($publisherInfoArr[0], null);
                    if ($id == -1){
                        $idsToReturn[] = $this->makePublisher($publisherInfoArr[0], null);
                    }else{
                        $idsToReturn[] = $id;
                    }
                }else{
                    $publisherInfoArr[1] = trim(str_replace("?", "", trim($publisherInfoArr[1])));
                    if($publisherInfoArr[1] == ""){
                        // check to see if location with null name exists
                        // if yes
                            // append to idsToReturn
                        // if no
                            // create publisher
                            // append to idsToReturn
                        $id = $this->getPublisherID($publisherInfoArr[0], null);
                        if ($id == -1){
                            $idsToReturn[] = $this->makePublisher($publisherInfoArr[0], null);
                        }else{
                            $idsToReturn[] = $id;
                        }
                    }else{
                        // check to see if location with name exists
                        // if yes
                            // append to idsToReturn
                        // if no
                            // create publisher
                            // append to idsToReturn
                        $id = $this->getPublisherID($publisherInfoArr[0], $publisherInfoArr[1]);
                        if ($id == -1){
                            $idsToReturn[] = $this->makePublisher($publisherInfoArr[0], $publisherInfoArr[1]);
                        }else{
                            $idsToReturn[] = $id;
                        }
                    }
                }
            }// foreach
        }

        return $idsToReturn;
    } // publisherHandle

    function getPublisherID($location, $name){
        $data = array();
        try{
            if($name == null){
                $stmt = $this->dbh->prepare("SELECT * FROM publisher WHERE placeOfPublication = :location AND publisherName is :name");
            }else{
                $stmt = $this->dbh->prepare("SELECT * FROM publisher WHERE placeOfPublication = :location AND publisherName = :name");
            }
            $stmt->bindParam(":location", $location, PDO::PARAM_STR);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
                
            }
            // var_dump($data);
            if(count($data)==0){
                return -1;
            }else{
                return $data[0];
            }
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }
    
    function makePublisher($location, $name){
        try{
            $stmt = $this->dbh->prepare("INSERT INTO publisher (placeOfPublication, publisherName) VALUES (:location, :name)");
            $stmt->bindParam(":location", $location, PDO::PARAM_STR);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    //**************** */ TITLE START [DONE] //******************* */
    function titleHandler($titleField){
        if($titleField == ""){
            return null;
        }
        // strip quotes
        $titleField = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $titleField);
        // echo "{$titleField}<br>";
        if($titleField == ""){
            return null;
        }
        return $titleField;
    }

    //**************** */ AUTHORSHIP START [DONE] //******************* */
    function authorshipHandler($authorshipField){
        switch($authorshipField){
            case "A":
            case "N":
            case "Y":
                return $authorshipField;
            default:
                return null;
        }
    }

    //**************** */ YEARNOTE START [DONE] //******************* */
    function yearNoteHandler($yearNoteField){
        // echo "{$yearNoteField}<br>";
        if (preg_match('/^[0-9]+$/', $yearNoteField)) {
            // echo "{$yearNoteField}<br>";
            return $yearNoteField;
        } else {
            // echo "BLANK OR INVALID<br>";
            return null;
        }
    }

    //**************** */ DESCRIPTOR START [DONE] //******************* */
    function descriptorHandler($descriptorField){
        if($descriptorField == ""){
            return null;
        }
        $descriptorField = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $descriptorField);
        // echo "{$descriptorField}<br>";
        if($descriptorField == ""){
            return null;
        }
        return $descriptorField;
    }

    //**************** */ NOTE START [DONE] //******************* */
    function noteHandler($noteField){
        if($noteField == ""){
            return null;
        }
        $noteField = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $noteField);
        if($noteField == ""){
            return null;
        }
        return $noteField;
    }

    //**************** */ AUTHOR START [DONE] //******************* */
    function authorHandler($authorField){
        /*
            Check for:
                - Blank
                - trim "
                - if contains [?,-\s] --> skip
                - Anonymous
                - " and " and ";" explodes
                -- loop
                    - [1] contains numbers --> skip
                    - [2] contains numbers --> age
                        - make sure to strip special character before grabbing numbers
                    - [2] !contains numbers --> title
        */
        $idsToReturn = array();
        $authorField = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $authorField);
        $authorField = trim($authorField);
        if($authorField == ""){
            return [ -1 ];
        }else{
            
            if(!strpos($authorField, '?')==false || !strpos($authorField, '- ')==false){
                return [ -1 ];
            }
            elseif(!strpos($authorField, " and ")==false){
                // explode on " and "
                $authorFieldArr = explode(" and ", $authorField);
            }elseif(!strpos($authorField, ";")==false){
                // explode on ";"
                $authorFieldArr = explode(";", $authorField);
            }else{
                $authorFieldArr[] = $authorField;
            }

            foreach($authorFieldArr as $authorInfo){
                $authorInfo = trim($authorInfo);
                // get age
                $age = null;
                $re = '/[0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9]/m';
                preg_match_all($re, $authorInfo, $matches, PREG_SET_ORDER, 0);
                if($matches != []){
                    $birth = substr($matches[0][0], 0, 4);
                    $death = substr($matches[0][0], 5, 4);
                    $age = $death-$birth;
                    // echo "birth: {$birth}; death: {$death}; age: {$age}<br>";
                    // remove age from string
                    $authorInfo = str_replace($matches[0][0], "", $authorInfo);
                    // clean string
                    $authorInfo = trim($authorInfo, " ");
                    $authorInfo = trim($authorInfo, ".");
                    $authorInfo = trim($authorInfo, " ");
                    $authorInfo = trim($authorInfo, ",");
                    $authorInfo = trim($authorInfo, " ");
                    $authorInfo = trim($authorInfo, ",");
                    $authorInfo = trim($authorInfo, " ");
                    if($authorInfo == ""){
                        return [ -1 ];
                    }
                }
                // store author and all related data
                $id = $this->getAuthorID($authorInfo, $age);
                if ($id == -1){
                    $idsToReturn[] = $this->makeAuthor($authorInfo, $age);
                }else{
                    $idsToReturn[] = $id;
                }
            } // foreach
        }
        return $idsToReturn;
    }

    function getAuthorID($authorInfo, $age){
        // var_dump($authorInfo);
        // var_dump($age);
        $data = array();
        try{
            if($age == null){
                $age = 0;
            }
            $stmt = $this->dbh->prepare("SELECT * FROM author WHERE name = :authorInfo AND lifeYears = :age");
            $stmt->bindParam(":authorInfo", $authorInfo, PDO::PARAM_STR);
            $stmt->bindParam(":age", intval($age), PDO::PARAM_INT);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            // var_dump($data);
            if(count($data)==0){
                return -1;
            }else{
                return $data[0];
            }
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    function makeAuthor($authorInfo, $age){
        try{
            $stmt = $this->dbh->prepare("INSERT INTO author (name, lifeYears) VALUES (:authorInfo, :age)");
            $stmt->bindParam(":authorInfo", $authorInfo, PDO::PARAM_STR);
            $stmt->bindParam(":age", intval($age), PDO::PARAM_INT);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    //**************** */ NAMED PERSON START [DONE] //******************* */
    function namedPersonsHandler($namedPersonsField){
        $namedPersonsField = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $namedPersonsField);
        if($namedPersonsField == ""){
            return null;
        }
        /*
            - explode on ";" 
            - foreach
                - trim, remove ?, trim
                - starts with "added " --> grab last two words as name
                - starts with "EeDd. "
                - starts with "EeDd "
                - starts with "by "
                - toLowerCase starts with "edited by "
                - Starts with "TtRrAaNnSs. "
                - Starts with "TtRrAaNnSs "
                - Starts with "TtRr. "
                - Starts with "TtRr "
                - else
                    - add to notes

        */
        $namedPersonsIDsToReturn = array();
        $notesToReturn = array();
        $namedPersonsField = explode(";", $namedPersonsField);
        foreach($namedPersonsField as $namedPersonsInfo){
            $role = null;
            $namedPersonsInfo = trim($namedPersonsInfo);
            $namedPersonsInfo = trim($namedPersonsInfo, "?");
            $namedPersonsInfo = trim($namedPersonsInfo);
            if(substr(strtolower($namedPersonsInfo), 0, 6) == "added "){
                $namedPersonsInfo = str_replace(substr($namedPersonsInfo, 0, 6), "", $namedPersonsInfo);
            }elseif(substr(strtolower($namedPersonsInfo), 0, 4) == "ed. "){
                $namedPersonsInfo = str_replace(substr($namedPersonsInfo, 0, 4), "", $namedPersonsInfo);
            }elseif(substr(strtolower($namedPersonsInfo), 0, 3) == "ed "){
                $namedPersonsInfo = str_replace(substr($namedPersonsInfo, 0, 3), "", $namedPersonsInfo);
            }elseif(substr(strtolower($namedPersonsInfo), 0, 3) == "by "){
                $namedPersonsInfo = str_replace(substr($namedPersonsInfo, 0, 3), "", $namedPersonsInfo);
            }elseif(substr(strtolower($namedPersonsInfo), 0, 10) == "edited by "){
                $namedPersonsInfo = str_replace(substr($namedPersonsInfo, 0, 10), "", $namedPersonsInfo);
            }elseif(substr(strtolower($namedPersonsInfo), 0, 7) == "trans. "){
                // do nothing
            }elseif(substr(strtolower($namedPersonsInfo), 0, 6) == "trans "){
                // do nothing
            }elseif(substr(strtolower($namedPersonsInfo), 0, 4) == "tr. "){
                // do nothing
            }elseif(substr(strtolower($namedPersonsInfo), 0, 3) == "tr "){
                // do nothing
            }else{
                $notesToReturn[] = $namedPersonsInfo;
                continue;
            } 

            // lifeYears section
            $age = null;
            $re = '/[0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9]/m';
            preg_match_all($re, $namedPersonsInfo, $matches, PREG_SET_ORDER, 0);
            if($matches != []){
                $birth = substr($matches[0][0], 0, 4);
                $death = substr($matches[0][0], 5, 4);
                $age = $death-$birth;
                // echo "birth: {$birth}; death: {$death}; age: {$age}<br>";
                // remove age from string
                $namedPersonsInfo = str_replace($matches[0][0], "", $namedPersonsInfo);
                // clean string
                $namedPersonsInfo = trim($namedPersonsInfo, " ");
                $namedPersonsInfo = trim($namedPersonsInfo, ".");
                $namedPersonsInfo = trim($namedPersonsInfo, " ");
                $namedPersonsInfo = trim($namedPersonsInfo, ",");
                $namedPersonsInfo = trim($namedPersonsInfo, " ");
                $namedPersonsInfo = trim($namedPersonsInfo, ",");
                $namedPersonsInfo = trim($namedPersonsInfo, " ");
                if($namedPersonsInfo == ""){
                    return [ -1 ];
                }
            }
            
            // store namedPersonsInfo and all related data
            $id = $this->getNamedPersonID($namedPersonsInfo, $role, $age);
            if ($id == -1){
                $namedPersonsIDsToReturn[] = $this->makeNamedPerson($namedPersonsInfo, $role, $age);
            }else{
                $namedPersonsIDsToReturn[] = $id;
            }
        } // foreach

        return [$namedPersonsIDsToReturn,$notesToReturn];
    }

    function getNamedPersonID($namedPersonsInfo, $role, $age){
        $data = array();
        try{
            if($age == null){
                $age = 0;
            }

            if($role == null){
                $stmt = $this->dbh->prepare("SELECT * FROM NAMED_PERSON WHERE name = :namedPersonsInfo AND lifeYears = :age AND role is :role");
            }else{
                $stmt = $this->dbh->prepare("SELECT * FROM NAMED_PERSON WHERE name = :namedPersonsInfo AND lifeYears = :age AND role = :role");
            }
            $stmt->bindParam(":namedPersonsInfo", $namedPersonsInfo, PDO::PARAM_STR);
            $stmt->bindParam(":age", intval($age), PDO::PARAM_INT);
            $stmt->bindParam(":role", $role, PDO::PARAM_STR);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            // var_dump($data);
            if(count($data)==0){
                return -1;
            }else{
                return $data[0];
            }
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    function makeNamedPerson($namedPersonsInfo, $role, $age){
        try{
            $stmt = $this->dbh->prepare("INSERT INTO NAMED_PERSON (name, role, lifeYears) VALUES (:namedPersonsInfo, :role, :age)");
            $stmt->bindParam(":namedPersonsInfo", $namedPersonsInfo, PDO::PARAM_STR);
            $stmt->bindParam(":role", $role, PDO::PARAM_STR);
            $stmt->bindParam(":age", intval($age), PDO::PARAM_INT);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    //**************** */ BOOK START [DONE] //******************* */
    function bookHandler($title, $authorship, $yearNote, $descriptor, $note, $extraNotes){
        // remove all except first return when running this for the first time
        $id = $this->getBook($title);
        if ($id == -1){
            return $this->makeBook($title, $authorship, $yearNote, $descriptor, $note, $extraNotes);
        }else{
            return $id;
        }
    }

    function getBook($title){
        $data = array();
        try{
            
            $stmt = $this->dbh->prepare("SELECT * FROM BOOK WHERE title = :title");
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            // var_dump($data);
            if(count($data)==0){
                return -1;
            }else{
                return $data[0];
            }
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }

    }

    function makeBook($title, $authorship, $yearNote, $descriptor, $note, $extraNotes){
        if($extraNotes != null){
            foreach($extraNotes as $extraNote){
                $note .= ";; ".$extraNote;
            }
        }
        try{
            $stmt = $this->dbh->prepare("INSERT INTO BOOK (title, authorship, yearNote, descriptor, note) VALUES (:title, :authorship, :yearNote, :descriptor, :note)");
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":authorship", $authorship, PDO::PARAM_STR);
            $stmt->bindParam(":yearNote", intval($yearNote), PDO::PARAM_INT);
            $stmt->bindParam(":descriptor", $descriptor, PDO::PARAM_STR);
            $stmt->bindParam(":note", $note, PDO::PARAM_STR);
            $stmt->execute();
            return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    //**************** */ BOOK_PUBLISHER START [DONE] //******************* */
    function bookPublisherHandler($bookID, $publisherIDs){
        // check if array[0] == -1
            // if yes, do nothing
        // else
            // foreach array as index
                // insert($bookID, index['publisherID'])

        if($publisherIDs[0] == -1){
            // do nothing
        }else{
            foreach($publisherIDs as $publisher){
                $this->makeBookPublisher($bookID, $publisher['publisherID']);
            }
        }
    }

    function makeBookPublisher($bookID, $publisherID){
        try{
            $stmt = $this->dbh->prepare("INSERT INTO BOOK_PUBLISHER (bookID, publisherID) VALUES (:bookID, :publisherID)");
            $stmt->bindParam(":bookID", intval($bookID), PDO::PARAM_INT);
            $stmt->bindParam(":publisherID", intval($publisherID), PDO::PARAM_INT);
            $stmt->execute();
            // return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    //**************** */ BOOK_AUTHOR START [DONE] //******************* */
    function bookAuthorHandler($bookID, $authorIDs){
        // check if array[0] == -1
            // if yes, do nothing
        // else
            // foreach array as index
                // insert($bookID, index['authorID'])

        if($authorIDs[0] == -1){
            // do nothing
        }else{
            foreach($authorIDs as $author){
                $this->makeBookAuthor($bookID, $author['authorID']);
            }
        }
    }

    function makeBookAuthor($bookID, $authorID){
        try{
            $stmt = $this->dbh->prepare("INSERT INTO BOOK_AUTHOR (bookID, authorID) VALUES (:bookID, :authorID)");
            $stmt->bindParam(":bookID", intval($bookID), PDO::PARAM_INT);
            $stmt->bindParam(":authorID", intval($authorID), PDO::PARAM_INT);
            $stmt->execute();
            // return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    //**************** */ BOOK_NAMED_PERSON START [DONE] //******************* */
    function bookNamedPersonsHandler($bookID, $namedPersonIDs){
        // check if array[0] == -1
            // if yes, do nothing
        // else
            // foreach array as index
                // insert($bookID, index['authorID'])

        if($namedPersonIDs == null){
            // do nothing
        }elseif(count($namedPersonIDs[0]) == 0){
            // do nothing
        }else{
            foreach($namedPersonIDs[0] as $namedPerson){
                $this->makeBookNamedPerson($bookID, $namedPerson['namedPersonID']);
            }
        }
    }

    function makeBookNamedPerson($bookID, $namedPersonID){
        try{
            $stmt = $this->dbh->prepare("INSERT INTO BOOK_NAMED_PERSON (bookID, namedPersonID) VALUES (:bookID, :namedPersonID)");
            $stmt->bindParam(":bookID", intval($bookID), PDO::PARAM_INT);
            $stmt->bindParam(":namedPersonID", intval($namedPersonID), PDO::PARAM_INT);
            $stmt->execute();
            // return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    //**************** */ BOOK_TYPE START [DONE] //******************* */
    function makeBookType($bookID, $typeID){
        $typeID = trim($typeID);
        $typeID = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $typeID);
        $typeID = trim($typeID);
        if($typeID == "unknown" || $typeID == ""){
            return -1;
        }
        try{
            $stmt = $this->dbh->prepare("INSERT INTO BOOK_TYPE (bookID, typeID) VALUES (:bookID, :typeID)");
            $stmt->bindParam(":bookID", intval($bookID), PDO::PARAM_INT);
            $stmt->bindParam(":typeID", $typeID, PDO::PARAM_STR);
            $stmt->execute();
            // return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }

    //**************** */ BOOK_SUBJECT START [WIP] //******************* */
    function makeBookSubject($bookID, $subjectID){
        $subjectID = trim($subjectID);
        $subjectID = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $subjectID);
        $subjectID = trim($subjectID);
        $subjectID = str_replace("?", "", $subjectID);

        if($subjectID == "unknown" || $subjectID == ""){
            return -1;
        }
        try{
            $stmt = $this->dbh->prepare("INSERT INTO BOOK_SUBJECT (bookID, subjectID) VALUES (:bookID, :subjectID)");
            $stmt->bindParam(":bookID", intval($bookID), PDO::PARAM_INT);
            $stmt->bindParam(":subjectID", $subjectID, PDO::PARAM_STR);
            $stmt->execute();
            // return $this->dbh->lastInsertId();
        }catch(PDOException $pe){
            echo $pe->getMessage();
            return -1;
        }
    }


} // class