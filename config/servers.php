<?php
return array(
	// Example server configuration. You may have more arrays like this one to
	// specify multiple server groups (however they should share the same login
	// server whilst they are allowed to have multiple char/map pairs).
	array(
		'ServerName'     => 'FluxRO',
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
			'UseMD5'   => true,
			//'Database' => 'ragnarok'
		),
		'CharMapServers' => array(
			array(
				'ServerName'    => 'FluxRO',
				'BaseExpRates'  => 200,
				'JobExpRates'   => 200,
				'MvpExpRates'   => 200,
				'DropRates'     => 25,
				'MvpDropRates'  => 25,
				'CardDropRates' => 25,
				'MaxCharSlots'  => 9,
				//'Database'      => 'ragnarok', // Defaults to DbConfig.Database
				'CharServer'    => array(
					'Address'   => '127.0.0.1',
					'Port'      => 6121
				),
				'MapServer'     => array(
					'Address'   => '127.0.0.1',
					'Port'      => 5121
				)
			)
		)
	)
);
?>