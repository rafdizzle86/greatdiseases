
// UDMv4.5 //
///////////////////////////////////////////////////////////////////
var um={'menuClasses':[],'itemClasses':[],'menuCode':[]};
///////////////////////////////////////////////////////////////////
//                                                               //
//  ULTIMATE DROP DOWN MENU Version 4.5 by Brothercake          //
//  http://www.udm4.com/                                         //
//                                                               //
//  This script may not be used or distributed without license   //
//                                                               //
///////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////
// CORE CONFIGURATION                                            //
///////////////////////////////////////////////////////////////////


//path to images folder
um.baseSRC = "images/";


//initialization trigger element ["id"];
um.trigger = "";


//navbar orientation
um.orientation = [
	"horizontal",	// alignment ["vertical"|"horizontal"|"popup"|"expanding"]
	"left",		// h align ["left"|"right"]
	"top",		// v align ["top"|"bottom"]
	"relative",	// positioning ["relative"|"absolute"|"fixed"|"allfixed"]
	"10px",	// x position ["em"|"ex"|"px"|"0"]
	"0",	// y position ["em"|"ex"|"px"|"0"]
	"1000",		// z order ["0" to "10000"] (menu takes 20000 headroom)
	];


//navbar list output
um.list = [
	"rigid",	// horizontal overflow ["rigid"|"flexible"]
	"yes",		// -SPARE-
	"yes", 		// -SPARE-
	];


//menu behaviors
um.behaviors = [
	"200",		// open timer ["milliseconds"|"0"]
	"500",		// close timer ["milliseconds"|"never"|"0"]
	"yes",		// reposition menus to stay inside the viewport ["yes"|"no"]
	"default",	// manage windowed controls for win/ie ["default","hide","iframe","none"]
	];


//reset behaviors
um.reset = [
	"yes",		// reset from document mouse click ["yes"|"no"]
	"yes",		// reset from window resize ["yes"|"no"]
	"yes",		// reset from text resize ["yes"|"no"]
	"no",		// reset after following link ["yes"|"no"]
	];


//horizontal continuation strip
um.hstrip = [
	"none",		// background ["color"|"#hex"|"rgb()"|"image.gif"|"none"]
	"yes",		// copy navbar item margin-right to margin-bottom ["yes"|"no"]
	];




///////////////////////////////////////////////////////////////////
// MODULE SETTINGS                                               //
///////////////////////////////////////////////////////////////////


//keyboard navigation [comment out or remove if not using]
um.keys = [
	"38",		// up ["n"] ("38" = up arrow key)
	"39",		// right ["n"] ("39" = right arrow key)
	"40",		// down ["n"] ("40" = down arrow key)
	"37",		// left ["n"] ("37" = left arrow key)
	"123",		// hotkey ["n"] ("38" = F12]
	"none",		// hotkey modifier ["none"|"shiftKey"|"ctrlKey"|"altKey"|"metaKey"]
	"27",		// escape ["n"|"none"] ("27" = escape key)
	"document.getElementsByTagName('a')[0]", // exit focus ["js-expression"]
	];




///////////////////////////////////////////////////////////////////
// NAVBAR DEFAULT STYLES                                         //
///////////////////////////////////////////////////////////////////


//styles which apply to the navbar
um.navbar = [
	"0",		// nav -> menu x-offset (+-)["n" pixels]
	"0",		// nav -> menu y-offset (+-)["n" pixels]
	"auto",	// width ["em"|"ex"|"px"] (vertical navbar only - horizontal navbar items have "auto" width) ("%" doesn't work right)
	];


//styles which apply to each navbar item
um.items = [
	"0",		// margin between items ["n" pixels]
	"0",		// border size ["n" pixels] (single value only)
	"collapse",	// border collapse ["collapse"|"separate"] (only applies when margin = "0")
	"#fff #bcc35b #bcc35b #bcc35b",// border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"#fff #369 #369 #369",// hover/focus border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// hover/focus border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"#dedfde #dedfde #000 #dedfde",// visited border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid dashed solid solid",// visited border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"15",		// left/right padding ["n" pixels] (single value only)
	"5",		// top/bottom padding ["n" pixels] (single value only)
	"#6a9172",		// background ["color"|"#hex"|"rgb()"|"image.gif"]
	"#dedfde",		// hover/focus background ["color"|"#hex"|"rgb()"|"image.gif"]
	"#6a9172",		// visited background ["color"|"#hex"|"rgb()"|"image.gif"]
	"13px",		// font size ["em"|"ex"|"%"|"px"|"pt"|"absolute-size"|"relative-size"]
	"verdana,arial,sans-serif",// font family ["font1,font2,font3"] (always end with a generic family name)
	"bold",		// font weight ["normal"|"bold"|"bolder"|"lighter|"100" to "900"]
	"none",		// text decoration ["none"|"underline"|"overline"|"line-through"]
	"left",		// text-align ["left"|"right"|"center"]
	"#fff",	// color ["color"|"#hex"|"rgb()"]
	"#000",	// hover/focus color ["color"|"#hex"|"rgb()"]
	"#fff",	// visited color ["color"|"#hex"|"rgb()"]
	"normal",	// font-style ["normal"|"italic"|"oblique"]
	"normal",	// hover/focus font-style ["normal"|"italic"|"oblique"]
	"normal",	// visited font-style ["normal"|"italic"|"oblique"]
	"letter-spacing:1px !important;",// additional link CSS (careful!)
	"",		// additional hover/focus CSS (careful!)
	"",		// additional visited CSS (careful!)
	"none",// menu indicator character/image ["text"|"image.gif"|"none"]
	"none",// menu indicator rollover character/image ["text"|"image.gif"|"none"] (must be same type)
	"7",		// clipping width of indicator image ["n" pixels] (only when using image arrows)
	">",		// alt text of indicator image ["text"] (only when using image arrows)
	];




///////////////////////////////////////////////////////////////////
// MENU DEFAULT STYLES                                           //
///////////////////////////////////////////////////////////////////


//styles which apply to each menu
um.menus = [
	"0",		// menu -> menu x-offset (+-)["n" pixels]
	"0",		// menu -> menu y-offset (+-)["n" pixels]
	"1",		// border size ["n" pixels] (single value only)
	"#ccc #f3e2a7 #f3e2a7 #ccc",// border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"9.8em",	// width ["em"|"ex"|"px"]
	"2",		// padding ["n" pixels] (single value only)
	"#dedfde",	// background ["color"|"#hex"|"rgb()"|"image.gif"]
	"",		// additional menu CSS (careful!) (you can use a transition here but *not* a static filter)
	"none",// shadow background ["color"|"#hex"|"rgb()"|"image.gif"|"none"]
	"0",		// shadow offset (+-) ["em"|"px"|"pt"|"%"|"0"]
	"filter:progid:DXImageTransform.Microsoft.Shadow(color=#bbddbb,direction=135,strength=2);",// additional shadow layer CSS (if you use a Microsoft.Shadow filter here then Win/IE5.5+ will do that *instead* of default shadow)
	];


//styles which apply to each menu item
um.menuItems = [
	"0",		// margin around items ["n" pixels] (single value only; margins are like table cellspacing)
	"1",		// border size ["n" pixels] (single value only)
	"separate",	// border collapse ["collapse"|"separate"] (only applies when margin = "0")
	"#dedfde #dedfde #aaadaa #dedfde",	// border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"#000",		// hover/focus border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// hover/focus border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"#dedfde #dedfde #aaadaa #dedfde",	// visited border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// visited border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"5",		// left/right padding ["n" pixels] (single value only)
	"1",		// top/bottom padding ["n" pixels] (single value only)
	"transparent",	// background ["color"|"#hex"|"rgb()"|"image.gif"]
	"#fff",	// hover/focus background ["color"|"#hex"|"rgb()"|"image.gif"]
	"transparent",	// visited background ["color"|"#hex"|"rgb()"|"image.gif"]
	"11px",		// font size ["em"|"ex"|"%"|"px"|"pt"|"absolute-size"|"relative-size"]
	"verdana,sans-serif",// font family ["font1,font2,font3"] (always end with a generic family name)
	"normal",	// font weight ["normal"|"bold"|"bolder"|"lighter|"100" to "900"]
	"none",		// text decoration ["none"|"underline"|"overline"|"line-through"]
	"left",		// text-align ["left"|"right"|"center"]
	"#000",		// color ["color"|"#hex"|"rgb()"]
	"#000",		// hover/focus color ["color"|"#hex"|"rgb()"]
	"#000",		// visited color ["color"|"#hex"|"rgb()"]
	"normal",	// font-style ["normal"|"italic"|"oblique"]
	"normal",	// hover/focus font-style ["normal"|"italic"|"oblique"]
	"normal",	// visited font-style ["normal"|"italic"|"oblique"]
	"",		// additional link CSS (careful!)
	"",		// additional hover/focus CSS (careful!)
	"",		// additional visited CSS (careful!)
	">",// submenu indicator character/image ["text"|"image.gif"|"none"]
	">",// submenu indicator rollover character/image ["text"|"image.gif"|"none"] (must be the same type)
	"3",		// clipping width of indicator image ["n" pixels] (only when using image arrows)
	">",		// alt text of indicator image ["text"] (only when using image arrows)
	];


