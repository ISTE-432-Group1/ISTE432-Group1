<?php
require_once('./PDO.DB.class.php');
require_once('./UIElementConstructor.class.php');

$dbh = new DB();
$elementConstructor = new UIElementConstructor();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>

<body>
    <?php 
        var_dump($_GET);
    ?>
    <form action="./Search.php" method="get">
        Attribute: <select name="attribute">
            <option value="Default">Default</option>
            <option value="edition">edition</option>
            <option value="title">title</option>
            <option value="fname">author's first name</option>
            <option value="lname">author's last name</option>
            <option value="publisher">publisher full name</option>
            <option value="subject">subject</option>
            <option value="type">type</option>
        </select>
        Search: <input type="text" name="searchText">
        <input type="submit" value="Submit">
    </form>
    <?php
        if(isset($_GET['attribute'])){
            switch($_GET['attribute']){
                case "Default":
                    // search
                    var_dump($dbh->searchDefault());
                    // display

                    // validation
                    if ($_GET['attribute'] = "Default" || $_GET['searchText'] = "") {
                        echo "Please enter a search specification.";
                    } else {
                        //used buildTable instead of interactive because there is no table query
                        //should we make a new table function for the search?
                        $select = $dbh->searchDefault();
                        $elementConstructor->buildTable($select);
                        var_dump($dbh->searchDefault());
                    }
                    break;
                case "edition":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    var_dump($dbh->searchFlex("edition.editionstring", $searchText));
                    // display
                    break;
                case "title":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    var_dump($dbh->searchFlex("title.titlestring", $searchText));
                    // display
                    break;
                case "fname":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    var_dump($dbh->searchFlex("named_person.fname", $searchText));
                    // display
                    break;
                case "lname":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    var_dump($dbh->searchFlex("named_person.lname", $searchText));
                    // display
                    break;
                case "publisher":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    var_dump($dbh->searchFlex("publisher.publisherName", $searchText));
                    // display
                    break;
                case "subject":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    var_dump($dbh->searchFlex("subject.subjectDescription", $searchText));
                    // display
                    break;
                case "type":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    var_dump($dbh->searchFlex("type.typeDescription", $searchText));
                    // display
                    break;
                default:
                    // display error message
                    echo "Not a valid search";
                    break;
            }
        }

    ?>
</body>

</html>