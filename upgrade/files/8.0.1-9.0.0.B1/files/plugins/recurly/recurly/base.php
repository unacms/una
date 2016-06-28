<?php

abstract class Recurly_Base
{
  protected $_href;
  protected $_client;
  protected $_links;

  public function __construct($href = null, $client = null)
  {
    $this->_href = $href;
    $this->_client = is_null($client) ? new Recurly_Client() : $client;
    $this->_links = array();
  }

  /**
   * Request the URI, validate the response and return the object.
   * @param string Resource URI, if not fully qualified, the base URL will be appended
   * @param string Optional client for the request, useful for mocking the client
   */
  public static function _get($uri, $client = null)
  {
    if (is_null($client)) {
      $client = new Recurly_Client();
    }
    $response = $client->request(Recurly_Client::GET, $uri);
    $response->assertValidResponse();
    return Recurly_Base::__parseResponseToNewObject($response, $uri, $client);
  }

  /**
   * Post to the URI, validate the response and return the object.
   * @param string Resource URI, if not fully qualified, the base URL will be appended
   * @param string Data to post to the URI
   * @param string Optional client for the request, useful for mocking the client
   */
  protected static function _post($uri, $data = null, $client = null)
  {
    if (is_null($client)) {
      $client = new Recurly_Client();
    }
    $response = $client->request(Recurly_Client::POST, $uri, $data);
    $response->assertValidResponse();
    $object = Recurly_Base::__parseResponseToNewObject($response, $uri, $client);
    $response->assertSuccessResponse($object);
    return $object;
  }

  /**
   * Put to the URI, validate the response and return the object.
   * @param string Resource URI, if not fully qualified, the base URL will be appended
   * @param string Optional client for the request, useful for mocking the client
   */
  protected static function _put($uri, $client = null)
  {
    if (is_null($client)) {
      $client = new Recurly_Client();
    }
    $response = $client->request(Recurly_Client::PUT, $uri);
    $response->assertValidResponse();
    if ($response->body) {
      $object = Recurly_Base::__parseResponseToNewObject($response, $uri, $client);
    }
    $response->assertSuccessResponse($object);
    return $object;
  }

  /**
   * Delete the URI, validate the response and return the object.
   * @param string Resource URI, if not fully qualified, the base URL will be appended
   * @param string Optional client for the request, useful for mocking the client
   */
  protected static function _delete($uri, $client = null)
  {
    if (is_null($client)) {
      $client = new Recurly_Client();
    }
    $response = $client->request(Recurly_Client::DELETE, $uri);
    $response->assertValidResponse();
    if ($response->body) {
      return Recurly_Base::__parseResponseToNewObject($response, $uri, $client);
    }
    return null;
  }

  protected static function _uriWithParams($uri, $params = null) {
    if (is_null($params) || !is_array($params)) {
      return $uri;
    }

    $vals = array();
    foreach ($params as $k => $v) {
      $vals[] = $k . '=' . urlencode($v);
    }

    return $uri . '?' . implode($vals, '&');
  }

  /**
   * Pretty string version of the object
   */
  public function __toString()
  {
    $href = $this->getHref();

    if (empty($href))
      $href = '[new]';
    else
      $href = '[href=' . $href . ']';

    $class = get_class($this);
    $values = $this->__valuesString();
    return "<$class$href $values>";
  }

  protected function __valuesString() {
    $values = array();
    ksort($this->_values);

    foreach($this->_values as $key => $value) {
      if (is_null($value)) {
        $values[] = "$key=null";
      } else if (is_int($value)) {
        $values[] = "$key=$value";
      } else if (is_bool($value)) {
        $values[] = "$key=" . ($value ? 'true' : 'false');
      } else if ($value instanceof Recurly_Stub) {
        $values[] = "$key=$value";
      } else if (is_array($value)) {
        $innerValues = array();
        foreach ($value as $innerValue)
          $innerValues[] = strval($innerValue);
        $innerValues = implode($innerValues, ', ');
        $values[] = "$key=[$innerValues]";
      } else if ($value instanceof DateTime) {
        $values[] = "$key=\"" . $value->format('Y-m-d H:i:s P') . '"';
      } else {
        $values[] = "$key=\"$value\"";
      }
    }

    return implode($values, ', ');
  }

