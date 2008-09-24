<?php
// This file should control all access to specified modules and actions.
return array(
	// Module/action permissions.
	// These are handled during runtime by Flux.
	// '*' is a default that is checked for any action that has not been
	// specified an access level.
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
			'buy'      => AccountLevel::NORMAL,
			'add'      => AccountLevel::NORMAL,
			'clear'    => AccountLevel::NORMAL,
			'cart'     => AccountLevel::NORMAL,
			'checkout' => AccountLevel::NORMAL,
			'remove'   => AccountLevel::NORMAL
		),
		'itemshop'  => array(
			'add'      => AccountLevel::ADMIN,
			'edit'     => AccountLevel::ADMIN,
			'delete'   => AccountLevel::ADMIN
		),
		'account'   => array(
			'index'    => AccountLevel::LOWGM,
			'view'     => AccountLevel::NORMAL,
			'create'   => AccountLevel::UNAUTH,
			'login'    => AccountLevel::UNAUTH,
			'logout'   => AccountLevel::NORMAL
		),
		'character' => array(
			'index'    => AccountLevel::LOWGM,
			'view'     => AccountLevel::NORMAL
		),
		'guild'     => array(
			'emblem'   => AccountLevel::ANYONE,
			'index'    => AccountLevel::LOWGM,
			'export'   => AccountLevel::ADMIN,
			'view'     => AccountLevel::ADMIN
		),
		'castle'    => array(
			'index'    => AccountLevel::LOWGM
		),
		'economy'   => array(
			'index'    => AccountLevel::NORMAL
		),
		'auction'   => array(
			'index'    => AccountLevel::LOWGM
		),
		'ranking'   => array(
			'index'    => AccountLevel::NORMAL
		),
		'item'      => array(
			'index'    => AccountLevel::NORMAL,
			'view'     => AccountLevel::NORMAL
		),
		'monster'   => array(
			'index'    => AccountLevel::NORMAL,
			'view'     => AccountLevel::NORMAL
		),
		'server'    => array(
			'status'     => AccountLevel::ANYONE,
			'status-xml' => AccountLevel::ANYONE
		),
		'logdata'   => array(
			'index'   => AccountLevel::ADMIN,
			'paypal'  => AccountLevel::ADMIN,
			'txnview' => AccountLevel::ADMIN,
			'char'    => AccountLevel::ADMIN,
			'inter'   => AccountLevel::ADMIN,
			'command' => AccountLevel::ADMIN,
			'branch'  => AccountLevel::ADMIN,
			'chat'    => AccountLevel::ADMIN,
			'login'   => AccountLevel::ADMIN,
			'mvp'     => AccountLevel::ADMIN,
			'npc'     => AccountLevel::ADMIN,
			'pick'    => AccountLevel::ADMIN,
			'zeny'    => AccountLevel::ADMIN
		),
		'ipban'     => array(
			'index'    => AccountLevel::ADMIN,
		),
		'service'   => array(
			'tos'      => AccountLevel::ANYONE
		),
		'captcha'   => array(
			'index'    => AccountLevel::ANYONE
		),
		'install'   => array(
			'index'    => AccountLevel::ANYONE
		),
		'test'      => array(
			'*'        => AccountLevel::ANYONE
		),
		'reload'    => array(
			'index'   => AccountLevel::ADMIN,
			'mobskill' => AccountLevel::ADMIN
		)
	),
	// General feature permissions, handled by the modules themselves.
	'features' => array(
		'ViewAccount'        => AccountLevel::HIGHGM,
		'ViewAccountBanLog'  => AccountLevel::HIGHGM,
		'DeleteAccount'      => AccountLevel::HIGHGM,
		'DeleteCharacter'    => AccountLevel::HIGHGM,
		'SeeAccountPassword' => AccountLevel::ADMIN,
		'TempBanAccount'     => AccountLevel::LOWGM,
		'TempUnbanAccount'   => AccountLevel::LOWGM,
		'PermBanAccount'     => AccountLevel::HIGHGM,
		'PermUnbanAccount'   => AccountLevel::HIGHGM,
		'SearchMD5Passwords' => AccountLevel::ADMIN,
		'ViewCharacter'      => AccountLevel::HIGHGM,
		'AddShopItem'        => AccountLevel::ADMIN,
		'EditShopItem'       => AccountLevel::ADMIN,
		'DeleteShopItem'     => AccountLevel::ADMIN,
		'ViewGuild'          => AccountLevel::ADMIN,
		
		'BanHigherPower'     => 5000
	)
);
?>