///////////////////////////////////////////////////////////////////
// MENU CLASSES [comment out or remove if not using]             //
///////////////////////////////////////////////////////////////////


//classes which apply to menus [optional]
um.menuClasses["orangeMenu"] = [
	"#fdcb95 #a97742 #a97742 #fdcb95",// border colors ["color"|"#hex"|"rgb()"]
	"solid",	// border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"8em",		// width ["em"|"ex"|"px"]
	"#fec",		// background ["color"|"#hex"|"rgb()"|"image.gif"]
	"",		// additional menu CSS (careful!) (you can use a transition here but *not* a static filter)
	"orangeshadow.png",// shadow background ["color"|"#hex"|"rgb()"|"image.gif"|"none"]
	"2px",		// shadow offset (+-) ["em"|"px"|"pt"|"%"|"0"]
	"filter:alpha(opacity=50);", // additional shadow layer CSS (if you use a Microsoft.Shadow filter here then Win/IE5.5+ will do that *instead* of default shadow)
	];


//classes which apply to menu items [optional]
um.itemClasses["orangeMenuItem"] = [
	"#fec",		// border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"#edbb85",	// hover/focus border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// hover/focus border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"#fec",		// visited border colors ["color"|"#hex"|"rgb()"] (single, double or four values)
	"solid",	// visited border styles ["solid"|"double"|"dotted"|"dashed"|"groove"|"ridge"|"inset"|"outset"] (single, double or four values; be careful with using "none")
	"#fec",		// background ["color"|"#hex"|"rgb()"|"image.gif"]
	"#f8fbd0",	// hover/focus background ["color"|"#hex"|"rgb()"|"image.gif"]
	"#fec",		// visited background ["color"|"#hex"|"rgb()"|"image.gif"]
	"70%",		// font size ["em"|"ex"|"%"|"px"|"pt"|"absolute-size"|"relative-size"]
	"tahoma,sans-serif",// font family ["font1,font2,font3"] (always end with a generic family name)
	"normal",	// font weight ["normal"|"bold"|"bolder"|"lighter|"100" to "900"]
	"none",		// text decoration ["none"|"underline"|"overline"|"line-through"]
	"left",		// text-align ["left"|"right"|"center"]
	"#803090",	// color ["color"|"#hex"|"rgb()"]
	"#5656bd",	// hover/focus color ["color"|"#hex"|"rgb()"]
	"#803090",	// visited color ["color"|"#hex"|"rgb()"]
	"normal",	// font-style ["normal"|"italic"|"oblique"]
	"normal",	// hover/focus font-style ["normal"|"italic"|"oblique"]
	"normal",	// visited font-style ["normal"|"italic"|"oblique"]
	"",		// additional link CSS (careful!)
	"",		// additional hover/focus CSS (careful!)
	"",		// additional visited CSS (careful!)
	">",// submenu indicator character/image ["text"|"image.gif"|"none"] (must be the same type as default submenu indicator)
	">",// submenu indicator rollover character/image ["text"|"image.gif"|"none"] (must be the same type)
	"..",		// alt text of indicator image  ["text"] (only when using image arrow)
	];




