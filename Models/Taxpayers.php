<?php

/**
 * Users model
 */
class Users {

    /**
     * Messages constructor
     * @param Registry $registry
     * @return void
     */
    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    /**
     * Get a sponsored users 
     * @param int $user the user
     * @return int the cache of messages
     */
    public function getSponsored() {
        $details = ", 
		(
			IF(itemtype='page'
				,(SELECT pagetype FROM pages WHERE ID=sponsoreditem LIMIT 1)
				,(SELECT CONCAT(gender,', ',(SELECT height FROM modelbody WHERE user_id = prof1.user_id LIMIT 1)) FROM profile prof1 WHERE user_id=sponsoreditem LIMIT 1)
			)
		) AS details";
        $picdetails = ", 
		(
			IF(itemtype='page'
				,(SELECT IFNULL((SELECT name FROM pics WHERE pics.ID=pagelogo LIMIT 1),'page.png') FROM pages WHERE ID=sponsoreditem LIMIT 1)
				,(SELECT (SELECT IFNULL(name,IF(prof2.gender='male','1.png','2.png')) FROM pics WHERE pics.ID=prof2.photo LIMIT 1) FROM profile prof2 WHERE user_id=sponsoreditem LIMIT 1)
			)
		) AS upic";

        $linkdetails = ", 
		(
			IF(itemtype='page'
				,(CONCAT('{bits}page/',sponsoreditem))
				,(CONCAT('{bits}profile/',sponsoreditem))
			)
		) AS link";


        $sql = "SELECT *
		, (IF(itemtype='page',(SELECT pagename FROM pages WHERE ID=sponsoreditem LIMIT 1),(SELECT CONCAT(firstname,' ',othernames,' ',surname) FROM profile WHERE user_id=sponsoreditem LIMIT 1))) AS name
		" . $details . "
		" . $picdetails . "
		" . $linkdetails . "
		 FROM sponsored";
        $cache = $this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }

    /**
     * Get a sponsored users 
     * @param int $user the user
     * @return int the cache of messages
     */
    public function getUserPhotos() {
        $sql = "SELECT * FROM pics";
        $cache = $this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }

    /**
     * Get a sponsored users 
     * @param int $user the user
     * @return int the cache of messages
     */
    public function changeUserAZcardImg($photo, $position, $user) {
        $sql = "";

        if ($position == "front") {
            $sql = "UPDATE azcardsettings SET frontpic='" . $photo . "' WHERE user_id='$user'";
        } else {
            $sql = "UPDATE azcardsettings SET back$position='" . $photo . "' WHERE user_id='$user'";
        }

        $this->registry->getObject('db')->cacheQuery($sql);
    }

    /**
     * Get a sponsored users 
     * @param int $user the user
     * @return int the cache of messages
     */
    public function changeTopDigit($photo, $position, $user) {
        $sql = "";

        if ($position != "") {
            $sql = "UPDATE topdigitalsettings SET td$position='" . $photo . "' WHERE user_id='$user'";
        }

        $this->registry->getObject('db')->cacheQuery($sql);
    }

    public function getUserSimple(Registry $registry, $value) {
        $user = NULL;

        $sql = "SELECT * FROM users WHERE ID='{$value}' OR username = '{$value}'";


        $this->registry->getObject('db')->executeQuery($sql);

        if ($this->registry->getObject('db')->numRows() >= 1) {
            $data = $this->registry->getObject('db')->getRows();
            //$this->id = $data['ID'];
            require_once( FRAMEWORK_PATH . 'Registry/user.class.php');
            //echo "User was found";
            $user = new User($registry, $data['ID'], $username = $data['username'], $password = $data['password_hash']);
        }



        return $user;
    }

    public function getUserProfile(Registry $registry, $value) {
        $user = NULL;

        $sql = "SELECT u.*
			, (SELECT photo FROM profile WHERE user_id=u.ID LIMIT 1) AS profpic
			, (SELECT weight FROM modelbody WHERE user_id=u.ID LIMIT 1) AS weight
		FROM users u WHERE (ID='{$value}' OR username = '{$value}')";


        $this->registry->getObject('db')->executeQuery($sql);

        if ($this->registry->getObject('db')->numRows() >= 1) {
            $data = $this->registry->getObject('db')->getRows();
            //$this->id = $data['ID'];
            //print_r($data);
            require_once( FRAMEWORK_PATH . 'Registry/user.class.php');
            //echo "User was found";
            $user = new User($registry, $data['ID'], $data['username'], $data['password_hash']);
        }



        return $user;
    }

    public function getMostActive(Registry $registry) {
        $user = NULL;

        $sql = "SELECT * FROM users u ORDER BY activityindex DESC LIMIT 1";


        $this->registry->getObject('db')->executeQuery($sql);

        if ($this->registry->getObject('db')->numRows() >= 1) {
            $data = $this->registry->getObject('db')->getRows();
            //$this->id = $data['ID'];
            //print_r($data);
            require_once( FRAMEWORK_PATH . 'Registry/user.class.php');
            //echo "User was found";
            $user = new User($registry, $data['ID'], $data['username'], $data['password_hash']);
        }



        return $user;
    }

    public function displayReport(Report $report) {
        $data = array();
        $data = $report->getRows();

        if ($data != null && count($data) > 0) {
            $cacheid = $this->registry->getObject('db')->cacheData($data);

            $this->registry->getObject('template')->getPage()->addTag('reportrows', array('DATA', $cacheid));
            $this->registry->getObject('template')->getPage()->addTag('stdname', $report->getStudentName());
            $this->registry->getObject('template')->getPage()->addTag('stdclass', $report->getClassName());
            $this->registry->getObject('template')->getPage()->addTag('reportterm', $report->getTerm());
            $this->registry->getObject('template')->getPage()->addTag('reportyear', $report->getYear());
        } else {
            //echo "Report DOesnt exist";
        }
    }

