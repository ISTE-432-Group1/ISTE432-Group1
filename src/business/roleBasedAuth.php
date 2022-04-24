<?php

// This file helps determine what role people need to 
// read, write, update, and delete each of our DB tables

function determine($table) {

    switch($table) {
        case "agreement":
        case "type": return ['4' => "", '3' => "r", '2' => "r", '1' => "crud"];
        case "author": return ['4' => "", '3' => "", '2' => "cru", '1' => "crud"];
        case "book": return ['4' => "r", '3' => "ru", '2' => "cru", '1' => "crud"];
        case "book_edition":
        case "edition": return ['4' => "", '3' => "cru", '2' => "cru", '1' => "crud"];
        case "format":
        case "publisher":
        case "subject":
        case "title":
        case "translator":
        case "editor": return ['4' => "", '3' => "", '2' => "", '1' => "crud"];
        case "named_person": return ['4' => "", '3' => "ru", '2' => "ru", '1' => "crud"];
        case "book_type":
        case "book_subject": return ['4' => "", '3' => "ru", '2' => "cru", '1' => "crud"];
        default: return ['4' => "", '3' => "", '2' => "", '1' => "crud"];
    }
}

// don't close php tag as we're including this file