// UDMv4.5 //
///////////////////////////////////////////////////////////////////
//                                                               //
//  ULTIMATE DROP DOWN MENU Version 4.5 by Brothercake           //
//  http://www.udm4.com/                                         //
//                                                               //
//  This script may not be used or distributed without license   //
//  udm-control.js                                               //
///////////////////////////////////////////////////////////////////
var umTree = null;
um.ready = 0;
um.pi = function(n) {
    n = parseInt(n, 10);
    return (isNaN(n) ? 0 : n);
};
um.un = 'undefined';
um.m = document;
um.gd = function(n) {
    return um.m.getElementById(n);
};
um.xd = function(n) {
    n.style.display = 'block';
};
um.xn = function(n) {
    n.style.display = 'none';
};
um.xv = function(n) {
    n.style.visibility = 'visible';
};
um.xh = function(n) {
    n.style.visibility = 'hidden';
};
um.ne = function(n) {
    return n.parentNode.className == 'udm';
};
if (typeof um.reset == um.un) {
    um.reset = ['yes', 'yes', 'yes'];
}
if (typeof um.hstrip == um.un) {
    um.hstrip = ['none', 'yes'];
}
if (typeof um.reset[3] == um.un) {
    um.reset[3] = 'no';
}
um.cx = ['orientation', 'list', 'behaviors', 'navbar', 'items', 'menus', 'menuItems', 'menuClasses', 'itemClasses'];
um.ei = 0;
um.e = [];
um.v = [];
um.w = [];
um.vl = 0;
um.wl = 0;
um.ek = 0;
um.im = [];
um.pcv = function(v) {
    if (v && /^[+\-]?[0-9]+$/.test(v)) {
        v = um.pi(v);
        if ((um.ei == 10 || um.ei == 11) && v < 1) {
            v = 1;
        }
    }
    if (v && /\.(gif|png|mng|jpg|jpeg|jpe|bmp)/i.test(v)) {
        um.im[um.ek] = new Image;
        um.im[um.ek++].src = um.baseSRC + v;
    }
    return v;
};
um.d = (typeof um.m.getElementById != um.un && (typeof um.m.createElement != um.un || typeof um.m.createElementNS != um.un) && typeof navigator.IBM_HPR == um.un);
um.u = navigator.userAgent.toLowerCase();
um.o5 = /opera[\/ ][56]/.test(um.u);
um.k = (navigator.vendor == 'KDE');
if (um.o5) {
    um.d = 0;
}
um.b = (um.d || um.o5);
um.o7 = (um.d && typeof window.opera != um.un);
um.o75 = 0;
um.o73 = 0;
um.o71 = 0;
if (um.o7) {
    um.ova = um.pi(um.u.split(/opera[\/ ]/)[1].match(/[7-9]/)[0]);
    um.ovi = um.pi(um.u.split(/opera[\/ ][7-9]\./)[1].match(/^[0-9]/)[0]);
    um.o75 = (um.ova >= 8 || um.ovi >= 5);
    um.o73 = (um.ova >= 8 || um.ovi >= 3);
    um.o71 = (um.ova == 7 && um.ovi <= 1);
}
um.s = (navigator.vendor == 'Apple Computer, Inc.');
um.s2 = (um.s && typeof XMLHttpRequest != um.un);
um.wie = (um.d && typeof um.m.all != um.un && typeof window.opera == um.un && !um.k);
um.mie = (um.wie && um.u.indexOf('mac') > 0);
um.mx = 0;
um.omie = 0;
if (um.mie) {
    um.wie = 0;
    um.iev = um.u;
    um.iev = um.iev.split('msie ');
    um.iev[1] = um.iev[1].split(';');
    um.iev = parseFloat(um.iev[1][0], 10);
    um.omie = (um.iev < 5.2);
}
um.ie = (um.wie || um.mie);
um.wie5 = (um.wie && um.u.indexOf('msie 5') > 0);
um.wie55 = (um.wie && um.u.indexOf('msie 5.5') > 0);
um.wie50 = (um.wie5 && !um.wie55);
um.wie6 = (um.wie && um.u.indexOf('msie 6') > 0);
if (um.wie6) {
    um.wie55 = 1;
}
um.wie7 = (um.wie && typeof XMLHttpRequest != um.un);
um.q = (um.wie5 || um.mie || ((um.wie6 || um.wie7 || um.o7) && um.m.compatMode != 'CSS1Compat'));
um.og = 0;
um.dg = 0;
if (navigator.product == 'Gecko' && !um.s) {
    um.sub = um.pi(navigator.productSub);
    um.og = (um.sub < 20030312);
    um.dg = (um.sub < 20030208);
}
if (um.b) {
    var i = 0;
    do {
        if (um.cx[i].indexOf('Classes') < 0) {
            um.cxl = um[um.cx[i]].length;
            var j = 0;
            do {
                if (typeof um[um.cx[i]][j] != um.un) {
                    um.pv = um.pcv(um[um.cx[i]][j]);
                    um.e[um.ei] = um.pv;
                    um.ei++;
                }
                j++;
            } while ( j < um . cxl );
        } else {
            for (j in um[um.cx[i]]) {
                if (typeof um[um.cx[i]][j] != 'function') {
                    um.cxl = um[um.cx[i]][j].length;
                    var k = 0;
                    do {
                        if (typeof um[um.cx[i]][j][k] != um.un) {
                            um.pcv(um[um.cx[i]][j][k]);
                        }
                        k++;
                    } while ( k < um . cxl );
                    if (um.cx[i] == 'menuClasses') {
                        um.v[j] = um[um.cx[i]][j];
                        um.vl++;
                    } else {
                        um.w[j] = um[um.cx[i]][j];
                        um.wl++;
                    }
                }
            }
        }
        i++;
    } while ( i < 9 );
    um.kb = (typeof um.keys != um.un && !(um.mie || um.o7 || um.k || (um.s && !um.s2)));
    um.skb = (um.kb || (typeof um.keys != um.un && ((um.o7 && !um.o71) || um.k)));
    um.sp = (typeof um.speech != um.un && um.wie);
    if (typeof um.speech != um.un) {
        um.e[12] = 'no';
        um.e[0] = 'vertical';
    }
    um.rp = (um.e[3] == 'relative');
    if (um.wie50 && um.rp) {
        um.e[12] = 'no';
    }
    um.dir = 'left';
    if (um.e[1] == 'rtl') {
        um.dir = 'right';
        um.e[1] = 'right';
    }
    um.e[13] = (um.e[13] == 'yes') ? 'default': (um.e[13] == 'no') ? 'iframe': um.e[13];
    um.hz = (um.wie50 && um.e[13] == 'default') || (um.wie && um.e[13] == 'hide');
    um.h = um.e[0] == 'horizontal';
    i = 4;
    do {
        if (parseFloat(um.e[i], 10) < 0) {
            um.e[i] = '0';
        }
        i++
    } while ( i < 6 );
    if (um.h && um.dir == 'right') {
        um.e[4] = '-' + um.e[4];
    }
    um.p = um.e[0] == 'popup';
    if (um.p) {
        um.va = ['left', 'top', 'absolute', '-2000px', '-2000px'];
        i = 0;
        do {
            um.e[i + 1] = um.va[i];
            i++
        } while ( i < 5 );
        um.e[14] = 0;
        um.e[15] = 0;
    }
    um.ep = 0;
    if (um.e[0] == 'expanding') {
        um.ep = 1;
        um.e[0] = 'vertical';
    }
    um.a = (um.e[1] == 'right');
    um.rg = (um.h && um.e[7] == 'rigid' && um.dir != 'right');
    um.fe = false;
    if (um.e[3] == 'allfixed') {
        um.e[3] = 'fixed';
        if (um.wie5 || um.wie6) {
            um.fe = true;
        }
    }
    um.f = (um.e[3] == 'fixed' && !(um.wie5 || um.wie6 || um.og));
    um.nc = (um.e[17] == 0 && um.e[19] == 'collapse');
    um.mc = (um.e[61] == 0 && um.e[63] == 'collapse');
    um.nm = ((um.og && um.rp) || (um.omie && um.h) || ((um.dg || um.wie50) && um.dir == 'right'));
    um.nr = (um.nm || um.mie);
    um.ns = (um.dg || um.o71 || (um.wie50 && um.rp) || (um.o7 && um.f) || um.mie);
    um.cns = (typeof um.m.createElementNS != um.un);
    um.ss = (um.cns && typeof um.m.styleSheets != um.un && !(um.s || um.k));
    if (um.kb) {
        i = 0;
        do {
            um.keys[i] = um.pi(um.keys[i]);
            i++
        } while ( i < 5 );
        if (um.keys[6] != 'none') {
            um.keys[6] = um.pi(um.keys[6]);
        } else {
            um.keys[6] = -1;
        }
    }
    um.ni = /(gif|png|mng|jpg|jpeg|jpe|bmp)/i.test(um.e[45]);
    um.mi = /(gif|png|mng|jpg|jpeg|jpe|bmp)/i.test(um.e[89]);
}
um.rn = 0;
um.rv = [];
um.addReceiver = function(f, c) {
    um.rv[um.rn++] = [f, c];
};
um.gp = function(n) {
    return n ? um.vn(n.nodeName).toLowerCase() == 'li' ? n: this.gp(n.parentNode) : null;
};
um.createElement = function(n, a) {
    um.el = (um.cns) ? um.m.createElementNS('http://www.w3.org/1999/xhtml', n) : um.m.createElement(n);
    if (typeof a != um.un) {
        for (var i in a) {
            switch (i) {
            case 'text':
                um.el.appendChild(um.m.createTextNode(a[i]));
                break;
            case 'class':
                um.el.className = a[i];
                break;
            default:
                um.el.setAttribute(i, '');
                um.el[i] = a[i];
                break;
            }
        }
    }
    return um.el;
};

/* UDMv4.5 */
/***************************************************************\

  ULTIMATE DROP DOWN MENU Version 4.5 by Brothercake
  http://www.udm4.com/

  This script may not be used or distributed without license

\***************************************************************/

var um = new Object;

