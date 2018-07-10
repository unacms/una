<?php

/**
 * Paginate through multiple pages of a resource. Used for lists that
 * may require multiple API calls to retrieve all the results.
 *
 * The pager moves forward only and can rewind to the first item.
 */
abstract class Recurly_Pager extends Recurly_Base implements Iterator, Countable
{
  private $_position = 0;    // position within the current page
  protected $_objects;       // current page of records

  /**
   * If the pager has a URL this will send a HEAD request to get the count of
   * all records. Otherwise it'll return the count of the cached _objects.
   *
   * @return integer number of records in list
   */
  public function count() {
    if (isset($this->_href)) {
      $headers = Recurly_Base::_head($this->_href, $this->_client);
      if (isset($headers['X-Records'])) {
        return intval($headers['X-Records']);
      }
    } elseif (isset($this->_objects) && is_array($this->_objects)) {
      return count($this->_objects);
    }

    return null;
  }

  /**
   * Rewind to the beginning
   */
  public function rewind() {
    $this->_loadFrom($this->_href);
    $this->_position = 0;
  }

  /**
   * The current object
   * @return Recurly_Resource the current object
   */
  public function current()
  {
    // Work around pre-PHP 5.5 issue that prevents `empty($this->count())`:
    if (!isset($this->_objects)) {
      $this->_loadFrom($this->_href);
    }

    if ($this->_position >= sizeof($this->_objects)) {
      if (isset($this->_links['next'])) {
        $this->_loadFrom($this->_links['next']);
        $this->_position = 0;
      }
      else {
        throw new Recurly_Error("Pager is not in a valid state");
      }
    }

    return $this->_objects[$this->_position];
  }

  /**
   * @return integer current position within the current page
   */
  public function key() {
    return $this->_position;
  }

  /**
   * Increments the position to the next element
   */
  public function next() {
    ++$this->_position;
  }

  /**
   * @return boolean True if the current position is valid.
   */
  public function valid() {
    return (isset($this->_objects[$this->_position]) || isset($this->_links['next']));
  }

  /**
   * Load another page of results into this pager.
   */
  protected function _loadFrom($uri) {
    if (empty($uri)) {
      return;
    }

    $response = $this->_client->request(Recurly_Client::GET, $uri);
    $response->assertValidResponse();

    $this->_objects = array();
    $this->__parseXmlToUpdateObject($response->body);
    $this->_afterParseResponse($response, $uri);
  }

  protected function _afterParseResponse($response, $uri) {
    $this->_loadLinks($response);
    $this->_href = isset($this->_links['start']) ? $this->_links['start'] : $uri;
  }

  protected static function _setState($params, $state) {
    if (is_null($params)) {
      $params = array();
    }
    $params['state'] = $state;
    return $params;
  }

  /**
   * The 'Links' header contains links to the next, previous, and starting pages.
   * This parses the links header into an array of links if the header is present.
   */
  private function _loadLinks($response) {
    $this->_links = array();

    if (isset($response->headers['Link'])) {
      $links = $response->headers['Link'];
      preg_match_all('/\<([^>]+)\>; rel=\"([^"]+)\"/', $links, $matches);
      if (sizeof($matches) > 2) {
        for ($i = 0; $i < sizeof($matches[1]); $i++) {
          $this->_links[$matches[2][$i]] = $matches[1][$i];
        }
      }
    }
  }

  protected function updateErrorAttributes() {}

  public function __toString()
  {
    $class = get_class($this);
    return "<{$class}[href={$this->getHref()}]>";
  }
}
