// table functions - These functions handle the table UI buttons
// the insert button didn't need any JS so it's not here

// update a row
function update() {
    alert("update clicked");
}

// delete a row
function del(table, conditionStatement) {

    //ask if they're sure they want to delete the row
    if (confirm("Are you sure you want to delete this row?")) {

        //create a virtual form and submit it with the information
        let form = document.createElement("form");
        form.action = "ExceptionTestUI.php?table=" + table;
        form.method = "POST";

        let tableName = document.createElement("input");
        tableName.type = "hidden";
        tableName.name = "table";
        tableName.value = table;
        form.append(tableName);

        let operation = document.createElement("input");
        operation.type = "hidden";
        operation.name = "operation";
        operation.value = "delete";
        form.append(operation);

        let condition = document.createElement("input");
        condition.type = "hidden";
        condition.name = "values";
        condition.value = conditionStatement;
        form.append(condition);

        document.body.append(form);
        form.submit();
    }
}