<?php
// $Id: test.php,v 1.2 2011/11/13 05:24:37 ohwada Exp $

//=========================================================
// webphoto module
// 2011-11-11 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_test
//=========================================================
class webphoto_admin_test extends webphoto_base_this
{
	var $_class_dir;

	var $_IGNORES_MAIN = array('header','header_file','header_inc_handler',
		'header_item_handler','header_submit','header_submit_imagemanager',
		'include_mail_recv','include_submit','include_submit_base');
	var $_IGNORES_ADMIN = array('header','header_edit','header_rss',
		'index','test');
	var $_IGNORES_INCLUDE = array('mytrustdirname','weblinks.inc','webphoto');

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_test( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_class_dir =& webphoto_lib_dir::getInstance();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_test( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	$this->exec_main();

	xoops_cp_footer();
}

function exec_main()
{
	echo '<a href="index.php">Admin</a> &gt;&gt; '."\n";
	echo '<a href="index.php?fct=test">Test</a>'."\n";

	$file_include = isset( $_GET['file'] ) ? $_GET['file'] : null;

	switch ( $file_include )
	{
		case 'xoops_version':
			$this->exec_xoops_version();
			break;

		case 'blocks':
			$this->exec_blocks();
			break;

		case 'comment.inc':
			$func = $this->_DIRNAME.'_comments_update' ;
			$this->exec_include( $file_include, $func );
			break;

		case 'data.inc':
			$func = $this->_DIRNAME.'_new' ;
			$this->exec_include( $file_include, $func );
			break;

		case 'notification.inc':
			$func = $this->_DIRNAME.'_notify_iteminfo' ;
			$this->exec_include( $file_include, $func );
			break;

		case 'oninstall.inc':
			$func = 'xoops_module_update_'.$this->_DIRNAME  ;
			$this->exec_include( $file_include, $func );
			break;

		case 'search.inc':
			$func = $this->_DIRNAME.'_search' ;
			$this->exec_include( $file_include, $func );
			break;

		case 'sitemap.plugin':
			$func = 'b_sitemap_'.$this->_DIRNAME ;
			$this->exec_include( $file_include, $func );
			break;

		case 'waiting.plugin':
			$func = 'b_waiting_'.$this->_DIRNAME ;
			$this->exec_include( $file_include, $func );
			break;

		default:
			$this->exec_list();
			break;
	}

}

function exec_list()
{
	echo '<h4>module</h4>'."\n";
	$this->print_module();

	echo '<h4>main</h4>'."\n";
	$this->print_list_main();

	echo '<h4>admin</h4>'."\n";
	$this->print_list_admin();

	echo '<h4>include</h4>'."\n";
	$this->print_list_include();

	echo '<h4>bin</h4>'."\n";
	$this->print_bin();
}

function print_module()
{
	echo '<ul>'."\n";

	$href = $this->_MODULE_URL .'/module_icon.php';
	echo '<li><a href="'.$href.'">module_icon</a></li>'."\n";

	$href = $this->build_href_include( 'xoops_version.php' );
	echo '<li><a href="'.$href.'">xoops_version</a></li>'."\n";

	$href = $this->build_href_include( 'blocks.php' );
	echo '<li><a href="'.$href.'">blocks</a></li>'."\n";

	echo '</ul>'."\n";
}

function print_bin()
{
	echo '<ul>'."\n";

	$pass = $this->get_config_by_name('bin_pass');
	$href = $this->_MODULE_URL .'/bin/retrieve.php?pass='.$pass;
	echo '<li><a href="'.$href.'">retrieve</a></li>'."\n";

	echo '</ul>'."\n";
}

function print_list_main()
{
	$path  = WEBPHOTO_TRUST_PATH .'/main';
	$this->print_list( $path, 'build_href_main', $this->_IGNORES_MAIN );
}

function print_list_admin()
{
	$path  = WEBPHOTO_TRUST_PATH .'/admin';
	$this->print_list( $path, 'build_href_admin', $this->_IGNORES_ADMIN );
}

function print_list_include()
{
	$path  = WEBPHOTO_ROOT_PATH .'/include';
	$this->print_list( $path, 'build_href_include', $this->_IGNORES_INCLUDE );
}

function build_href_main( $file )
{
	$fct  = $this->strip_ext( $file );
	$href = $this->_MODULE_URL .'/index.php?fct='.$fct;
	return $href;
}

function build_href_admin( $file )
{
	$fct  = $this->strip_ext( $file );
	$href = $this->_MODULE_URL .'/admin/index.php?fct='.$fct;
	return $href;
}

function build_href_include( $file )
{
	$fct  = $this->strip_ext( $file );
	$href = $this->_MODULE_URL .'/admin/index.php?fct=test&amp;file='. $fct;
	return $href;
}

function print_list( $path, $func, $ingores )
{
	$files = $this->_class_dir->get_files_in_dir( $path, 'php' );

	echo '<ul>'."\n";
	foreach( $files as $file ) 
	{
		$fct = $this->strip_ext( $file );

		if ( in_array($fct, $ingores) ) {
			continue;
		}

		$href = $this->$func( $file );
		echo '<li><a href="'.$href.'">'.$fct.'</a></li>'."\n";
	}
	echo '</ul>'."\n";
}

function exec_xoops_version()
{
	$flag = false;
	$file_full = $this->_MODULE_DIR .'/xoops_version.php';

	echo '<h4>xoops_version</h4>'."\n";

	if ( file_exists($file_full) ) {
		include $file_full;

		$flag = true;
		echo "<pre>\n";
		print_r($modversion);
		echo "</pre>\n";
	}

	if ( !$flag ) {
		echo 'NOT find <br />';
	}
}

function exec_blocks()
{
	$flag = false;
	$file_full = $this->_MODULE_DIR .'/blocks/blocks.php';
	$func = 'b_webphoto_topnews_show';

	$options = $this->build_blocks_top_options();

	echo '<h4>xoops_version</h4>'."\n";

	if ( file_exists($file_full) ) {
		include $file_full;

		if ( function_exists($func) ) {
			$flag = true;
			$result = $func( $options );
			echo "<pre>\n";
			print_r($result);
			echo "</pre>\n";
		}
	}

	if ( !$flag ) {
		echo 'NOT find <br />';
	}
}

function build_blocks_top_options()
{
	$str  = $this->_DIRNAME;
	$str .= $this->get_ini('xoops_version_blocks_top_options');
	$opt  = explode('|', $str);
	return $opt ;
}

function exec_include( $file_name, $func )
{
	echo ' &gt;&gt; include'."\n";

	$flag = false;
	$file_full = $this->_MODULE_DIR .'/include/'. $file_name .'.php';

	echo '<h4>'. $file_name .' - '. $func .'</h4>'."\n";

	if ( file_exists($file_full) ) {
		include $file_full;

		if ( function_exists($func) ) {
			$flag = true;
			$result = $func();
			echo "<pre>\n";
			print_r($result);
			echo "</pre>\n";
		}
	}

	if ( !$flag ) {
		echo 'NOT find <br />';
	}
}

// --- class end ---
}

?>