    public function saveUserUpdate($table, $update, $userid) {
        $flag = false;
        $this->registry->getObject('db')->updateRecords($table, $update, $userid);
        $flag = true;
        return $flag;
    }

    public function saveNewUser($arg, $action) {
        //print_r($arg);
        echo $action;
        if ($action == "edit") {
            $update = array();
            $update['description'] = $this->description;
            $update['name'] = $this->name;
            $update['type'] = $this->type;
            $update['creator'] = $this->creator;
            $update['active'] = $this->active;
            $update['created'] = $this->created;
            $this->registry->getObject('db')->updateRecords('groups', $update, 'ID=' . $this->id);
        } else {
            $insert = array();
            $insertprofile = array();
            $insertprofilesettings = array();
            $insertazcardsettings = array();
            $inserttdsettings = array();
            $insertcontacts = array();
            $insertsizes = array();
            $insertbody = array();

            //$insert = $arg;
            //print_r($insert);
            /* $insert['description'] = $this->description;
              $insert['name'] = $this->name;
              $insert['type'] = $this->type;
              $insert['creator'] = $this->creator; */
            //$insert['active'] = $this->active;

            for ($ctr = 0; $ctr < count($arg); $ctr++) {
                $field = $arg[$ctr];
                //echo "Before: ".$field." AFter: ".substr($field,9)."<br/>";
                if ($field == 'register_username' || $field == 'register_email') {
                    $insert[substr($field, 9)] = $_SESSION[$field];
                } else if ($field == 'register_terms') {
                    //$insert[substr($field,9)] = $_SESSION[$field];
                } else if ($field == 'register_password' || $field == 'register_password_confirm') {
                    $insert['password_hash'] = md5($_SESSION['register_password']);
                    //$insertprofile['password'] = $_SESSION[$field];
                } else if ($field == 'register_user_dob') {
                    $insertprofile['user_dob'] = $_POST['register_user_yob'] . "-" . $_POST['register_user_mob'] . "-" . $_POST['register_user_dob'];
                    $insertprofile['name'] = "name";
                    $insertprofile['photo'] = "1";
                    $insertprofile['bio'] = "n/a";
                    $insertprofile['residence'] = "n/a";
                    //$insertprofile['password'] = $_SESSION[$field];
                } else if ($field == 'register_phone1' || $field == 'register_phone2' || $field == 'register_email' || $field == 'register_website' || $field == 'register_currloc') {
                    //$insertcontacts['password_hash'] = md5( $_SESSION['register_password'] );
                    $insertcontacts[substr($field, 9)] = $_SESSION[$field];
                    //$insertprofile['password'] = $_SESSION[$field];
                } else {
                    $insertprofile[substr($field, 9)] = $_SESSION[$field];
                }
            }
            /* for($insert as $key => $value){
              //$field = $arg[$ctr];
              echo $value;
              $field = $value;
              $insert[$field] = $_SESSION[$field];
              } */

            //$insert['pagecreator'] = $this->registry->getObject('authenticate')->getUser()->getUserID();

            $this->registry->getObject('db')->insertRecords('users', $insert);
            $this->id = $this->registry->getObject('db')->lastInsertID();

            $insertprofile['user_id'] = $this->id;
            $insertcontacts['user_id'] = $this->id;
            $this->registry->getObject('db')->insertRecords('profile', $insertprofile);
            $this->registry->getObject('db')->insertRecords('contacts', $insertcontacts);

            $insertprofilesettings['user_id'] = $this->id;
            $this->registry->getObject('db')->insertRecords('profilesettings', $insertprofilesettings);

            $insertazcardsettings['user_id'] = $this->id;
            $this->registry->getObject('db')->insertRecords('azcardsettings', $insertazcardsettings);

            $inserttdsettings['user_id'] = $this->id;
            //$this->registry->getObject('db')->insertRecords( 'topdigitalsettings', $inserttdsettings);
            $this->registry->getObject('db')->insertRecords('topdigitalsettings', $inserttdsettings);

            $insertsizes['user_id'] = $this->id;
            $insertsizes['shoes'] = "0";
            $insertsizes['suits'] = "0";
            $insertsizes['shirts'] = "0";
            $insertsizes['pants'] = "0";
            $insertsizes['dress'] = "0";
            $insertsizes['skirt'] = "0";
            $insertsizes['chests'] = "0";
            $insertsizes['suit_length'] = "0";
            $insertsizes['bra'] = "0";
            $this->registry->getObject('db')->insertRecords('clothsizes', $insertsizes);

            $insertbody['user_id'] = $this->id;
            $insertbody['height'] = "0";
            $insertbody['hunit'] = "0";
            $insertbody['weight'] = "0";
            $insertbody['wunit'] = "0";
            $insertbody['eyes'] = "0";
            $insertbody['eyedesc'] = "0";
            $insertbody['hair'] = "0";
            $insertbody['hairdesc'] = "0";
            $insertbody['tattoo'] = "0";
            $insertbody['tattoodesc'] = "0";
            $this->registry->getObject('db')->insertRecords('modelbody', $insertbody);

            $inserttdpics = array();
            for ($i = 1; $i <= 10; $i++) {
                $inserttdpics['user_id'] = $this->id;
                $inserttdpics['picfor'] = "topdigital";
                $this->registry->getObject('db')->insertRecords('pics', $inserttdpics);
            }

            
        }
    }

}

?>