<?php

/**
 * taxpayer class :
 * Created by Ian Bryan Banda
 * As part of the MraChallenge Project
 */
class Taxpayer {

    /**
     * The registry object
     */
    private $registry;

    /**
     * email of the taxpayer
     */
    private $email;
    private $apiresult;

    /**
     * Deletion status of the Tax Payer
     */
    private $deleted;

    /**
     * Name of the creator
     */
    private $creatorName;

    /**
     * Username of the taxpayer
     */
    private $username;

    /**
     * Date when the taxpayer's business was registered
     */
    private $busRegDate;

    /**
     * TPIN of the taxpayer
     */
    private $tpin;

    /**
     * Physical location of the taxpayer
     */
    private $phyLocation;

    /**
     * Trading Name of the taxpayer
     */
    private $tradingName;

    /**
     * Post object - if saving the first post too
     */
    private $mobileNumber;

    /**
     * Group the taxpayer was posted within
     */
    private $busCertNumber;

    /**
     * Topic constructor
     * @param Registry $registry the registry object
     * @param int $id the ID of the taxpayer
     * @return void
     */
    public function __construct(Registry $registry, $tpin = "") {
        $this->registry = $registry;
        $this->tpin = $tpin;
    }

    /**
     * Set the username of the officer processing the taxpayer
     * @param String $username
     * @return void
     */
    public function setUsername($v) {
        $this->username = $v;
    }

    /**
     * Set the email of the taxpayer
     * @param String $email
     * @return void
     */
    public function setEmail($v) {
        $this->email = $v;
    }

    /**
     * Set the phycial location of the taxpayer
     * @param String $phylocation
     * @return void
     */
    public function setPhyLocation($v) {
        $this->phyLocation = $v;
    }

    /**
     * Set the mobile number of the taxpayer
     * @param String $mobile number
     * @return void
     */
    public function setMobileNumber($v) {
        $this->mobileNumber = $v;
    }

    /**
     * Set the business certificate number of the taxpayer
     * @param String $buscertnumber
     * @return void
     */
    public function setBusCertNumber($v) {
        $this->busCertNumber = $v;
    }

    /**
     * Set the business registration date of the taxpayer
     * @param String $busregdate
     * @return void
     */
    public function setBusRegDate($v) {
        $this->busRegDate = $v;
    }

    /**
     * Set the TPIN of the taxpayer
     * @param String $tpin
     * @return void
     */
    public function setTpin($v) {
        $this->tpin = $v;
    }

    /**
     * Set whether the taxpayer is deleted or not
     * @param String $tpin
     * @return void
     */
    public function setDeleted($v) {
        $this->deleted = $v;
    }

    /**
     * Set the Trading Name of the taxpayer
     * @param String $tradingName
     * @return void
     */
    public function setTradingName($v) {
        $this->tradingName = $v;
    }

    /**
     * Save the taxpayer into the database
     * @return void
     */
    public function save($action) {
        if ($action == "edit") {
           $d = array(
                    "TPIN" => "$this->tpin",
                    "BusinessCertificateNumber" => "$this->busCertNumber",
                    "TradingName" => "$this->tradingName",
                    "BusinessRegistrationDate" => "$this->busRegDate",
                    "MobileNumber" => "$this->mobileNumber",
                    "Email" => "$this->email",
                    "PhysicalLocation" => "$this->phyLocation",
                    "Username" => $this->username,
                    "Deleted" => false
                );
                
                $data = json_encode($d);

                $api = $this->registry->getObject("api");
                $url = BASEURL . "/Taxpayers/edit";
                $api->processRequest($url, $data, 'POST');
                //print_r($result);
                $this->apiresult = json_decode($api->getDBResult(),true);
        } 
        else if ($action == "new") {

            $d = array(
                "TPIN" => "$this->tpin",
                "BusinessCertificateNumber" => "$this->busCertNumber",
                "TradingName" => "$this->tradingName",
                "BusinessRegistrationDate" => "$this->busRegDate",
                "MobileNumber" => "$this->mobileNumber",
                "Email" => "$this->email",
                "PhysicalLocation" => "$this->phyLocation",
                "Username" => $this->username,
                "Deleted" => false
            );
            $data = json_encode($d);

            $api = $this->registry->getObject("api");
            $url = BASEURL . "/Taxpayers/add";
            $api->processRequest($url, $data, 'POST');
            //print_r($result);
            //print_r($api->getDBResult(), true);
            $apiresult = json_decode($api->getDBResult(), true);
            $this->apiresult = $apiresult;
            //print_r($apiresult);
           
        }
    }

    /**
     * Get the api result array after operations on  the taxpayer
     */
    public function getAPIResults() {
        return $this->apiresult;
    }

    /**
     * Get the username of the officer processing the taxpayer
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Get the email this taxpayer
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Get the deletion status of this taxpayer
     * @return string
     */
    public function getDeleted() {
        return $this->deleted;
    }

    /**
     * Get the business registration date this taxpayer
     * @return string
     */
    public function getBusRegDate() {
        return $this->busRegDate;
    }

    /**
     * Get the business certification number for this taxpayer
     * @return string
     */
    public function getBusCertNumber() {
        return $this->busCertNumber;
    }

    /**
     * Get the Mobile Phone number of  this taxpayer
     * @return string
     */
    public function getMobileNumber() {
        return $this->mobileNumber;
    }

    /**
     * Get the TPIN of this taxpayer
     * @return string
     */
    public function getTpin() {
        return $this->tpin;
    }

    /**
     * Get the Trading Name of this taxpayer
     * @return string
     */
    public function getTradingName() {
        return $this->tradingName;
    }

    /**
     * Delete the current taxpayer
     * @return boolean
     */
    public function delete() {
        $sql = "DELETE FROM taxpayers WHERE ID=" . $this->id;
        $this->registry->getObject('db')->executeQuery($sql);
        if ($this->registry->getObject('db')->affectedRows() > 0) {
            $sql = "DELETE FROM posts WHERE taxpayer=" . $this->id;
            $this->registry->getObject('db')->executeQuery($sql);
            $this->id = 0;
            return true;
        } else {
            return false;
        }
    }

}

?>