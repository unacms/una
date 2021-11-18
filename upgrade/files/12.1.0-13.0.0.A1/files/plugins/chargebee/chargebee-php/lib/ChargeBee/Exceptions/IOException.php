<?php

class ChargeBee_IOException extends Exception {

    private $errorNo;

    function __construct($message, $errorNo) {
        parent::__construct($message);
        $this->errorNo = $errorNo;
    }

    public function getCurlErrorCode() {
        return $this->errorNo;
    }

}

?>
