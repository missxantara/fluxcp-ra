<?php
require_once 'phpmailer/class.phpmailer.php';

class Flux_Mailer {
	protected $pm;
	
	public function __construct()
	{
		$this->pm = $pm = new PHPMailer();
		
		if (Flux::config('MailerUseSMTP')) {
			$pm->IsSMTP();
			
			if (is_array($hosts=Flux::config('MailerSMTPHosts'))) {
				$hosts = implode(';', $hosts);
			}
			
			$pm->Host = $hosts;
			
			if ($user=Flux::config('MailerSMTPUsername')) {
				$pm->SMTPAuth = true;
				
				if (Flux::config('MailerSMTPUseSSL')) {
					$pm->SMTPSecure = 'ssl';
				}
				if ($port=Flux::config('MailerSMTPPort')) {
					$pm->Port = (int)$port;
				}
				
				$pm->Username = $user;
				
				if ($pass=Flux::config('MailerSMTPPassword')) {
					$pm->Password = $pass;
				}
			}
		}
		
		// From address.
		$pm->From     = Flux::config('MailerFromAddress');
		$pm->FromName = Flux::config('MailerFromName');
		
		// Always use HTML.
		$pm->IsHTML(true);
	}
	
	public function send($recipient, $subject, $template, array $templateVars = array())
	{
		$templatePath = FLUX_DATA_DIR."/templates/$template.php";
		if (!file_exists($templatePath)) {
			return false;
		}
		
		$find = array();
		$repl = array();
		
		foreach ($templateVars as $key => $value) {
			$find[] = '{'.$key.'}';
			$repl[] = $value;
		}
		
		ob_start();
		include $templatePath;
		$content = ob_get_clean();
		
		if (!empty($find) && !empty($repl)) {
			$content = str_replace($find, $repl, $content);
		}
		
		$this->pm->AddAddress($recipient);
		
		$this->pm->Subject = $subject;
		$this->pm->Body    = $content;
		
		return $this->pm->Send();
	}
}
?>