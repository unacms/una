<?php

class ChargeBee_Model
{

	protected $_values;

  protected $_subTypes;

	protected $_data = array();

	protected $allowed = array();

	function __construct($values, $subTypes=array())
	{
		$this->_values = $values;
		$this->_subTypes = $subTypes;
		$this->_load();
	}
	
	public function param($k)
	{
	  if(array_key_exists($k, $this->_values))
	  {
	    return $this->_values[$k];
	  }
	  else
	  {
	    throw new Exception("Unknown param $k in " . get_class($this));
	  }
	}
	
	public function __set($k, $v)
	{
		$this->_data[$k] = $v;
	}
	
	public function __isset($k)
	{
		return isset($this->_data[$k]);
	}
	
	public function __unset($k)
	{
		unset($this->data[$k]);
	}
	
	public function __get($k)
	{
		if (isset($this->_data[$k])) 
		{
			return $this->_data[$k];
		} 
		else if(in_array($k, $this->allowed))
		{
			return null;
		}
        else if(substr($k, 0, 2) == "cf") //All the custom fields start with prefix cf. 
        { 
               return null;
        } 
		else
		{
			throw new Exception("Unknown property $k in " . get_class($this));
		}
	}

  private function isHash($array)
  {
    if (!is_array($array))
      return false;
    foreach (array_keys($array) as $k) {
      if (is_numeric($k))
        return false;
    }
    return true;
  }

	private function _load()
	{
		foreach($this->_values as $k => $v) 
		{
			$setVal = null;
			if($this->isHash($v) && array_key_exists($k, $this->_subTypes))
			{
				$setVal = new $this->_subTypes[$k]($v);
			}
			else if(is_array($v) && array_key_exists($k, $this->_subTypes))
			{
					$setVal = array();
					foreach($v as $stV)
					{
						array_push($setVal, new $this->_subTypes[$k]($stV));
					}
			}
			if(is_null($setVal))
			{
				$setVal = $v;
			}
			$this->_data[ChargeBee_Util::toCamelCaseFromUnderscore($k)] = $setVal;
		}
	}

}

?>
