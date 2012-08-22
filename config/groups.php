<?php
final class AccountGroup {
/*	Corresponds to the different 'level' attribrutes */
	const ANYONE =   -2;
	const UNAUTH =   -1;
	const NORMAL =    0;
	const LOWGM  =    1;
	const HIGHGM =    2;
	const ADMIN  =   99;
	const NOONE  = 9999;
	
	private static $groups = array(
/**
 *	Syntax:
 * 		<group_id> => array(
 *			'name'  => "<group name>",
 *			'level' => "<group level>",
 * 		),
 */
		0 => array(
			'name'  => "Player",
			'level' => AccountGroup::LOWGM
		),
		1 => array(
			'name'  => "Super Player",
			'level' => AccountGroup::LOWGM
		),
		2 => array(
			'name'  => "Support",
			'level' => AccountGroup::LOWGM
		),
		3 => array(
			'name'  => "Script Manager",
			'level' => AccountGroup::LOWGM
		),
		4 => array(
			'name'  => "Event Manager",
			'level' => AccountGroup::LOWGM
		),
		10 => array(
			'name'  => "Law Enforcement",
			'level' => AccountGroup::HIGHGM
		),
		99 => array(
			'name'  => "Admin",
			'level' => AccountGroup::ADMIN
		)
	);

	// DON'T TOUCH ANYTHING BELOW. THIS IS FOR DEVELOPERS.
	
	/**
	 * Get array of all groups.
	 *
	 * @return array
	 * @access public
	 */
   public static function getArray() {
        return self::$groups;
    }
	
	/**
	 * Get array of group IDs that satisfy the operation 
	 * condition that compares the group level.
	 *
	 * @param int $compare
	 * @param string $op
	 * @return array
	 * @access public
	 */
    public static function getGroupID($compare, $op) {
		$group_id = array();
		foreach(self::$groups as $id => $group) {
			if( ($op == '<' && $group['level'] < $compare) || ($op == '>' && $group['level'] > $compare) ||
				($op == '<=' && $group['level'] <= $compare) || ($op == '>=' && $group['level'] >= $compare)) {
				array_push($group_id, $id);
			}
		}
        return $group_id;
    }
	
	/**
	 * Get array of group IDs that satisfy the operation 
	 * condition that compares the group level.
	 *
	 * @param int $compare
	 * @param string $op
	 * @return array
	 * @access public
	 */
    public static function getGroupLevel($group_id) {
		if(isset(self::$groups[$group_id]['level'])) {
			return self::$groups[$group_id]['level'];
		}
		else {
			return AccountGroup::NORMAL;
		}
    }
}
?>