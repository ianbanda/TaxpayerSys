<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Auth {

    private $registry;//receives the $registry object passed via the constructor upon instantiation
    private $loggedin;//boolean variable used to hold the login status
    private $user;//holds the user object being authenticated

    public function __construct($registry) {
        $this->registry = $registry;

        if (isset($_SESSION['myemail']) && isset($_SESSION["password"])) {
            $this->sessionAuthenticate($_SESSION['myemail'], $_SESSION['password']);
        } else {
            //echo 'other route';
        }
    }

    /*
     * Authenticates a user if the neccessary session variables are set
     * 
     */
    private function sessionAuthenticate($email, $pass) {
        require_once(FRAMEWORK_PATH . 'Models/User.php');
        $this->user = new User($this->registry, $email, $pass);

        if ($this->user->loggedin() == 1) {
            $this->loggedin = TRUE;
        } else {
            $this->loggedIn = false;
            $this->loginFailureReason = 'User not found';
        }
    }

    public function login() {
        $data = array();

        if (isset($_POST['loginform'])) {

            $fields = array(
                "Email" => "email",
                "Password" => "required"
            );

            $validator = $this->registry->getObject('validator');
            $validationResult = $validator->validate($fields);

            if ($validationResult['valid'] == TRUE) {
                $email = $_POST["email"];
                $password = $_POST["password"];

                require_once 'Models/User.php';
                $this->user = new User($this->registry, $email, $password);

                if ($this->user->loggedin()) {
                    echo 'logged in';
                    //header("location:index.php");
                } else {
                    header("location:../?remark=" . $this->user->getLoginFailureReason());
                }
            }
        } else {
            //$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Home/loggedout.tpl.php', 'footer.tpl.php');
        }
        //$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/new.tpl.php', 'footer.tpl.php');
    }

    public function getUser() {
        return $this->user;
    }

    public function logout() {
        if (isset($_SESSION['myemail'])) {
            $email = $_SESSION['myemail'];
        } else {
            $email = "banda.ian45@gmail.com";
        }
        $data = array(
            "Email" => $email,
        );

        $url = BASEURL . "/Auth/logout";
        $api = $this->registry->getObject("api");
        $api->processRequest($url, json_encode($data), 'POST');
        $dbresult = json_decode($api->getDBResult(), true);

        if ($dbresult["ResultCode"] === 1) {

            $_SESSION["loggedin"] = "";
            $_SESSION["myemail"] = "";
            $_SESSION['password'] = "";
            unset($_SESSION["loggedin"]);
            unset($_SESSION["myemail"]);
            unset($_SESSION['password']);

            $this->loggedin = FALSE;

            header("location:../");
        }
    }

    public function loggedin($param) {
        return $this->loggedin;
    }

}
