CREATE TABLE 'PUBLISHER' (
    'publisherID' int NOT NULL,
    'placeOfPublication' varchar, 
    'publisherName' varchar, 
    'publisherType' varchar
    PRIMARY KEY ('publisherID')
);

CREATE TABLE 'BOOK' (
  'bookID' int(11) AUTO_INCREMENT NOT NULL, 
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
  'paymentAmount' decimal(6,2),
  PRIMARY KEY ('bookID'),
  CONSTRAINT 'BOOK_fk' FOREIGN KEY ('publisherID') REFERENCES 'PUBLISHER' ('publisherID')
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE 'BOOK_COLLABORATOR' (
    'bookID' int(11) NOT NULL,
    'collaboratorID' int(11) AUTO_INCREMENT NOT NULL,
    PRIMARY KEY ('bookID', 'collaboratorID'), 
    CONSTRAINT 'BOOK_COLLABORATOR_fk' FOREIGN KEY ('bookID') REFERENCES 'BOOK' ('bookID') 
);

CREATE TABLE 'SUBJECT' (
    'subjectID' int AUTO_INCREMENT NOT NULL,
    'name' varchar(255),
    'field' varchar(255),
    PRIMARY KEY ('subjectID')
);

CREATE TABLE 'BOOK_SUBJECT' (
    'bookID' int NOT NULL,
    'subjectID' int NOT NULL,
    PRIMARY KEY ('bookID', 'subjectID'),
    CONSTRAINT 'BOOK_SUBJECT_BOOK_fk' FOREIGN KEY ('bookID') REFERENCES 'BOOK' ('bookID'),
    CONSTRAINT 'BOOK_SUBJECT_SUBJECT_fk' FOREIGN KEY ('subjectID') REFERENCES 'SUBJECT' ('subjectID')
);

CREATE TABLE 'VARIANT_TITLES' (
    'bookID' int NOT NULL,
    'variant' varchar(255),
    'note' text,
    PRIMARY KEY ('bookID', 'variant'),
    CONSTRAINT 'VARIANT_TITLES_BOOK_fk' FOREIGN KEY ('bookID') REFERENCES 'BOOK' ('bookID')
);

CREATE TABLE 'TYPE' (
    'typeID' int AUTO_INCREMENT NOT NULL,
    'name' varchar(255),
    PRIMARY KEY ('typeID')
);

CREATE TABLE 'BOOK_TYPE' (
    'bookID' int NOT NULL,
    'typeID' int NOT NULL,
    PRIMARY KEY ('bookID', 'typeID'),
    CONSTRAINT 'BOOK_TYPE_BOOK_fk' FOREIGN KEY ('bookID') REFERENCES 'BOOK' ('bookID'),
    CONSTRAINT 'BOOK_TYPE_TYPE_fk' FOREIGN KEY ('typeID') REFERENCES 'TYPE' ('typeID'),
);

CREATE TABLE 'NAMED_PERSON' (
    'namedPersonID' int AUTO_INCREMENT NOT NULL,
    'name' varchar(255),
    'role' varchar(255),
    'lifeYears' int,
    PRIMARY KEY ('namedPersonID')
);

CREATE TABLE 'VARIANT_PERSON' (
    'namedPersonID' int NOT NULL,
    'variant' varchar(255),
    'note' text,
    PRIMARY KEY ('namedPersonID', 'variant'),
    CONSTRAINT 'VARIANT_PERSON_NAMED_PERSON_fk' FOREIGN KEY ('namedPersonID') REFERENCES 'NAMED_PERSON' ('namedPersonID')
);

CREATE TABLE 'AUTHOR' (
    'authorID' int AUTO_INCREMENT NOT NULL,
    'name' varchar(255),
    'authorship' varchar(255),
    'lifeYears' int,
    PRIMARY KEY ('authorID')
);

CREATE TABLE 'BOOK_AUTHOR' (
    'bookID' int NOT NULL,
    'authorID' int NOT NULL,
    'namedPersonID' int NOT NULL,
    PRIMARY KEY ('bookID', 'authorID', 'namedPersonID'),
    CONSTRAINT 'BOOK_BOOK_AUTHOR_fk' FOREIGN KEY ('bookID') REFERENCES 'BOOK' ('bookID'),
    CONSTRAINT 'AUTHOR_BOOK_AUTHOR_fk' FOREIGN KEY ('authorID') REFERENCES 'AUTHOR' ('authorID'),
    CONSTRAINT 'NAMED_PERSON_BOOK_AUTHOR_fk' FOREIGN KEY ('namedPersonID') REFERENCES 'NAMED_PERSON' ('namedPersonID')
);
