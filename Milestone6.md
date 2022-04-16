# Milestone 6: Testing

## Team Members and Roles

Team Lead - Darlene Ardila

Programmer - Aaron Putterman

Front-end designer - Ben Shapiro

DB Administrator - Michelle Bobilev


## Background

* What’s wrong with the current system?
    * The current system’s third column does not allow for multiple “subject[s] of life writing” to be selected. For example, if a book featured an adventurous criminal, it could only be labeled with one of those categories.
    * The fourth column in the existing table doesn’t seem to include any form of uniquely identifying/labeling authors. All that is included is first name, last name, and their age.
    * The data dictionary explicitly tells us that column six has numerous records that contain unvalidated data.
    * Column seven doesn’t include a consistently applied method for labeling books that don’t have a clear publication date.
    * Columns eight and nine are “a mass of notes” (according to the data dictionary), and contain data with unvalidated/mixed data types.
    * On top of all these issues with VAIN’s present state, it is not normalized, and contains a large amount of repeated records.

Summary:

The current system has multiple issues. First, the third column does not allow to select multiple “subject[s] of life writing” - this is an issue because if a book features an adventurous criminal, it would only be labeled with either adventurous or criminal, not both categories. Another issue is that there is no way to uniquely identify or label authors, as of right now they can only be labeled with first name, last name and age. This is an issue because two different authors with the same name and age would have to be added as one individual, not two different authors. Additionally, the data dictionary explicitly contains numerous records with unvalidated data in column six, column seven lacks a method or labeling books without a clear publication date, and column eight and nine are “a mass of notes” according to the data dictionary, containing data with unvalidated/mixed data types. On top of the aforementioned problems with the system, VAIN in its present state is not normalized and contains many repeated records. 


## Description

This project is on the Victorian Autobiography Information Network, the goal of which is to allow users, authors, historians, professors and other researchers to catalog historical text. It also allows the general public to search the archive while giving authenticated users to categorize new titles and their genres. 

The scope of the project involves the cataloging of books based on type, authorship, subject, author, title, publisher, year, descriptor, named persons, notes, and where it is located. Being able to locate a book based on any of these search queries will be available to do by all types of users. Anyone can become a contributor and add contributions and then editors will be the only ones who are able to approve those contributions. Editors will also be able to edit and delete books. Administrators can also be editors and are the only ones who can create other editors as well as other administrators. Administrators also control accounts and DDL statements.

The current system poses certain challenges which requires optimization of the database and both the front and back end. These challenges will be approached through the creation of a new normalized and data-validated database, distinct user roles and strict account actions.


## Goals



* Brief statement about what we want – A new database, normalized, with better data validation
    * Our group plans to build out a new database that is normalized and only logs validated data. To accomplish this, we will make the following changes:
* Changes we will make:
    * Clean existing data
        * trim values
        * remove repeated records
            * Cleaning existing data by trimming values and removing repeated records.
    * Establish a normalized database with new tables, rather than cramming everything into columns
    * Create a pertinent interface per role to use for role actions
* Add four distinct **roles** to users: public (unauthorized), authorized: contributor, editor, administrator
    * Public - can browse, search, and execute canned queries
    * Contributor - proposes contributions
    * Editor - approves contributions
    * Administrator - controls accounts and manipulates database via DDL
* **Accounts** actions
    * Administrator - creates editor and administrator accounts 
    * Contributor - any role can become contributor 
    * ONLY administrator can create admin and contributor accounts, admin can also be editor


## 


## Project Requirements



* Users without an account will be able to browse, search, and execute canned queries
* Any user can become a contributor and propose contributions but not publish their contributions
* Editors will review and decide whether a contribution with be published or not
* Administrators control accounts and DDL statements. They are the only users who can create another administrator or an editor. An administrator can also be an editor.


## 


## Business Rules 



*  All data contributed to the database will be cleaned and checked against existing entries to ensure consistency and avoid duplication. An example of where duplication could possibly occur would be two entries made by the same author, but the (single) author is not properly connected to both records.
* Searches will be run based on a number of parameters outlined in the first milestone document.
* All data will be sanitized and validated to prevent duplicate entries and searches that may contain SQL injections, scripts, or other malicious content. This will be done using JavaScript and PHP, and before anything is committed to the database.
* Public users will be able to view the entries and search via a search function. Their only chance for text input is when searching for any of the attributes connected to a record, but will be sanitized and validated before reaching the database.
* Anyone can be a contributor. These are a step up from the public view, but still have the same search functionality. Contributors can also add to the database new records, authors, books, etc. but none of it will be viewable until an Editor user has approved of it.
* Editors are the next step up from Contributors. They are accounts that must be made specifically by Admin users (see factory design pattern below). They can contribute in the same way Contributors can. Editors will also be able to search entries in the same manner public users can, and view/edit/submit pending contributions via a list of ready-to-publish content.
* Admin users are the highest level of users in the system. They can create any type of user, and have permissions to do anything a lower level user can perform.


