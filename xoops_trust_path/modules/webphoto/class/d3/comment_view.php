<?php
// $Id: comment_view.php,v 1.5 2009/09/08 16:18:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-09-01 K.OHWADA
// BUG: not show reply in guest on XCL
// 2009-07-18 K.OHWADA
// BUG: not show child in thead
// 2008-07-01 K.OHWADA
// change $action_link
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/include/comment_constants.php';
include_once XOOPS_ROOT_PATH.'/class/commentrenderer.php';

// XOOPS Cube Legacy 2.1
if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
	include_once XOOPS_ROOT_PATH.'/modules/legacy/include/xoops2_system_constants.inc.php';

// XOOPS 2.0
} else {
	include_once XOOPS_ROOT_PATH.'/modules/system/constants.php';
}

//=========================================================
// class webphoto_d3_comment_view
// subsitute for core's comment_view.php
//=========================================================
class webphoto_d3_comment_view
{
	var $_comment_handler;
	var $_groupperm_class;

	var $_DIRNAME;
	var $_MODULE_DIR;
	var $_MODULE_URL;

	var $_SYSTEM_COMMENT;

	var $_MODULE_ID      = 0;
	var $_xoops_uid      = 0;
	var $_is_module_admin = false;

	var $_xoops_com_mode  = null;
	var $_xoops_com_order = null;
	var $_xoops_module_comments = null;
	var $_xoops_module_config_com_rule     = null;
	var $_xoops_module_config_com_anonpost = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_d3_comment_view()
{
	$this->_comment_handler =& xoops_gethandler('comment');
	$this->_groupperm_class =& webphoto_xoops_groupperm::getInstance();

	$this->_init_xoops_param();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_d3_comment_view();
	}
	return $instance;
}

