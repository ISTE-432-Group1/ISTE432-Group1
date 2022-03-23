-- Book
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES (10, "1 vol; edition of 750 of which 716 sold; Murray paid 20l to the author and cleared 11.14.9 himself", null);
-- type: a
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES (10, "a", null);
-- Authorship: 
-- Subject: a
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES (10, "a", null);
-- Author: Hackett, James
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES (11, "James", "Hackett", null, null, null);
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES (11, 10);
-- Title: Narrative of the Expedition which Sailed from England in 1817, to Join the South American Patriots-
INSERT INTO TITLE (titleID, titleString) VALUES (10, "Narrative of the Expedition which Sailed from England in 1817, to Join the South American Patriots-");
-- Publisher: London: John Murray --> 4
-- Year: 1818
    -- gets used in bookedition insert
-- Descriptor: 1 vol; edition of 750 of which 716 sold; Murray paid 20l to the author and cleared 11.14.9 himself
    -- gets used in bookedition insert & agreementType
-- Named Person: 
-- Notes: 
-- Located: 
-- Other:

-- INORDER: 
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








-- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ## -- ##

-- Book
INSERT INTO BOOK (bookID, bookDescriptor, bookNote) VALUES ();
-- type: 
INSERT INTO BOOK_TYPE (bookID, typeID, bookTypeNote) VALUES ();
-- Authorship: 
-- Subject: 
INSERT INTO BOOK_SUBJECT (bookID, subjectID, bookSubjectNote) VALUES ();
-- Author: 
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES ();
INSERT INTO AUTHOR (namedPersonID, bookID) VALUES ();
-- Title: 
INSERT INTO TITLE (titleID, titleString) VALUES ();
-- Publisher: 
INSERT INTO PUBLISHER (publisherID, publisherName, publisherLocation) VALUES ();
-- Year: 
    -- gets used in bookedition insert
-- Descriptor: 
    -- gets used in bookedition insert & agreementType
-- Named Person: 
INSERT INTO NAMED_PERSON (namedPersonID, fname, lname, nobilityTitle, lifeYears, personNote) VALUES ();
-- Notes: 
-- Located: 
-- Other:
INSERT INTO EDITION (editionID, editionString) VALUES ();

-- INORDER: 
