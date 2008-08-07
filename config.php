<?php
return array(
	'servers' => array(
		array(
			'name'    => 'Primary Login Server',
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
			'char_map_servers' => array(
				array(
					'name'        => 'Mid-rate',
					'blvl_rate'   => 200, // Informative.
					'jlvl_rate'   => 200, // Informative.
					'drop_rate'   => 30,  // Used as a drop rate multiplier in item searches.
					
					'char_server' => array(
						'name'    => 'First Char Server',
						'address' => '218.54.139.105',
						'port'    => 5121
					),
					'map_server'  => array(
						'name'    => 'First Map Server',
						'address' => '218.54.139.105',
						'port'    => 6121
					)
				),
				array(
					'name'        => 'Low-rate',
					'blvl_rate'   => 5,
					'jlvl_rate'   => 5,
					'drop_rate'   => 2,
					
					'char_server' => array(
						'name'    => 'Second Char Server',
						'address' => '218.54.139.105',
						'port'    => 5121
					),
					'map_server'  => array(
						'name'    => 'Second Map Server',
						'address' => '218.54.139.105',
						'port'    => 6121
					)
				)
			)
		)
	)
);
?>