<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api {

    private $dbResult;
    
    function __construct() {
        
    }

    function processRequest($url, $data, $method) {

        $postheaders = "empty:object";
        
        if($method=="POST"){
            $postheaders = "Content-Length:" . strlen($data);
        }
        $headers = array(
            "Content-Type:application/json",
            "candidateid:banda.ian45@gmail.com",
            "password:password000122",
            "apikey:3fdb48c5-336b-47f9-87e4-ae73b8036a1c"
            ,$postheaders
        );

        $ch = curl_init($url);
        
        if($method=="POST"){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $this->dbResult = $result;
        curl_close($ch);
        //print_r($result);//array printout used during debugging

        //$result = file_get_contents($url);
        $jresult = json_decode($result, true);
        //var_dump(json_decode($result, true));
        //print_r($jresult);//array printout used during debugging
        return $jresult;
    }

    public function getDBResult() {
        return $this->dbResult;
    }
}
