# Milestone 2: Design and Design Patterns


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

Javascript - frontend

MySQLi - database


## 


## Design Patterns

**Factory method pattern - for the creation of different accounts**

* Factory or factory method pattern is ideal for making new objects without exposing the object code to the user. When you have several different class types that can extend an abstract parent class, the factory method pattern is the pattern you want. Using the factory method pattern to handle the logic of account creation is a good idea because accounts are tied to security - different types of accounts have different privilege levels, granting them access to different functions. Exposing the underlying code for accounts and their creation to the end user would be a liability, so keeping it concealed and using the factory method pattern to create new accounts is more secure than just instantiating a new account object.

**Builder pattern - for building the front end depending on the privilege level of the user**

* The builder pattern is used for the construction of modular things, using components. If you have a front end made up of components and that front end differs in appearance depending on who is accessing it, the builder pattern is a great choice. In our system, different types of accounts have different permissions, i.e., editors can make changes to content, but contributors and guests cannot. So if you have a button on your UI for editing content, you want that only to be loaded if the user has signed in on an account with at least editor level permissions. If the signed-in account doesn’t have permission to edit, the button shouldn’t exist for them, and the code for that button shouldn’t even be exposed to them. The builder pattern is good for constructing front ends with the right components for the right user, and keeping it secure so the user can’t see the code for components they don’t have access to.

**Memento pattern - for keeping track of the states of our DAOs**

* The Memento pattern saves the state of an object. The point of the memento pattern’s capturing of the object’s internal state is so that you can restore the object to that state later in time. When working with databases, saving the state of the DB or of individual tables is usually a feature built into the dbms, and if something goes wrong or for any other reason you can restore a table to that saved state. This is ideal for the database itself, but there are times when you’ll want to save the state of a data access object before it’s written to our database. What if the user signs out while working on something? What if the user just closes the app without saving anything? Automatically writing to the database itself in these scenarios could pose risks to the data, but saving the state of work would be a great way to ensure they don’t lose too much of it, and could continue working on it when they come back without having to redo all of their work.


## Entity Relationship Diagram


![ER Diagram](https://github.com/ISTE-432-Group1/ISTE432-Group1/blob/main/ERDiagramMilestone2.png?raw=true)


## Timeline

Jan 28, 2022 - Milestone 1 Requirements

Feb 18, 2022 - Milestone 2 Design

Feb 25, 2022 - Milestone 3 Layering

Mar 11, 2022 - Milestone 4 Exception Handling

Mar 25, 2022 - Milestone 5 Refactoring

Apr 8, 2022 - Milestone 6 Testing

Apr 15, 2022 - Milestone 7 Packaging

Apr 22, 2022 - Milestone 8 Finalized Project Code
