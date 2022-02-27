CREATE TABLE USER (
    userID int AUTO_INCREMENT NOT NULL,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    role varchar(255) NOT NULL,
    PRIMARY KEY (userID)
);

CREATE TABLE PUBLISHER (
    publisherID int AUTO_INCREMENT NOT NULL,
    placeOfPublication varchar(255), 
    publisherName varchar(255), 
    publisherType varchar(255) NULL,
    PRIMARY KEY (publisherID)
);

CREATE TABLE BOOK (
  bookID int AUTO_INCREMENT NOT NULL, 
  title varchar(512) NOT NULL,
  authorship varchar(1),
  yearNote varchar(4), 
  lastModifiedUserID int NULL,
  dateModified varchar(50), 
  descriptor text, 
  note text, 
--   numberVolumes varchar(255), 
--   numberPages varchar(255), 
--   editionFormat varchar(255), 
--   agreementType varchar(255), 
--   paymentAmount decimal(6,2),
  PRIMARY KEY (bookID),
  CONSTRAINT BOOK_USER_fk FOREIGN KEY (lastModifiedUserID) REFERENCES USER (userID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE BOOK_PUBLISHER (
    bookID int NOT NULL, 
    publisherID int NOT NULL,
    CONSTRAINT BOOK_PUBLISHER_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID),
    CONSTRAINT BOOK_PUBLISHER_PUBLISHER_fk FOREIGN KEY (publisherID) REFERENCES PUBLISHER (publisherID)
);

-- Mentioned in ERD plan but doesn't feel obvious when implementing. Waitng till later to try this.
-- DROP TABLE IF EXISTS BOOK_COLLABORATOR;
-- CREATE TABLE BOOK_COLLABORATOR (
--     bookID int(11) NOT NULL,
--     collaboratorID int(11) AUTO_INCREMENT NOT NULL,
--     PRIMARY KEY (bookID, collaboratorID), 
--     CONSTRAINT BOOK_COLLABORATOR_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID) 
-- );

CREATE TABLE SUBJECT (
    subjectID varchar(3) NOT NULL,
    name varchar(255),
    PRIMARY KEY (subjectID)
);

CREATE TABLE BOOK_SUBJECT (
    bookID int NOT NULL,
    subjectID varchar(3) NOT NULL,
    PRIMARY KEY (bookID, subjectID),
    CONSTRAINT BOOK_SUBJECT_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID),
    CONSTRAINT BOOK_SUBJECT_SUBJECT_fk FOREIGN KEY (subjectID) REFERENCES SUBJECT (subjectID)
);

CREATE TABLE VARIANT_TITLES (
    bookID int NOT NULL,
    variant varchar(255),
    note text,
    PRIMARY KEY (bookID, variant),
    CONSTRAINT VARIANT_TITLES_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID)
);

CREATE TABLE TYPE (
    typeID varchar(3) NOT NULL,
    name varchar(255),
    PRIMARY KEY (typeID)
);

CREATE TABLE BOOK_TYPE (
    bookID int NOT NULL,
    typeID varchar(3) NOT NULL,
    PRIMARY KEY (bookID, typeID),
    CONSTRAINT BOOK_TYPE_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID),
    CONSTRAINT BOOK_TYPE_TYPE_fk FOREIGN KEY (typeID) REFERENCES TYPE (typeID)
);

CREATE TABLE NAMED_PERSON (
    namedPersonID int AUTO_INCREMENT NOT NULL,
    name varchar(255),
    role varchar(255),
    lifeYears int,
    PRIMARY KEY (namedPersonID)
);

CREATE TABLE VARIANT_PERSON (
    namedPersonID int NOT NULL,
    variant varchar(255),
    note text,
    PRIMARY KEY (namedPersonID, variant),
    CONSTRAINT VARIANT_PERSON_NAMED_PERSON_fk FOREIGN KEY (namedPersonID) REFERENCES NAMED_PERSON (namedPersonID)
);

CREATE TABLE AUTHOR (
    authorID int AUTO_INCREMENT NOT NULL,
    name varchar(255),
    lifeYears int,
    PRIMARY KEY (authorID)
);

CREATE TABLE BOOK_AUTHOR (
    bookID int NOT NULL,
    authorID int NOT NULL,
    PRIMARY KEY (bookID, authorID),
    CONSTRAINT BOOK_BOOK_AUTHOR_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID),
    CONSTRAINT AUTHOR_BOOK_AUTHOR_fk FOREIGN KEY (authorID) REFERENCES AUTHOR (authorID)
);

CREATE TABLE BOOK_NAMED_PERSON (
    bookID int, 
    namedPersonID int, 
    PRIMARY KEY (bookID, namedPersonID), 
    CONSTRAINT BOOK_NAMED_PERSON_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID), 
    CONSTRAINT BOOK_NAMED_PERSON_NAMED_PERSON_fk FOREIGN KEY (namedPersonID) REFERENCES NAMED_PERSON (namedPersonID)
);
