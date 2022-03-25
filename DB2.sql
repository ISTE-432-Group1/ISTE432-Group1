-- CREATE DATABASE `ISTE432pt2` ;
-- USE `ISTE432pt2` ;


-- TODO:
-- [DONE]- Make tables
-- [DONE]- Set data types
-- [DONE]- Discern ??? types
-- [DONE]- Add auto increments
-- [DONE]- Add primary keys
-- [DONE]- Add foreign keys
        -- CONSTRAINT a_b_fk FOREIGN KEY (aattribute) REFERENCES b (battribute)
-- [DONE]- Reorganized order of tables loaded in
-- [DONE]- Create values listed at bottom
-- []- Make dummy records (5-10)
-- []- Remove random comments from this file
-- []- Load into Solace / whatever Ben made the connection for
-- []- Make a user table [id, username, password, etc.]
-- [DONE/IGNORING]- BOOK_EDITION.illustrations & PUBLISHER.publisherType need to be added to dummy inserts


-- namedPerson[_namedPersonID_,fname,lname,nobilityTitle,lifeYears,personNote]
  -- The note entry above will include any aliases or unstructured source info about names and titles. Will this work?? (Yes)
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS NAMED_PERSON;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE NAMED_PERSON (
    namedPersonID int(11) NOT NULL AUTO_INCREMENT,
    fname varchar(255),
    lname varchar(255),
    nobilityTitle varchar(255),
    lifeYears int(11),
    personNote text,
    PRIMARY KEY(namedPersonID)
);

-- book[_bookID_,bookDescriptor,bookNote]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS BOOK;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE BOOK (
    bookID int(11) NOT NULL AUTO_INCREMENT,
    bookDescriptor text,
    bookNote text,
    PRIMARY KEY(bookID)
);

-- title[_titleID_,titleString]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS TITLE;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE TITLE (
    titleID int(11) NOT NULL AUTO_INCREMENT,
    titleString text,
    PRIMARY KEY(titleID)
);

-- type[_typeID_,typeDescription]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS TYPE;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE TYPE (
    typeID varchar(3) NOT NULL,
    typeDescription text,
    PRIMARY KEY(typeID)
);

-- subject[_subjectID_,subjectDescription]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS SUBJECT;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE SUBJECT (
    subjectID varchar(3) NOT NULL,
    subjectDescription text,
    PRIMARY KEY(subjectID)
);

-- edition[_editionID_,editionString]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS EDITION;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE EDITION (
    editionID int(11) NOT NULL AUTO_INCREMENT,
    editionString text,
    PRIMARY KEY(editionID)
);

-- publisher[_publisherID_,publisherName,publisherLocation,publisherType]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS PUBLISHER;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE PUBLISHER (
    publisherID int(11) NOT NULL AUTO_INCREMENT,
    publisherName varchar(255),
    publisherLocation varchar(255),
    -- publisherType ???,
    PRIMARY KEY(publisherID)
);

-- format[_formatID_,formatName]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS FORMAT;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE FORMAT (
    formatID int(11) NOT NULL AUTO_INCREMENT,
    formatName varchar(255),
    PRIMARY KEY(formatID)
);

-- agreement[_agreementTypeID_,agreementTypeName,agreementTypeNote]
  -- agreementTypeNote contains whatever a person who checks "other" specifies
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS AGREEMENT;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE AGREEMENT (
    agreementTypeID int(11) NOT NULL AUTO_INCREMENT,
    agreementTypeName varchar(255),
    agreementTypeNote text,
    PRIMARY KEY(agreementTypeID)
);

-- author[_*namedPersonID*_,_*bookID*_]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS AUTHOR;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE AUTHOR (
    namedPersonID int(11) NOT NULL,
    bookID int(11) NOT NULL,
    PRIMARY KEY(namedPersonID, bookID),
    CONSTRAINT AUTHOR_NAMED_PERSON_fk FOREIGN KEY (namedPersonID) REFERENCES NAMED_PERSON (namedPersonID) ON DELETE CASCADE,
    CONSTRAINT AUTHOR_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID) ON DELETE CASCADE
);

