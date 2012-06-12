<?php
require_once 'Flux/Error.php';

class Flux_RegisterError extends Flux_Error {
	const USERNAME_ALREADY_TAKEN = 0;
	const USERNAME_TOO_SHORT     = 1;
	const USERNAME_TOO_LONG      = 2;
	const PASSWORD_TOO_SHORT     = 3;
	const PASSWORD_TOO_LONG      = 4;
	const PASSWORD_MISMATCH      = 5;
	const PASSWORD_NEED_UPPER    = 6;
	const PASSWORD_NEED_LOWER    = 7;
	const PASSWORD_NEED_NUMBER   = 8;
	const PASSWORD_NEED_SYMBOL   = 9;
	const EMAIL_ADDRESS_IN_USE   = 10;
	const INVALID_EMAIL_ADDRESS  = 11;
	const INVALID_GENDER         = 12;
	const INVALID_SERVER         = 13;
	const INVALID_SECURITY_CODE  = 14;
	const INVALID_USERNAME       = 15;
	const INVALID_PASSWORD       = 16;
}
?>