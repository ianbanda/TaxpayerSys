<?php

class urlprocessor {

    private $urlBits = array();
    private $urlPath;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    /**
     * Set the URL path
     * @param String the url path
     */
    public function setURLPath($path) {
        $this->urlPath = $path;
    }

    /**
     * Gets data from the current URL
     * @return void
     */
    public function getURLData() {
        $urldata = ( isset($_GET['page']) ) ? $_GET['page'] : '';
        $this->urlPath = $urldata;
		//print_r($urldata);
        if ($urldata == '') {
            $this->urlBits[] = '';
            $this->urlPath = '';
        } else {
            $data = explode('/', $urldata);
            while (!empty($data) && strlen(reset($data)) === 0) {
                array_shift($data);
            }
            while (!empty($data) && strlen(end($data)) === 0) {
                array_pop($data);
            }
            $this->urlBits = $this->array_trim($data);
			//print_r($this->urlBits);
        }
        
    }

    public function getURLBits() {
        return $this->urlBits;
    }

    public function getURLBit($whichBit) {
        return ( isset($this->urlBits[$whichBit]) ) ? $this->urlBits[$whichBit] : 0;
    }

    public function getURLPath() {
        return $this->urlPath;
    }

    private function array_trim($array) {
        while (!empty($array) && strlen(reset($array)) === 0) {
            array_shift($array);
        }

        while (!empty($array) && strlen(end($array)) === 0) {
            array_pop($array);
        }

        return $array;
    }

    
}

?>