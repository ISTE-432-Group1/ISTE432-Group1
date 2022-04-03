# Milestone 5 Updates

## Performance and Refactoring

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

## New ERD Diagram

![ER Diagram](https://github.com/ISTE-432-Group1/ISTE432-Group1/blob/main/VAIN%20ERD-1.png?raw=true)
