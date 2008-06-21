<?php
// $Id: redo_form.php,v 1.1 2008/06/21 12:22:20 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_redo_form
//=========================================================
class webphoto_admin_redo_form extends webphoto_form_this
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_redo_form( $dirname , $trust_dirname )
{
	$this->webphoto_form_this( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_redo_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// redothumbs
//---------------------------------------------------------
function print_form_redothumbs( $param )
{
	$cfg_width   = $this->_config_class->get_by_name('width');
	$cfg_height  = $this->_config_class->get_by_name('height');

	$this->set_row( $param );

	$cap_size = _AM_WEBPHOTO_TEXT_NUMBERATATIME."<br /><br /><span style='font-weight:normal'>"._AM_WEBPHOTO_LABEL_DESCNUMBERATATIME."</span>";
	$cap_resize =_AM_WEBPHOTO_RADIO_RESIZE.' ( '. $cfg_width .' x '. $cfg_width .' )';

	if( $param['counter'] && ( $param['counter'] < $param['size'] ) ) {
		$submit_button  = _AM_WEBPHOTO_FINISHED.' &nbsp; ';
		$submit_button .= '<a href="'. $this->_THIS_FCT_URL .'">';
		$submit_button .= _AM_WEBPHOTO_LINK_RESTART."</a>\n" ;
	} else {
		$submit_button = $this->build_input_submit( 'submit', _AM_WEBPHOTO_SUBMIT_NEXT );
	}

	echo $this->build_form_begin();
	echo $this->build_input_hidden('fct', 'redothumbs');
	echo $this->build_table_begin();

	echo $this->build_line_title( _AM_WEBPHOTO_FORM_RECORDMAINTENANCE );
	echo $this->build_row_text( _AM_WEBPHOTO_TEXT_RECORDFORSTARTING, 'start' );
	echo $this->build_row_text( $cap_size,                   'size' );
	echo $this->build_row_radio_yesno( _AM_WEBPHOTO_RADIO_FORCEREDO, 'forceredo' );
	echo $this->build_row_radio_yesno( _AM_WEBPHOTO_RADIO_REMOVEREC, 'removerec' );
	echo $this->build_row_radio_yesno( $cap_resize,         'resize' );
	echo $this->build_line_ele( '', $submit_button );

	echo $this->build_table_end();
	echo $this->build_form_end(); 
}

// --- class end ---
}

?>