function init( $dirname )
{
	$this->_DIRNAME    = $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH  .'/modules/'. $dirname;
	$this->_MODULE_URL = XOOPS_URL        .'/modules/'. $dirname;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function assgin_tmplate()
{
	global $xoopsTpl;

	if ( XOOPS_COMMENT_APPROVENONE == $this->_xoops_module_config_com_rule ) {
		return false;
	}

	$this->_include_lang_file();

	$xoopsTpl->assign( 'xoops_iscommentadmin', $this->_groupperm_class->has_system_comment() );

	$comment_config = $this->_xoops_module_comments;

	$com_itemid = (trim($comment_config['itemName']) != '' && isset($_GET[$comment_config['itemName']])) ? intval($_GET[$comment_config['itemName']]) : 0;
	if ($com_itemid <= 0) {
		return false;
	}

	$com_mode = isset($_GET['com_mode']) ? $this->_sanitize( trim($_GET['com_mode']) ) : '';
	if ($com_mode == '') {
		$com_mode = $this->_xoops_com_mode ;
	}

	$xoopsTpl->assign('comment_mode', $com_mode);

	if ( !isset($_GET['com_order']) ) {
		$com_order = $this->_xoops_com_order;
	} else {
		$com_order = intval($_GET['com_order']);
	}

	if ($com_order != XOOPS_COMMENT_OLD1ST) {
		$xoopsTpl->assign(array('comment_order' => XOOPS_COMMENT_NEW1ST, 'order_other' => XOOPS_COMMENT_OLD1ST));
		$com_dborder = 'DESC';
	} else {
		$xoopsTpl->assign(array('comment_order' => XOOPS_COMMENT_OLD1ST, 'order_other' => XOOPS_COMMENT_NEW1ST));
		$com_dborder = 'ASC';
	}

	// admins can view all comments and IPs, others can only view approved(active) comments
	if ( $this->_is_module_admin ) {
		$admin_view = true;
	} else {
		$admin_view = false;
	}

	$com_id = isset($_GET['com_id']) ? intval($_GET['com_id']) : 0;
	$com_rootid = isset($_GET['com_rootid']) ? intval($_GET['com_rootid']) : 0;

// --- flat ---
	if ($com_mode == 'flat') {
		$comments = $this->_getByItemId( $com_itemid, $com_dborder);

		$renderer =& XoopsCommentRenderer::instance($xoopsTpl);
		$renderer->setComments($comments);
		$renderer->renderFlatView($admin_view);

// --- thread ---
	} elseif ($com_mode == 'thread') {
		// RMV-FIX... added extraParam stuff here

// absolute URL
// BUG: not show child in thead
//		$comment_url = $comment_config['pageName'] . '?';
		$comment_url = $this->_MODULE_URL.'/'.$comment_config['pageName'].'?';

		if (isset($comment_config['extraParams']) && is_array($comment_config['extraParams'])) {
			$extra_params = '';
			foreach ($comment_config['extraParams'] as $extra_param) 
			{
			    // This page is included in the module hosting page -- param could be from anywhere
				if (isset(${$extra_param})) {
					$extra_params .= $extra_param .'='.${$extra_param}.'&amp;';
				} elseif (isset($_POST[$extra_param])) {
					$extra_params .= $extra_param .'='.$_POST[$extra_param].'&amp;';
				} elseif (isset($_GET[$extra_param])) {
					$extra_params .= $extra_param .'='.$_GET[$extra_param].'&amp;';
				} else {
					$extra_params .= $extra_param .'=&amp;';
				}
				//$extra_params .= isset(${$extra_param}) ? $extra_param .'='.${$extra_param}.'&amp;' : $extra_param .'=&amp;';
			}
			$comment_url .= $extra_params;
		}

		$xoopsTpl->assign('comment_url', $comment_url.$comment_config['itemName'].'='.$com_itemid.'&amp;com_mode=thread&amp;com_order='.$com_order);

	// Show specific thread tree
		if (!empty($com_id) && !empty($com_rootid) && ($com_id != $com_rootid)) {
			$comments = $this->_getThread($com_rootid, $com_id);

			if (false != $comments) {
				$renderer =& XoopsCommentRenderer::instance($xoopsTpl);
				$renderer->setComments($comments);
				$renderer->renderThreadView($com_id, $admin_view);
			}

	// Show all threads
		} else {
			$top_comments = $this->_getTopComments( $com_itemid, $com_dborder );
			$c_count = count($top_comments);
			if ($c_count> 0) {
				for ($i = 0; $i < $c_count; $i++) 
				{
					$comments = $this->_getThread($top_comments[$i]->getVar('com_rootid'), $top_comments[$i]->getVar('com_id'));

					if (false != $comments) {
						$renderer =& XoopsCommentRenderer::instance($xoopsTpl);
						$renderer->setComments($comments);
						$renderer->renderThreadView($top_comments[$i]->getVar('com_id'), $admin_view);
					}

					unset($comments);
				}
			}
		}

// --- Show all threads ---
	} else {
		$top_comments = $this->_getTopComments( $com_itemid, $com_dborder );
		$c_count = count($top_comments);
		if ($c_count> 0) {
			for ($i = 0; $i < $c_count; $i++) 
			{
				$comments = $this->_getThread( $top_comments[$i]->getVar('com_rootid'), $top_comments[$i]->getVar('com_id') );

				$renderer =& XoopsCommentRenderer::instance($xoopsTpl);
				$renderer->setComments($comments);
				$renderer->renderNestView($top_comments[$i]->getVar('com_id'), $admin_view);
			}
		}
	}

// assign comment nav bar

// absolute URL
//	$navbar = '
//<form method="get" action="'.$comment_config['pageName'].'">
	$action_link = $this->_MODULE_URL.'/'.$comment_config['pageName'];

	$navbar  = '<form method="get" action="'. $action_link .'">';
	$navbar .= '<table width="95%" class="outer" cellspacing="1">';
	$navbar .= '<tr>';
	$navbar .= '<td class="even" align="center"><select name="com_mode"><option value="flat"';

	if ($com_mode == 'flat') {
		$navbar .= ' selected="selected"';
	}
	$navbar .= '>'._FLAT.'</option><option value="thread"';

	if ($com_mode == 'thread' || $com_mode == '') {
		$navbar .= ' selected="selected"';
	}
	$navbar .= '>'. _THREADED .'</option><option value="nest"';

	if ($com_mode == 'nest') {
		$navbar .= ' selected="selected"';
	}
	$navbar .= '>'. _NESTED .'</option></select> <select name="com_order"><option value="'.XOOPS_COMMENT_OLD1ST.'"';

	if ($com_order == XOOPS_COMMENT_OLD1ST) {
		$navbar .= ' selected="selected"';
	}
	$navbar .= '>'. _OLDESTFIRST .'</option><option value="'.XOOPS_COMMENT_NEW1ST.'"';

	if ($com_order == XOOPS_COMMENT_NEW1ST) {
		$navbar .= ' selected="selected"';
	}

	unset($postcomment_link);
	$navbar .= '>'. _NEWESTFIRST .'</option></select><input type="hidden" name="'.$comment_config['itemName'].'" value="'.$com_itemid.'" /> <input type="submit" value="'. _CM_REFRESH .'" class="formButton" />';

// BUG: not show reply in guest on XCL
// for XCL
	$xoopsTpl->assign('com_anonpost', $this->_xoops_module_config_com_anonpost);

	if ( $this->_xoops_module_config_com_anonpost || $this->_xoops_uid ) {

// absolute URL
//		$postcomment_link = 'comment_new.php?com_itemid='.$com_itemid.'&amp;com_order='.$com_order.'&amp;com_mode='.$com_mode;
		$postcomment_link = $this->_MODULE_URL.'/index.php?fct=comment_new&amp;com_itemid='.$com_itemid.'&amp;com_order='.$com_order.'&amp;com_mode='.$com_mode;

// for xoops 2.0
		$xoopsTpl->assign('anon_canpost', true);
	}

	$link_extra = '';

	if (isset($comment_config['extraParams']) && is_array($comment_config['extraParams'])) {
		foreach ($comment_config['extraParams'] as $extra_param) 
		{
			if (isset(${$extra_param})) {
				$link_extra .= '&amp;'.$extra_param.'='.${$extra_param};
				$hidden_value = $this->_sanitize( ${extra_param} );
				$extra_param_val = ${$extra_param};

			} elseif (isset($_POST[$extra_param])) {
				$extra_param_val = $_POST[$extra_param];
		
			} elseif (isset($_GET[$extra_param])) {
				$extra_param_val = $_GET[$extra_param];
			}
		
			if (isset($extra_param_val)) {
				$link_extra .= '&amp;'.$extra_param.'='.$extra_param_val;
				$hidden_value = $this->_sanitize( $extra_param_val );
				$navbar .= '<input type="hidden" name="'.$extra_param.'" value="'.$hidden_value.'" />';
			}
		}
	}

	if (isset($postcomment_link)) {
		$navbar .= '&nbsp;<input type="button" onclick="self.location.href=\''.$postcomment_link.''.$link_extra.'\'" class="formButton" value="'._CM_POSTCOMMENT.'" />';
	}

	$navbar .= '</td></tr></table></form>';

//	$xoopsTpl->assign(array('commentsnav' => $navbar, 'editcomment_link' => 'comment_edit.php?com_itemid='.$com_itemid.'&amp;com_order='.$com_order.'&amp;com_mode='.$com_mode.''.$link_extra, 'deletecomment_link' => 'comment_delete.php?com_itemid='.$com_itemid.'&amp;com_order='.$com_order.'&amp;com_mode='.$com_mode.''.$link_extra, 'replycomment_link' => 'comment_reply.php?com_itemid='.$com_itemid.'&amp;com_order='.$com_order.'&amp;com_mode='.$com_mode.''.$link_extra));

// absolute URL
	$xoopsTpl->assign(array(
		'commentsnav' => $navbar, 
		'editcomment_link' => $this->_MODULE_URL.'/index.php?fct=comment_edit&amp;com_itemid='.$com_itemid.'&amp;com_order='.$com_order.'&amp;com_mode='.$com_mode.''.$link_extra, 
		'deletecomment_link' => $this->_MODULE_URL.'/comment_delete.php?com_itemid='.$com_itemid.'&amp;com_order='.$com_order.'&amp;com_mode='.$com_mode.''.$link_extra, 
		'replycomment_link' => $this->_MODULE_URL.'/index.php?fct=comment_reply&amp;com_itemid='.$com_itemid.'&amp;com_order='.$com_order.'&amp;com_mode='.$com_mode.''.$link_extra
	));

	// assign some lang variables
	$xoopsTpl->assign(array('lang_from' => _CM_FROM, 'lang_joined' => _CM_JOINED, 'lang_posts' => _CM_POSTS, 'lang_poster' => _CM_POSTER, 'lang_thread' => _CM_THREAD, 'lang_edit' => _EDIT, 'lang_delete' => _DELETE, 'lang_reply' => _REPLY, 'lang_subject' => _CM_REPLIES, 'lang_posted' => _CM_POSTED, 'lang_updated' => _CM_UPDATED, 'lang_notice' => _CM_NOTICE));

	return true;
}

function _getByItemId( $item_id, $order = null, $status = null, $limit = null, $start = 0 )
{
	return $this->_comment_handler->getByItemId( $this->_MODULE_ID, $item_id, $order, $status, $limit, $start );
}

function _getThread( $comment_rootid, $comment_id, $status = null )
{
	return $this->_comment_handler->getThread( $comment_rootid, $comment_id, $status );
}

function _getTopComments( $item_id, $order, $status = null )
{
	return $this->_comment_handler->getTopComments( $this->_MODULE_ID, $item_id, $order, $status );
}

function _sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function _init_xoops_param()
{
	global $xoopsConfig, $xoopsModuleConfig, $xoopsUser, $xoopsModule;

	$this->_xoops_com_mode  = $xoopsConfig['com_mode'];
	$this->_xoops_com_order = $xoopsConfig['com_order'];

	if ( is_object($xoopsModule) ) {
		$this->_MODULE_ID             = $xoopsModule->mid();
		$this->_xoops_module_comments = $xoopsModule->getInfo('comments');
	}

	if ( is_object($xoopsUser) ) {
		$this->_xoops_uid         = $xoopsUser->getVar('uid');
		$this->_xoops_com_mode    = $xoopsUser->getVar('umode');
		$this->_xoops_com_order   = $xoopsUser->getVar('uorder');

		if ( $xoopsUser->isAdmin( $this->_MODULE_ID ) ) {
			$this->_is_module_admin = true;
		}
	}

	if ( isset($xoopsModuleConfig['com_rule']) ) {
		$this->_xoops_module_config_com_rule = $xoopsModuleConfig['com_rule'];
	}

	if ( isset($xoopsModuleConfig['com_anonpost']) ) {
		$this->_xoops_module_config_com_anonpost = $xoopsModuleConfig['com_anonpost'];
	}

}

function _include_lang_file()
{
	global $xoopsConfig;
	$LANGUAGE = $xoopsConfig['language'];
	if ( file_exists(XOOPS_ROOT_PATH.'/language/'.$LANGUAGE.'/comment.php') ) {
		include_once XOOPS_ROOT_PATH.'/language/'.$LANGUAGE.'/comment.php';
	} else {
		include_once XOOPS_ROOT_PATH.'/language/english/comment.php';
	}
}

// --- class end ---
}

?>