## 


## Technology Used

PHP - backend

JavaScript - frontend

MySQL - database


## 


## Design Patterns

**Factory method pattern - for the creation of different accounts**

* Factory or factory method pattern is ideal for making new objects without exposing the object code to the user. When you have several different class types that can extend an abstract parent class, the factory method pattern is the pattern you want. Using the factory method pattern to handle the logic of account creation is a good idea because accounts are tied to security - different types of accounts have different privilege levels, granting them access to different functions. Exposing the underlying code for accounts and their creation to the end user would be a liability, so keeping it concealed and using the factory method pattern to create new accounts is more secure than just instantiating a new account object.

**Builder pattern - for building the front end depending on the privilege level of the user**

* The builder pattern is used for the construction of modular things, using components. If you have a front end made up of components and that front end differs in appearance depending on who is accessing it, the builder pattern is a great choice. In our system, different types of accounts have different permissions, i.e., editors can make changes to content, but contributors and guests cannot. So if you have a button on your UI for editing content, you want that only to be loaded if the user has signed in on an account with at least editor level permissions. If the signed-in account doesn’t have permission to edit, the button shouldn’t exist for them, and the code for that button shouldn’t even be exposed to them. The builder pattern is good for constructing front ends with the right components for the right user, and keeping it secure so the user can’t see the code for components they don’t have access to.

**Memento pattern - for keeping track of the states of our DAOs**

* The Memento pattern saves the state of an object. The point of the memento pattern’s capturing of the object’s internal state is so that you can restore the object to that state later in time. When working with databases, saving the state of the DB or of individual tables is usually a feature built into the dbms, and if something goes wrong or for any other reason you can restore a table to that saved state. This is ideal for the database itself, but there are times when you’ll want to save the state of a data access object before it’s written to our database. What if the user signs out while working on something? What if the user just closes the app without saving anything? Automatically writing to the database itself in these scenarios could pose risks to the data, but saving the state of work would be a great way to ensure they don’t lose too much of it, and could continue working on it when they come back without having to redo all of their work.

## Layering

**Data**
* This layer provides access to data hosted within the boundaries of the system, and data exposed by other networked systems; perhaps accessed through services. The data layer exposes generic interfaces that the components in the business layer can consume.
    * Classes:
        * PDO.DB.class.php

**Business**
* This layer implements the core functionality of the system, and encapsulates the relevant business logic. It generally consists of components, some of which may expose service interfaces that other callers can use. 
    * Classes:
        * Account.class.php
        * AdminAccount.class.php
        * EditorAccount.class.php
        * ContributorAccount.class.php
        * AccountFactory.php
        * Validator.php
        * Sanitizer.php
        * BusinessRuleCheck.php


**Presentation**
* This layer contains the user oriented functionality responsible for managing user interaction with the system, and generally consists of components that provide a common bridge into the core business logic encapsulated in the business layer.
    * Classes:
        * Form.js
        * Header.js
        * Footer.js
        * ContentDisplay.js
        * TableDisplay.js
        * Search.js
        * Builder.js
        * Memento.js
        * Style.css
        * Login.php
        * Profile.php
        * ManageAccount.php
        * Edit.php
        * Contribute.php
        * Index.php


## Exception Handling
Considering the layered architecture of our project, we have decided to place exception handling in our data layer. We will have a class called PDO.DB.class.php that will handle these exceptions as well as input validation and sanitization. The responsibility of handling and modifying the exceptions will be that of the database administrator. Some of the exceptions we are handling on our project currently are the following:
* Inserting into a table errors
* Locating tables to insert into errors
* Deleting table errors
* Selecting table errors
* Updating table errors
* Syntax errors
* Valid table errors
* Valid column errors
* Etc.


## Testing

For Milestone 4, we created and loaded the new database schema presented to us in class. We made some initial PHP code to help read and input data, with some minor validation. For Milestone 5, we added update and delete functionality, and refactored validation and sanitization code. We removed a lot of repeated lines of code, and opted to put them in reusable functions. We refactored some of the string concatenation functionality to be more organized. We abstracted some of the table building functionality to increase reusability. Finally, we created a new ER Diagram to match the new DB schema.

