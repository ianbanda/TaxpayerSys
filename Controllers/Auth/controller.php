<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Authcontroller {

    private $registry;
    private $loggedin;

    public function __construct($registry, $directCall = true) {
        $this->registry = $registry;

        $urlBits = $this->registry->getObject('url')->getURLBits();

        if (isset($urlBits[1])) {
            switch ($urlBits[1]) {
                case 'login':
                    $this->login();
                    break;
                case 'logout':
                    $this->logout();
                    break;

                default:
                    break;
            }
        } else {
            
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'main.tpl.php', 'footer.tpl.php');
            $registry->getObject('template')->addTemplateBit('topbar', 'topbar.tpl.php');
            
        }
        header("Location:../index.php");
    }

    public function login() {
        //print_r($_POST);
        $data = array();

        if (isset($_POST['loginform'])) {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $_POST["email"] = $email;

            $loginarray = array("Email" => $email, "Password" => $password);

            $data = array(
                "Email" => $email,
                "Password" => $password
            );
            $data = json_encode($data);

            $url = BASEURL . "/auth/login";
            $api = $this->registry->getObject("api");
            $result = $api->processRequest($url, $data, 'POST');

            if ($result["ResultCode"] === 1) {
                $_SESSION["loggedin"] = "1";
                $_SESSION["myemail"] = $email;
                $this->loggedin = TRUE;
            } else {
                $_SESSION["loggedin"] = "";
                $_SESSION["email"] = "";
                unset($_SESSION["loggedin"]);
                unset($_SESSION["myemail"]);
                $this->loggedin = FALSE;
            }
            header("location:../");
            $result = $api->processRequest($url, $data, 'POST');
        }
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/new.tpl.php', 'footer.tpl.php');
    }

    public function logout() {
        //print_r($_POST);
        $email = "banda.ian45@gmail.com";
        $data = array(
            "Email" => $email,
        );


        $data = json_encode($data);

        $url = BASEURL . "/Auth/logout";
        $api = $this->registry->getObject("api");
        $result = $api->processRequest($url, $data, 'POST');

        if ($result["ResultCode"] === 1) {

            $_SESSION["loggedin"] = "";
            unset($_SESSION["loggedin"]);
            session_destroy(); 
            $this->loggedin = FALSE;
            
            header("location:../");
        }
    }

    public function loggedin($param) {
        return $this->loggedin;
    }

}
