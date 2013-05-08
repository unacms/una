<?php

/**
 * @author 
 */
abstract class PPXmlMessage
{

	/**
	 * @return string
	 */
	public function toSOAP()
	{
		return $this->toXMLString();
	}



	/**
	 * @return string
	 */
	public function toXMLString()
	{
		if (count($properties = get_object_vars($this)) >= 2 && array_key_exists('value', $properties)) {
			$attributes = array();
			foreach (array_keys($properties) as $property) {
				if ($property === 'value') continue;
				if (($annots = PPUtils::propertyAnnotations($this, $property)) && isset($annots['attribute'])) {
					if (($propertyValue = $this->{$property}) === NULL || $propertyValue == NULL) {
						$attributes[] = NULL;
						continue;
					}

					$attributes[] = $property . '="' . PPUtils::escapeInvalidXmlCharsRegex($propertyValue) . '"';
				}
			}

			if (count($attributes)) {
				return implode(' ', $attributes) . '>' . PPUtils::escapeInvalidXmlCharsRegex($this->value);
			}
		}

		$xml = array();
		foreach ($properties as $property => $defaultValue) {
			if (($propertyValue = $this->{$property}) === NULL || $propertyValue == NULL) {
				continue;
			}

			if (is_array($defaultValue) || is_array($propertyValue)) {
				foreach ($propertyValue as $item) {
					if (!is_object($item)) {
						$xml[] = $this->buildProperty($property, $item);
					}else{
						$xml[] = $this->buildProperty($property, $item);
					}
				}

			} else {
				$xml[] = $this->buildProperty($property, $propertyValue);
			}
		}

		return implode($xml);
	}



	/**
	 * @param string $property
	 * @param PPXmlMessage|string $value
	 * @param string $namespace
	 * @return string
	 */
	private function buildProperty($property, $value, $namespace = 'ebl')
	{
		$annotations = PPUtils::propertyAnnotations($this, $property);
		if (!empty($annotations['namespace'])) {
			$namespace = $annotations['namespace'];
		}
		if (!empty($annotations['name'])) {
			$property = $annotations['name'];
		}

		$el = '<' . $namespace . ':' . $property;
		if (!is_object($value)) {
			$el .= '>' . PPUtils::escapeInvalidXmlCharsRegex($value);

		} else {
			if (substr($value = $value->toXMLString(), 0, 1) === '<' || $value=='') {
				$el .= '>' . $value;

			} else {
				$el .= ' ' . $value;
			}
		}

		return $el . '</' . $namespace . ':' . $property . '>';
	}



	/**
	 * @param array $map
	 * @param string $prefix
	 */
	public function init(array $map = array(), $prefix = '')
	{
		if (empty($map)) {
			return;
		}

		if (($first = reset($map)) && !is_array($first) && !is_numeric(key($map))) {
			parent::init($map, $prefix);
			return;
		}

		$propertiesMap = PPUtils::objectProperties($this);
		$arrayCtr = array();		
		foreach ($map as $element) {
		
			if (empty($element) || empty($element['name'])) {
				continue;

			} elseif (!array_key_exists($property = strtolower($element['name']), $propertiesMap)) {
				if (!preg_match('~^(.+)[\[\(](\d+)[\]\)]$~', $property, $m)) {
					continue;
				}

				$element['name'] = $m[1];
				$element['num'] = $m[2];
			}
			$element['name'] = $propertiesMap[strtolower($element['name'])];
			if(PPUtils::isPropertyArray($this, $element['name'])) {				
				$arrayCtr[$element['name']] = isset($arrayCtr[$element['name']]) ? ($arrayCtr[$element['name']]+1) : 0;				
				$element['num'] = $arrayCtr[$element['name']];
			} 
			if (!empty($element["attributes"]) && is_array($element["attributes"])) {
				foreach ($element["attributes"] as $key => $val) {
					$element["children"][] = array(
						'name' => $key,
						'text' => $val,
					);
				}

				if (isset($element['text'])) {
					$element["children"][] = array(
						'name' => 'value',
						'text' => $element['text'],
					);
				}

				$this->fillRelation($element['name'], $element);

			} elseif (!empty($element['text'])) {
				$this->{$element['name']} = $element['text'];

			} elseif (!empty($element["children"]) && is_array($element["children"])) {
				$this->fillRelation($element['name'], $element);
			}
		}		
	}



	/**
	 * @param string $property
	 * @param array $element
	 */
	private function fillRelation($property, array $element)
	{
		if (!class_exists($type = PPUtils::propertyType($this, $property))) {
			trigger_error("Class $type not found.", E_USER_NOTICE);
			return; // just ignore
		}

		if (isset($element['num'])) { // array of objects
			$this->{$property}[$element['num']] = $item = new $type();
			$item->init($element['children']);

		} else {
			$this->{$property} = new $type();
			$this->{$property}->init($element["children"]);
		}
	}

}