  public function getHref() {
    return $this->_href;
  }
  public function setHref($href) {
    $this->_href = $href;
  }

  private function addLink($name, $href, $method){
    $this->_links[$name] = new Recurly_Link($name, $href, $method);
  }

  public function getLinks() {
    return $this->_links;
  }

  /* ******************************************************
     ** XML Parser
     ****************************************************** */
  /**
   * Mapping of XML node to PHP object name
   */
  static $class_map = array(
    'account' => 'Recurly_Account',
    'accounts' => 'Recurly_AccountList',
    'address' => 'Recurly_Address',
    'add_on' => 'Recurly_Addon',
    'add_ons' => 'Recurly_AddonList',
    'billing_info' => 'Recurly_BillingInfo',
    'adjustment' => 'Recurly_Adjustment',
    'adjustments' => 'Recurly_AdjustmentList',
    'coupon' => 'Recurly_Coupon',
    'unique_coupon_codes' => 'Recurly_UniqueCouponCodeList',
    'currency' => 'Recurly_Currency',
    'details' => 'array',
    'discount_in_cents' => 'Recurly_CurrencyList',
    'error' => 'Recurly_FieldError',
    'errors' => 'Recurly_ErrorList',
    'fraud' => 'Recurly_FraudInfo',
    'invoice' => 'Recurly_Invoice',
    'invoices' => 'Recurly_InvoiceList',
    'line_items' => 'array',
    'measured_unit' => 'Recurly_MeasuredUnit',
    'measured_units' => 'Recurly_MeasuredUnitList',
    'note' => 'Recurly_Note',
    'notes' => 'Recurly_NoteList',
    'plan' => 'Recurly_Plan',
    'plans' => 'Recurly_PlanList',
    'plan_code' => 'string',
    'plan_codes' => 'array',
    'pending_subscription' => 'Recurly_Subscription',
    'redemption' => 'Recurly_CouponRedemption',
    'redemptions' => 'Recurly_CouponRedemptionList',
    'setup_fee_in_cents' => 'Recurly_CurrencyList',
    'subscription' => 'Recurly_Subscription',
    'subscriptions' => 'Recurly_SubscriptionList',
    'subscription_add_ons' => 'array',
    'subscription_add_on' => 'Recurly_SubscriptionAddOn',
    'tax_detail' => 'Recurly_Tax_Detail',
    'tax_details' => 'array',
    'transaction' => 'Recurly_Transaction',
    'transactions' => 'Recurly_TransactionList',
    'transaction_error' => 'Recurly_TransactionError',
    'unit_amount_in_cents' => 'Recurly_CurrencyList',
    'usage' => 'Recurly_Usage',
    'usages' => 'Recurly_UsageList'
  );

  // Use a valid Recurly_Response to populate a new object.
  protected static function __parseResponseToNewObject($response, $uri, $client) {
    $dom = new DOMDocument();
    if (empty($response->body) || !$dom->loadXML($response->body, LIBXML_NOBLANKS)) {
      return null;
    }

    $rootNode = $dom->documentElement;

    $obj = Recurly_Resource::__createNodeObject($rootNode);
    $obj->_client = $client;
    Recurly_Resource::__parseXmlToObject($rootNode->firstChild, $obj);
    if ($obj instanceof self) {
      $obj->_afterParseResponse($response, $uri);
    }
    return $obj;
  }

  // Optional method to allow objects access to the full response with headers.
  protected function _afterParseResponse($response, $uri) { }

  // Use the XML to update $this object.
  protected function __parseXmlToUpdateObject($xml)
  {
    $dom = new DOMDocument();
    if (empty($xml) || !$dom->loadXML($xml, LIBXML_NOBLANKS)) return null;

    $rootNode = $dom->documentElement;

    if ($rootNode->nodeName == $this->getNodeName()) {
      // update the current object
      Recurly_Resource::__parseXmlToObject($rootNode->firstChild, $this);
    } else if ($rootNode->nodeName == 'errors') {
      // add element to existing object
      Recurly_Resource::__parseXmlToObject($rootNode->firstChild, $this->_errors);
    }
    $this->updateErrorAttributes();
  }

