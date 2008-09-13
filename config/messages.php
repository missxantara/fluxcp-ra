<?php
return array(
	'NotAcceptingDonations'   => "We're sorry, but we are currently not accepting any donations.  We apologize for the inconvenience.",
	//'NotAcceptingDonations'   => "We're sorry, but our donation system is currently undergoing maintenance, please try again later.",
	'InvalidLoginServer'      => 'Invalid login server selected, please try again with a valid server.',
	'InvalidLoginCredentials' => 'Invalid login credentials, please verify that you typed the correct info and try again.',
	'UnexpectedLoginError'    => 'Unexpected error occurred, please try again or report to an admin.',
	'CriticalLoginError'      => 'Something bad happened.  Report to an administrator ASAP.',
	'UsernameAlreadyTaken'    => "The username you've chosen has already been taken by another user.",
	'UsernameTooShort'        => sprintf('Your username should be around %d to %d characters long.', Flux::config('MinUsernameLength'), Flux::config('MaxUsernameLength')),
	'UsernameTooLong'         => sprintf('Your username should be around %d to %d characters long.', Flux::config('MinUsernameLength'), Flux::config('MaxUsernameLength')),
	'PasswordTooShort'        => sprintf('Your password should be around %d to %d characters long.', Flux::config('MinPasswordLength'), Flux::config('MaxPasswordLength')),
	'PasswordTooLong'         => sprintf('Your password should be around %d to %d characters long.', Flux::config('MinPasswordLength'), Flux::config('MaxPasswordLength')),
	'PasswordsDoNotMatch'     => "Your passwords do not match, please make sure that you'ved typed them both correctly.",
	'EmailAddressInUse'       => "The e-mail address you've entered is already registered to another account.  Please use a different e-mail address.",
	'InvalidEmailAddress'     => "The e-mail address you've entered is not in a valid e-mail address format.",
	'InvalidGender'           => 'Gender should be "M" or "F"',
	'InvalidServer'           => "The server you've selected does not exist.",
	'InvalidSecurityCode'     => 'Please enter the security code as it is, case-sensitively.',
	'CriticalRegisterError'   => 'Something bad happened.  Report to an administrator ASAP.',
	'TemporarilyBanned'       => 'Your account is temporarily banned.',
	'PermanentlyBanned'       => 'Your account is permanently banned.'
);
?>