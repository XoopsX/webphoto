/* $Id: select_option_disabled_emulation.js,v 1.1 2009/05/31 18:34:03 ohwada Exp $ */

/* ========================================================
 * 2009-05-30 K.OHWADA
 * change funtion name
 * ========================================================
 */

/****************************************************************
* Author:	Alistair Lattimore
* Website:	http://www.lattimore.id.au/
* Contact:	http://www.lattimore.id.au/contact/
*			Errors, suggestions or comments
* Date:		30 June 2005
* Version:	1.0
* Purpose:	Emulate the disabled attributte for the <option> 
*			element in Internet Explorer.
* Use:		You are free to use this script in non-commercial
*			applications. You are however required to leave
*			this comment at the top of this file.
*
*			I'd love an email if you find a use for it on your 
*			site, though not required.
****************************************************************/

/* window.onload = function() { */
function select_option_disabled_onload() {
	if (document.getElementsByTagName) {
		var s = document.getElementsByTagName("select");

		if (s.length > 0) {
			window.select_current = new Array();

			for (var i=0, select; select = s[i]; i++) {
				select.onfocus = function(){ window.select_current[this.id] = this.selectedIndex; }
				select.onchange = function(){ select_option_disabled_restore(this); }
				select_option_disabled_emulate(select);
			}
		}
	}
}

/* function restore(e) { */
function select_option_disabled_restore(e) {
	if (e.options[e.selectedIndex].disabled) {
		e.selectedIndex = window.select_current[e.id];
	}
}

/* function emulate(e) { */
function select_option_disabled_emulate(e) {
	for (var i=0, option; option = e.options[i]; i++) {
		if (option.disabled) {
			option.style.color = "graytext";
		}
		else {
			option.style.color = "menutext";
		}
	}
}