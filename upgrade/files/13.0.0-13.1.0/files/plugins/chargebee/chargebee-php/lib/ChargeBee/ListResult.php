<?php

namespace ChargeBee\ChargeBee;

use Countable;
use ArrayAccess;
use Iterator;

class ListResult implements Countable, ArrayAccess, Iterator
{
    private $response;

    private $nextOffset;

    protected $_items;

    private $_responseHeaders;

    private $_index = 0;

    public function __construct($response, $nextOffset, $_responseHeaders = null)
    {
        $this->response = $response;
        $this->nextOffset = $nextOffset;
        $this->_responseHeaders = $_responseHeaders;
        $this->_items = [];
        $this->_initItems();
    }

    public function getResponseHeaders()
    {
        return $this->_responseHeaders;
    }

    private function _initItems()
    {
        foreach ($this->response as $r) {
            array_push($this->_items, new Result($r));
        }
    }

    public function nextOffset()
    {
        return $this->nextOffset;
    }

    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->_items);
    }

    //Implementation for ArrayAccess functions
    #[\ReturnTypeWillChange]
    public function offsetSet($k, $v)
    {
        $this->_items[$k] = $v;
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($k)
    {
        return isset($this->_items[$k]);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($k)
    {
        unset($this->_items[$k]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($k)
    {
        return isset($this->_items[$k]) ? $this->_items[$k] : null;
    }

    //Implementation for Iterator functions
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->_items[$this->_index];
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->_index;
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        ++$this->_index;
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->_index = 0;
    }

    #[\ReturnTypeWillChange]
    public function valid()
    {
        if ($this->_index < count($this->_items)) {
            return true;
        } else {
            return false;
        }
    }

    public function toJson()
    {
        return json_encode($this->response);
    }
}
