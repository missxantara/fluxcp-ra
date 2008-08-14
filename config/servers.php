<?php
return array(
	// Example server configuration. You may have more arrays like this one to
	// specify multiple server groups (however they should share the same login
	// server whilst they are allowed to have multiple char/map pairs).
	array(
		'ServerName'     => 'MyRO',
		// Global database configuration (excludes logs database configuration).
		'DbConfig'       => array(
			//'Socket'     => '/tmp/mysql.sock',
			//'Port'       => 3306,
			'Hostname'   => '127.0.0.1',
			'Username'   => 'ragnarok',
			'Password'   => 'ragnarok',
			'Database'   => 'ragnarok',
			'Persistent' => true
		),
		// This is kept separate because many people choose to have their logs
		// database accessible under different credentials, and often on a
		// different server entirely to ensure the reliability of the log data.
		'LogsDbConfig'   => array(
			//'Socket'   => '/tmp/mysql.sock',
			//'Port'     => 3306,
			'Hostname'   => '127.0.0.1',
			'Username'   => 'ragnarok',
			'Password'   => 'ragnarok',
			'Database'   => 'ragnarok',
			'Persistent' => true
		),
		// Login server configuration.
		'LoginServer'    => array(
			'Address'  => '127.0.0.1',
			'Port'     => 6900,
			'UseMD5'   => false,
			//'Database' => 'ragnarok'
		),
		'CharMapServers' => array(
			array(
				'ServerName'   => "Foo",
				'BaseExpRates' => 5,
				'JobExpRates'  => 5,
				'DropRates'    => 3,
				//'Database'     => 'ragnarok', // Defaults to DbConfig.Database
				'CharServer'   => array(
					'Address'  => '127.0.0.1',
					'Port'     => 6121
				),
				'MapServer'    => array(
					'Address'  => '127.0.0.1',
					'Port'     => 5121
				)
			),
			array(
				'ServerName'   => "Bar",
				'BaseExpRates' => 200,
				'JobExpRates'  => 200,
				'DropRates'    => 25,
				//'Database'     => 'ragnarok', // Defaults to DbConfig.Database
				'CharServer'   => array(
					'Address'  => '127.0.0.1',
					'Port'     => 6121
				),
				'MapServer'    => array(
					'Address'  => '127.0.0.1',
					'Port'     => 5121
				)
			),
			array(
				'ServerName'   => "Baz",
				'BaseExpRates' => 200,
				'JobExpRates'  => 200,
				'DropRates'    => 25,
				//'Database'     => 'ragnarok', // Defaults to DbConfig.Database
				'CharServer'   => array(
					'Address'  => '127.0.0.1',
					'Port'     => 6121
				),
				'MapServer'    => array(
					'Address'  => '127.0.0.1',
					'Port'     => 5121
				)
			)
		)
	),
	// Second server configuration example ;)
	array(
		'ServerName'     => 'HisRO',
		'DbConfig'       => array(
			'Hostname'   => '127.0.0.1',
			'Username'   => 'ragnarok',
			'Password'   => 'ragnarok',
			'Database'   => 'ragnarok',
			'Persistent' => true
		),
		'LogsDbConfig'   => array(
			'Hostname'   => '127.0.0.1',
			'Username'   => 'ragnarok',
			'Password'   => 'ragnarok',
			'Database'   => 'ragnarok',
			'Persistent' => true
		),
		'LoginServer'    => array(
			'Address'  => '127.0.0.1',
			'Port'     => 6900,
			'UseMD5'   => false,
		),
		'CharMapServers' => array(
			array(
				'ServerName'   => "Qux",
				'BaseExpRates' => 5,
				'JobExpRates'  => 5,
				'DropRates'    => 3,
				'CharServer'   => array(
					'Address'  => '127.0.0.1',
					'Port'     => 6121
				),
				'MapServer'    => array(
					'Address'  => '127.0.0.1',
					'Port'     => 5121
				)
			),
			array(
				'ServerName'   => "Quux",
				'BaseExpRates' => 5,
				'JobExpRates'  => 5,
				'DropRates'    => 3,
				'CharServer'   => array(
					'Address'  => '127.0.0.1',
					'Port'     => 6121
				),
				'MapServer'    => array(
					'Address'  => '127.0.0.1',
					'Port'     => 5121
				)
			)
		)
	),
	// Third server configuration example! :D
	array(
		'ServerName'     => 'HerRO',
		'DbConfig'       => array(
			'Hostname'   => '127.0.0.1',
			'Username'   => 'ragnarok',
			'Password'   => 'ragnarok',
			'Database'   => 'ragnarok',
			'Persistent' => true
		),
		'LogsDbConfig'   => array(
			'Hostname'   => '127.0.0.1',
			'Username'   => 'ragnarok',
			'Password'   => 'ragnarok',
			'Database'   => 'ragnarok',
			'Persistent' => true
		),
		'LoginServer'    => array(
			'Address'  => '127.0.0.1',
			'Port'     => 6900,
			'UseMD5'   => false,
		),
		'CharMapServers' => array(
			array(
				'ServerName'   => "Spam",
				'BaseExpRates' => 5,
				'JobExpRates'  => 5,
				'DropRates'    => 3,
				'CharServer'   => array(
					'Address'  => '127.0.0.1',
					'Port'     => 6121
				),
				'MapServer'    => array(
					'Address'  => '127.0.0.1',
					'Port'     => 5121
				)
			),
			array(
				'ServerName'   => "Ham",
				'BaseExpRates' => 5,
				'JobExpRates'  => 5,
				'DropRates'    => 3,
				'CharServer'   => array(
					'Address'  => '127.0.0.1',
					'Port'     => 6121
				),
				'MapServer'    => array(
					'Address'  => '127.0.0.1',
					'Port'     => 5121
				)
			),
			array(
				'ServerName'   => "Eggs",
				'BaseExpRates' => 5,
				'JobExpRates'  => 5,
				'DropRates'    => 3,
				'CharServer'   => array(
					'Address'  => '127.0.0.1',
					'Port'     => 6121
				),
				'MapServer'    => array(
					'Address'  => '127.0.0.1',
					'Port'     => 5121
				)
			)
		)
	)
);
?>