### Example: Error Checking

#### Old Code
```php
    // [DONE] `book_edition`,
    if($table == "book_edition"){
        // `bookID`, --> [0]
        $bookID = trim($values[0]);
        if(!(integer(intval($bookID)) && intval($bookID) > 0) || $bookID == ""){
            return "Error: bookID entered not valid.";
        }
        // `editionID`, --> [1]
        $editionID = trim($values[1]);
        if(!(integer(intval($editionID)) && intval($editionID) > 0) || $editionID == ""){
            return "Error: editionID entered not valid.";
        }
        // `publisherID`, --> [2]
        $publisherID = trim($values[2]);
        if(!(integer(intval($publisherID)) && intval($publisherID) > 0) || $publisherID == ""){
            return "Error: publisherID entered not valid.";
        }
        // `titleID`, --> [3]
        $titleID = trim($values[3]);
        if(!(integer(intval($titleID)) && intval($titleID) > 0) || $titleID == ""){
            return "Error: titleID entered not valid.";
        }
        // `formatID`, --> [4]
        $formatID = trim($values[3]);
        if(!(integer(intval($formatID)) && intval($formatID) > 0) || $formatID == ""){
            return "Error: formatID entered not valid.";
        }
        // `year`, --> [5]
        $year = trim($values[5]);
        if($year == ""){
            // pass
        }else if(!(integer(intval($year)) && intval($year) > 0)){
            return "Error: year entered not valid.";
        }
        // `numberPages`, --> [6]
        $numberPages = trim($values[6]);
        if($numberPages == ""){
            // pass
        }else if(!(integer(intval($numberPages)) && intval($numberPages) > 0)){
            return "Error: numberPages entered not valid.";
        }
        // `numberVolumes`, --> [7]
        $numberVolumes = trim($values[7]);
        if($numberVolumes == ""){
            // pass
        }else if(!(integer(intval($numberVolumes)) && intval($numberVolumes) > 0)){
            return "Error: numberVolumes entered not valid.";
        }
        // `agreementTypeID`, --> [8]
        $agreementTypeID = trim($values[8]);
        if(!(integer(intval($agreementTypeID)) && intval($agreementTypeID) > 0) || $agreementTypeID == ""){
            return "Error: agreementTypeID entered not valid.";
        }
        // `salePrice`, --> [9]
        $salePrice = trim($values[9]);
        $salePrice = trim($salePrice, "-");
        $salePrice = trim($salePrice, "-");
        if($salePrice == ""){
            // pass
        }else if(!(decimal($salePrice))){
            return "Error: salePrice entered not valid.";
        }
        // `paymentAgreedAmount`, --> [10]
        $paymentAgreedAmount = trim($values[10]);
        $paymentAgreedAmount = trim($paymentAgreedAmount, "-");
        $paymentAgreedAmount = trim($paymentAgreedAmount, "-");
        if($paymentAgreedAmount == ""){
            // pass
        }else if(!(decimal($paymentAgreedAmount))){
            return "Error: paymentAgreedAmount entered not valid.";
        }
        // `illustrationsPayment`, --> [11]
        $illustrationsPayment = trim($values[11]);
        $illustrationsPayment = trim($illustrationsPayment, "-");
        $illustrationsPayment = trim($illustrationsPayment, "-");
        if($illustrationsPayment == ""){
            // pass
        }else if(!(decimal($illustrationsPayment))){
            return "Error: illustrationsPayment entered not valid.";
        }
        // `copiesSold`, --> [12]
        $copiesSold = trim($values[12]);
        if($copiesSold == ""){
            // pass
        }else if(!(integer(intval($copiesSold)) && intval($copiesSold) > 0)){
            return "Error: copiesSold entered not valid.";
        }
        // `copiesRemaining`, --> [13]
        $copiesRemaining = trim($values[13]);
        if($copiesRemaining == ""){
            // pass
        }else if(!(integer(intval($copiesRemaining)) && intval($copiesRemaining) > 0)){
            return "Error: copiesRemaining entered not valid.";
        }
        // `profitLoss`, --> [14]
        $profitLoss = trim($values[14]);
        $profitLoss = trim($profitLoss, "-");
        $profitLoss = trim($profitLoss, "-");
        if($profitLoss == ""){
            // pass
        }else if(!(decimal($profitLoss))){
            return "Error: profitLoss entered not valid.";
        }
        // `proceedsAuthor`, --> [15]
        $proceedsAuthor = trim($values[15]);
        $proceedsAuthor = trim($proceedsAuthor, "-");
        $proceedsAuthor = trim($proceedsAuthor, "-");
        if($proceedsAuthor == ""){
            // pass
        }else if(!(decimal($proceedsAuthor))){
            return "Error: proceedsAuthor entered not valid.";
        }
        // `formatNote`,  --> [16]
        $formatNote = trim($values[16]);
        if(sqlMetaChars($formatNote) || sqlInjection($formatNote) || sqlInjectionUnion($formatNote) || sqlInjectionSelect($formatNote) || sqlInjectionInsert($formatNote) || sqlInjectionDelete($formatNote) || sqlInjectionUpdate($formatNote) || sqlInjectionDrop($formatNote) || crossSiteScripting($formatNote) || crossSiteScriptingImg($formatNote)){
            return "Error: formatNote entered not valid.";
        }
    }
```
#### Updated Code
```php
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
```