  protected static function __parseXmlToObject($node, &$object)
  {
    while ($node) {
      //print "Node: {$node->nodeType} -- {$node->nodeName}\n";

      if ($node->nodeType == XML_TEXT_NODE) {
        if ($node->wholeText != null) {
          $text = trim($node->wholeText);
          if (!empty($text)) {
            $object->description = $text;
          }
        }
      } else if ($node->nodeType == XML_ELEMENT_NODE) {
        $nodeName = str_replace("-", "_", $node->nodeName);

        if ($object instanceof Recurly_Pager) {
          $new_obj = Recurly_Resource::__createNodeObject($node);
          if (!is_null($new_obj)) {
            Recurly_Resource::__parseXmlToObject($node->firstChild, $new_obj);
            $object->_objects[] = $new_obj;
          }
          $node = $node->nextSibling;
          continue;
        } else if ($object instanceof Recurly_ErrorList) {
          if ($nodeName == 'error') {
            $object[] = Recurly_Resource::parseErrorNode($node);
            $node = $node->nextSibling;
            continue;
          }
        } else if (is_array($object)) {
          if ($nodeName == 'error') {
            $object[] = Recurly_Resource::parseErrorNode($node);
            $node = $node->nextSibling;
            continue;
          }

          $new_obj = Recurly_Resource::__createNodeObject($node);
          if (!is_null($new_obj)) {
            if (is_object($new_obj) || is_array($new_obj)) {
              Recurly_Resource::__parseXmlToObject($node->firstChild, $new_obj);
            }
            $object[] = $new_obj;
          }
          $node = $node->nextSibling;
          continue;
        }

        $numChildren = $node->childNodes->length;
        if ($numChildren == 0) {
          // No children, we might have a link
          $href = $node->getAttribute('href');
          if (!empty($href)) {
            if ($nodeName == 'a') {
              $linkName = $node->getAttribute('name');
              $method = $node->getAttribute('method');
              $object->addLink($linkName, $href, $method);
            } else {
              if (!is_object($object)) {
                $object->$nodeName = new Recurly_Stub($nodeName, $href);
              }
              else {
                $object->$nodeName = new Recurly_Stub($nodeName, $href, $object->_client);
              }
            }
          }
        } else if ($node->firstChild->nodeType == XML_ELEMENT_NODE) {
          // has element children, drop in and continue parsing
          $new_obj = Recurly_Resource::__createNodeObject($node);
          if (!is_null($new_obj)) {
            $object->$nodeName = Recurly_Resource::__parseXmlToObject($node->firstChild, $new_obj);
          }
        } else {
          // we have a single text child
          if ($node->hasAttribute('nil')) {
            $object->$nodeName = null;
          } else {
            switch ($node->getAttribute('type')) {
              case 'boolean':
                $object->$nodeName = ($node->nodeValue == 'true');
                break;
              case 'date':
              case 'datetime':
                $object->$nodeName = new DateTime($node->nodeValue);
                break;
              case 'float':
                $object->$nodeName = (float)$node->nodeValue;
                break;
              case 'integer':
                $object->$nodeName = (int)$node->nodeValue;
                break;
              case 'array':
                $object->$nodeName = array();
                break;
              default:
                $object->$nodeName = $node->nodeValue;
            }
          }
        }
      }

      $node = $node->nextSibling;
    }

    if (isset($object->_unsavedKeys))
      $object->_unsavedKeys = array();

    return $object;
  }

  private static function parseErrorNode($node)
  {
    $error = new Recurly_FieldError();
    $error->field = $node->getAttribute('field');
    $error->symbol = $node->getAttribute('symbol');
    $error->description = $node->firstChild->wholeText;

    return $error;
  }

  private static function __createNodeObject($node)
  {
    $nodeName = str_replace("-", "_", $node->nodeName);

    if (!array_key_exists($nodeName, Recurly_Resource::$class_map)) {
      return null; // Unknown element
    }

    $node_class = Recurly_Resource::$class_map[$nodeName];

    if ($node_class == null)
      return new Recurly_Object();
    else if ($node_class == 'array')
      return array();
    else if ($node_class == 'string')
      return $node->firstChild->wholeText;
    else {
      if ($node_class == 'Recurly_CurrencyList') {
        $new_obj = new $node_class($nodeName);
      } else
        $new_obj = new $node_class();

      $href = $node->getAttribute('href');
      if (!empty($href)) {
        $new_obj->setHref($href);
      }

      return $new_obj;
    }
  }
}

// In case node_class is not specified.
class Recurly_Object {}
