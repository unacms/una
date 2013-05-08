<?php
 /**
  * Stub objects for Permissions 
  * Auto generated code 
  * 
  */
/**
 * 
 */
if(!class_exists('ErrorData', false)) {
class ErrorData  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var integer 	 
	 */ 
	public $errorId;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $domain;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $subdomain;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $severity;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $category;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $message;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $exceptionId;

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var ErrorParameter 	 
	 */ 
	public $parameter;


}
}



/**
 * @hasAttribute
 * 
 */
if(!class_exists('ErrorParameter', false)) {
class ErrorParameter  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 
	 * @attribute 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $name;

	/**
	 * 
	 * @access public
	 
	 
	 * @value
	 	 	 	 
	 * @var string 	 
	 */ 
	public $value;


}
}



/**
 * This is the sample message 
 */
if(!class_exists('ResponseEnvelope', false)) {
class ResponseEnvelope  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var dateTime 	 
	 */ 
	public $timestamp;

	/**
	 * Application level acknowledgment code. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $ack;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $correlationId;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $build;


}
}



/**
 * This specifies the list of parameters with every request to
 * the service. 
 */
if(!class_exists('RequestEnvelope', false)) {
class RequestEnvelope  
  extends PPMessage   {

	/**
	 * This should be the standard RFC 3066 language identification
	 * tag, e.g., en_US. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $errorLanguage;

	/**
	 * Constructor with arguments
	 */
	public function __construct($errorLanguage = NULL) {
		$this->errorLanguage = $errorLanguage;
	}


}
}



/**
 * 
 */
if(!class_exists('FaultMessage', false)) {
class FaultMessage  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var ResponseEnvelope 	 
	 */ 
	public $responseEnvelope;

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var ErrorData 	 
	 */ 
	public $error;


}
}



/**
 * Describes the request for permissions over an account.
 * Primary element is "scope", which lists the permissions
 * needed. 
 */
class RequestPermissionsRequest  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var RequestEnvelope 	 
	 */ 
	public $requestEnvelope;

	/**
	 * URI of the permissions being requested. 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $scope;

	/**
	 * URL on the client side that will be used to communicate
	 * completion of the user flow. The URL can include query
	 * parameters. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $callback;

	/**
	 * Constructor with arguments
	 */
	public function __construct($scope = NULL, $callback = NULL) {
		$this->scope = $scope;
		$this->callback = $callback;
	}


}



/**
 * Returns the temporary request token 
 */
class RequestPermissionsResponse  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var ResponseEnvelope 	 
	 */ 
	public $responseEnvelope;

	/**
	 * Temporary token that identifies the request for permissions.
	 * This token cannot be used to access resources on the
	 * account. It can only be used to instruct the user to
	 * authorize the permissions. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $token;

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var ErrorData 	 
	 */ 
	public $error;


}



/**
 * The request use to retrieve a permanent access token. The
 * client can either send the token and verifier, or a subject.
 * 
 */
class GetAccessTokenRequest  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var RequestEnvelope 	 
	 */ 
	public $requestEnvelope;

	/**
	 * The temporary request token received from the
	 * RequestPermissions call. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $token;

	/**
	 * The verifier code returned to the client after the user
	 * authorization flow completed. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $verifier;

	/**
	 * The subject email address used to represent existing 3rd
	 * Party Permissions relationship. This field can be used in
	 * lieu of the token and verifier. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $subjectAlias;


}



/**
 * Permanent access token and token secret that can be used to
 * make requests for protected resources owned by another
 * account. 
 */
class GetAccessTokenResponse  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var ResponseEnvelope 	 
	 */ 
	public $responseEnvelope;

	/**
	 * Identifier for the permissions approved for this
	 * relationship. 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $scope;

	/**
	 * Permanent access token that identifies the relationship that
	 * the user authorized. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $token;

	/**
	 * The token secret/password that will need to be used when
	 * generating the signature. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $tokenSecret;

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var ErrorData 	 
	 */ 
	public $error;


}



/**
 * Request to retrieve the approved list of permissions
 * associated with a token. 
 */
class GetPermissionsRequest  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var RequestEnvelope 	 
	 */ 
	public $requestEnvelope;

	/**
	 * The permanent access token to ask about. 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $token;

	/**
	 * Constructor with arguments
	 */
	public function __construct($token = NULL) {
		$this->token = $token;
	}


}



/**
 * The list of permissions associated with the token. 
 */
class GetPermissionsResponse  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var ResponseEnvelope 	 
	 */ 
	public $responseEnvelope;

	/**
	 * Identifier for the permissions approved for this
	 * relationship. 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $scope;

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var ErrorData 	 
	 */ 
	public $error;


}



/**
 * Request to invalidate an access token and revoke the
 * permissions associated with it. 
 */
class CancelPermissionsRequest  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var RequestEnvelope 	 
	 */ 
	public $requestEnvelope;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $token;

	/**
	 * Constructor with arguments
	 */
	public function __construct($token = NULL) {
		$this->token = $token;
	}


}



/**
 * 
 */
class CancelPermissionsResponse  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var ResponseEnvelope 	 
	 */ 
	public $responseEnvelope;

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var ErrorData 	 
	 */ 
	public $error;


}



/**
 * List of Personal Attributes to be sent as a request. 
 */
class PersonalAttributeList  
  extends PPMessage   {

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $attribute;


}



/**
 * A property of User Identity data , represented as a
 * Name-value pair with Name being the PersonalAttribute
 * requested and value being the data. 
 */
class PersonalData  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $personalDataKey;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var string 	 
	 */ 
	public $personalDataValue;


}



/**
 * Set of personal data which forms the response of
 * GetPersonalData call. 
 */
class PersonalDataList  
  extends PPMessage   {

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var PersonalData 	 
	 */ 
	public $personalData;


}



/**
 * Request to retrieve basic personal data.Accepts
 * PersonalAttributeList as request and responds with
 * PersonalDataList. This call will accept only 'Basic'
 * attributes and ignore others. 
 */
class GetBasicPersonalDataRequest  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var RequestEnvelope 	 
	 */ 
	public $requestEnvelope;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var PersonalAttributeList 	 
	 */ 
	public $attributeList;

	/**
	 * Constructor with arguments
	 */
	public function __construct($attributeList = NULL) {
		$this->attributeList = $attributeList;
	}


}



/**
 * Request to retrieve personal data.Accepts
 * PersonalAttributeList as request and responds with
 * PersonalDataList. This call will accept both 'Basic' and
 * Advanced attributes. 
 */
class GetAdvancedPersonalDataRequest  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var RequestEnvelope 	 
	 */ 
	public $requestEnvelope;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var PersonalAttributeList 	 
	 */ 
	public $attributeList;

	/**
	 * Constructor with arguments
	 */
	public function __construct($attributeList = NULL) {
		$this->attributeList = $attributeList;
	}


}



/**
 * 
 */
class GetBasicPersonalDataResponse  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var ResponseEnvelope 	 
	 */ 
	public $responseEnvelope;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var PersonalDataList 	 
	 */ 
	public $response;

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var ErrorData 	 
	 */ 
	public $error;


}



/**
 * 
 */
class GetAdvancedPersonalDataResponse  
  extends PPMessage   {

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var ResponseEnvelope 	 
	 */ 
	public $responseEnvelope;

	/**
	 * 
	 * @access public
	 
	 	 	 	 
	 * @var PersonalDataList 	 
	 */ 
	public $response;

	/**
	 * 
     * @array
	 * @access public
	 
	 	 	 	 
	 * @var ErrorData 	 
	 */ 
	public $error;


}



