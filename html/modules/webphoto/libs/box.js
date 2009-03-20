/* ========================================================
 * $Id: box.js,v 1.3 2009/03/20 06:13:53 ohwada Exp $
 * ========================================================
 */

var webphoto_box_gmap_init_flag = false;
var webphoto_timeline_init_flag = false;

function webphoto_box_gmap_timeline_init() 
{
	webphoto_box_init();
	webphoto_gmap_load_marks();
	webphoto_timeline_init();
}

function webphoto_box_gmap_init() 
{
	webphoto_box_init();
	webphoto_gmap_load_marks();
}

function webphoto_box_timeline_init() 
{
	webphoto_box_init();
	webphoto_timeline_init();
}

function webphoto_gmap_timeline_init() 
{
	webphoto_gmap_load_marks();
	webphoto_timeline_init();
}

function webphoto_gmap_init() 
{
	webphoto_gmap_load_marks();
}

function webphoto_timeline_init() 
{
	timeline_load_0();
}

function webphoto_box_init() 
{
    webphoto_box_visible_load( webphoto_box_list );
}

function webphoto_box_visible (boxid, flg) 
{
    if (flg) {
        Element.show(boxid);
    } else {
        Element.hide(boxid);
    }
}

function webphoto_box_visible_flip (boxid, flg) 
{
    if (flg) {
        webphoto_box_visible(boxid + "_a", 0);
        webphoto_box_visible(boxid + "_b", 1);
    } else {
        webphoto_box_visible(boxid + "_a", 1);
        webphoto_box_visible(boxid + "_b", 0);
    }

    var manager = new CookieManager({shelfLife:365});
    manager.setCookie(boxid, flg);

}

function webphoto_box_visible_load (boxids) 
{
    var manager = new CookieManager({shelfLife:365});
    var boxid = boxids.split(",");

    for (var i = 0; i < boxid.length; i ++) {
        if (boxid[i] != "") {
            var flg = manager.getCookie(boxid[i]);
            if (flg != null)
              if (flg == "1") {
                webphoto_box_visible(boxid[i] + "_a", 0);
                webphoto_box_visible(boxid[i] + "_b", 1);
            } else {
                webphoto_box_visible(boxid[i] + "_a", 1);
                webphoto_box_visible(boxid[i] + "_b", 0);
            }
        }
    }
}

function webphoto_box_gmap_on() 
{
	webphoto_box_visible_flip('webphoto_box_gmap', 1);
	webphoto_gmap_on_load_marks();
}

function webphoto_box_gmap_off() 
{
	webphoto_box_visible_flip('webphoto_box_gmap', 0);
	webphoto_gmap_init_flag_true();
}

function webphoto_box_timeline_on() 
{
	webphoto_box_visible_flip('webphoto_box_timeline', 1);
	webphoto_timeline_on_load();
}

function webphoto_box_timeline_off() 
{
	webphoto_box_visible_flip('webphoto_box_timeline', 0);
	webphoto_timeline_init_flag = true;
}

function webphoto_timeline_on_load() 
{
	if ( webphoto_timeline_init_flag == false ) {
		webphoto_timeline_init();
		webphoto_timeline_init_flag = true;
	}
}
