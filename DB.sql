DROP TABLE IF EXISTS 'Book';
CREATE TABLE 'book' (
  'bookID' int(11) NOT NULL, 
  'title' varchar(100) NOT NULL,
  'publisherID' int(11) NOT NULL, 
  'yearNote' varchar(100), 
  'dateModified' date(100), 
  PRIMARY KEY ('bookID')
) ENGINE=InnoDB DEFAULT CHARSET=utf8;