<?php
    require_once('../data/PDO.DB.class.php');
    require_once('./presentation/UIElementConstructor.class.php');
    require_once('../business/validations.php');

    $dbh = new DB();

    session_name("iste432_project");
    session_start();

    // first, let's see if they logged in
    if(!isset($_SESSION['loggedIn']) || !isset($_SESSION['roleID']['roleID'])) {
        // They didn't log in! throw 'em back to login.php
        header("Location: login.php");
        die();
        var_dump($_SESSION);
        // echo "didn't log in";
    }
    if($_SESSION['roleID']['roleID'] == 1){
        // do the thing
    }else{
        header("Location: login.php");
        die();
    }

    if(isset($_POST['user']) && isset($_POST['password'])){
        $user = trim($_POST['user']);
        $password = trim($_POST['password']);
        if($user == "" || strlen($user) > 50 || sqlMetaChars($user) || sqlInjection($user) || sqlInjectionUnion($user) ||
        sqlInjectionSelect($user) || sqlInjectionInsert($user) || sqlInjectionDelete($user) ||
        sqlInjectionUpdate($user) || sqlInjectionDrop($user) || crossSiteScripting($user) ||
        crossSiteScriptingImg($user)) {
            $user = "";
            die();
        }
        if($password == "" || strlen($password) > 50 || sqlMetaChars($password) || sqlInjection($password) || sqlInjectionUnion($password) ||
        sqlInjectionSelect($password) || sqlInjectionInsert($password) || sqlInjectionDelete($password) ||
        sqlInjectionUpdate($password) || sqlInjectionDrop($password) || crossSiteScripting($password) ||
        crossSiteScriptingImg($password)) {
            $password = "";
            die();
        }
        // hash password
        // $hashedPassword = hash("sha256", $password);
        $hashedPassword = password_hash($password, PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 1]);
        // confirm if the user exists
        $db = new DB();
        $db->insertNewUser($user, $hashedPassword, $_POST['roleID']);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Create User</title>
</head>

<body>
    <a class="button" href="./ExceptionTestUI.php">Go Back</a><br>

    <h1>Create User</h1>
    <form action="./AdminRegister.php" method="post">
        Username: <input type="text" name="user" id="user" required><br>
        Password: <input type="text" name="password" id="password" required><br>
        Role: <select name="roleID">
            <option value="4">Public</option>
            <option value="3">Contributor</option>
            <option value="2">Editor</option>
            <option value="1">Admin</option>
        </select>
        <br>
        <input class="button" type="submit" value="Register"><br>
    </form><br>

    <a class="button" href="./logout.php">Logout</a>

</body>

</html>