-- editor[_*namedPersonID*_,_*bookID*_]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS EDITOR;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE EDITOR (
    namedPersonID int(11) NOT NULL,
    bookID int(11) NOT NULL,
    PRIMARY KEY(namedPersonID, bookID),
    CONSTRAINT EDITOR_NAMED_PERSON_fk FOREIGN KEY (namedPersonID) REFERENCES NAMED_PERSON (namedPersonID) ON DELETE CASCADE,
    CONSTRAINT EDITOR_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID) ON DELETE CASCADE
);

-- translator[_*namedPersonID*_,_*bookID*_]
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS TRANSLATOR;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE TRANSLATOR (
    namedPersonID int(11) NOT NULL,
    bookID int(11) NOT NULL,
    PRIMARY KEY(namedPersonID, bookID),
    CONSTRAINT TRANSLATOR_NAMED_PERSON_fk FOREIGN KEY (namedPersonID) REFERENCES NAMED_PERSON (namedPersonID) ON DELETE CASCADE,
    CONSTRAINT TRANSLATOR_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID) ON DELETE CASCADE
);

-- bookEdition[_*bookID*_,_*editionID*_,_*publisherID*_,*titleID*,*formatID*,year,yearNote,numberPages,numberVolumes,*agreementTypeID*,salePrice,paymentAgreedAmount,illustrations,illustrationsPayment,copiesSold,copiesRemaining,profitLoss,proceedsAuthor,formatNote]
  -- formatNote contains whatever a person who checks "other" for formatID specifies
  SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS BOOK_EDITION;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE BOOK_EDITION (
    bookID int(11) NOT NULL,
    editionID int(11) NOT NULL,
    publisherID int(11) NOT NULL,
    titleID int(11) NOT NULL,
    formatID int(11) NOT NULL,
    year int(11),
    numberPages int(11), 
    numberVolumes int(11),
    agreementTypeID int(11) NOT NULL,
    salePrice DECIMAL(10,2),
    paymentAgreedAmount DECIMAL(10,2),
    -- illustrations ???,
    illustrationsPayment DECIMAL(10,2),
    copiesSold int(11),
    copiesRemaining int(11),
    profitLoss DECIMAL(10,2),
    proceedsAuthor DECIMAL(10,2),
    formatNote text,
    PRIMARY KEY(bookID, editionID, publisherID),
    CONSTRAINT BOOK_EDITION_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID) ON DELETE CASCADE,
    CONSTRAINT BOOK_EDITION_EDITION_fk FOREIGN KEY (editionID) REFERENCES EDITION (editionID) ON DELETE CASCADE,
    CONSTRAINT BOOK_EDITION_PUBLISHER_fk FOREIGN KEY (publisherID) REFERENCES PUBLISHER (publisherID) ON DELETE CASCADE,
    CONSTRAINT BOOK_EDITION_TITLE_fk FOREIGN KEY (titleID) REFERENCES TITLE (titleID) ON DELETE CASCADE,
    CONSTRAINT BOOK_EDITION_FORMAT_fk FOREIGN KEY (formatID) REFERENCES FORMAT (formatID) ON DELETE CASCADE,
    CONSTRAINT BOOK_EDITION_AGREEMENT_fk FOREIGN KEY (agreementTypeID) REFERENCES AGREEMENT (agreementTypeID) ON DELETE CASCADE
);

-- bookType[_*bookID*_,_*typeID*_,bookTypeNote]
  -- bookTypeNote contains whatever a person who checks "other" specifies
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS BOOK_TYPE;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE BOOK_TYPE (
    bookID int(11) NOT NULL,
    typeID varchar(3) NOT NULL,
    bookTypeNote text,
    PRIMARY KEY(bookID, typeID),
    CONSTRAINT BOOK_TYPE_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID) ON DELETE CASCADE,
    CONSTRAINT BOOK_TYPE_TYPE_fk FOREIGN KEY (typeID) REFERENCES TYPE (typeID) ON DELETE CASCADE
);

