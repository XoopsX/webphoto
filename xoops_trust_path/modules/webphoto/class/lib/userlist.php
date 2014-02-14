<?php
// $Id: userlist.php,v 1.2 2010/01/26 08:25:45 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-20 K.OHWADA
// XOOPS_CUBE_LEGACY
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_userlist
//=========================================================
class webphoto_lib_userlist
{
	var $_member_handler;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_userlist()
{
	$this->_member_handler =& xoops_gethandler('member');
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_userlist();
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function build_param_by_groupid( $group_id, $limit=0, $start=0 )
{
	$arr = array(
		'group_id'  => $group_id ,
		'total'     => $this->get_user_count_by_groupid( $group_id ) ,
		'user_list' => $this->get_users_by_groupid( $group_id, $limit, $start ) ,
	);
	if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$arr['xoops_cube_legacy'] = XOOPS_CUBE_LEGACY;
	}
	return $arr;
}

function get_user_count_by_groupid( $group_id )
{
	return $this->_member_handler->getUserCountByGroup( $group_id );
}

function get_users_by_groupid( $group_id, $limit=0, $start=0 )
{
	$users = $this->_member_handler->getUsersByGroup( $group_id, true, $limit, $start );

	$arr = array();
	foreach( $users as $user )
	{
		$uid          = $user->getVar('uid','n');
		$uname        = $user->getVar('uname','n');
		$name         = $user->getVar('name','n');
		$user_regdate = $user->getVar('user_regdate','n');
		$last_login   = $user->getVar('last_login','n');
		$posts        = $user->getVar('posts','n');
		$level        = $user->getVar('level','n');

		$arr[] = array(
			'uid'               => $uid ,
			'uname'             => $uname ,
			'name'              => $name ,
			'user_regdate'      => $user_regdate ,
			'last_login'        => $last_login ,
			'posts'             => $posts ,
			'level'             => $level ,
			'uname_s'           => $this->sanitize($uname) ,
			'name_s'            => $this->sanitize($name) ,
			'user_regdate_disp' => formatTimestamp($user_regdate, 's') ,
			'last_login_disp'   => formatTimestamp($last_login, 'l') ,
		);
	}

	return $arr;
}

function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

// --- class end ---
}

?>