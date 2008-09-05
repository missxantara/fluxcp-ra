<?php
class Flux_LogFile {
	private $fp;
	public $filename;
	public $dateFormat = '[Y-m-d h:i:s] ';
	
	public function __construct($filename, $mode = 'a')
	{
		$this->filename = realpath(dirname($filename)).'/'.basename($filename);
		$this->fp = fopen($this->filename, 'a');
	}
	
	public function __destruct()
	{
		if ($this->fp) {
			fclose($this->fp);
		}
	}
	
	public function puts()
	{
		$args = func_get_args();
		if (count($args) > 0) {
			$args[0]   = sprintf("%s%s\n", date($this->dateFormat), $args[0]);
			$arguments = array_merge(array($this->fp), $args);
			return call_user_func_array('fprintf', $arguments);
		}
		else {
			return false;
		}
	}
}
?>