<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Validator {

    public $fields = array();
    private $registry;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    public function validate($fields) {
        $this->fields = $fields;
        $flag = "NO";
        $errorCtr = 0;

        
        //print_r($fields);

        foreach ($this->fields as $field => $value) {
            if ($value == "required" || $value == "email") {
                $msg = "";
                $data = "";
                if (isset($_POST[$field])) {
                    $data = $this->sanitizeData($_POST[$field]);
                    $data = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
                }
                
                if (empty($data)) {
                    $msg = "<span class='w3-text-orange formerror'>" . "The above field cannot be empty" . "</span><br/>";
                    $this->displayError($field, $data, $msg);
                    $errorCtr++;
                } else {
                    if ($value == "email") {
                        //$msg = $this->validateEmail($field);
                        $email = filter_var($data, FILTER_VALIDATE_EMAIL);
                        if($email===false)
                        {
                            $msg = "Email is not valid";
                        }
                        
                        $msg = $this->validateEmail($field);
                        
                        $this->displayError($field, $data, $msg);
                        if(!empty($msg))
                        {
                            $errorCtr;//Increases the error counter value if email message returns non-empty
                        }
                    } else {
                        $msg = "";
                        $this->displayError($field, $data, $msg);
                    }
                }
            }
        }

        if($errorCtr<1)
        {
            $flag = "YES";
        }
        
        $result = array("valid" => "$flag", "fields" => $this->fields);
        return $result;
    }

    /**
     * Sanitize data
     * @param String the data to be sanitized
     * @return String the sanitized data
     */
    public function validateEmail($email) {
        $message="";
        $email = $_POST[$email];
        //check if email is more than 1 line long
        if (strpos(( urldecode($email)), "\r") === true || strpos(( urldecode($email)), "\n") === true ) {
            $message = 'Your email address is not valid (security)';
            
        }
 else
        {
            if (empty($email)) {
                $message = 'Your email address cannot be empty ';
            } else {

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message="";
                } else {
                    $message = "$email is not a valid email address";
                }
            }
        }
        return $message;
    }

    /**
     * Sanitize data
     * @param String the data to be sanitized
     * @return String the sanitized data
     */
    public function sanitizeData($value) {
        // Stripslashes 
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }

       
        return $value;
    }

    private function displayError($field,$value, $msg) {
        $field." error is ".$msg;
        $this->registry->getObject("template")->getPage()->addTag($field, $value);
        $this->registry->getObject("template")->getPage()->addTag($field . '_error', $msg);
    }

    public function isEmpty($str) {
        if ($str == NULL || $str == "") {
            $flag = TRUE;
        } else {
            $flag = FALSE;
        }

        return $flag;
    }

}

?>