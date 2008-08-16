<?php
// This is the application configuration file. All values have been set to
// the default, and should be changed as needed.
return array(
	'BaseURI'       => '/~kuja/flux',        // The base URI is the base web root on which your application lies.
	'SiteTitle'     => 'Flux Control Panel', // This value is only used if the theme decides to use it.
	'ThemeName'     => 'default',            // The theme name of the theme you would like to use.  Themes are in FLUX_ROOT/themes.
	'DefaultModule' => 'main',               // This is the module to execute when none has been specified.
	'DefaultAction' => 'index',              // This is the default action for any module, probably should leave this alone.
	'UseCleanUrls'  => true,                 // Set to true if you're running Apache and it supports mod_rewrite and .htaccess files.
	'DebugMode'     => true                  // Set to false to minimize technical details from being output by Flux.
);
?>