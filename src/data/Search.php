<?php
require_once('./PDO.DB.class.php');
require_once('./UIElementConstructor.class.php');

$dbh = new DB();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Search</title>
</head>

<body>
    <?php 
        //var_dump($_GET);
    ?>
    <a href="./login.php">Login</a>
    <h1>Search a Book!</h1>
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

        // function searchDisplay($output) {
        //     foreach($output as $book) {
        //         echo "
        //             <p><span style='font-weight: bold'>Edition:<span/> {$book['editionstring']}</p>
        //             <p><span style='font-weight: bold'>Title:<span> {$book['titlestring']}</p>
        //             <p><span style='font-weight: bold'>Author:<span> {$book['fname']} {$book['lname']}</p>
        //             <p><span style='font-weight: bold'>Publisher:<span> {$book['publisherName']}</p>
        //             <p><span style='font-weight: bold'>Subject:<span> {$book['subjectDescription']}</p>
        //             <p><span style='font-weight: bold'>Type:<span> {$book['typeDescription']}</p>
        //         ";
        //     }
        // }
        if(isset($_GET['attribute'])){
            
            switch($_GET['attribute']){
                //------------------------------ DEFAULT -----------------------------------
                case "Default":
                    // search
                    //var_dump($dbh->searchDefault());
                    // display
                    $output = $dbh->searchDefault();
                    $results = count($output);
                    echo "<h2>Your search resulted in {$results} result(s).</h2><hr>";
                    foreach($output as $book) {
                        echo "
                            <p><span style='font-weight: bold'>ID:</span> {$book['bookid']}</p>
                            <p><span style='font-weight: bold'>Edition:</span> {$book['editionstring']}</p>
                            <p><span style='font-weight: bold'>Title:</span> {$book['titlestring']}</p>
                            <p><span style='font-weight: bold'>Author:</span> {$book['fname']} {$book['lname']}</p>
                            <p><span style='font-weight: bold'>Publisher:</span> {$book['publisherName']}</p>
                            <p><span style='font-weight: bold'>Subject:</span> {$book['subjectDescription']}</p>
                            <p><span style='font-weight: bold'>Type:</span> {$book['typeDescription']}</p>
                            <hr>

                        ";
                    }

                    // validation
                    // if ($_GET['attribute'] == "Default" || $_GET['searchText'] == "") {
                    //     echo "Please enter a search specification.";
                    // } else {
                    //     //used buildTable instead of interactive because there is no table query
                    //     //should we make a new table function for the search?
                    //     $select = $dbh->searchDefault();
                    //     $elementConstructor->buildTable($select);
                    //     var_dump($dbh->searchDefault());
                    // }
                    
                    break;
                //------------------------------ EDITION -----------------------------------
                case "edition":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    //var_dump($dbh->searchFlex("edition.editionstring", $searchText));
                    // display
                    $output = $dbh->searchFlex("edition.editionstring", $searchText);
                    $results = count($output);
                    echo "<h2>Your search resulted in {$results} result(s).</h2><hr>";
                    foreach($output as $book) {
                        echo "
                            <p><span style='font-weight: bold'>Edition:</span> {$book['editionstring']}</p>
                            <p><span style='font-weight: bold'>Title:</span> {$book['titlestring']}</p>
                            <p><span style='font-weight: bold'>Author:</span> {$book['fname']} {$book['lname']}</p>
                            <p><span style='font-weight: bold'>Publisher:</span> {$book['publisherName']}</p>
                            <p><span style='font-weight: bold'>Subject:</span> {$book['subjectDescription']}</p>
                            <p><span style='font-weight: bold'>Type:</span> {$book['typeDescription']}</p>
                            <hr>

                        ";
                    }
                    break;
                //------------------------------ TITLE -----------------------------------
                case "title":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    //var_dump($dbh->searchFlex("title.titlestring", $searchText));
                    // display
                    $output = $dbh->searchFlex("title.titlestring", $searchText);
                    $results = count($output);
                    echo "<h2>Your search resulted in {$results} result(s).</h2><hr>";
                    foreach($output as $book) {
                        echo "
                            <p><span style='font-weight: bold'>Title:</span> {$book['titlestring']}</p>
                            <p><span style='font-weight: bold'>Edition:</span> {$book['editionstring']}</p>
                            <p><span style='font-weight: bold'>Author:</span> {$book['fname']} {$book['lname']}</p>
                            <p><span style='font-weight: bold'>Publisher:</span> {$book['publisherName']}</p>
                            <p><span style='font-weight: bold'>Subject:</span> {$book['subjectDescription']}</p>
                            <p><span style='font-weight: bold'>Type:</span> {$book['typeDescription']}</p>
                            <hr>
                        ";
                    }
                    break;
                //------------------------------ FNAME -----------------------------------
                case "fname":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    //var_dump($dbh->searchFlex("named_person.fname", $searchText));
                    // display
                    $output = $dbh->searchFlex("named_person.fname", $searchText);
                    $results = count($output);
                    echo "<h2>Your search resulted in {$results} result(s).</h2><hr>";
                    foreach($output as $book) {
                        echo "
                            <p><span style='font-weight: bold'>Author:</span> {$book['fname']} {$book['lname']}</p>
                            <p><span style='font-weight: bold'>Title:</span> {$book['titlestring']}</p>
                            <p><span style='font-weight: bold'>Edition:</span> {$book['editionstring']}</p>
                            <p><span style='font-weight: bold'>Publisher:</span> {$book['publisherName']}</p>
                            <p><span style='font-weight: bold'>Subject:</span> {$book['subjectDescription']}</p>
                            <p><span style='font-weight: bold'>Type:</span> {$book['typeDescription']}</p>
                            <hr>
                        ";
                    }
                    break;
                //------------------------------ LNAME -----------------------------------
                case "lname":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    //var_dump($dbh->searchFlex("named_person.lname", $searchText));
                    // display
                    $output = $dbh->searchFlex("named_person.lname", $searchText);
                    $results = count($output);
                    echo "<h2>Your search resulted in {$results} result(s).</h2><hr>";
                    foreach($output as $book) {
                        echo "
                            <p><span style='font-weight: bold'>Author:</span> {$book['fname']} {$book['lname']}</p>
                            <p><span style='font-weight: bold'>Title:</span> {$book['titlestring']}</p>
                            <p><span style='font-weight: bold'>Edition:</span> {$book['editionstring']}</p>
                            <p><span style='font-weight: bold'>Publisher:</span> {$book['publisherName']}</p>
                            <p><span style='font-weight: bold'>Subject:</span> {$book['subjectDescription']}</p>
                            <p><span style='font-weight: bold'>Type:</span> {$book['typeDescription']}</p>
                            <hr>
                        ";
                    }
                    break;
                //------------------------------ PUBLISHER -----------------------------------
                case "publisher":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    //var_dump($dbh->searchFlex("publisher.publisherName", $searchText));
                    // display
                    $output = $dbh->searchFlex("publisher.publisherName", $searchText);
                    $results = count($output);
                    echo "<h2>Your search resulted in {$results} result(s).</h2><hr>";
                    foreach($output as $book) {
                        echo "
                            <p><span style='font-weight: bold'>Publisher:</span> {$book['publisherName']}</p>
                            <p><span style='font-weight: bold'>Title:</span> {$book['titlestring']}</p>
                            <p><span style='font-weight: bold'>Edition:</span> {$book['editionstring']}</p>
                            <p><span style='font-weight: bold'>Author:</span> {$book['fname']} {$book['lname']}</p>
                            <p><span style='font-weight: bold'>Subject:</span> {$book['subjectDescription']}</p>
                            <p><span style='font-weight: bold'>Type:</span> {$book['typeDescription']}</p>
                            <hr>
                        ";
                    }
                    break;
                //------------------------------ SUBJECT -----------------------------------
                case "subject":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    //var_dump($dbh->searchFlex("subject.subjectDescription", $searchText));
                    // display
                    $output = $dbh->searchFlex("subject.subjectDescription", $searchText);
                    $results = count($output);
                    echo "<h2>Your search resulted in {$results} result(s).</h2><hr>";
                    foreach($output as $book) {
                        echo "
                            <p><span style='font-weight: bold'>Subject:</span> {$book['subjectDescription']}</p>
                            <p><span style='font-weight: bold'>Title:</span> {$book['titlestring']}</p>
                            <p><span style='font-weight: bold'>Edition:</span> {$book['editionstring']}</p>
                            <p><span style='font-weight: bold'>Author:</span> {$book['fname']} {$book['lname']}</p>
                            <p><span style='font-weight: bold'>Publisher:</span> {$book['publisherName']}</p>
                            <p><span style='font-weight: bold'>Type:</span> {$book['typeDescription']}</p>
                            <hr>
                        ";
                    }
                    break;
                //------------------------------ TYPE -----------------------------------
                case "type":
                    // validate searchText
                    $searchText = $_GET['searchText'];
                    // search
                    //var_dump($dbh->searchFlex("type.typeDescription", $searchText));
                    // display
                    $output = $dbh->searchFlex("type.typeDescription", $searchText);
                    $results = count($output);
                    echo "<h2>Your search resulted in {$results} result(s).</h2><hr>";
                    foreach($output as $book) {
                        echo "
                            <p><span style='font-weight: bold'>Type:</span> {$book['typeDescription']}</p>
                            <p><span style='font-weight: bold'>Title:</span> {$book['titlestring']}</p>
                            <p><span style='font-weight: bold'>Edition:</span> {$book['editionstring']}</p>
                            <p><span style='font-weight: bold'>Author:</span> {$book['fname']} {$book['lname']}</p>
                            <p><span style='font-weight: bold'>Publisher:</span> {$book['publisherName']}</p>
                            <p><span style='font-weight: bold'>Subject:</span> {$book['subjectDescription']}</p>
                            <hr>
                        ";
                    }
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