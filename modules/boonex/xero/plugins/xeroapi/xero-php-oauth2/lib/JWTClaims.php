<?php
namespace XeroAPI\XeroPHP;

use \Firebase\JWT\JWT;
use \Firebase\JWT\JWK;

class JWTClaims
{
    private $idToken;
    private $jwtDecoded;
    private $email;
    private $family_name;
    private $given_name;
    private $username;
    private $session_id;
    private $user_id;
    private $subvalue;
    private $expiration;
    private $auth_time;
    private $iss;
    private $at_hash;
    private $sid;
    private $authentication_event_id;
    private $aud;
    private $iat;
    private $client_id;
    private $jti;
    private $scope;
    private $nbf;

    /**
    * Decode and verify an id token, then set the JWT claim values into the object
    * @param string $token - an encrypted json web token 
    * @return object $verifiedJWT
    */
    private function verify($token) {
        $json = file_get_contents('https://identity.xero.com/.well-known/openid-configuration/jwks');
        $jwks =  json_decode($json, true);
        $supportedAlgorithm = array('RS256');
        $verifiedJWT = JWT::decode($token, JWK::parseKeySet($jwks), $supportedAlgorithm);

        return $verifiedJWT;
    }

    /**
    * Decode and verify an access token, then set the JWT claim values into the object
    * @param string $token - an encrypted json web token 
    * @return JWTClaims $this
    */
    public function decodeAccessToken($token) {
        $verifiedJWT = $this->verify($token);

        $this->nbf = $verifiedJWT->nbf;
        $this->expiration = $verifiedJWT->exp;
        $this->iss = $verifiedJWT->iss;
        $this->aud = $verifiedJWT->aud;
        $this->client_id = $verifiedJWT->client_id;
        $this->auth_time = $verifiedJWT->auth_time;
        $this->user_id = $verifiedJWT->xero_userid;
        $this->session_id = $verifiedJWT->global_session_id;
        $this->jti = $verifiedJWT->jti;
        $this->authentication_event_id = $verifiedJWT->authentication_event_id;
        $this->scope = $verifiedJWT->scope;

        return $this;
    }

    /**
    * Decode and verify an id token, then set the JWT claim values into the object
    * @param string $token - an encrypted json web token 
    * @return JWTClaims $this
    */
    public function decodeIdToken($token) {
        $verifiedJWT = $this->verify($token);

        $this->nbf = $verifiedJWT->nbf;
        $this->expiration = $verifiedJWT->exp;
        $this->iss = $verifiedJWT->iss;
        $this->aud = $verifiedJWT->aud;
        $this->iat = $verifiedJWT->iat;
        $this->at_hash = $verifiedJWT->at_hash;
        $this->sid = $verifiedJWT->sid;
        $this->subvalue = $verifiedJWT->sub;
        $this->auth_time = $verifiedJWT->auth_time;
        $this->preferred_username = $verifiedJWT->preferred_username;
        $this->email = $verifiedJWT->email;
        $this->given_name = $verifiedJWT->given_name;
        $this->family_name = $verifiedJWT->family_name;

        return $this;
        
    }

    // Deprecated in favor of token specific decode methods 4/2021
    public function decode() {
        
        if (isset($this->idToken)) {
            $tks = explode('.', $this->idToken);
            list($headb64, $bodyb64, $cryptob64) = $tks;
            $this->jwtDecoded = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64),true);
        
            $this->subvalue = $this->jwtDecoded->{'sub'};
            $this->expiration = $this->jwtDecoded->{'exp'};
            $this->email = $this->jwtDecoded->{'email'};
            $this->family_name = $this->jwtDecoded->{'family_name'};
            $this->given_name = $this->jwtDecoded->{'given_name'};
            $this->username = $this->jwtDecoded->{'preferred_username'};
            $this->session_id = $this->jwtDecoded->{'global_session_id'};
            $this->user_id = $this->jwtDecoded->{'xero_userid'};
            $this->auth_time = $this->jwtDecoded->{'auth_time'};
            $this->iss = $this->jwtDecoded->{'iss'};
            $this->at_hash = $this->jwtDecoded->{'at_hash'};

            // not every jwt token seems to contain this key!
            $this->sid = isset($this->jwtDecoded->{'sid'}) ? $this->jwtDecoded->{'sid'} : null;
        
            // No idea why these values can't be read
            //but appear when dumping jwtDecoded?!?!
            //$this->aud = $this->jwtDecoded->{'aud'};
            //$this->iat = $this->jwtDecoded->{'iat'};
        }

        if (isset($this->accessToken)) {
            $tks = explode('.', $this->accessToken);
            list($headb64, $bodyb64, $cryptob64) = $tks;
            $this->jwtAccessDecoded = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64),true);

            $this->authentication_event_id = $this->jwtAccessDecoded->{'authentication_event_id'};
        }

        return $this;
    }

    public function setTokenId($param = null) {
        $this->idToken = $param;
    }

    public function setTokenAccess($param = null) {
        $this->accessToken = $param;
    }

    // Entire JWT decoded into Object
    public function getJwtDecoded() {
        return $this->jwtDecoded;
    }

    // The user’s email address
    public function getEmail() {
        return $this->email;
    }

    // The user’s family name
    public function getFamilyName() {
        return $this->family_name;
    }

    // The user’s given name
    public function getGivenName() {
        return $this->given_name;
    }

    // The user’s preferred username
    public function getPreferredUsername() {
        return $this->username;
    }

    // The global session id
    public function getGlobalSessionId() {
        return $this->session_id;
    }

    // The user’s Xero id
    public function getXeroUserId() {
        return $this->user_id;
    }

    // The time of authentication
    public function getAuthTime() {
        return $this->auth_time;
    }

    //The unique identifier for the end user
    public function getSub() {
        return $this->subvalue;
    }

    public function getAudValue() {
        return $this->aud;
    }

    //The expiry time
    public function getExp() {
        return $this->expiration;
    }

    //The issue time
    public function getIat() {
        return $this->iat;
    }

    //The issuer of the token (i.e. https://identity.xero.com)
    public function getIss() {
        return $this->iss;
    }

    //The at hash
    public function getAtHash() {
        return $this->at_hash;
    }

    //The session id
    public function getSid() {
        return $this->sid;
    }

    //The authentication event id
    public function getAuthenticationEventId() {
        return $this->authentication_event_id;
    }

    //The client id
    public function getClientId() {
        return $this->client_id;
    }

    //The unique idetifier for the JWT
    public function getJti() {
        return $this->jti;
    }

    //The scope
    public function getScope() {
        return $this->scope;
    }

    //The identifies the time before which the JWT MUST NOT be accepted for processing
    public function getNbf() {
        return $this->nbf;
    }
}
?>