# ISTE.432 - Group 1 - Victorian Autobiography Information Network

### Summary
The Victorian Autobiography Information Network in its current form allows for users, authors, historians, professors, and other researchers to catalog historical text. Basic users are able to search the archive, while authenticated users are able to categorize new titles and their genres.

Authenticated users are able to insert a book’s “type of life writing,” “authorship,” “subject of life writing,” “author,” “title,” “publisher,” “year of publication,” and a long list of quantifiers  into the database. These column names are listed in a provided data dictionary.

The information we have on the state of VAIN is provided to us through Professor Mick McQuaid for an assignment. We were also provided with the data currently stored.

The current system’s third column does not allow for multiple “subject[s] of life writing” to be selected. For example, if a book featured an adventurous criminal, it could only be labeled with one of those categories. The fourth column in the existing table doesn’t seem to include any form of uniquely identifying/labeling authors. All that is included is first name, last name, and their age. The data dictionary explicitly tells us that column six has numerous records that contain unvalidated data. Column seven doesn’t include a consistently applied method for labeling books that don’t have a clear publication date. Columns eight and nine are “a mass of notes” (according to the data dictionary), and contain data with unvalidated/mixed data types. On top of all these issues with VAIN’s present state, it is not normalized, and contains a large amount of repeated records.


### Goals
Our group plans to build out a new database that is normalized and only logs validated data. To accomplish this, we will make the following changes:
* Cleaning existing data by trimming values and removing repeated records.
* Establish a normalized database with new tables, rather than cramming everything into columns of a single table
* Add four distinct roles to users: public (unauthorized), authorized: contributor, editor, administrator
  * Public - can browse, search, and execute canned queries
  * Contributor - proposes contributions
  * Editor - approves contributions
  * Administrator - controls accounts and manipulates database via DDL
* Accounts actions
  * Administrator - creates editor and administrator accounts 
  * Contributor - any role can become contributor 
  * ONLY administrator can create admin and contributor accounts, admin can also be editor


### Stakeholders
* Users
  * Users will want to browse and search through the database of books.
* Authors
  * Authors will want to be able to create entries and also look at other books in the database.
* Researchers, historians, and professors
  * Researchers will want to add entries as well as browse and search for books that will help them in their work.
  * Researchers and historians will also want to fact check other researchers and delete entries if needed.
  * Historians will want to primarily search and browse for books and maybe add their own entries as well.
  * Professors will want their students to search and browse books for their school work. 


### Scope
The scope of the project involves the cataloging of books based on type, authorship, subject, author, title, publisher, year, descriptor, named persons, notes, and where it is located. Being able to locate a book based on any of these search queries will be available to do by all types of users. Anyone can become a contributor and add contributions and then editors will be the only ones who are able to approve those contributions. Editors will also be able to edit and delete books. Administrators can also be editors and are the only ones who can create other editors as well as other administrators. Administrators also control accounts and DDL statements.


### Input
* Information on books (type, authorship, subject, author, title, publisher, year, descriptor, named persons, notes and location) can queried by the users to catalog/search books
* Partial user input will be received as users select their account type and act according to their role.
  * Editor: approve contributions
  * Contributor: propose contributions
  * Administrator: add/delete accounts and control their statuses
* Public - partial user input will be received as users enter search queries
* The public will be able to browse and view entries, but they will not be able to edit or delete entries, and they will not be able to create new entries. Even anonymous viewers without an account have this privilege level.
* Contributors can propose contributions, these can be new entries or changes to existing entries, but they can not edit entries. Anyone can make a contributor account in the system.
* Editors have the authority to actually add new entries, edit existing entries, and delete existing entries. Not just anyone can make an account with editor privilege, and administrator is needed for this. 
* Administrators can create and control accounts and their privileges. They have the highest privilege level, giving them the same abilities as all privilege levels below them (editor, contributor, public).


### Processing
* Searches will be run on partial user input (while entering or searching for a book, author, title, etc…) to provide examples of existing information in the database that fits the partial queries 
* Propositions provided by contributors will be cleaned and checked against existing entries to ensure consistency and avoid duplication
* Searches will be run based on the following parameters: type, authorship, subject, author, title, publisher, year, descriptor, named persons, notes and location.
* Searches will be run based on partial user input (i.e. search by a single field, pull up all results by a an author whose name starts with a letter or contains letters)
* All data will be sanitized and validated on entry to the database. 
* All contributions will be checked against existing data, to ensure that it’s valid and to avoid duplicate entries.


### Output
* Public: Users will be able to view the entries and search via a search function.
* Editors: Editors will be able to see entries and pending contributions as well. List of proposed contributions to be accepted into the catalog or declined.
* Contributors: Contributors will also be able to search, and will be able to see pending contributions in the system. Can view a searchable collection of books.
* Administrators: Admins can see all of the above in addition to accounts and account settings. Can see a searchable collection of cataloged books. 
