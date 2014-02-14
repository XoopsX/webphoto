<?php
// $Id: xoops_base.php,v 1.3 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// _include_setting_php()
// 2009-05-15 K.OHWADA
// _include_once_file() -> _include_global_php()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_xoops_base
// substitute for clsss/xoops/base.php
//=========================================================
class webphoto_xoops_base extends webphoto_lib_handler
{
	var $_cached_config_search_array = null;
	var $_cached_group_objs = null;

	var $_MY_MODULE_ID  = 0;
	var $_LANGUAGE;

	var $_STR_JPAPANESE   = 'japanese|japaneseutf|ja_utf8';

	var $_xoops_config = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_xoops_base()
{
	$this->webphoto_lib_handler();

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
	$this->get_system_config();

	$this->_MY_MODULE_ID = $this->get_my_module_id();
	$this->_LANGUAGE     = $this->get_config_by_name( 'language' );

	$this->_include_global_php();
	$this->_include_setting_php();
}

function _include_global_php()
{
	$file = 'global.php';

	$file_sys_lang  = $this->_build_system_lang_file( $file, $this->_LANGUAGE );
	$file_sys_eng   = $this->_build_system_lang_file( $file, 'english' );

// for XCL 2.1
	$file_leg_lang  = $this->_build_legacy_lang_file( $file, $this->_LANGUAGE );
	$file_leg_eng   = $this->_build_legacy_lang_file( $file, 'english' );

	if ( file_exists( $file_sys_lang ) ) {
		include_once $file_sys_lang;

	} elseif ( file_exists( $file_sys_eng ) ) {
		include_once $file_sys_eng;

	} elseif ( file_exists( $file_leg_lang ) ) {
		include_once $file_leg_lang;

	} elseif ( file_exists( $file_leg_eng ) ) {
		include_once $file_leg_eng;
	}
}

function _include_setting_php()
{
// for XCL 2.2
	$file = 'setting.php';

	$file_leg_lang  = $this->_build_legacy_lang_file( $file, $this->_LANGUAGE );
	$file_leg_eng   = $this->_build_legacy_lang_file( $file, 'english' );

	if ( file_exists( $file_leg_lang ) ) {
		include_once $file_leg_lang;

	} elseif ( file_exists( $file_leg_eng ) ) {
		include_once $file_leg_eng;
	}
}

function _build_system_lang_file( $file, $lang )
{
	$str  = XOOPS_ROOT_PATH .'/language/'. $lang .'/'. $file;
	return $str;
}

function _build_legacy_lang_file( $file, $lang )
{
	return $this->_build_mod_lang_file( $file, $lang, 'legacy' );
}

function _build_mod_lang_file( $file, $lang, $module )
{
	$str  = XOOPS_ROOT_PATH .'/modules/'. $module .'/language/'. $lang .'/'. $file;
	return $str;
}

function get_language()
{
	return $this->_LANGUAGE ;
}

function set_db_charset()
{
	return $this->_db->set_charset() ;
}

//---------------------------------------------------------
// config
//---------------------------------------------------------
function get_config_by_name( $name )
{
	if ( isset( $this->_xoops_config[ $name ] ) ) {
		return  $this->_xoops_config[ $name ];
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

//---------------------------------------------------------
// my module
//---------------------------------------------------------
function get_my_module_id( $format='s' )
{
	return $this->get_module_mid_by_dirname( WEBPHOTO_DIRNAME );
}

function get_my_module_name( $format='s' )
{
	return $this->get_my_module_value_by_name( 'name', $format );
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
	return false;
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
function get_system_config()
{
	$conf = $this->get_config_by_modid_catid( 0, 1 );

	$GLOBALS['xoopsConfig'] = $conf;
	$this->_xoops_config = $conf;

	return $conf;
}

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
	return $this->get_config_by_modid_catid( $mid, 0 );
}

function get_config_by_modid_catid( $modid, $catid )
{
	$sql  = 'SELECT * FROM '. $this->db_prefix('config') ;
	$sql .= ' WHERE (conf_modid = '.intval($modid);
	$sql .= ' AND conf_catid = '.intval($catid);
	$sql .= ' ) ';
	$sql .= ' ORDER BY conf_order ASC';

	$rows = $this->get_rows_by_sql( $sql );

	$arr = array();
	foreach ( $rows as $row ) {
		$arr[ $row['conf_name'] ] = $row['conf_value'];
	}

	return $arr;
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
	$sql  = 'SELECT * FROM '.$this->db_prefix('modules');
	$sql .= ' WHERE dirname = '.$this->quote($dirname);
	$row  = $this->get_row_by_sql( $sql );
	return $row['mid'];
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
		return $module->getVar( $name, $format='s' );
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
	$this->_cached_group_objs = $objs;
	return $objs;
}

function get_group_by_id_name( $id, $name, $format='s' )
{
	if ( !is_array( $this->_cached_group_objs ) ) {
		$this->_cached_group_objs = $this->get_group_obj();
	}
	if ( isset( $this->_cached_group_objs[ $id ] ) ) {
		return  $this->_cached_group_objs[ $id ]->getVar( $name, $format );
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

// --- class end ---
}

?>