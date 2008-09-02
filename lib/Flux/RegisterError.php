<?php
require_once 'Flux/Error.php';

class Flux_RegisterError extends Flux_Error {
	const USERNAME_ALREADY_TAKEN = 0;
	const USERNAME_TOO_SHORT     = 1;
	const USERNAME_TOO_LONG      = 2;
	const PASSWORD_TOO_SHORT     = 3;
	const PASSWORD_TOO_LONG      = 4;
	const PASSWORD_MISMATCH      = 5;
	const EMAIL_ADDRESS_IN_USE   = 6;
	const INVALID_EMAIL_ADDRESS  = 7;
	const INVALID_GENDER         = 8;
	const INVALID_SERVER         = 9;
	const INVALID_SECURITY_CODE  = 10;
}
?>