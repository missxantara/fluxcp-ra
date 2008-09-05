<?php
// This is the application configuration file. All values have been set to
// the default, and should be changed as needed.
return array(
	'BaseURI'              => '/~kuja/flux',            // The base URI is the base web root on which your application lies.
	'SiteTitle'            => 'Flux Control Panel',     // This value is only used if the theme decides to use it.
	'ThemeName'            => 'default',                // The theme name of the theme you would like to use.  Themes are in FLUX_ROOT/themes.
	'MinUsernameLength'    => 4,                        //
	'MaxUsernameLength'    => 20,                       //
	'MinPasswordLength'    => 6,                        //
	'MaxPasswordLength'    => 20,                       //
	'AllowDuplicateEmails' => false,                    //
	'SessionKey'           => 'fluxSessionData',        // Shouldn't be changed, just specifies the session key to be used for session data.
	'DefaultModule'        => 'main',                   // This is the module to execute when none has been specified.
	'DefaultAction'        => 'index',                  // This is the default action for any module, probably should leave this alone. (Deprecated)
	'OutputCleanHTML'      => true,                     // Use this if you have Tidy installed to clean your HTML output when serving pages.
	'ShowCopyright'        => true,                     // Whether or not to show the copyright footer.
	'UseCleanUrls'         => true,                     // Set to true if you're running Apache and it supports mod_rewrite and .htaccess files.
	'DebugMode'            => true,                     // Set to false to minimize technical details from being output by Flux.
	'UseCaptcha'           => true,                     // Use CAPTCHA image for account registration to prevent automated account creations. (Requires GD2)
	'CreditExchangeRate'   => 1,                        // The rate at which credits are exchanged for dollars.
	'DonationCurrency'     => 'EUR',                    //
	'MoneyDecimalPlaces'   => 0,                        //
	'MoneyThousandsSymbol' => '.',                      //
	'MoneyDecimalSymbol'   => ',',                      //
	'AcceptDonations'      => true,                     // Whether or not to accept donations.
	'PayPalIpnUrl'         => 'www.sandbox.paypal.com', // The URL for PayPal's IPN responses (www.paypal.com for live and www.sandbox.paypal.com for testing)
	'PayPalBusinessEmail'  => 'shugotenshi@gmail.com',  // Enter the e-mail under which you have registered your business account.
	'PayPalReceiverEmails' => array(                    // These are the receiver e-mail addresses who are allowed to receive payment.
		'bytefl_1220541393_biz@gmail.com',
		'bytefl_1220541393_biz@gmail.com'
	),
	
	// These are the main menu items that should be displayed by themes.
	// They route to modules and actions.  Whether they are displayed or
	// not at any given time depends on the user's account level and/or
	// their login status.
	'MenuItems'       => array(
		'Home'          => array('module' => 'main'),
		'Register'      => array('module' => 'account', 'action' => 'create'),
		'Login'         => array('module' => 'account', 'action' => 'login'),
		'Logout'        => array('module' => 'account', 'action' => 'logout'),
		'My Account'    => array('module' => 'account', 'action' => 'view'),
		'Purchase'      => array('module' => 'purchase'),
		'Donate'        => array('module' => 'donate'),
		'Server Status' => array('module' => 'server', 'action' => 'status'),
		'Server Logs'   => array('module' => 'server', 'action' => 'log'),
		//'IP Ban List'   => array('module' => 'ipban'),
		//'Accounts'      => array('module' => 'account'),
		//'Characters'    => array('module' => 'character'),
		//'Guilds'        => array('module' => 'guild'),
		//'Castles'       => array('module' => 'castle'),
		//'Auction'       => array('module' => 'auction'),
		//'Economy'       => array('module' => 'economy'),
		//'Ranking'       => array('module' => 'ranking'),
		//'Items'         => array('module' => 'item'),
		//'Monsters'      => array('module' => 'monster'),
		'Installation'  => array('module' => 'install'),
	),
	
	// Specifies which modules and actions should be ignored by Tidy
	// (enabled/disabled by the OutputCleanHTML option).
	'TidyIgnore'      => array(
		array('module' => 'captcha')
	)
);
?>