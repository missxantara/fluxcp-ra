<?php
require_once 'Flux/Captcha.php';
$captcha = new Flux_Captcha();
$session->setSecurityCodeData($captcha->code);
$captcha->display();
?>