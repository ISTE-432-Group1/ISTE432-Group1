<?php 

class Admin {
    private $userName;
    private $role;
    private $password;
    private $accountType;

    function __construct($userName="", $role="", $password="", $accountType="") {
        $this->userName=$userName;
        $this->role=$role;
        $this->password=$password;
        $this->accountType=$accountType;
    }

    function getUserName() {return $this->userName;}
    function getRole() {return $this->role;}
    function getpassword() {return $this->password;}
    function getAccountType() {return $this->accountType;}
   
}//Admin class