-- bookSubject[_*bookID*_,_*subjectID*_,bookSubjectNote]
  -- bookTypeNote contains whatever a person who checks "other" specifies
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS BOOK_SUBJECT;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE BOOK_SUBJECT (
    bookID int(11) NOT NULL,
    subjectID varchar(3) NOT NULL,
    bookSubjectNote text,
    PRIMARY KEY(bookID, subjectID),
    CONSTRAINT BOOK_SUBJECT_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID) ON DELETE CASCADE,
    CONSTRAINT BOOK_SUBJECT_SUBJECT_fk FOREIGN KEY (subjectID) REFERENCES SUBJECT (subjectID) ON DELETE CASCADE
);

-- # Values

-- Note that two of the IDs are not auto-incremented but are codes found in vain.tsv: typeID, subjectID

-- ## typeID, typeDescription

-- a, autobiography
INSERT INTO TYPE (typeID, typeDescription) VALUES ("a", "autobiography");
-- b, biography
INSERT INTO TYPE (typeID, typeDescription) VALUES ("b", "biography");
-- c, compilation
INSERT INTO TYPE (typeID, typeDescription) VALUES ("c", "compilation");
-- d, diary--journal
INSERT INTO TYPE (typeID, typeDescription) VALUES ("d", "diary-journal");
-- f, fictional
INSERT INTO TYPE (typeID, typeDescription) VALUES ("f", "fictional");
-- g, gallows--broadside
INSERT INTO TYPE (typeID, typeDescription) VALUES ("g", "gallows-broadside");
-- l, letters--correspondence
INSERT INTO TYPE (typeID, typeDescription) VALUES ("l", "letters-correspondence");
-- m, memoir
INSERT INTO TYPE (typeID, typeDescription) VALUES ("m", "memoir");
-- o, other
INSERT INTO TYPE (typeID, typeDescription) VALUES ("o", "other");

## subjectID, subjectDescription

-- a, adventure
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("a", "adventure");
-- c, criminal
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("c", "criminal");
-- cy, celebrity
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("cy", "celebrity");
-- d, domestic
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("d", "domestic");
-- g, great--man
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("g", "great-man");
-- h, historical
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("h", "historical");
-- l, literary--memoir
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("l", "literary-memoir");
-- m, military
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("m", "military");
-- mc, middle--class
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("mc", "middle-class");
-- p, politics
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("p", "politics");
-- r, religious
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("r", "religious");
-- s, satire
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("s", "satire");
-- sc, social--critique
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("sc", "social-critique");
-- sd, school--days
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("sd", "school-days");
-- t, travel
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("t", "travel");
-- th, theatre
INSERT INTO SUBJECT (subjectID, subjectDescription) VALUES ("th", "theatre");

## formatName  

-- 4to
INSERT INTO FORMAT (formatID,formatName) VALUE (1, "4to"); -- 1
-- 8vo
INSERT INTO FORMAT (formatID,formatName) VALUE (2, "8vo"); -- 2
-- 12mo
INSERT INTO FORMAT (formatID,formatName) VALUE (3, "12mo"); -- 3
-- 24mo
INSERT INTO FORMAT (formatID,formatName) VALUE (4, "24mo"); -- 4
-- other
INSERT INTO FORMAT (formatID,formatName) VALUE (5, "other"); -- 5

## agreementTypeName

-- half profits
INSERT INTO AGREEMENT (agreementTypeID, agreementTypeName) VALUE (1, "half profits"); -- 1
-- payment for copyright
INSERT INTO AGREEMENT (agreementTypeID, agreementTypeName) VALUE (2, "payment for copyright"); -- 2
-- at risk
INSERT INTO AGREEMENT (agreementTypeID, agreementTypeName) VALUE (3, "at risk"); -- 3
-- costs paid by author
INSERT INTO AGREEMENT (agreementTypeID, agreementTypeName) VALUE (4, "costs paid by author"); -- 4
-- other
INSERT INTO AGREEMENT (agreementTypeID, agreementTypeName) VALUE (5, "other"); -- 5


