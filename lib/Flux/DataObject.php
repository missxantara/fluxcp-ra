<?php
/**
 * Objectifies a given object.
 */
class Flux_DataObject {
	protected $object;
	
	/**
	 * Create new DataObject.
	 *
	 * @param StdClass $object
	 * @param array $default Default values
	 * @access public
	 */ 
	public function __construct(StdClass $object = null, $defaults = array())
	{
		if (is_null($object)) {
			$object = new StdClass();
		}
		
		$this->object = $object;
		
		foreach ($defaults as $prop => $value) {
			if (!isset($object->{$prop})) {
				$object->{$prop} = $value;
			}
		}
	}
	
	public function __get($prop)
	{
		if (isset($this->object->{$prop})) {
			return $this->object->{$prop};
		}
		else {
			return null;
		}
	}
}
?>