um.e = ['horizontal', 'left', 'top', 'relative', '10px', '0', 1000, 'rigid', 'yes', 'yes', 200, 500, 'yes', 'default', -80, 22, 'auto', 0, 0, 'collapse', '#fff #bcc35b #bcc35b #bcc35b', 'solid', '#fff #369 #369 #369', 'solid', '#dedfde #dedfde #000 #dedfde', 'solid', 15, 5, '#6a9172', '#dedfde', '#6a9172', '13px', 'verdana, arial, sans-serf;', 'bold', 'none', 'left', '#fff', '#000', '#fff', 'normal', 'normal', 'normal', '', '', '', 'none', 'none', 7, '>', -7, 0, 1, '#ccc #f3e2a7 #f3e2a7 #ccc', 'solid', '9.8em', 2, '#dedfde', '', 'none', '0', 'filter:progid:DXImageTransform.Microsoft.Shadow(color=#bbddbb,direction=135,strength=2);', 0, 1, 'separate', '#dedfde #dedfde #aaadaa #dedfde', 'solid', '#000', 'solid', '#dedfde #dedfde #aaadaa #dedfde', 'solid', 5, 1, 'transparent', '#fff', 'transparent', '11px', 'verdana, arial, sans-serf;', 'normal', 'none', 'left', '#000', '#000', '#000', 'normal', 'normal', 'normal', '', '', '', 'right-black.gif', 'right-black.gif', 3, '>', ];
um.v = [];
um.v['orangeMenu'] = ['#fdcb95 #a97742 #a97742 #fdcb95', 'solid', '8em', '#fec', '', 'orangeshadow.png', '2px', 'filter:alpha(opacity=50);', ];
um.w = [];
um.w['orangeMenuItem'] = ['#fec', 'solid', '#edbb85', 'solid', '#fec', 'solid', '#fec', '#f8fbd0', '#fec', '70%', 'tahoma,sans-serif', 'normal', 'none', 'left', '#803090', '#5656bd', '#803090', 'normal', 'normal', 'normal', '', '', '', 'right-black.gif', 'right-black.gif', '..', ];
um.vl = 1;
um.wl = 1;
um.menuCode = [];
um.baseSRC = "images/";
um.trigger = '';
var i, j;
um.imn = ['orange-yellow-gradient.jpg', 'orange-cream-gradient.jpg', 'orange-yellow-gradient.jpg', 'right-black.gif', 'right-black.gif', 'greenshadow.png', 'right-black.gif', 'right-black.gif', 'orangeshadow.png', 'right-black.gif', 'right-black.gif', ];
um.im = [];
i = 0;
do {
    um.im[i] = new Image;
    um.im[i].src = um.baseSRC + um.imn[i];
    i++;
} while ( i < 11 );
var umTree = null;
um.gp = function(umRI) {
    return umRI ? um.vn(umRI.nodeName).toLowerCase() == 'li' ? umRI: this.gp(umRI.parentNode) : null;
};
um.ready = 0;
um.pi = function(n) {
    n = parseInt(n, 10);
    return (isNaN(n) ? 0 : n);
};
um.un = 'undefined';
um.m = document;
um.gd = function(umD) {
    return um.m.getElementById(umD);
};
um.xd = function(umD) {
    umD.style.display = 'block';
};
um.xn = function(umD) {
    umD.style.display = 'none';
};
um.xv = function(umD) {
    umD.style.visibility = 'visible';
};
um.xh = function(umD) {
    umD.style.visibility = 'hidden';
};
um.ne = function(umD) {
    return umD.parentNode.className == 'udm';
};
um.u = navigator.userAgent.toLowerCase();
um.d = (typeof um.m.getElementById != um.un && (typeof um.m.createElement != um.un || typeof um.m.createElementNS != um.un) && typeof navigator.IBM_HPR == um.un);
um.o5 = /opera[\/ ][56]/.test(um.u);
um.k = (navigator.vendor == 'KDE');
if (um.o5) {
    um.d = 0;
};
um.b = (um.d || um.o5);
um.o7 = (um.d && typeof window.opera != um.un);
um.o75 = 0;
um.o73 = 0;
um.o71 = 0;
if (um.o7) {
    um.ova = um.pi(um.u.split(/opera[\/ ]/)[1].match(/[7-9]/)[0]);
    um.ovi = um.pi(um.u.split(/opera[\/ ][7-9]\./)[1].match(/^[0-9]/)[0]);
    um.o75 = (um.ova >= 8 || um.ovi >= 5);
    um.o73 = (um.ova >= 8 || um.ovi >= 3);
    um.o71 = (um.ova == 7 && um.ovi <= 1);
}
um.s = (navigator.vendor == 'Apple Computer, Inc.');
um.s2 = (um.s && typeof XMLHttpRequest != um.un);
um.wie = (um.d && typeof um.m.all != um.un && typeof window.opera == um.un && !um.k);
um.mie = (um.wie && um.u.indexOf('mac') > 0);
um.mx = 0;
um.omie = 0;
if (um.mie) {
    um.wie = 0;
    um.omie = (/msie 5\.[0-1]/.test(um.u));
}
um.ie = (um.wie || um.mie);
um.wie5 = (um.wie && um.u.indexOf('msie 5') > 0);
um.wie55 = (um.wie && um.u.indexOf('msie 5.5') > 0);
um.wie50 = (um.wie5 && !um.wie55);
um.wie6 = (um.wie && um.u.indexOf('msie 6') > 0);
if (um.wie6) {
    um.wie55 = 1;
}
um.wie7 = (um.wie && typeof XMLHttpRequest != um.un);
um.q = (um.wie5 || um.mie || ((um.wie6 || um.wie7 || um.o7) && um.m.compatMode != 'CSS1Compat'));
um.og = 0;
um.dg = 0;
if (navigator.product == 'Gecko' && !um.s) {
    um.sub = um.pi(navigator.productSub);
    um.og = (um.sub < 20030312);
    um.dg = (um.sub < 20030208);
}
um.hstrip = ['none', 'yes'];
um.reset = ['yes', 'yes', 'yes', 'no'];
um.keys = [38, 39, 40, 37, 123, 'none', 27, 'document.getElementsByTagName("a")[4]', ];
um.kb = (true && !(um.mie || um.o7 || um.k || (um.s && !um.s2)));
um.skb = (um.kb || (true && ((um.o7 && !um.o71) || um.k)));
um.sp = (false && um.wie);
if (um.wie50 && um.r) {
    um.e[12] = 'no';
}
um.rp = false;
um.p = false;
um.hz = ((um.wie50 && true) || (um.wie && false));
um.a = false;
um.h = false;
um.rg = (um.h && false && true);
um.ep = 0;
if (false) {
    um.ep = 1;
    um.e[0] = 'vertical';
}
um.fe = false;
if (um.e[3] == 'allfixed') {
    um.e[3] = 'fixed';
    if (um.wie5 || um.wie6) {
        um.fe = true;
    }
}
um.f = (um.e[3] == 'fixed' && !(um.wie5 || um.wie6 || um.og));
um.nc = false;
um.mc = false;
um.nm = ((um.og && um.rp) || (um.omie && um.h) || ((um.dg || um.wie50) && false));
um.nr = (um.nm || um.mie);
um.ns = (um.dg || um.o71 || (um.wie50 && um.rp) || (um.o7 && um.f) || um.mie);
um.cns = (typeof um.m.createElementNS != um.un);
um.ss = (um.cns && typeof um.m.styleSheets != um.un && !(um.s || um.k));
um.ni = true;
um.mi = true;
um.rn = 0;
um.rv = [];
um.addReceiver = function(umFC, umEC) {
    um.rv[um.rn++] = [umFC, umEC];
};
um.createElement = function(umE, umA) {
    um.el = (um.cns) ? um.m.createElementNS('http://www.w3.org/1999/xhtml', umE) : um.m.createElement(umE);
    if (typeof umA != um.un) {
        for (var i in umA) {
            switch (i) {
            case 'text':
                um.el.appendChild(um.m.createTextNode(umA[i]));
                break;
            case 'class':
                um.el.className = umA[i];
                break;
            default:
                um.el.setAttribute(i, '');
                um.el[i] = umA[i];
                break;
            }
        }
    }
    return um.el;
};
um.ap = function(c, v) {
    var r = um.rv.length;
    if (r > 0) {
        for (var i = 0; i < r; i++) {
            if (um.rv[i][1] == '') {
                um.rv[i][0](v, c);
            } else if (c == um.rv[i][1]) {
                um.rv[i][0](v);
            }
        }
    }

};
if (um.wie) {
    um.eva = [];
    um.ex = ['onmouseover', 'onmouseout', 'onmousedown', 'onmouseup', 'onclick', 'onmousewheel', 'onfilterchange', 'onkeydown', 'onfocus', 'onactivate', 'onscroll', 'over', 'out'];
    um.gg = um.ex.length;
    window.attachEvent('onunload',
    function() {
        um.lil = umTree.getElementsByTagName('li');
        um.lin = um.lil.length;
        i = 0;
        do {
            um.gc(um.lil[i]).detachEvent((um.wie55) ? 'onactivate': 'onfocus', um.eva[i]);
            i++;
        } while ( i < um . lin );
        um.da = document.all.length;
        i = 0;
        do {
            um.t = document.all[i];
            j = 0;
            do {
                um.t[um.ex[j]] = null;
                j++;
            } while ( j < um . gg );
            i++;
        } while ( i < um . da );
    });
}
if (!um.k && typeof window.addEventListener != um.un) {
    window.addEventListener('load', umIni, 0);
} else if (um.o7) {
    um.m.addEventListener('load', umIni, 0);
} else if (um.wie) {
    window.attachEvent('onload', umIni);
} else {
    if (typeof window.onload == 'function') {
        um.on = onload;
        window.onload = function() {
            um.on();
            umIni();
        };
    } else {
        window.onload = umIni;
    }
}
function umIni(g) {
    if (typeof g == um.un) {
        g = 1;
    }
    if (typeof um.ini != um.un || (um.k && typeof window.sidebar == um.un)) {
        return;
    }
    if (um.drt) {
        clearTimeout(um.drt);
    }
    um.ini = 1;
    um.ha = 0;
    umTree = (um.b) ? um.gd('udm') : null;
    if (umTree && um.d) {
        if (g) {
            um.ap('000', umTree);
        }
        for (i in um.menuCode) {
            var l = um.gd(i);
            if (l) {
                if (um.mie) {
                    um.menuCode[i] = um.menuCode[i].replace(/<\/(li|ul)>/ig, '</$1>\n');
                }
                l.innerHTML += um.menuCode[i];
                if (um.mie) {
                    um.dm = um.gm(l);
                    um.xn(um.dm);
                    um.xh(um.dm);
                }
            }
        }
        um.bub = 0;
        um.wsr = 0;
        um.rtl = um.m.getElementsByTagName('html')[0].getAttribute('dir') == 'rtl';
        um.kdf = 0;
        if (um.o7) {
            um.m.addEventListener('keydown',
            function(e) {
                if (e.keyCode == 16) {
                    um.kdf = 1;
                }
            },
            0);
            um.m.addEventListener('keyup',
            function(e) {
                if (e.keyCode == 16) {
                    um.kdf = 0;
                }
            },
            0);
        }
        um.skb = (um.skb && typeof umKM == 'function');
        um.kb = (um.skb && um.kb);
        if (um.skb) {
            um.kbm = new umKM;
            if (g) {
                um.ap('001', um.kbm);
            }
        }
        um.sp = (um.sp && typeof udmSpeechModule == 'function');
        um.n = new umNav(umTree, g);
        if (g) {
            um.ap('009', um.n);
        }
        if (um.fe) {
            um.tr.style.left = (um.getScrollAmount(1)) + 'px';
            um.tr.style.top = (um.getScrollAmount()) + 'px';
            window.attachEvent('onscroll',
            function() {
                um.tr.style.left = (um.getScrollAmount(1)) + 'px';
                um.tr.style.top = (um.getScrollAmount()) + 'px';
            });
        }
        if (um.s) {
            umTree.style.KhtmlOpacity = '1';
        }
        um.s1 = (typeof umTree.style.KhtmlOpacity != um.un);
        um.ready = 1;
        if (g) {
            um.ap('010', um.tr);
        }
    }
};
function umNav(umTree, g) {
    um.n = this;
    um.tr = umTree;
    if (um.wie) {
        um.tr.style.color = 'black';
    }
    um.jv = 'javascript:void(0)';
    var l = umTree.getElementsByTagName('li');
    if (l.length == 0) {
        return;
    }
    var i = 0;
    do {
        if (um.wl > 0) {
            var b = um.es(l[i].className);
            if (b == '' && !um.ne(l[i])) {
                var a = um.gp(l[i].parentNode);
                b = um.es(a.className);
                if (b != '' && !um.ne(a)) {
                    l[i].className = b;
                }
            }
        }
        this.it(l[i]);
        if (g) {
            um.ap('008', l[i]);
        }
        i++;
    } while ( i < l . length );
    if (um.vl > 0) {
        um.mo = um.gu(um.tr);
        um.en = um.mo.length;
        if (um.en > 0) {
            i = 0;
            do {
                b = um.es(um.mo[i].className);
                if (b == '') {
                    a = um.mo[i].parentNode.parentNode;
                    b = um.es(a.className);
                    if (b != '' && b != 'udm') {
                        um.mo[i].className = b;
                    }
                }
                i++;
            } while ( i < um . en );
        }
    }
    um.mf = 0;
    um.lf = 0;
    um.ety = typeof document.addEventListener != um.un ? 'addEventListener': typeof document.attachEvent != um.un ? 'attachEvent': '';
    um.epx = um.ety == 'attachEvent' ? 'on': '';
    if (um.ety != '') {
        um.m[um.ety](um.epx + 'mousedown',
        function(e) {
            if (!e) {
                e = window.event;
            }
            um.mf = 1;
            if (um.skb) {
                um.ha = 0;
            }
            clearInterval(um.oc);
            um.or = 0;
            if (um.reset[0] != 'no') {
                if (um.hz) {
                    if (!um.tr.contains(event.srcElement)) {
                        um.n.ts('visible');
                    }
                }
                um.cm(e);
            }
        },
        0);
        um.m[um.ety](um.epx + 'mouseup',
        function() {
            um.mf = 0;
        },
        0);
    }
    if (um.kb) {
        um.kbm.bdh();
    }
    if (um.skb && um.o7) {
        um.kbm.bfh();
    }
    um.cc = null,
    um.cr = 0,
    um.oc = null,
    um.or = 0;
    if (!um.ie) {
        um.tr.contains = function(n) {
            return (n == null) ? false: (n == this) ? true: this.contains(n.parentNode);
        };
    }
    um.lw = um.getWindowDimensions();
    um.lh = um.gc(um.tr).offsetHeight;
    if (um.og && um.hstrip[0] != 'none') {
        um.tr.style.height = (um.hstrip[1] == 'yes') ? (um.lh + um.e[17]) + 'px': um.lh + 'px';
    }
    var p = um.m.getElementById('udm-purecss');
    if (p) {
        p.disabled = 1;
    }
    um.vs = setInterval('um.n.ws()', 55);
};
umNav.prototype.it = function(l) {
    if (um.wie) {
        var f = (um.wie55) ? 'onactivate': 'onfocus';
        um.gc(l).attachEvent(f, um.eva[um.eva.length] = function() {
            if (um.kb && !um.lf) {
                um.bub = 0;
                l.over(1, um.gc(l));
            }
        });
    }
    var a = um.es(l.className);
    var h = (a.indexOf('onclick') != -1) ? 'onclick': 'onmouseover';
    var s = um.ne(l);
    var umM = (typeof um.gu(l)[0] != um.un) ? um.gu(l)[0] : null;
    if (typeof um.fl == um.un) {
        um.fl = um.gc(l);
    }
    if (umM && !um.nr) {
        if (((s && um.e[45] != 'none') || (!s && um.e[89] != 'none')) && um.n.cck()) {
            if (s) {
                var r = um.e[45];
                var x = (um.ni) ? um.e[48] : '¦¦';
            } else {
                r = um.e[89];
                x = (um.mi) ? um.e[92] : '¦¦';
                if (typeof um.w[a] != um.un) {
                    r = um.w[a][23];
                    x = (um.mi) ? um.w[a][25] : '¦¦';
                }
            }
            if (x == '¦¦') {
                var t = {
                    'class': 'udmA',
                    'text': r
                };
                var u = u = um.gc(l).appendChild(um.createElement('span', t));
            } else {
                if (um.wie) {
                    um.gc(l).insertAdjacentHTML('beforeEnd', '<img class=\'udmA\' alt=\'' + x + '\' title=\'\' />');
                    u = um.gc(l).lastChild;
                    u.src = um.baseSRC + r;
                } else if (um.s || um.k) {
                    t = {
                        'class': 'udmA'
                    };
                    u = um.gc(l).appendChild(um.createElement('span', t));
                    t = {
                        'src': um.baseSRC + r,
                        'alt': x,
                        'title': ''
                    };
                    u.appendChild(um.createElement('img', t));
                } else {
                    t = {
                        'class': 'udmA',
                        'alt': x,
                        'title': ''
                    };
                    u = um.gc(l).appendChild(um.createElement('img', t));
                    u.src = um.baseSRC + r;
                }
            }
            if (h == 'onclick') {
                u.onmousedown = function() {
                    return false;
                }
            };
            u.onmouseover = function(e) {
                var t = um.gp(this.parentNode).parentNode.childNodes;
                var n = t.length;
                for (var i = 0; i < n; i++) {
                    if (t[i].nodeName != '#text' && um.gu(t[i]).length > 0) {
                        if (um.gu(t[i])[0].style.visibility == 'visible') { (!e) ? event.cancelBubble = 1 : e.stopPropagation();
                            this.parentNode.style.zIndex = um.e[6] += 2;
                            return false;
                            break;
                        }
                    }
                    clearInterval(um.oc);
                    um.or = 0;
                }
                return true;
            };
            u.onmouseout = function() {
                clearInterval(um.oc);
                um.or = 0;
            };
            um.xd(u);
            if (s) {
                this.wp(u, l, um.e[26], um.e[18], 1);
            }
        }
    }
    if (um.mie) {
        var v = l.getElementsByTagName('span')[0];
        if (typeof v != um.un) {
            v.onclick = function() {
                this.parentNode.click();
            };
        }
    }
    if (um.mie) {
        t = um.gc(l);
        if (t.className && /nohref/.test(t.className)) {
            um.gc(l).href = um.jv;
        }
    }
    if (um.skb) {
        um.kbm.bth(l);
    }
    l.onmousedown = function(e) {
        um.lf = 1;
        um.ap('030', um.gc(this)); (!e) ? event.cancelBubble = 1 : e.stopPropagation();
    };
    l.onmouseup = function(e) {
        um.ap('035', um.gc(this)); (!e) ? event.cancelBubble = 1 : e.stopPropagation();
    };
    if (h != 'onclick') {
        l.onclick = function(e) {
            if (!um.bub) {
                um.qc(um.gc(this).href);
            }
            um.bub = 1;
        };
    } else if (!um.mie) {
        l.onmouseover = function() {
            um.n.lr(um.gc(l), 1);
            um.bub = 0;
        };
    }
    if (! (um.mie && h == 'onclick')) {
        l[h] = function(e) {
            var v = (um.ie) ? window.event.srcElement: e.target;
            if (v.nodeName == '#text' && e.type == 'click') {
                v = v.parentNode;
            }
            if (!um.gp(v)) {
                return false;
            }
            var b = um.es(um.gp(v).className);
            var c = (um.lf && !um.nm && b.indexOf('onclick') != -1);
            if (c) {
                um.rt = um.e[10];
                um.e[10] = 1;
            }
            if (b.indexOf('onclick') == -1) {
                um.bub = 0;
            } else if (!um.lf) {
                if (!um.bub) {
                    um.qc(v.href);
                }
                um.bub = 1;
            }
            this.over(0, v);
            if (c) {
                um.e[10] = um.rt;
                um.lf = 0;
                if (v.nodeName != '#text' && um.gu(um.gp(v)).length > 0) {
                    if (typeof v.blur != um.un) {
                        v.blur();
                    }
                    if (um.gu(um.gp(v))[0].style.display == 'block') {
                        um.n.cd(this.parentNode); (!e) ? event.cancelBubble = 1 : e.stopPropagation();
                        return false;
                    } (!e) ? event.cancelBubble = 1 : e.stopPropagation();
                    b = um.es(um.gp(v).className);
                    return (b.indexOf('(true)') != -1);
                } else {
                    um.qc(v.href);
                    um.bub = 1;
                }
            }
            if (!e) {
                e = window.event;
            }
            return (e.type == 'click' || um.o7);
        };
        l.onmouseout = function(e) {
            this.out(e);
        };
    }
    l.over = function(f, t) {
        if (um.bub || (!f && um.ha && um.kdf)) {
            return false;
        }
        var c = um.n.cck();
        if (!c || um.mf) {
            um.mf = 0;
            if (!um.ec) {
                if (um.gm(this)) {
                    this.removeChild(um.gm(this));
                }
            }
            return false;
        }
        if (f) {
            if (!um.wsr && !um.ie) {
                um.kbm.cws(um.tr);
                um.wsr = 1;
            }
            um.ha = 1;
            if (um.ie && event.altKey) {
                um.n.ck(um.gp(t).parentNode);
            }
            um.ap('040', t);
        }
        if (!f) {
            var n = um.vn(t.nodeName).toLowerCase();
            if (/(li|ul)/.test(n)) {
                return false;
            }
            if (um.skb) {
                if (!um.lf) {
                    um.e[10] = um.mt[0];
                    um.e[11] = um.mt[1];
                }
                um.nf = um.gc(this);
                if (um.ha) {
                    um.n.ck(l.parentNode);
                    um.n.cd(um.gp(t).parentNode);
                    um.nf.focus();
                    um.nf.blur();
                    um.ha = 0;
                }
            }
            um.ap('020', t);
        }
        clearInterval(um.cc);
        um.cr = 0;
        um.n.lr(um.gc(l), 1);
        um.n.pr(umM, l, f, t);
        return l;
    };
    l.out = function(e) {
        if (um.o7 && um.ha && um.kdf) {
            return;
        }
        if (um.lf) {
            um.gc(this).blur();
        }
        um.lf = 0;
        if (!e) {
            e = window.event;
            e.relatedTarget = e.toElement;
        }
        if (!l.contains(e.relatedTarget)) {
            if (!um.tr.contains(e.relatedTarget)) {
                clearInterval(um.cc);
                um.cr = 0;
            }
            um.n.cp(umM, l);
            um.ap('025', um.gc(this));
        }
    };
    if (!um.ie) {
        l.contains = function(n) {
            return (n == null) ? false: (n == this) ? true: this.contains(n.parentNode);
        };
    }
};
umNav.prototype.cck = function() {
    if (typeof document.defaultView != um.un && typeof document.defaultView.getComputedStyle != um.un) {
        um.sa = document.defaultView.getComputedStyle(um.fl, '').getPropertyValue('display');
    } else if (typeof um.fl.currentStyle != um.un && um.fl.currentStyle) {
        um.sa = um.fl.currentStyle.display;
    }
    um.mv = 1;
    um.ec = (!um.wie || um.tr.currentStyle.color == 'black');
    return ((um.sa != 'inline' || typeof um.sa == um.un) && um.ec);
};
umNav.prototype.lr = function(l, v) {
    if (l && typeof l.style != um.un) {
        um.cl = um.es(l.className);
        um.ii = um.ne(um.gp(l));
        if (v) {
            l.style.zIndex = um.e[6] += 2; (um.cl == '') ? l.className = 'udmR': l.className += (l.className.indexOf('udmR') == -1) ? ' udmR': '';
        } else {
            if (um.cl.indexOf('udmR') != -1) {
                l.className = um.cl.replace(/([ ]?udmR)/g, '');
            }
        }
        um.n.wv(l, um.ii);
    }
};
umNav.prototype.pr = function(m, l, f, r) {
    if (um.skb && f) {
        um.kbm.cu(m, l, r);
    }
    if (!um.nm && m && m.style.visibility != 'visible') {
        if (um.wie && !um.wie7) {
            if (um.e[61] > 0) {
                um.gc(m).style.marginTop = um.e[61] + 'px';
            } else if (um.e[63] == 'collapse') {
                m.firstChild.style.marginTop = 0 + 'px';
            }
        }
        if (um.skb && f) {
            um.n.ou(m);
        }
        if (! (um.skb && f)) {
            um.n.tu(m, null);
        }
    }
    if (m == null) {
        um.n.tu(null, l);
    }
};
umNav.prototype.tu = function(m, l) {
    if (um.cr) {
        clearInterval(um.oc);
        um.oj = m;
        um.ij = l;
        um.or = 1;
        um.oc = setInterval('um.n.tu(um.oj,um.ij)', um.e[10]);
    } else if (um.or) {
        clearInterval(um.oc);
        um.or = 0;
        this.ou(m, l);
    } else {
        um.ap('061', m);
        um.oj = m;
        um.ij = l;
        um.or = 1;
        um.oc = setInterval('um.n.tu(um.oj,um.ij)', um.e[10]);
    }
};
umNav.prototype.ou = function(m, l) {
    if (m == null) {
        this.cd(l.parentNode);
        return false;
    }
    this.cd(um.gp(m).parentNode);
    if (typeof m.m == um.un) {
        m.m = um.gu(m);
        m.l = m.m.length;
        if (m.l > 0) {
            for (var i = 0; i < m.l; i++) {
                um.xh(m.m[i]);
                um.xn(m.m[i]);
            }
        }
    }
    if (um.ep) {
        m.style.position = 'static';
    }
    if (um.hz) {
        this.ts('hidden');
    }
    um.xd(m);
    if (!um.nr && um.e[89] != 'none') {
        var c = m.childNodes.length;
        for (i = 0; i < c; i++) {
            var t = m.childNodes.item(i);
            var n = um.vn(t.nodeName).toLowerCase();
            if (n == 'li') {
                var a = um.n.ga(um.gc(t));
                if (a) {
                    this.wp(a, t, um.e[70], um.e[62], 0);
                }
            }
        }
    }
    um.ap('058', m);
    this.pu(m);
    if (um.e[12] == 'yes') {
        this.ru(m);
    }
    um.mp = {
        x: (m.offsetLeft),
        y: (m.offsetTop)
    };
    um.sh = null;
    if (!um.ns && um.e[58] != 'none') {
        this.hl(m);
    }
    if (um.wie55 && (um.e[13] == 'default' || um.e[13] == 'iframe')) {
        this.il(m);
    }
    um.hf = (um.wie55 && typeof m.filters != 'unknown' && m.filters && m.filters.length > 0);
    if (um.hf) {
        m.filters[0].Apply();
    }
    if (um.wie && um.h) {
        var t = m.parentNode;
        if (um.ne(t)) {
            t = t.style;
            t.position = 'absolute';
            t.zIndex = um.e[6] += 2;
            t.position = 'relative';
        }
    }
    um.xv(m);
    if (um.hf) {
        um.ap('065', m);
        m.filters[0].Play();
        if (um.sh) {
            m.onfilterchange = function() {
                um.xd(um.sh);
                um.ap('066', m);
            };
        }
    } else if (um.sh) {
        um.xd(um.sh);
    }
    if (um.wie50) {
        um.xn(m);
        um.xd(m);
    }
    if (um.ep && um.s && m.offsetLeft < -1000) {
        var fs = um.pi(document.defaultView.getComputedStyle(m, '').getPropertyValue('font-size'));
        m.style.fontSize = (fs - 1) + 'px';
        setTimeout(function() {
            m.style.fontSize = fs + 'px';
        },
        0);
    }
    um.ap('060', m);
    return m;
};
umNav.prototype.cd = function(m) {
    var s = um.mie ? um.gt(m, 'ul') : um.gu(m);
    var n = s.length;
    for (var i = 0; i < n; i++) {
        this.clm(s[i]);
    }
};
umNav.prototype.ck = function(m) {
    var l = um.mie ? um.gt(m, 'a') : m.getElementsByTagName('a');
    var n = l.length;
    for (var i = 0; i < n; i++) {
        this.lr(l[i], 0);
    }
};
umNav.prototype.cp = function(m, l) {
    clearTimeout(um.oc);
    um.or = 0;
    this.lr(um.gc(l), 0);
    if (!um.nm && m) {
        this.cot(m);
    }
};
umNav.prototype.cot = function(m) {
    if (um.cr) {
        clearInterval(um.cc);
        um.cr = 0;
        this.clm(m);
    } else if (um.e[11] != 'never') {
        um.ap('071', m);
        um.cb = m;
        um.cr = 1;
        um.cc = setInterval('um.n.cot(um.cb)', um.e[11]);
    }
};
umNav.prototype.clm = function(m) {
    if (m.style.visibility == 'visible') {
        if (typeof um.sim == um.un || !um.sim || um.ha) {
            um.xh(m);
            um.xn(m);
            if (um.hz) {
                if (um.ne(m.parentNode)) {
                    this.ts('visible');
                }
            }
            um.t = ['udmC', 'udmS'];
            for (var i = 0; i < 2; i++) {
                var b = m.parentNode.lastChild;
                if (b && b.className && b.className.indexOf(um.t[i]) != -1) {
                    m.parentNode.removeChild(b);
                }
            }
        }
        um.ap('070', m);
    }
};
umNav.prototype.ga = function(l) {
    var a = null;
    var t = ['span', 'img'];
    for (var k = 0; k < 2; k++) {
        var s = l.getElementsByTagName(t[k]);
        var n = s.length;
        for (var j = 0; j < n; j++) {
            var b = um.es(s[j].className);
            if (b == 'udmA') {
                a = s[j];
                break;
            }
        }
    }
    return a;
};
umNav.prototype.wp = function(a, l, p, b, n) {
    a.fn = arguments;
    if (a.offsetHeight > 0 && !um.o7) {
        this.wpo(a.fn[0], a.fn[1], a.fn[2], a.fn[3], a.fn[4]);
    } else {
        a.c = 0;
        a.ti = window.setInterval(function() {
            if (a.offsetHeight > 0) {
                clearInterval(a.ti);
                um.n.wpo(a.fn[0], a.fn[1], a.fn[2], a.fn[3], a.fn[4]);
            } else {
                a.c++;
                if (a.c >= 100) {
                    clearInterval(a.ti);
                }
            }
        },
        55);
    }
    return true;
};
umNav.prototype.wpo = function(a, l, p, b, n) {
    var s = um.gc(l);
    var t = [a.offsetWidth, a.offsetHeight];
    a.style.marginTop = um.pi(((s.offsetHeight - t[1]) / 2) - b) + 'px';
    s.style[(um.a || um.rtl) ? 'paddingLeft': 'paddingRight'] = ((p * 2) + t[0]) + 'px';
    if (um.wie && um.rtl) {
        a.style.marginRight = ((n) ? (0 - um.e[26]) : (0 - um.e[70])) + 'px';
    }
    if (((um.wie50 && um.a) || (um.wie55 && um.rtl)) && n && um.h) {
        a.style.top = (b) + 'px';
        a.style.left = (b) + 'px';
    }
    if ((n && um.ni) || (!n && um.mi)) {
        var c = ((n) ? um.e[47] : um.e[91]);
        if ((t[0] - c) < 0) {
            c = t[0];
        }
        a.style.clip = (um.a || um.rtl) ? 'rect(0,' + c + 'px,' + t[1] + 'px,0)': 'rect(0,' + t[0] + 'px,' + t[1] + 'px,' + (t[0] - c) + 'px)';
    }
    um.xv(a);
    return true;
};
umNav.prototype.wv = function(l, n) {
    if (um.nr) {
        return false;
    }
    var a = this.ga(l);
    if (a) {
        var c = um.es(l.className);
        var r = (c.indexOf('udmR') == -1);
        if (c.indexOf('udmY') != -1) {
            r = 0;
        }
        var p = um.es(um.gp(l).className);
        var t = (um.s || um.k) ? a.firstChild: a;
        t.src = um.baseSRC + ((n) ? (r) ? um.e[45] : um.e[46] : (typeof um.w[p] != um.un) ? (r) ? um.w[p][23] : um.w[p][24] : (r) ? um.e[89] : um.e[90]);
    }
    return a;
};
umNav.prototype.pu = function(m) {
    m.style.height = 'auto';
    m.style.overflow = 'visible';
    var s = (um.ne(m.parentNode));
    var l = m.parentNode;
    var p = {
        tw: l.offsetWidth,
        th: l.offsetHeight,
        mw: m.offsetWidth,
        pw: (s) ? um.gc(l).offsetWidth: l.parentNode.offsetWidth
    };
    var x = (um.p) ? 2000 : 0;
    var y = (um.p) ? 2000 : 0;
    if (! ((um.h || um.p) && s)) {
        x = (s) ? (um.a ? (0 - p.mw) : p.pw) : ((um.a ? (0 - p.mw) : p.pw) - um.e[51] - um.e[55]);
        y = (0 - p.th);
    } else if (um.h && s && um.a) {
        x = (0 - p.mw + p.tw);
    }
    x += (s) ? (um.a ? (0 - um.e[14]) : um.e[14]) : (um.a ? (0 - um.e[49]) : um.e[49]);
    y += (s) ? (um.e[2] == 'bottom') ? (0 - um.e[15]) : um.e[15] : um.e[50];
    if (s) {
        if (um.h) {
            if (um.e[2] == 'bottom') {
                y -= (m.offsetHeight + p.th);
            }
            if (um.s) {
                if (um.nc && !um.a) {
                    x -= um.e[18];
                }
                if (!um.s1 && um.rp) {
                    x += um.getRealPosition(um.tr, 'x');
                    y += um.getRealPosition(um.tr, 'y');
                }
            }
            if (um.mie) {
                x -= um.gc(l).offsetWidth;
                if (um.nc && um.a) {
                    x += um.e[18];
                }
                y += p.th;
            }
            if (um.ie && um.hstrip[1] == 'yes') {
                y -= um.e[17];
            }
        } else if (um.ie && um.nc) {
            y -= um.e[18];
        }
    }
    m.style.marginLeft = x + 'px';
    m.style.marginTop = y + 'px';
    if (!um.p || !s) {
        m.style.left = 'auto';
        m.style.top = 'auto';
        if (um.s1 || um.k) {
            m.style.top = (p.th) + 'px';
        }
    }
    if (um.wie50) {
        um.xn(m);
        um.xd(m);
    }
};
umNav.prototype.ru = function(m) {
    var c = um.es(m.className);
    if (/nomove/.test(c)) {
        return false;
    }
    var w = um.getWindowDimensions();
    var p = {
        x: um.getRealPosition(m, 'x'),
        y: um.getRealPosition(m, 'y'),
        w: m.offsetWidth,
        h: m.offsetHeight,
        pw: m.parentNode.parentNode.offsetWidth,
        m: 32,
        nx: -1,
        ny: -1,
        sc: um.getScrollAmount(),
        scx: um.getScrollAmount(1)
    };
    if (um.wie50 && um.rtl) {
        p.x -= um.m.body.clientWidth;
    }
    if (typeof um.scr != um.un) {
        p.h = scr.gmh(m);
    }
    var s = (um.ne(m.parentNode));
    if (um.s) {
        p.x -= um.m.body.offsetLeft;
        p.y -= um.m.body.offsetTop;
    } else if (um.mie) {
        var t = um.e[55] + um.e[51];
        p.x -= t;
        p.y -= t;
    } else {
        t = m;
        while (!um.ne(t.parentNode)) {
            p.x += um.e[51];
            p.y += um.e[51];
            t = t.parentNode.parentNode;
        }
    }
    if (!um.ie && um.e[3] == 'fixed' && s) {
        p.x += p.scx;
        p.y += p.sc;
    }
    t = [(p.x + p.w), (w.x - p.m + p.scx)];
    if (t[0] > t[1]) {
        if (s) {
            p.nx = (((um.p) ? p.x: 0) - (t[0] - t[1]));
        } else {
            p.nx = (((um.p) ? (0 - p.w - p.pw + um.e[55] - um.e[49]) : (0 - p.w - um.e[55] - um.e[51])) - um.e[49]);
        }
    }
    if (p.x < 0) {
        if (!s) {
            p.nx = (0 - um.e[55] - um.e[51] + p.pw + um.e[49]);
        }
    }
    um.yd = (p.y + p.h) - (w.y - p.m + p.sc);
    if (um.f && !s) {
        um.yd += p.sc;
    }
    if (um.yd > 0) {
        t = m.parentNode;
        um.y = um.getRealPosition(t, 'y');
        while (!um.ne(t)) {
            um.y += um.e[51];
            t = t.parentNode.parentNode;
        }
        p.ny = (0 - um.y - (p.m * 2) + w.y + p.sc - p.h);
        if (um.f) {
            p.ny -= p.sc;
        }
    }
    if (p.y < 0) {
        p.ny = (0 - (0 - p.y));
    }
    if (p.nx != -1) {
        if (um.p) {
            m.style.left = p.nx + 'px';
        } else {
            m.style.marginLeft = p.nx + 'px';
        }
        um.ap('110', m);
    }
    if (p.ny != -1) {
        if (um.p && um.ne(m.parentNode)) {
            m.style.marginTop = (2000 - um.yd) + 'px';
        } else {
            m.style.marginTop = p.ny + 'px';
        }
        um.ap('120', m);
    }
    t = m;
    var y = (um.wie50 && !um.p) ? ((um.pi(m.style.marginTop) + m.parentNode.offsetHeight + um.getRealPosition(m.parentNode, 'y')) - p.sc) : (um.getRealPosition(t, 'y') - p.sc);
    while (!um.ne(t.parentNode)) {
        y += um.e[51];
        t = t.parentNode.parentNode;
    }
    if (um.f) {
        y += p.sc;
    }
    if (y < 0) {
        p.ny = um.pi(m.style.marginTop);
        if (isNaN(p.ny)) {
            p.ny = 0;
        }
        m.style.marginTop = (p.ny - y) + 'px';
    }
    t = m;
    var x = um.getRealPosition(t, 'x') - p.scx;
    while (!um.ne(t.parentNode)) {
        x += um.e[51];
        t = t.parentNode.parentNode;
    }
    if (x < 0) {
        m.style.marginLeft = (um.p && um.ne(m.parentNode)) ? '2000px': (p.scx > 0 ? 0 - x: 0) + 'px';
        m.style.left = '0';
    }
    return true;
};
umNav.prototype.hl = function(m) {
    var d = {
        'class': 'udmS'
    };
    um.sh = m.parentNode.appendChild(um.createElement('span', d));
    var c = um.es(m.className);
    if (c != '') {
        if (typeof um.v[c] != um.un) {
            if (um.sh.className.indexOf(c) == -1) {
                um.sh.className += ' ' + c;
            }
        }
    }
    um.sh.style.width = m.offsetWidth + 'px';
    var h = m.offsetHeight;
    if (typeof um.scr != um.un) {
        h = scr.gmh(m);
    }
    um.sh.style.height = h + 'px';
    var p = {
        x: (m.offsetLeft),
        y: (m.offsetTop)
    };
    var s = um.ne(um.sh.parentNode);
    if (um.s && !um.s1 && !s) {
        p.x -= um.e[51];
        p.y -= um.e[51];
    }
    um.sh.style.left = p.x + 'px';
    um.sh.style.top = p.y + 'px';
    return um.sh;
};
umNav.prototype.il = function(m) {
    var c = m.parentNode.appendChild(um.createElement('iframe', {
        'class': 'udmC',
        'src': 'javascript:false;'
    }));
    c.tabIndex = '-1';
    c.style.width = m.offsetWidth + 'px';
    c.style.height = (typeof um.scr != um.un ? scr.gmh(m) : m.offsetHeight) + 'px';
    c.style.left = m.offsetLeft + 'px';
    c.style.top = m.offsetTop + 'px';
    return c;
};
umNav.prototype.ts = function(v) {
    var s = um.m.getElementsByTagName('select');
    var n = s.length;
    if (n > 0) {
        var i = 0;
        do {
            s[i++].style.visibility = v;
        } while ( i < n );
        um.ap((v == 'hidden') ? '067': '077', s);
    }
};
umNav.prototype.ws = function() {
    clearInterval(um.vs);
    var h = um.gc(um.tr).offsetHeight;
    var w = um.getWindowDimensions();
    if ((h != um.lh && um.reset[2] != 'no') || ((w.x != um.lw.x || w.y != um.lw.y) && um.reset[1] != 'no')) {
        um.closeAllMenus();
        um.lw = w;
        um.lh = h;
        if (um.og && um.hstrip[0] != 'none') {
            um.tr.style.height = (um.hstrip[1] == 'yes') ? (um.lh + um.e[17]) + 'px': um.lh + 'px';
        }
    }
    um.vs = setInterval('um.n.ws()', 55);
};
um.qc = function(l) {
    if (um.reset[3] == 'yes' && l != '' && l != um.jv) {
        um.closeAllMenus();
    }
};
um.vn = function(n) {
    return n.replace(/html[:]+/, '');
};
um.es = function(c) {
    return c == null ? '': c;
};
um.gt = function(r, t, a) {
    if (!a) {
        a = [];
    }
    for (var i = 0; i < r.childNodes.length; i++) {
        if (r.childNodes[i].nodeName.toUpperCase() == t.toUpperCase() || t == '*') {
            a[a.length] = r.childNodes[i];
        }
        a = um.gt(r.childNodes[i], t, a);
    }
    return a;
};
um.gc = function(r) {
    return r.getElementsByTagName('a')[0];
};
um.gu = function(r) {
    return r.getElementsByTagName('ul');
};
um.gm = function(r) {
    var m = null;
    var c = r.childNodes;
    var l = c.length;
    for (var i = 0; i < l; i++) {
        var n = um.vn(c[i].nodeName).toLowerCase();
        if (n == 'ul') {
            m = c[i];
            break;
        }
    }
    return m;
};
um.cm = function(e) {
    if (!e) {
        e = window.event;
    }
    if (!um.tr.contains(e.srcElement || e.target) || e.keyCode) {
        um.closeAllMenus();
    }
};
um.refresh = function(g) {
    if (typeof g == um.un) {
        g = 0;
    }
    delete um.ini;
    um.ready = 0;
    if (umTree) {
        var l = um.tr.getElementsByTagName('li');
        var n = l.length;
        for (i = 0; i < n; i++) {
            var a = um.n.ga(l[i]);
            if (a) {
                a.parentNode.removeChild(a);
            }
        }
    }
    umIni(g);
};
um.closeAllMenus = function() {
    um.n.cd(um.tr);
    um.n.ck(um.tr);
    um.ha = 0;
};
um.getWindowDimensions = function() {
    if (typeof window.innerWidth != um.un) {
        var w = {
            x: window.innerWidth,
            y: window.innerHeight
        };
    } else if (um.q) {
        w = {
            x: um.m.body.clientWidth,
            y: um.m.body.clientHeight
        };
    } else {
        w = {
            x: um.m.documentElement.offsetWidth,
            y: um.m.documentElement.offsetHeight
        };
    }
    return w;
};
um.getScrollAmount = function(d) {
    return ((typeof d == um.un || !d) ? (typeof window.pageYOffset != um.un ? window.pageYOffset: um.q ? um.m.body.scrollTop: um.m.documentElement.scrollTop) : (typeof window.pageXOffset != um.un ? window.pageXOffset: um.q ? um.m.body.scrollLeft: um.m.documentElement.scrollLeft));
};
um.getRealPosition = function(r, d) {
    um.ps = (d == 'x') ? r.offsetLeft: r.offsetTop;
    um.te = r.offsetParent;
    while (um.te) {
        um.ps += (d == 'x') ? um.te.offsetLeft: um.te.offsetTop;
        um.te = um.te.offsetParent;
    }
    return um.ps;
};
if (typeof um.trigger != um.un && um.trigger != '' && !um.mie) {
    um.drt = null;
    um.drw = function() {
        this.n = typeof this.n == um.un ? 0 : this.n++;
        if (typeof um.m.getElementsByTagName != um.un && um.m.getElementsByTagName('body')[0] && um.gd('udm') && um.gd(um.trigger)) {
            try {
                umIni();
            } catch(err) {
                clearTimeout(um.drt);
                return;
            }
        } else if (this.n < 60) {
            um.drt = setTimeout('um.drw()', 250);
        }
    };
    um.drw();
}
// END OF UDMv4.5 //