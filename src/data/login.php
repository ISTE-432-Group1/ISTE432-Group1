<?php
    spl_autoload_register(function($class){
        include_once("./PDO.DB.class.php");
    });
    include_once("./validations.php");


    session_name("iste432_project");
    session_start();

    if(isset($_SESSION['loggedIn'])){
        if($_SESSION['loggedIn']){
            // redirect to ExceptionTestUI.php
            header("Location: ExceptionTestUI.php");
            exit;
        }
    }

    if(isset($_POST['user']) && isset($_POST['password'])){
        $user = trim($_POST['user']);
        $password = trim($_POST['password']);
        if($user == "" || strlen($user) > 50 || sqlMetaChars($user) || sqlInjection($user) || sqlInjectionUnion($user) ||
        sqlInjectionSelect($user) || sqlInjectionInsert($user) || sqlInjectionDelete($user) ||
        sqlInjectionUpdate($user) || sqlInjectionDrop($user) || crossSiteScripting($user) ||
        crossSiteScriptingImg($user)) {
            $user = "";
        }
        if($password == "" || strlen($password) > 50 || sqlMetaChars($password) || sqlInjection($password) || sqlInjectionUnion($password) ||
        sqlInjectionSelect($password) || sqlInjectionInsert($password) || sqlInjectionDelete($password) ||
        sqlInjectionUpdate($password) || sqlInjectionDrop($password) || crossSiteScripting($password) ||
        crossSiteScriptingImg($password)) {
            $password = "";
        }
        
        // confirm if the user exists
        $db = new DB();
        $unconfirmedUsers = $db->confirmLogin($user);
        // var_dump($unconfirmedUsers);
        if(sizeof($unconfirmedUsers) > 0){
            if(password_verify($password,$unconfirmedUsers[0][2])){
                // valid login
                $_SESSION['loggedIn'] = true;
                $_SESSION['roleID'] = $unconfirmedUsers[0];
                header("Location: ExceptionTestUI.php");
                exit;
            }else{
                // invalid login
                echo "Not the right password!</br>";
            }
        }else{
            echo "Invalid login.</br>";
        }
        
        
    }else{
        echo "You need to login.</br>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>

    <form action="./login.php" method="post">
        Username: <input type="text" name="user" id="user" required><br>
        Password: <input type="text" name="password" id="password" required><br>
        <input type="submit" value="Login"><br>
    </form>

    <!-- <a href="./signup.php">Sign Up</a> -->

</body>
</html>
