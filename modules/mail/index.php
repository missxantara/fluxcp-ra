<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Form Mailer';
$preview = '';

if (count($_POST)) {
	$prev    = (bool)$params->get('_preview');
	$to      = trim($params->get('to'));
	$subject = trim($params->get('subject'));
	$body    = trim($params->get('body'));
	
	if (!$to) {
		$errorMessage = 'Please enter a "to" address.';
	}
	elseif (!$subject) {
		$errorMessage = 'Please enter a subject.';
	}
	elseif (!$body) {
		$errorMessage = 'Please enter some body text.';
	}
	
	if (empty($errorMessage)) {
		if ($prev) {
			require_once 'markdown/markdown.php';
			$preview = Markdown($body);
		}
		else {
			require_once 'Flux/Mailer.php';
			
			$mail = new Flux_Mailer();
			$opts = array('_ignoreTemplate' => true, '_useMarkdown' => true);
			
			if ($mail->send($to, $subject, $body, $opts)) {
				$session->setMessageData("Your e-mail has been successfully sent to $to.");
				$this->redirect();
			}
			else {
				$errorMessage = 'The mailer system failed to send the e-mail.  This could be a misconfiguration.';
			}
		}
	}
}
?>