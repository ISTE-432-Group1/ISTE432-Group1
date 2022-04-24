// table functions - These functions handle the table UI buttons
// the insert button didn't need any JS so it's not here
let activeRow = -1;
let savedRowContent;
let fieldsToUpdate;
let valuesToUpdate;



// update a row
function update(table, conditionStatement, rowId) {
    if(activeRow != -1) {
        cancelUpdate(activeRow);
    }
    activeRow = rowId;
    savedRowContent = document.getElementById(rowId).innerHTML;
    let tablefields = Array.from(document.getElementById(rowId).children);
    let tableheaders = Array.from(document.getElementById('tableheaders').children);
    let fieldvalues = [];
    let fieldnames = [];
    tablefields.forEach(element => {
        fieldvalues.push(element.textContent);
    });
    tableheaders.forEach(element => {
        fieldnames.push(element.textContent);
    });
    fieldsToUpdate = [];
    valuesToUpdate = [];
    for(i = 0; i < tableheaders.length; i++) {
        if(fieldnames[i].indexOf("(") == -1) {
            tablefields[i].innerHTML = "<input id = type='text' id='" + fieldnames[i] + 
                                       "' value='" + fieldvalues[i] + 
                                       "' placeholder='" + fieldnames[i] + "' />";
            fieldsToUpdate.push(fieldnames[i]);
            valuesToUpdate.push(tablefields[i]);
        }
    }
    if(tablefields[tablefields.length-1].innerHTML.indexOf("Delete") == -1) {
        tablefields[tablefields.length-1].innerHTML= "<button onclick='cancelUpdate(" + rowId + ")'>cancel</button> <button onclick='sendUpdate(\"" + table + "\", \"" + conditionStatement + "\")'>confirm</button>";
    }
    else {
        tablefields[tablefields.length-2].innerHTML= "<button onclick='cancelUpdate(" + rowId + ")'>cancel</button>";
        tablefields[tablefields.length-1].innerHTML= "<button onclick='sendUpdate(\"" + table + "\", \"" + conditionStatement + "\")'>confirm</button>";
    }
}

function cancelUpdate(id) {
    document.getElementById(id).innerHTML = savedRowContent;
}

function sendUpdate(table, conditionStatement) {

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
    operation.value = "update";
    form.append(operation);

    let condition = document.createElement("input");
    condition.type = "hidden";
    condition.name = "condition";
    condition.value = conditionStatement;
    form.append(condition);

    for(i = 0; i < fieldsToUpdate.length; i++) {
        let keyValPair = document.createElement("input");
        keyValPair.type = "hidden";
        keyValPair.name = fieldsToUpdate[i];
        keyValPair.value = valuesToUpdate[i].firstChild.value;
        form.append(keyValPair);
    }
    document.body.append(form);
    form.submit();
}

// delete a row
function del(table, conditionStatement) {
    if(activeRow != -1) {
        cancelUpdate(activeRow);
    }
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