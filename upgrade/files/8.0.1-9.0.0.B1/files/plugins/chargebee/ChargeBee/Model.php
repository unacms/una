<?php

class ChargeBee_Model
{

	protected $_values;
	
	protected $_subTypes;
	protected $_dependantTypes;

	protected $_data = array();

	protected $allowed = array();

	function __construct($values, $subTypes=array(), $dependantTypes=array())
	{
		$this->_values = $values;
		$this->_subTypes = $subTypes;
		$this->_dependantTypes=$dependantTypes;
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
	
	private function __getDependant($k)
	{
		if (isset($this->_dependantTypes[$k])) 
		{
			return $this->_dependantTypes[$k];
		} 
		else if(in_array($k, $this->allowed))
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
			if($this->isHash($v) && array_key_exists($k, $this->_dependantTypes))
			{
				continue;
			}
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
	
	public function _initDependant($obj, $type, $subTypes = array())
	{
		if(!array_key_exists($type, $obj))
		{
	    	return $this;
		}
		$class=$this->__getDependant($type);
		if($this->isHash($obj) && !is_null($class))
		{
			$dependantObj = new $class($obj[$type], $subTypes);
			$this->_data[ChargeBee_Util::toCamelCaseFromUnderscore($type)] = $dependantObj;
		}
		return $this;
	}
	
	public function _initDependantList($obj, $type, $subTypes = array())
	{
		if(!array_key_exists($type, $obj))
		{
	    	return $this;
		}
		if(!is_array($obj[$type])){
			return $this;
		}
		$class=$this->__getDependant($type);
		if(!is_null($class)){
			$setVal = array();
			foreach($obj[$type] as $dt)
			{
				array_push($setVal, new $class($dt, $subTypes));
			}
			$this->_data[ChargeBee_Util::toCamelCaseFromUnderscore($type)] = $setVal;
		}
		return $this;
	}	
			
}

?>
