<?php

require_once "./PDO.DB.class.php";

$db = new DB();

$handle = fopen("vain.tsv", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        $lineArr = explode("\t", $line);
        // skip first line
        if($lineArr[0] == "Type"){
            continue;
        }

        // publisher
        if(count($lineArr) < 6){
            continue;
        }else{
            $publisherIDs = $db->publisherHandler($lineArr[5]);
            // TODO: use this later
                // [0] = -1, it's got none
                // loop through indexes and add them to publisher_book table

            // print publisher info
            // var_dump($publisherIDs);
            // echo "<br>";
            // echo "<br>";
        }

        // title
        $title = $db->titleHandler($lineArr[4]);
        if ($title == ""){
            continue;
        }
        // print publisher info
        // var_dump($title);
        // echo "<br>";
        // echo "<br>";

        // authorship
        $authorship = $db->authorshipHandler($lineArr[1]);
        // var_dump($authorship);
        // echo "<br>";
        // echo "<br>";

        // yearNote
        $yearNote = $db->yearNoteHandler($lineArr[6]);
        // var_dump($yearNote);
        // echo "<br>";
        // echo "<br>";

        // descriptor
        $descriptor = $db->descriptorHandler($lineArr[7]);
        // var_dump($descriptor);
        // echo "<br>";
        // echo "<br>";

        // note
        $note = $db->noteHandler($lineArr[9]);
        // var_dump($note);
        // echo "<br>";
        // echo "<br>";

        // at this point we have everything we need to make a book
        // but first let's do what we did to publisher, but for author and named-person

        // author
        $authorIDs = $db->authorHandler($lineArr[3]);
        // var_dump($authorIDs);
        // echo "<br>";
        // echo "<br>";

        // named-person
            // [0] is an array with named-persons
            // [1] is an array with notes
        $namedPersonsIDs = $db->namedPersonsHandler($lineArr[8]);
        if($namedPersonsIDs == null){
            $extraNotes = null;
        }else{
            $extraNotes = $namedPersonsIDs[1];
        }
        // var_dump($namedPersonsIDs);
        // echo "<br>";
        // echo "<br>";

        // make book
        $book = $db->bookHandler($title, $authorship, $yearNote, $descriptor, $note, $extraNotes);
        if(is_array($book)){
            $bookID = $book['bookID'];
        }else{
            $bookID = $book;
        }
        var_dump($bookID);
        echo "<br>";
        // echo "<br>";

        // publisher_book lookup
        $db->bookPublisherHandler($bookID, $publisherIDs);

        // book_author lookup
        $db->bookAuthorHandler($bookID, $authorIDs);

        // book_named-person lookup
        $db->bookNamedPersonsHandler($bookID, $namedPersonsIDs);

        // book_type lookup
        $db->makeBookType($bookID, $lineArr[0]);

        // book_subject lookup
        $db->makeBookSubject($bookID, $lineArr[2]);

        // done!


        // echo "Authorship: {$authorship}; YearNote: {$yearNote}; Descriptor: {$descriptor}; Note: {$note}; Author: {$author[0]}<br><br>";


    }
    echo "made it here";
    fclose($handle);
} else {
    // error opening the file.
} 

?>