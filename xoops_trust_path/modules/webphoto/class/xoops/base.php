<?php
// $Id: base.php,v 1.11 2010/10/10 12:46:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// get_member_user_count()
// 2010-02-01 K.OHWADA
// get_module_info_value_by_dirname()
// 2009-12-06 K.OHWADA
// get_system_groups()
// 2009-04-19 K.OHWADA
// get_xoops_themecss()
// 2009-01-25 K.OHWADA
// get_block_options_by_bid()
// 2009-01-10 K.OHWADA
// user_to_server_time()
// 2008-11-16 K.OHWADA
// get_cached_groups()
// 2008-10-01 K.OHWADA
// use XOOPS_GROUP_ANONYMOUS in get_my_user_groups()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_xoops_base
//=========================================================
class webphoto_xoops_base
{
	var $_cached_config_search_array = null;
	var $_cached_group_objs = null;

	var $_MY_MODULE_ID  = 0;
	var $_LANGUAGE;

	var $_STR_JPAPANESE   = 'japanese|japaneseutf|ja_utf8';

	var $_SYSTEM_GROUPS = 
		array( XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS );

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_xoops_base()
{
	$this->_init();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_xoops_base();
	}
	return $instance;
}

function _init()
{
	$this->_MY_MODULE_ID = $this->get_my_module_id();
	$this->_LANGUAGE     = $this->get_config_by_name( 'language' );
}

//---------------------------------------------------------
// config
//---------------------------------------------------------
function get_config_by_name( $name )
{
	global $xoopsConfig;
	if ( isset( $xoopsConfig[ $name ] ) ) {
		return  $xoopsConfig[ $name ];
	}
	return false;
}

function is_japanese( $str=null )
{
	if ( empty($str) ) {
		$str = $this->_STR_JPAPANESE ;
	}

	if ( in_array( $this->_LANGUAGE, explode('|', $str ) ) ) {
		return true;
	}
	return false;
}

function get_xoops_themecss()
{
	return getcss( $this->get_config_by_name('theme_set') );
}

//---------------------------------------------------------
// my module
//---------------------------------------------------------
function get_my_module_id( $format='s' )
{
	return $this->get_my_module_value_by_name( 'mid', $format );
}

function get_my_module_name( $format='s' )
{
	return $this->get_my_module_value_by_name( 'name', $format );
}

function get_my_module_version( $flag_format=false )
{
	$ver = $this->get_my_module_value_by_name( 'version' );
	if ( $flag_format ) {
		$ver = $this->convertVersionIntToFloat($ver);
	}
	return $ver;
}

function get_my_module_value_by_name( $name, $format='s' )
{
	global $xoopsModule;
	if ( is_object($xoopsModule) ) {
		return  $xoopsModule->getVar( $name, $format );
	}
	return false;
}

//---------------------------------------------------------
// my user
//---------------------------------------------------------
function get_my_user_uid( $format='s' )
{
	return $this->get_my_user_value_by_name( 'uid', $format );
}

function get_my_user_uname( $format='s' )
{
	return $this->get_my_user_value_by_name( 'uname', $format );
}

function get_my_user_value_by_name( $name, $format='s' )
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		return $xoopsUser->getVar( $name, $format );
	}
	return false;
}

function get_my_user_groups()
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		return $xoopsUser->getGroups() ;
	}
	return array( XOOPS_GROUP_ANONYMOUS );
}

function get_my_user_is_login()
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		return true;
	}
	return false;
}

function get_my_user_is_module_admin()
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		if ( $xoopsUser->isAdmin( $this->_MY_MODULE_ID ) ) {
			return true;
		}
	}
	return false;
}

//---------------------------------------------------------
// config handler
//---------------------------------------------------------
function has_my_module_config()
{
	$config_handler =& xoops_gethandler('config');
	return count( $config_handler->getConfigs(
		new Criteria( 'conf_modid' , $this->_MY_MODULE_ID ) ) );
}

function get_module_config_by_dirname( $dirname )
{
	return $this->get_module_config_by_mid( 
		$this->get_module_mid_by_dirname( $dirname ) );
}

function get_module_config_by_mid( $mid )
{
	$config_handler =& xoops_gethandler('config');
	return $config_handler->getConfigsByCat( 0, $mid );
}

function get_search_config()
{
	$config_handler =& xoops_gethandler('config');
	$conf = $config_handler->getConfigsByCat( XOOPS_CONF_SEARCH );
	$this->_cached_config_search_array = $conf;
	return $conf;
}

function get_search_config_by_name( $name )
{
	if ( !is_array( $this->_cached_config_search_array ) ) {
		$this->_cached_config_search_array = $this->get_search_config();
	}
	if ( isset( $this->_cached_config_search_array[ $name ] ) ) {
		return  $this->_cached_config_search_array[ $name ];
	}
	return false;
}

//---------------------------------------------------------
// module handler
//---------------------------------------------------------
function get_module_mid_by_dirname( $dirname, $format='s' )
{
	return $this->get_module_value_by_dirname( $dirname, 'mid', $format );
}

function get_module_name_by_dirname( $dirname, $format='s' )
{
	return $this->get_module_value_by_dirname( $dirname, 'name', $format );
}

