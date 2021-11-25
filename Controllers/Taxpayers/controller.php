<?php

class Taxpayerscontroller {

    private $registry;

    public function __construct($registry, $directCall = true) {
        $this->registry = $registry;

        $urlBits = $this->registry->getObject('url')->getURLBits();

        if (isset($_SESSION['loggedin'])) {
            if (isset($urlBits[1])) {
                switch (strtolower($urlBits[1])) {
                    case 'add':
                        $this->addTaxpayer();
                        break;
                    case 'edit':
                        $this->editTaxpayer();
                        break;
                    case 'delete':
                        $this->deleteTaxpayer();
                        break;

                    case 'list':
                        $this->listTaxpayers();
                        break;

                    default:
                        $this->listTaxpayers();
                        break;
                }
            } else {
                $this->listTaxpayers();
                $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/list.tpl.php', 'footer.tpl.php');
            }
        } else {
            header("location:../index.php");
        }
    }
    
    private function listTaxpayers() {
        $url = BASEURL . "/Taxpayers/getAll";
        $result = $this->registry->getObject('api')->processRequest($url, null, 'GET');
        //print_r($result);
        
        $taxpayers = $result;
        
        $cache = $this->registry->getObject('template')->cacheData($result);
        $this->registry->getObject('template')->getPage()->addTag('listTaxpayers', array('DATA', $cache));
    }

    private function loadEditForm()
    {
        $fields = array(
            "editUsername"=>"required",
            "editEmail"=>"email",
            "editTpin"=>"required",
            "editTradingName"=>"required",
            "editBusRegDate"=>"required",
            "editBusCertNum"=>"required",
            "editPhyLocation"=>"required",
            "editMobileNumber"=>"required",
        );
        
        
        
        foreach ($fields as $field => $value) {
                $this->registry->getObject("template")->getPage()->addTag($field, "");
                $this->registry->getObject("template")->getPage()->addTag($field."_error", "");
        }
    }

    private function loadAddForm()
    {
        $fields = array(
            "username"=>"email",
            "email"=>"email",
            "tpin"=>"required",
            "tradingName"=>"required",
            "busRegDate"=>"required",
            "busCertNum"=>"required",
            "phyLocation"=>"required",
            "mobileNumber"=>"required",
        );

        //Sets the value of formerror to blank
        $this->registry->getObject('template')->getPage()->addTag('formerror', '');
        
        foreach ($fields as $field => $value) {
                $this->registry->getObject("template")->getPage()->addTag($field, "");
                $this->registry->getObject("template")->getPage()->addTag($field."_error", "");
        }
    }

    public function deleteTaxpayer() {
        if (isset($_GET['tpin'])) {
            $tpin = $_GET['tpin'];
            $d = array(
                "TPIN" => "$tpin",
            );
            $data = json_encode($d);
            //print_r($data);

            $api = $this->registry->getObject("api");
            $url = BASEURL . "/Taxpayers/delete";
            $result = $api->processRequest($url, $data, 'POST');
            //print_r($result);
        }
        header("location:../index.php");
    }

    public function addTaxpayer() {
        //print_r($_POST);
        $data = array();
        $fields = array(
            "username"=>"email",
            "email"=>"email",
            "tpin"=>"required",
            "tradingName"=>"required",
            "busRegDate"=>"required",
            "busCertNum"=>"required",
            "phyLocation"=>"required",
            "mobileNumber"=>"required",
        );

        

        if (isset($_POST['taxpayerform'])) 
            {
            
            $valResult = $this->registry->getObject('validator')->validate($fields);
            //print_r($valResult); 
            $validated = "NO";
            foreach ($valResult as $key => $value) {
                $validated = $value;
                break;
            }

            if ($validated=="YES") {
                $email = $_POST['email'];
                $tpin = $_POST['tpin'];
                $tradingname = $_POST['tradingName'];
                $busregdate = $_POST['busRegDate'];
                $certnum = $_POST['busCertNum'];
                $phylocation = $_POST['phyLocation'];
                $mobnumber = $_POST['mobileNumber'];

                $d = array(
                    "TPIN" => "$tpin",
                    "BusinessCertificateNumber" => "$certnum",
                    "TradingName" => "$tradingname",
                    "BusinessRegistrationDate" => "$busregdate",
                    "MobileNumber" => "$mobnumber",
                    "Email" => "$email",
                    "PhysicalLocation" => "$phylocation",
                    "Username" => "banda.ian45@gmail.com",
                    "Deleted" => false
                );
                $data = json_encode($d);

                $api = $this->registry->getObject("api");
                $url = BASEURL . "/Taxpayers/add";
                $customresult = $api->processRequest($url, $data, 'POST');
                //print_r($result);
                
                $dbresult = json_decode($api->getDBResult(),true);
                //print_r($dbresult);
                $this->registry->getObject('template')->getPage()->addTag('formerror', $dbresult['Remark']);
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('formerror', '');
            }
        }
        else
        {
            $this->loadAddForm();
        }
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/new.tpl.php', 'footer.tpl.php');
    }

    public function editTaxpayer() {
        $data = array();
        $fields = array(
            "editUsername"=>"required",
            "editEmail"=>"email",
            "editTPIN"=>"required",
            "editTradingName"=>"required",
            "editBusinessRegistrationDate"=>"required",
            "editBusinessCertificateNumber"=>"required",
            "editPhysicalLocation"=>"required",
            "editMobileNumber"=>"required",
        );

        if (isset($_POST['editTaxpayerForm'])) {
            $valResult = $this->registry->getObject('validator')->validate($fields);
            //print_r($_POST); 
            $validated = "NO";
            foreach ($valResult as $key => $value) {
                $validated = $value;
                break;
            }

            if ($validated=="YES") {
                $email = $_POST['editEmail'];
                $tpin = $_POST['editTPIN'];
                $tradingname = $_POST['editTradingName'];
                $busregdate = $_POST['editBusinessRegistrationDate'];
                $certnum = $_POST['editBusinessCertificateNumber'];
                $phylocation = $_POST['editPhysicalLocation'];
                $mobnumber = $_POST['editMobileNumber'];

                $d = array(
                    "TPIN" => "$tpin",
                    "BusinessCertificateNumber" => "$certnum",
                    "TradingName" => "$tradingname",
                    "BusinessRegistrationDate" => "$busregdate",
                    "MobileNumber" => "$mobnumber",
                    "Email" => "$email",
                    "PhysicalLocation" => "$phylocation",
                    "Username" => "banda.ian45@gmail.com",
                    "Deleted" => false
                );
                $data = json_encode($d);

                $api = $this->registry->getObject("api");
                $url = BASEURL . "/Taxpayers/edit";
                $result = $api->processRequest($url, $data, 'POST');
                //print_r($result);
                $dbresult = json_decode($api->getDBResult(),true);
                //print_r($dbresult);
                if(isset($dbresult['Remark'])){
                    $this->registry->getObject('template')->getPage()->addTag('formerror', $dbresult['Remark']);
                } else {
                    $this->registry->getObject('template')->getPage()->addTag('formerror', '');
                }
                
            } else {
                //echo 'the form had errors';
                $this->registry->getObject('template')->getPage()->addTag('formerror', 'There were errors with the form');
            }
        }
        else
        {
            $this->registry->getObject('template')->getPage()->addTag('formerror', '');
            $this->loadEditForm();
        }
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/edit.tpl.php', 'footer.tpl.php');
    }

}

?>