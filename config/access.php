<?php
// This file should control all access to specified modules and actions.
return array(
	// Module/action permissions.
	// These are handled during runtime by Flux.
	// '*' is a default that is checked for any action that has not been
	// specified an access level.
	'modules' => array(
		'main'      => array(
			'*'        => AccountGroup::ANYONE
		),
		'donate'    => array(
			'index'    => AccountGroup::ANYONE,
			'notify'   => AccountGroup::ANYONE,
			'update'   => AccountGroup::ANYONE,
			'complete' => AccountGroup::ANYONE,
			'history'  => AccountGroup::NORMAL,
			'trusted'  => AccountGroup::NORMAL
		),
		'purchase'  => array(
			'index'    => AccountGroup::ANYONE,
			'add'      => AccountGroup::ANYONE,
			'clear'    => AccountGroup::NORMAL,
			'cart'     => AccountGroup::NORMAL,
			'checkout' => AccountGroup::NORMAL,
			'remove'   => AccountGroup::NORMAL,
			'pending'  => AccountGroup::NORMAL
		),
		'itemshop'  => array(
			'add'      => AccountGroup::ADMIN,
			'edit'     => AccountGroup::ADMIN,
			'delete'   => AccountGroup::ADMIN,
			'imagedel' => AccountGroup::ADMIN
		),
		'account'   => array(
			'index'    => AccountGroup::LOWGM,
			'view'     => AccountGroup::NORMAL,
			'create'   => AccountGroup::UNAUTH,
			'login'    => AccountGroup::UNAUTH,
			'logout'   => AccountGroup::NORMAL,
			'transfer' => AccountGroup::NORMAL,
			'xferlog'  => AccountGroup::NORMAL,
			'cart'     => AccountGroup::NORMAL,
			'changepass' => AccountGroup::NORMAL,
			'edit'       => AccountGroup::ADMIN,
			'changesex'  => AccountGroup::NORMAL,
			'confirm'    => AccountGroup::UNAUTH,
			'resend'     => AccountGroup::UNAUTH,
			'resetpass'  => AccountGroup::UNAUTH,
			'resetpw'    => AccountGroup::UNAUTH,
			'changemail' => AccountGroup::NORMAL,
			'confirmemail' => AccountGroup::NORMAL,
			'prune'        => AccountGroup::ANYONE
		),
		'character' => array(
			'index'    => AccountGroup::LOWGM,
			'view'     => AccountGroup::NORMAL,
			'online'   => AccountGroup::ANYONE,
			'prefs'    => AccountGroup::NORMAL,
			'changeslot' => AccountGroup::NORMAL,
			'resetlook'  => AccountGroup::NORMAL,
			'resetpos'   => AccountGroup::NORMAL,
			'mapstats'   => AccountGroup::ANYONE,
			'divorce'    => AccountGroup::NORMAL
		),
		'guild'     => array(
			'emblem'   => AccountGroup::ANYONE,
			'index'    => AccountGroup::LOWGM,
			'export'   => AccountGroup::ADMIN,
			'view'     => AccountGroup::NORMAL
		),
		'castle'    => array(
			'index'    => AccountGroup::ANYONE
		),
		'economy'   => array(
			'index'    => AccountGroup::NORMAL
		),
		'auction'   => array(
			'index'    => AccountGroup::LOWGM
		),
		'ranking'   => array(
			'character' => AccountGroup::ANYONE,
			'guild'     => AccountGroup::ANYONE,
			'zeny'      => AccountGroup::ANYONE,
			'death'     => AccountGroup::ANYONE
		),
		'item'      => array(
			'index'    => AccountGroup::ANYONE,
			'view'     => AccountGroup::ANYONE,
			'add'      => AccountGroup::ADMIN,
			'edit'     => AccountGroup::ADMIN,
			'copy'     => AccountGroup::ADMIN
		),
		'monster'   => array(
			'index'    => AccountGroup::ANYONE,
			'view'     => AccountGroup::ANYONE
		),
		'server'    => array(
			'status'     => AccountGroup::ANYONE,
			'status-xml' => AccountGroup::ANYONE,
			'info'       => AccountGroup::ANYONE
		),
		'logdata'   => array(
			'index'   => AccountGroup::ADMIN,
			'txnview' => AccountGroup::ADMIN,
			'char'    => AccountGroup::ADMIN,
			'inter'   => AccountGroup::ADMIN,
			'command' => AccountGroup::ADMIN,
			'branch'  => AccountGroup::ADMIN,
			'chat'    => AccountGroup::ADMIN,
			'login'   => AccountGroup::ADMIN,
			'mvp'     => AccountGroup::ADMIN,
			'npc'     => AccountGroup::ADMIN,
			'pick'    => AccountGroup::ADMIN,
			'zeny'    => AccountGroup::ADMIN
		),
		'cplog'     => array(
			'index'      => AccountGroup::ADMIN,
			'paypal'     => AccountGroup::ADMIN,
			'login'      => AccountGroup::ADMIN,
			'resetpass'  => AccountGroup::ADMIN,
			'changepass' => AccountGroup::ADMIN,
			'changemail' => AccountGroup::ADMIN,
			'ban'        => AccountGroup::ADMIN,
			'ipban'      => AccountGroup::ADMIN
		),
		'ipban'     => array(
			'index'    => AccountGroup::ADMIN,
			'add'      => AccountGroup::ADMIN,
			'unban'    => AccountGroup::ADMIN,
			'edit'     => AccountGroup::ADMIN,
			'remove'   => AccountGroup::ADMIN
		),
		'service'   => array(
			'tos'      => AccountGroup::ANYONE
		),
		'captcha'   => array(
			'index'    => AccountGroup::ANYONE
		),
		'install'   => array(
			'index'    => AccountGroup::ANYONE,
			'reinstall' => AccountGroup::ADMIN
		),
		'test'      => array(
			'*'        => AccountGroup::ANYONE
		),
		'woe'       => array(
			'index'   => AccountGroup::ANYONE
		),
		'mail'      => array(
			'index'   => AccountGroup::ADMIN
		),
		'history'   => array(
			'index'       => AccountGroup::NORMAL,
			'cplogin'     => AccountGroup::NORMAL,
			'gamelogin'   => AccountGroup::NORMAL,
			'emailchange' => AccountGroup::NORMAL,
			'passchange'  => AccountGroup::NORMAL,
			'passreset'   => AccountGroup::NORMAL
		)
	),
	// General feature permissions, handled by the modules themselves.
	'features' => array(
		'ViewAccount'        => AccountGroup::HIGHGM, // View another person's account details.
		'ViewAccountBanLog'  => AccountGroup::HIGHGM, // View another person's account ban log.
		'DeleteAccount'      => AccountGroup::ADMIN,  // (not yet implemented)
		'DeleteCharacter'    => AccountGroup::ADMIN,  // (not yet implemented)
		'SeeAccountPassword' => AccountGroup::NOONE,  // If not using MD5, view another person's password in list.
		'TempBanAccount'     => AccountGroup::LOWGM,  // Has ability to temporarily ban an account.
		'TempUnbanAccount'   => AccountGroup::LOWGM,  // Has ability to remove a temporary ban on an account.
		'PermBanAccount'     => AccountGroup::HIGHGM, // Has ability to permanently ban an account.
		'PermUnbanAccount'   => AccountGroup::HIGHGM, // Has ability to remove a permanent ban on an account.
		'SearchMD5Passwords' => AccountGroup::NOONE,  // Ability to search MD5'd passwords in list.
		'ViewCharacter'      => AccountGroup::HIGHGM, // View another person's character details.
		'DivorceCharacter'   => AccountGroup::LOWGM,  // Divorce another character.
		'AddShopItem'        => AccountGroup::ADMIN,  // Ability to add an item to the shop.
		'EditShopItem'       => AccountGroup::ADMIN,  // Ability to modify a shop item's details.
		'DeleteShopItem'     => AccountGroup::ADMIN,  // Ability to remove an item for sale on the shop.
		'ViewGuild'          => AccountGroup::ADMIN,  // Ability to view another guild's details.
		'SearchWhosOnline'   => AccountGroup::ANYONE, // Ability to search the "Who's Online" page.
		'ViewOnlinePosition' => AccountGroup::LOWGM,  // Ability to see a character's current map on "Who's Online" page.
		'EditAccountGroupID' => AccountGroup::ADMIN,  // Ability to edit another person's account group ID.
		'EditAccountBalance' => AccountGroup::ADMIN,  // Ability to edit another person's account balance.
		'ModifyAccountPrefs' => AccountGroup::ADMIN,  // Ability to modify another person's account preferences.
		'ModifyCharPrefs'    => AccountGroup::ADMIN,  // Ability to modify another person's character preferences.
		'IgnoreHiddenPref'   => AccountGroup::LOWGM,  // Ability to see users on "Who's Online" page, hidden or not.
		'IgnoreHiddenPref2'  => AccountGroup::LOWGM,  // Ability to see users on "Who's Online" page, hidden by app config or not.
		'SeeHiddenMapStats'  => AccountGroup::LOWGM,  // Ability to see hidden map statistics.
		'ChangeSlot'         => AccountGroup::LOWGM,  // Minimum group level required to change another character's slot.
		'ModifyIpBan'        => AccountGroup::ADMIN,  // Minimum group level required to modify an existing IP ban.
		'RemoveIpBan'        => AccountGroup::ADMIN,  // Minimum group level required to remove an existing IP ban.
		'HideFromZenyRank'   => AccountGroup::NORMAL, // Ability to set "Hide from zeny ranking" pref.
		'SeeItemDbScripts'   => AccountGroup::ANYONE, // Ability to see item_db scripts in view page.
		'SeeItemDb2Scripts'  => AccountGroup::ADMIN,  // Ability to see item_db2 scripts in view page.
		'ViewRawTxnLogData'  => AccountGroup::ADMIN,  // Minimum group level required to view Raw Transaction Log in txnview page.
		'ResetLook'          => AccountGroup::LOWGM,  // Minimum group level required to reset another character's look.
		'ResetPosition'      => AccountGroup::LOWGM,  // Minimum group level required to reset another character's position.
		'ViewWoeDisallowed'  => AccountGroup::LOWGM,  // Minimum group level required to bypass WoE-disabled page security check.
		'SeeCpLoginLogPass'  => AccountGroup::NOONE,  // Minimum group level required to see password in CP login log (also requires CpLoginLogShowPassword in application.php)
		'SearchCpLoginLogPw' => AccountGroup::NOONE,  // Minimum group level required to search through passwords in the CP login log.
		'SeeCpResetPass'     => AccountGroup::NOONE,  // Minimum group level required to see passwords in CP log's "password resets" page.
		'SearchCpResetPass'  => AccountGroup::NOONE,  // Minimum group level required to search passwords in CP log's "password resets" page.
		'SeeCpChangePass'    => AccountGroup::NOONE,  // Minimum group level required to see passwords in CP log's "password changes" page.
		'SearchCpChangePass' => AccountGroup::NOONE,  // Minimum group level required to search passwords in CP log's "password changes" page.
		'SeeAccountID'       => AccountGroup::LOWGM,  // Minimum group level required to see Account ID on account view and character view pages.
		'SeeUnknownItems'    => AccountGroup::LOWGM,  // Minimum group level required to see unidentified items as identified.
		'AvoidSexChangeCost' => AccountGroup::LOWGM,  // Avoid paying cost (if any) for sex changes.
		
		'EditHigherPower'    => AccountGroup::NOONE,
		'BanHigherPower'     => AccountGroup::NOONE
	)
);
?>