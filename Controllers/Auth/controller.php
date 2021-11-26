<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Authcontroller {

    private $registry;
    private $loggedin;
    private $user;

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
        //header("Location:../index.php");
    }

    public function login() {
        $this->registry->getObject('auth')->login();
        
        if ($this->registry->getObject('auth')->getUser()->loggedin()) {
            header("location:../index.php");
        } else {
            header("location:../index.php?remark=Wrong Email or Password<br/>");
        }
        
    }

    public function getUser()
    {
    	return $this->user;
    }

    
    public function logout() {
        $this->registry->getObject('auth')->logout();
    }


}
