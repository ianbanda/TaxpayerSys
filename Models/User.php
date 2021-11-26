<?php

class User
{
    private $email;//holds the user email
    private $password;//holds the user password
    private $registry;//holds the registry object assigned on instantiation within the constructor
    private $loggedin;//holds the login status of the user
    private $firstname;//holds the first name of the user
    private $lastname;//holds the surname of the user
    private $loginFailureReason;//Holds a string explain why a login attempt has failed
    public function __construct($registry,$email,$password) {
        $this->registry = $registry;

        if(!empty($email)&&!empty($password))
        {
            $loginarray = array("Email" => $email, "Password" => $password);

                $data = array(
                    "Email" => $email,
                    "Password" => $password
                );
                $data = json_encode($data);

                $url = BASEURL . "/auth/login";
                $api = $this->registry->getObject("api");
                $result = $api->processRequest($url, $data, 'POST');
                $loginresult = $api->getDBResult();
                
                $loginresult = json_decode($loginresult,true);
                
                //Checks whether the index authenticated from the server is set to 1 or true
                //if set to 1, then user is considered to be successfully authenticated
                if(isset($loginresult["Authenticated"]) && (intval($loginresult["Authenticated"])==1))
                {
                    $this->loggedin = TRUE;
                    $_SESSION["loggedin"] = "1";
                    $_SESSION["myemail"] = $email;
                    $_SESSION["password"] = $password;
                    
                    $details = $loginresult['UserDetails'];

                    $this->username = $details['Username'];
                    $this->firstname = $details['FirstName'];
                    $this->lastname = $details['LastName'];
                    $this->email = $details['email'];
                }
                else
                {
                    if(isset($loginresult['Remark'])){
                        $this->loginFailureReason = $loginresult['Remark'];
                    }else{
                        $this->loginFailureReason = "Wrong username or password";
                    }
                }
        }
    }
    
    public function loggedin() {
        return $this->loggedin;
    }
    
    public function getFirstname() {
        return $this->firstname;
    }
    
    public function getEmail() {
        return $this->email;
    }

    
    public function getLastname() {
        return $this->lastname;
    }
    
    public function getFullname() {
        return $this->firstname." ".$this->lastname;
    }
    
    public function getLoginFailureReason() {
        return $this->loginFailureReason;
    }
    
    public function setLoginFailureReason($v) {
        $this->loginFailureReason = $v;
    }
    
    public function setFirstname($v) {
        $this->firstname = $v;
    }
    
    public function setLastname($v) {
        $this->lastname = $v;
    }
    
    public function setEmail($v) {
        $this->email = $v;
    }
    
    public function setLoggedin($v) {
        $this->loggedin = $v;
    }
}
