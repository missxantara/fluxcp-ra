<?php
return array(
	'servers' => array(
		'Login Server' => array(
			'address' => '218.54.139.105',
			'port'    => 6900,
			'md5auth' => false,
			'dbconf'  => array(
				'host' => '127.0.0.1',
				'user' => 'ragnarok',
				'pass' => 'ragnarok',
				'name' => 'ragnarok',
				'port' => 3306,             // -- Implied.
				'sock' => '/tmp/mysql.sock' // -- Usually never necessary.
			),
			'map_servers'  => array(
				'First Map Server' => array(
					'address' => '218.54.139.105',
					'port'    => 6121,
					'dbconf'  => '[LoginServer]' // -- Implied.
				),
				'Second Map Server' => array(
					'address' => '218.54.139.105',
					'port'    => 6121,
					'dbconf'  => '[LoginServer]' // -- Implied.
				)
			),
			'char_servers' => array(
				'First Character Server' => array(
					'address' => '218.54.139.105',
					'port'    => 5121
				),
				'Second Character Server' => array(
					'address' => '218.54.139.105',
					'port'    => 5121
				)
			)
		)
	)
);
?>