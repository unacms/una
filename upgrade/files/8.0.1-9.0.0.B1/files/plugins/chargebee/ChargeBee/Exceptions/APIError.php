<?php

class ChargeBee_APIError extends Exception {

    private $httpStatusCode;    
    private $jsonObject;
    private $type;
    private $apiErrorCode;
    private $param;

    function __construct($httpStatusCode, $jsonObject) {
        parent::__construct($jsonObject['message']);
        $this->jsonObject = $jsonObject;
        $this->type = isset($jsonObject['type'])? $jsonObject['type']:null;
        $this->apiErrorCode = $jsonObject['api_error_code'];
        $this->param = isset($jsonObject['param'])?$jsonObject['param']:null;        
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getHttpStatusCode() {
        return $this->httpStatusCode;
    }

    public function getType() {
        return $this->type;
    }

    public function getApiErrorCode() {
        return $this->apiErrorCode;
    }

    public function getParam() {
        return $this->param;
    }

    /**
     * This function has been deprecated. Use getHttpStatusCode.
     * @deprecated
     */
    public function getHttpCode() {
        return $this->httpStatusCode;
    }

    /**
     * This function has been deprecated. There IO errors are now thrown as 
     * ChargeBee_IOException.
     * @deprecated
     */
    public function getErrorNo() {
        return 0;
    }

    public function getJsonObject() {
        return $this->jsonObject;
    }

}

?>
