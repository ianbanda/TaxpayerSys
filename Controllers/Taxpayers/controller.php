<?php

class Taxpayerscontroller {

    private $registry;

    /*
     * Main constructor for class
     */
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
                        header("location:../Taxpayers");
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
    
    /*
     * Function that gets and displays the Taxpayer list
     * 
     * 
     */
    private function listTaxpayers() {
        $url = BASEURL . "/Taxpayers/getAll";
        $result = $this->registry->getObject('api')->processRequest($url, null, 'GET');
        
        $taxpayers = $result;
        
        $cache = $this->registry->getObject('template')->cacheData($result);
        $this->registry->getObject('template')->getPage()->addTag('listTaxpayers', array('DATA', $cache));
        if(isset($_GET['remark'])){
            $this->registry->getObject('template')->getPage()->addTag('remark', $_GET['remark']);
        }
        else{
            $this->registry->getObject('template')->getPage()->addTag('remark', '');
        }
    }

    /*
     * Function that initializes the edit Taxpayer form
     * 
     * 
     */
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

    /*
     * Function that initializes the add Taxpayer form
     * 
     * 
     */
    private function loadAddForm()
    {
        $fields = array(
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

    /*
     * Function that sends the delete Taxpayer command to the Web service
     * via the api class
     * 
     * 
     */
    public function deleteTaxpayer() {
        if (isset($_GET['response']) && $_GET['response'] == "yes") {

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
            header("location:../Taxpayers?remark=Tax payer".$_GET['tpin']." was successfully deleted");
        } else {
            if (isset($_GET['tpin'])) {
                $this->showDeleteConfirmation($_GET['tpin']);
            }else
            {
                header("location:../Taxpayers/list");
            }
        }
    }
    
    /*
     * Function that calls the templates to display the delete Taxpayer prompt
     * 
     * 
     */
    public function showDeleteConfirmation($tpin) {
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/delconfirm.tpl.php', 'footer.tpl.php');
        $this->registry->getObject('template')->getPage()->addTag('tpin', $tpin);
        
    }

    /*
     * Function that validates the submitted Taxpayer creation fields and connects
     * with the webservice via the api class
     * 
     * 
     */
    public function addTaxpayer() {
        //print_r($_POST);
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/new.tpl.php', 'footer.tpl.php');
        $data = array();
        $fields = array(
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

                
                require_once 'Models/Taxpayer.php';
                $tp = new Taxpayer($this->registry,$tpin);
                $tp->setBusCertNumber($certnum);
                $tp->setPhyLocation($phylocation);
                $tp->setMobileNumber($mobnumber);
                $tp->setBusRegDate($busregdate);
                $tp->setTradingName($tradingname);
                $tp->setTpin($tpin);
                $tp->setEmail($email);
                $tp->setUsername($this->registry->getObject('auth')->getUser()->getEmail());
                //echo("Email is ".$this->registry->getObject('auth')->getUser()->getEmail());
                
                
                
                $tp->save("new");

                $apiresult = $tp->getAPIResults();
                
                //print_r($apiresult);
                if(isset($apiresult['Remark'])){
                    $this->registry->getObject('template')->getPage()->addTag('formerror', $apiresult['Remark']);
                }
                else{
                    $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/creationsuccess.tpl.php', 'footer.tpl.php');
                    $this->registry->getObject('template')->getPage()->addTag('formerror', 'Taxpayer was successfully created');
                }
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
        //$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/new.tpl.php', 'footer.tpl.php');
    }

    /*
     * Function that validates the submitted Taxpayer edit fields and connects
     * with the webservice via the api class
     * 
     * 
     */
    public function editTaxpayer() {
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/edit.tpl.php', 'footer.tpl.php');
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
            
            $tpin = "";

            if ($validated=="YES") {
                $email = $_POST['editEmail'];
                $tpin = $_POST['editTPIN'];
                $tradingname = $_POST['editTradingName'];
                $busregdate = $_POST['editBusinessRegistrationDate'];
                $certnum = $_POST['editBusinessCertificateNumber'];
                $phylocation = $_POST['editPhysicalLocation'];
                $mobnumber = $_POST['editMobileNumber'];
                
                require_once 'Models/Taxpayer.php';
                $tp = new Taxpayer($this->registry,$tpin);
                $tp->setBusCertNumber($certnum);
                $tp->setPhyLocation($phylocation);
                $tp->setMobileNumber($mobnumber);
                $tp->setBusRegDate($busregdate);
                $tp->setTradingName($tradingname);
                $tp->setTpin($tpin);
                $tp->setEmail($email);
                $tp->setUsername($this->registry->getObject('auth')->getUser()->getEmail());
                //echo("Email is ".$this->registry->getObject('auth')->getUser()->getEmail());
                
                if (isset($_POST['editTPForm'])) {

                    $tp->save("edit");

                    $apiresult = $tp->getAPIResults();
                    //print_r($dbresult);
                    if (isset($apiresult['Remark'])) {
                        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/edit.tpl.php', 'footer.tpl.php');
                        $this->registry->getObject('template')->getPage()->addTag('formerror', $apiresult['Remark']);
                    } else {
                        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/editsuccess.tpl.php', 'footer.tpl.php');
                        $this->registry->getObject('template')->getPage()->addTag('tpin', $tpin);
                    }
                }else
                {
                    $this->registry->getObject('template')->getPage()->addTag('formerror', '');
                }
                $this->registry->getObject('template')->getPage()->addTag('tpin', $tpin);
            } 
            else {
                //echo 'the form had errors';
                $this->registry->getObject('template')->getPage()->addTag('formerror', 'There were errors with the form');
            }
        }
        else
        {
            echo 'jkdfjhfjk';
            $this->registry->getObject('template')->getPage()->addTag('formerror', '');
            $this->loadEditForm();
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'Taxpayers/edit.tpl.php', 'footer.tpl.php');
        }
        
    }

}

?>