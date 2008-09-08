<?php
/**
 * Draws a CAPTCHA image for use in forms such as the registration form.
 */
class Flux_Captcha {
	/**
	 * GD image resource.
	 *
	 * @access protected
	 * @var resource
	 */
	protected $gd;
	
	/**
	 * CAPTCHA options.
	 *
	 * @access public
	 * @var array
	 */
	public $options;
	
	/**
	 * Security code.
	 *
	 * @access public
	 * @var code
	 */
	public $code;
	
	/**
	 * Create new CAPTCHA.
	 */
	public function __construct($options = array())
	{
		$this->options = array_merge(
			array(
				'chars'      => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWWXYZ0123456789',
				'length'     => 5,
				'background' => FLUX_DATA_DIR.'/captcha/background.png',
				'fontPath'   => FLUX_DATA_DIR.'/captcha/fonts',
				'fontName'   => 'anonymous.gdf'
			),
			$options
		);
		
		// Let GD know where our fonts are.
		putenv("GDFONTPATH={$this->options['fontPath']}");
		
		// Generate security code.
		$this->generateCode();
		
		// Generate CAPTCHA image.
		$this->generateImage();
	}
	
	/**
	 * Generate the security code to be used.
	 *
	 * @access protected
	 */
	protected function generateCode()
	{
		$code  = '';
		$chars = str_split($this->options['chars']);
		
		for ($i = 0; $i < $this->options['length']; ++$i) {
			$code .= $chars[array_rand($chars)];
		}
		
		$this->code = $code;
		return $code;
	}
	
	/**
	 * Generate the image.
	 *
	 * @access protected
	 */
	protected function generateImage()
	{
		$this->gd = imagecreatefrompng($this->options['background']);
		$loadFont = imageloadfont($this->options['fontPath'].'/'.$this->options['fontName']);
		imagestring($this->gd, $loadFont, 15, 5, $this->code, imagecolorallocate($this->gd, 255, 255, 255));
	}
	
	/**
	 * Display image.
	 *
	 * @access public
	 */
	public function display()
	{
		header('Content-Type: image/png');
		imagepng($this->gd);
		exit;
	}
	
	public function __destruct()
	{
		imagedestroy($this->gd);
	}
}
?>