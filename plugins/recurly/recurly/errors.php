<?php

/**
 * Exception class used by the Recurly PHP Client.
 *
 * @category   Recurly
 * @package    Recurly_Client_PHP
 * @copyright  Copyright (c) 2011 {@link http://recurly.com Recurly, Inc.}
 */
class Recurly_Error extends Exception {}

class Recurly_NotFoundError extends Recurly_Error {}

class Recurly_UnauthorizedError extends Recurly_Error {}

class Recurly_ConfigurationError extends Recurly_Error {}

class Recurly_ConnectionError extends Recurly_Error {}

class Recurly_RequestError extends Recurly_Error {}

class Recurly_ApiRateLimitError extends Recurly_RequestError {}

class Recurly_ForgedQueryStringError extends Recurly_Error {}

class Recurly_ValidationError extends Recurly_Error
{
  var $object;
  var $errors;
  
  function __construct($message, $object, $errors) {
    $this->object = $object;
    $this->errors = $errors;

    // Create a better error message
    $errs = array();
    foreach ($errors as $err) {
      if ($err instanceof Recurly_TransactionError) {
        # Return just the customer message from the transaction error
        parent::__construct($err->customer_message);
        return;
      }
      else
        $errs[] = strval($err);
    }
    $message = ucfirst(implode($errs, ', '));
    if (substr($message, -1) != '.')
      $message .= '.';
    parent::__construct($message);
  }
}

class Recurly_ServerError extends Recurly_Error {}

class Recurly_FieldError
{
  var $field;
  var $symbol;
  var $description;
  
  public function __toString() {
    if (!empty($this->field) && ($this->__readableField() != 'base')) {
      return $this->__readableField() . ' ' . $this->description;
    }
    else {
      return $this->description;
    }
  }
  
  private function __readableField() {
    if (empty($this->field))
      return null;

    $pos = strrpos($this->field, '.');
    if ($pos === false)
      return str_replace('_', ' ', $this->field);
    else
      return str_replace('_', ' ', substr($this->field, $pos + 1));
  }
}