-- Book 1
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (1, "1 vol and 143 pp; apparently real autobiography; republished in 1815 and 1817 in England and in 1821 in New York", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (1, "James", "Downing", null, null, null);
INSERT INTO TITLE (titleID, titleString) VALUES (1, "A Narrative of the Life of James Downing [In verse], a Blind Man, Late a Private in His Majesty's 20th Regiment of the Foot, Containing Historical, Naval, Military, Moral, Religious and Entertaining Reflections. Composed by Himself in Easy Verse.");
INSERT INTO PUBLISHER (publisherID, publisherName, publisherLocation) VALUES (1, "J. Haddon", "London");
INSERT INTO EDITION (editionID, editionString) VALUES (1, "1st Edition");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (1, 1, 1, 1, 5, 1811, 143, 1, 5, null, null, null, null, null, null, null, null);

INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (1, "a", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (1, "m", null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (1, 1);

-- Book 2
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (2, "1 vol of 210 pp; first published in 1811 (York: C. Peacock) and may have been privately printed (since it was 'Printed by C. Peacock, for W. Alexander' in the first instance)", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (2, "Mary", "Alexander", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (2, 2);
INSERT INTO PUBLISHER (publisherID, publisherName, publisherLocation) VALUES (2, "C. Peacock", "York");
INSERT INTO TITLE (titleID, titleString) VALUES (2, "Some Account of the Life and Religious Experience of Mary Alexander, late of Needham Market [Written by Herself]");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (2, 1, 2, 2, 5, 1811, 210, 1, 5, null, null, null, null, null, null, null, null);

INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (2, "a", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (2, "R", null);

-- Book 3
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (3, "1 vol; military and religious", null);
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (3, "a", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (3,"r",null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (3, "Richard", "Marks", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (3, 3);
INSERT INTO TITLE (titleID, titleString) VALUES (3, "The Retrospect, or Review of Providential Mercies; with Anecdotes of Various Characters, and an Address to Naval Officers");
INSERT INTO PUBLISHER (publisherID, publisherName, publisherLocation) VALUES (3, "J. Nisbet", "London");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (3, 1, 3, 3, 5, null, null, 1, 5, null, null, null, null, null, null, null, null);
    
-- Book 4
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (4, "edition of 500 on half-profits, and only 477 sold; netted Murray and author each 8.9.2", null);
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (4, "b", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (4, "g", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (4, "Richard", "Duppa", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (4, 4);
INSERT INTO TITLE (titleID, titleString) VALUES (4, "Life of Raffaello Sanzio da Urbino");
INSERT INTO PUBLISHER (publisherID, publisherName, publisherLocation) VALUES (4, "John Murray", "London");
INSERT INTO EDITION (editionID, editionString) VALUES (2, "of 500");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (4, 2, 4, 4, 5, 1816, null, null, 1, null, 8.92, null, 477, null, null, null, null);
    
-- Book 5
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (5, "edition of 750 copies priced at 14s.9d or 13s, of which 680 sold; half-profits for Murray gave him and author each 136.15.11 by April 1817; no record in BLIC and only WC record is an edition in Philadelphia and an edition described as 'London: Printed for Sir Richard Phillips...'; published again in 1818 with this information: appears to be 1 vol quarto selling at 1.11.0 or 1.14.0, but hard to tell because there are '2nd editions' listed for Buckhardts 'Nubia' and Buckhardts 'Syria' at the close of the accounting for 'Travels'; so it may have been 2 vols initially, then retitled for new editions in 1822; for 1st edition, appears to have paid 200l to the author and still cleared 515.1.9 by selling 918 copies of the 1000-copy edition; cleared another 37.1.6 from sales of 476 of 500 in the 2nd edition of 'Nubia' and 218.10.11 from sales of 930 of 1000 of the 1st edition of 'Syria'; an edition of a 3rd volume of work from Burkhardt was never published and therefore goes down as a 25l loss for Murray", null);
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (5, "c", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (5, "t", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (5, "Thomas", "Legh", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (5, 5);
INSERT INTO TITLE (titleID, titleString) VALUES (5, "Some Account of the Travels of M. Burckhardt, in Egypt and Nubia");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (5, 1, 4, 5, 5, 1817, null, 750, 1, null, null, null, 680, 70, null, null, null);

-- Book 6
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (6, "published in the US but also sold by London: John Murray; 1 vol quarto; first edition of 500 copies; Murray paid 200l for the copyright but still cleared a profit of 457.2.4 by 1818; 2nd edition of 2 vols 8vo published March 1817 in 750 copies; Murray paid 100l more for the right to publish this edition but still cleared another 166.2.9; printed a 3rd edition in 2 vols 8vo of 750 in 1819 and finally took a bath on it, being stuck with 496 as late as 1828 when they were sold off; in 1843 he was still recording his loss as 30.14.6 on this edition", null);
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (6, "b", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (6, "r", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (6, "John", "Vandelure", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (6, 6);
INSERT INTO TITLE (titleID, titleString) VALUES (6, "Narrative of the Travels of John Vandeluer on the Western Continent Containing an Account of the Conversion of an Indian Chief and His Family to Christianity");
INSERT INTO PUBLISHER (publisherID, publisherName, publisherLocation) VALUES (5, "E. Goodale", "Hallowell, Maine");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (6, 1, 5, 6, 5, 1817, null, 500, 1, null, null, null, 680, 70, null, null, null);
    
-- Book 7
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (7, "1 vol; 3500 copies printed in 2 editions (of 1750); price 1.12.6; 2360 were sold 1817-1820 and thus yielded 1/3 shares of 135.7.10 each to Hall, Murray, and Lt. Clifford (whoever that is); a 3rd edition (of 1500) was tried in 1819 with Murray eventually selling them off at 4/8, then 4/11 to recover his money and show a modest profit on it", null);
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (7, "a", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (7, "t", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (7, "Basil", "Hall", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (7, 7);
INSERT INTO TITLE (titleID, titleString) VALUES (7, "Account of a Voyage of Discovery to the West Coast of Corea, and the Great Loo-Choo Island");
INSERT INTO EDITION (editionID, editionString) VALUES (3, "1750 edition");
INSERT INTO EDITION (editionID, editionString) VALUES (4, "1817 edition");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (7, 3, 4, 7, 5, 1750, null, 1, 5, null, null, null, 2360, 1140, null, null, null);
INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (7, 4, 4, 7, 5, 1817, null, 1, 5, null, null, null, null, null, null, null, null);
    
-- Book 8
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (8, "1 vol 8vo; first edition of 1250 in Oct 1817 sold by March 1818; 2nd edition of 3500 printed in December 1817; Murray and the author each took half profits of 89.0.11 and a halfpenny; 3rd edition of 3000 printed in 1819 and finally disposed of in 1819 for a total of 218.6.8, leaving a loss on the third edition of 458.11.10, which means that after hawking this book for five years, Murray overreached and turned no profit.", null);
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (8, "a", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (8, "t", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (8, "John", "McLeod", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (8, 8);
INSERT INTO TITLE (titleID, titleString) VALUES (8, "The Shipwreck of the Alceste");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (8, 1, 4, 8, 5, 1817, null, 8, 5, null, null, null, null, null, null, null, null);
    
-- Book 9
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (9, "1 vol, edition of 750; paid 500l for the copyright and sold 718 copies; managed to produce a profit of 96.6.2 by July 1820", null);
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (9, "a", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (9, "t", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (9, "T. Edward", "Bowdich", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (9, 9);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (10, "Gaspard", "Theodore Mallien", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (10, 9);
INSERT INTO TITLE (titleID, titleString) VALUES (9, "Travels in Africa");

INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (9, 1, 4, 9, 5, 1818, null, 1, 5, 5, null, null, 718, null, null, null, null);
    
-- Book 10
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (10, "1 vol; edition of 750 of which 716 sold; Murray paid 20l to the author and cleared 11.14.9 himself", null);
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (10, "a", null);
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (10, "a", null);
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (11, "James", "Hackett", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (11, 10);
INSERT INTO TITLE (titleID, titleString) VALUES (10, "Narrative of the Expedition which Sailed from England in 1817, to Join the South American Patriots-");
INSERT INTO BOOK_EDITION 
    (bookID, editionID, publisherID, titleID, formatID, year, numberPages, numberVolumes, agreementTypeID, salePrice, paymentAgreedAmount, illustrationsPayment, copiesSold, copiesRemaining, profitLoss, proceedsAuthor, formatNote) 
    VALUES 
    (10, 1, 4, 10, 5, 1818, null, 1, 5, null, null, null, 716, 34, null, null, null);