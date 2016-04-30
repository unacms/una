<?php

/**
 * Paginate through multiple pages of a resource. Used for lists that
 * may require multiple API calls to retrieve all the results.
 *
 * The pager moves forward only and can rewind to the first item.
 */
abstract class Recurly_Pager extends Recurly_Base implements Iterator
{
  private $_position = 0;    // position within the current page
  protected $_count = null;  // total number of records
  protected $_objects;       // current page of records

  /**
   * Number of records in this list.
   * @return integer number of records in list
   */
  public function count() {
    if (!isset($this->_count)) {
      if (isset($this->_objects)) {
        $this->_count = count($this->_objects);
      } elseif (isset($this->_href)) {
        // Don't bother with the HEAD request the server takes the same amount
        // of time to generate them so, might as well just get the results at
        // the same time.
        $this->_loadFrom($this->_href);
      }
    }
    return $this->_count;
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
    if ($this->count() == 0) {
      return null;
    }

    while ($this->_position >= sizeof($this->_objects)) {
      if (isset($this->_links['next'])) {
        $this->_loadFrom($this->_links['next']);
        $this->_position = 0;
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
  protected function _loadFrom($uri, $params = null) {
    if (empty($uri)) {
      return;
    }

    $uri = Recurly_Base::_uriWithParams($uri, $params);
    $response = $this->_client->request(Recurly_Client::GET, $uri);
    $response->assertValidResponse();

    $this->_objects = array();
    $this->__parseXmlToUpdateObject($response->body);
    $this->_afterParseResponse($response, $uri);
  }

  protected function _afterParseResponse($response, $uri) {
    $this->_href = $uri;
    $this->_loadRecordCount($response);
    $this->_loadLinks($response);
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

  /**
   * Find the total number of results in the collection from the 'X-Records' header.
   */
  private function _loadRecordCount($response)
  {
    if (isset($response->headers['X-Records'])) {
      $this->_count = intval($response->headers['X-Records']);
    }
  }

  protected function updateErrorAttributes() {}


  public function __toString()
  {
    $class = get_class($this);
    $count = (!empty($this->_count) ? "count={$this->_count}" : '');

    return "<{$class}[href={$this->getHref()}] $count>";
  }
}
