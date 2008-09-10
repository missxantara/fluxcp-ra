<?php
/**
 * Objectifies a given object.
 */
class Flux_DataObject {
	/**
	 * Storage object.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_data = array();
	
	/**
	 * Create new DataObject.
	 *
	 * @param StdClass $object
	 * @param array $default Default values
	 * @access public
	 */ 
	public function __construct(array $data = null, $defaults = array())
	{
		if (!is_null($data)) {
			$this->_data = $data;
		}
		
		foreach ($defaults as $prop => $value) {
			if (!isset($this->_data[$prop])) {
				$this->_data[$prop] = $value;
			}
		}
	}
	
	public function __set($prop, $value)
	{
		$this->_data[$prop] = $value;
		return $value;
	}
	
	public function __get($prop)
	{
		if (isset($this->_data[$prop])) {
			return $this->_data[$prop];
		}
		else {
			return null;
		}
	}
}
?>