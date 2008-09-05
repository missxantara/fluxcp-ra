<?php
// This file should control all access to specified modules and actions.
return array(
	// Module/action permissions.
	// These are handled during runtime by Flux.
	'modules' => array(
		'main'      => array(
			'*'        => AccountLevel::ANYONE
		),
		'donate'    => array(
			'index'    => AccountLevel::ANYONE,
			'notify'   => AccountLevel::ANYONE,
		),
		'purchase'  => array(
			'index'    => AccountLevel::ANYONE,
			'checkout' => AccountLevel::NORMAL
		),
		'account'   => array(
			'index'    => AccountLevel::LOWGM,
			'search'   => AccountLevel::LOWGM,
			'view'     => AccountLevel::NORMAL,
			'create'   => AccountLevel::UNAUTH,
			'update'   => AccountLevel::NORMAL,
			'login'    => AccountLevel::UNAUTH,
			'logout'   => AccountLevel::NORMAL,
		),
		'character' => array(
			'index'    => AccountLevel::LOWGM,
			'search'   => AccountLevel::LOWGM,
			'view'     => AccountLevel::NORMAL,
			'update'   => AccountLevel::NORMAL
		),
		'guild'     => array(
			'index'    => AccountLevel::LOWGM,
			'search'   => AccountLevel::LOWGM,
			'view'     => AccountLevel::NORMAL,
			'save'     => AccountLevel::NORMAL
		),
		'castle'    => array(
			'index'    => AccountLevel::LOWGM,
			'view'     => AccountLevel::NORMAL,
			'save'     => AccountLevel::NORMAL
		),
		'economy'   => array(
			'index'    => AccountLevel::NORMAL
		),
		'auction'   => array(
			'index'    => AccountLevel::LOWGM,
		),
		'ranking'   => array(
			'index'    => AccountLevel::NORMAL
		),
		'item'      => array(
			'index'    => AccountLevel::NORMAL
		),
		'monster'   => array(
			'index'    => AccountLevel::NORMAL
		),
		'server'    => array(
			'status'   => AccountLevel::ANYONE,
			'log'      => AccountLevel::LOWGM
		),
		'ipban'     => array(
			'index'    => AccountLevel::ADMIN,
		),
		'service'   => array(
			'tos'      => AccountLevel::ANYONE
		),
		'install'   => array(
			'index'    => AccountLevel::ADMIN
		),
		'captcha'   => array(
			'index'    => AccountLevel::ANYONE
		),
		'test'      => array(
			'index'    => AccountLevel::ANYONE
		)
	),
	// General feature permissions, handled by the modules themselves.
	'features' => array(
		'Donate'          => AccountLevel::NORMAL,
		'Purchase'        => AccountLevel::NORMAL,
		'DeleteAccount'   => AccountLevel::HIGHGM,
		'DeleteCharacter' => AccountLevel::HIGHGM
	)
);
?>