## Summary Report

```sql
+-----------+-----------------+------------+
| SubjectID | Subject         | # of Books |
+-----------+-----------------+------------+
| R         | Religious       |        218 |
| T         | Travel          |        200 |
| G         | Great Man       |        166 |
| L         | Literary Memoir |        138 |
| M         | Military        |         88 |
| H         | Historical      |         82 |
| MC        | Middle-class    |         72 |
| A         | Adventure       |         67 |
| D         | Domestic        |         49 |
| C         | Criminal        |         46 |
| SC        | Social Critique |         40 |
| S         | Satire          |         33 |
| CY        | Celebrity       |         23 |
| SD        | School Days     |          9 |
| P         | Politics        |          4 |
| TH        | Theatre         |          4 |
+-----------+-----------------+------------+
```

## Testing 

 PHPUnit is used for unit testing. 
 
Installation:
 - cd to directory where you want to run your tests from (i.e. tests)
 - run `wget -O phpunit https://phar.phpunit.de/phpunit-9.phar`
 - run `chmod +x phpunit`
  
Writing Tests:
 - Test functions must have names beginning with the word 'test'
 
To run tests:
 - cd to this directory (tests) on abp6318's Solace
 - run `php ./phpunit ./UnitTests.php`
  - '.' means a successful test
  - 'F' means a failed test
  - 'W' means a warning happened while trying to run test file

Test Functions

```php
public function testStringText(){
        $this->assertFalse(stringText("This is a string."));
        $this->assertFalse(stringText(""));
        $this->assertTrue(stringText("<script>alert('dangerous script oooo')</script>"));
    }

    public function testNotDecimal(){
        $this->assertTrue(notDecimal("10"));
        $this->assertFalse(notDecimal("10.0"));
    }
    
    public function testIntegerNotEmpty0(){
        $this->assertFalse(integerNotEmpty0("10"));
        $this->assertTrue(integerNotEmpty0(""));
        $this->assertTrue(integerNotEmpty0("0"));
        $this->assertTrue(integerNotEmpty0("test"));
    }
    
    public function testAlphabeticID(){
        $this->assertFalse(alphabeticID("abc"));
        $this->assertFalse(alphabeticID("de"));
        $this->assertTrue(alphabeticID("abcd"));
        $this->assertTrue(alphabeticID(""));
        $this->assertTrue(alphabeticID("123"));
    }
    
    public function testStringNotEmpty255(){
        $this->assertFalse(stringNotEmpty255("This is a string."));
        $this->assertTrue(stringNotEmpty255(""));
        $this->assertTrue(stringNotEmpty255("<script>alert('dangerous script oooo')</script>"));
        $this->assertTrue(stringNotEmpty255("01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789"));
    }
```

## Entity Relationship Diagram


![ER Diagram](https://github.com/ISTE-432-Group1/ISTE432-Group1/blob/main/VAIN%20ERD-1.png?raw=true)



## Timeline

~~Jan 28, 2022 - Milestone 1 Requirements~~

~~Feb 18, 2022 - Milestone 2 Design~~

~~Feb 27, 2022 - Milestone 3 Layering~~

~~Mar 25, 2022 - Milestone 4 Exception Handling~~

~~Apr 1, 2022 - Milestone 5 Refactoring~~

~~Apr 15, 2022 - Milestone 6 Testing~~

Apr 22, 2022 - Milestone 7 Packaging

Apr 22, 2022 - Milestone 8 Finalized Project Code



## New ERD Diagram

![ER Diagram](https://github.com/ISTE-432-Group1/ISTE432-Group1/blob/main/VAIN%20ERD-1.png?raw=true)

