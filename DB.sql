DROP TABLE IF EXISTS 'Book';
CREATE TABLE 'book' (
  'bookID' int(11) NOT NULL, 
  'title' varchar(100) NOT NULL,
  'publisherID' int(11) NOT NULL, 
  'yearNote' varchar(100), 
  'dateModified' varchar(50), 
  'descriptor' varchar(255), 
  'note' varchar(255), 
  'numberVolumes' varchar(255), 
  'numberPages' varchar(255), 
  'editionFormat' varchar(255), 
  'agreementType' varchar(255), 
  'paymentAmount' decimal(6,2)
  PRIMARY KEY ('bookID')
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE SUBJECT (
    subjectID int AUTO_INCREMENT NOT NULL,
    name varchar(255),
    field varchar(255),
    PRIMARY KEY (subjectID)
);

CREATE TABLE BOOK_SUBJECT (
    bookID int NOT NULL,
    subjectID int NOT NULL,
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
    typeID int AUTO_INCREMENT NOT NULL,
    name varchar(255),
    PRIMARY KEY (typeID)
);

CREATE TABLE BOOK_TYPE (
    bookID int NOT NULL,
    typeID int NOT NULL,
    PRIMARY KEY (bookID, typeID),
    CONSTRAINT BOOK_TYPE_BOOK_fk FOREIGN KEY (bookID) REFERENCES BOOK (bookID),
    CONSTRAINT BOOK_TYPE_TYPE_fk FOREIGN KEY (typeID) REFERENCES TYPE (typeID),
);