function get_module_value_by_dirname( $dirname, $name, $format='s' )
{
	$module_handler =& xoops_gethandler('module');
	$module = $module_handler->getByDirname( $dirname );
	if ( is_object($module) ) {
		return $module->getVar( $name, $format );
	}
	return false;
}

function get_module_info_version_by_dirname( $dirname, $flag_format=false )
{
	$ver = $this->get_module_info_value_by_dirname( $dirname, 'version' );
	if ( $ver && $flag_format ) {
		$ver = $this->convertVersionFromModinfoToInt($ver);
	}
	return $ver;
}

function get_module_info_value_by_dirname( $dirname, $name )
{
	$module_handler =& xoops_gethandler('module');
	$module = $module_handler->getByDirname( $dirname );
	if ( is_object($module) ) {
		return $module->getInfo( $name );
	}
	return false;
}

//---------------------------------------------------------
// user handler
//---------------------------------------------------------
function get_user_uname_from_id( $uid, $usereal=0 )
{
	return XoopsUser::getUnameFromId( $uid, $usereal );
}

function get_user_email_from_id( $uid )
{
	$user_handler =& xoops_gethandler('user');
	$obj = $user_handler->get( $uid );
	if ( is_object($obj) ) {
		return $obj->getVar('email');
	}
	return false;
}

function build_userinfo( $uid, $usereal=0 )
{
	$uname = $this->get_user_uname_from_id( $uid, $usereal );

// geust
	$uid = intval($uid);
	if ( $uid == 0 ) {
		return $uname;
	}

	$str  = '<a href="'. XOOPS_URL .'/userinfo.php?uid='. $uid .'">'. $uname .'</a>';
	return $str;
}

//---------------------------------------------------------
// group handler
//---------------------------------------------------------
function get_group_obj()
{
	$group_handler = xoops_gethandler("group");
	$objs = $group_handler->getObjects( null, true );
	return $objs;
}

function get_cached_group_obj()
{
	if ( !is_array( $this->_cached_group_objs ) ) {
		$this->_cached_group_objs = $this->get_group_obj();
	}
	return $this->_cached_group_objs ;
}

function get_cached_groups( $none=false, $none_name='---', $format='s' )
{
	$objs = $this->get_cached_group_obj() ;
	$arr = array();
	if ( $none ) {
		$arr[0] = $none_name;
	}
	foreach ( $objs as $obj )
	{
		$groupid = $obj->getVar( 'groupid', $format );
		$name    = $obj->getVar( 'name',    $format );
		$arr[ $groupid ] = $name ;
	}
	return $arr ;
}

function get_cached_group_by_id_name( $id, $name, $format='s' )
{
	$objs = $this->get_cached_group_obj() ;

	if ( isset( $objs[ $id ] ) ) {
		return  $objs[ $id ]->getVar( $name, $format );
	}
	return false;
}

function get_system_groups()
{
	return $this->_SYSTEM_GROUPS;
}

function is_system_group( $id )
{
	if ( in_array( $id, $this->_SYSTEM_GROUPS ) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// member handler
//---------------------------------------------------------
function get_member_user_list( $limit=0, $start=0 )
{
	$criteria = new CriteriaCompo();
	$criteria->setStart($start);
	$criteria->setLimit($limit);
	$criteria->setSort( 'uname' );

	$member_handler =& xoops_gethandler('member');
	return $member_handler->getUserList( $criteria );
}

function get_member_user( $id )
{
	$member_handler =& xoops_gethandler('member');
	return $member_handler->getUser( $id );
}

function get_member_users_by_group( $group_id, $asobject=false, $limit=0, $start=0 )
{
	$member_handler =& xoops_gethandler('member');
    return $member_handler->getUsersByGroup( $group_id, $asobject, $limit, $start );
}

function get_member_user_count( $criteria=null )
{
	$member_handler =& xoops_gethandler('member');
	return $member_handler->getUserCount( $criteria );
}

//--------------------------------------------------------
// xoops block handler
//--------------------------------------------------------
function get_block_options_by_bid( $bid )
{
	$obj = $this->get_block_by_bid( $bid );
	if ( is_object($obj) ) {
		$options = explode( '|', $obj->getVar('options') );
		return $options ;
	}
	return null;
}

function get_block_by_bid( $bid )
{
	$block_handler =& xoops_gethandler('block');
	return $block_handler->get( $bid );
}

//---------------------------------------------------------
// timestamp
//---------------------------------------------------------
function user_to_server_time( $time, $default=0 )
{
	if ( $time <= 0 ) {
		return $default ;
	}

	global $xoopsConfig, $xoopsUser;
	if ($xoopsUser) {
		$timeoffset = $xoopsUser->getVar("timezone_offset");
	} else {
		$timeoffset = $xoopsConfig['default_TZ'];
	}
	$timestamp = $time - (($timeoffset - $xoopsConfig['server_TZ']) * 3600);
	return $timestamp;
}


//---------------------------------------------------------
// same as Legacy_Utils
//---------------------------------------------------------
function convertVersionFromModinfoToInt($version)
{
	return round(100 * floatval($version));
}

function convertVersionIntToFloat($version)
{
	return round(floatval(intval($version) / 100), 2);
}

// --- class end ---
}

?>