/**
 *     Popup
 *     Copyright (C) 2011 - 2014 www.gopiplus.com
 *     http://www.gopiplus.com/work/2011/01/14/wordpress-popup/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

var Popuptmr;
var Popupt;
var Popupobj;

function PopupDivFunc() {
	Popupobj = PopupgObj();
	PopupsLft();
	Popupshw(true);
	Popupt = 0;
	PopupsTmr();
}

function PopupDivStop() {
	Popupt = -100;
	PopupsTmr();
	return false;
}

function PopupsTmr() {
	Popuptmr = setInterval("fd()",20);
}

function fd() {
	var amt = Math.abs(Popupt+=10);
	if(amt == 0 || amt == 100) clearInterval(Popuptmr);
	amt = (amt == 100)?99.999:amt;
  	
	Popupobj.style.filter = "alpha(opacity:"+amt+")";
	Popupobj.style.KHTMLOpacity = amt/100;
	Popupobj.style.MozOpacity = amt/100;
	Popupobj.style.opacity = amt/100;
	
	if(amt == 0) Popupshw(false);
}

function PopupsLft() {
	var w = 350;	// set this to 1/2 the width of the PopupDiv div defined in the style sheet 
			// there's not a reliable way to retrieve an element's width via javascript!!
					
	var l = (document.body.innerWidth)? document.body.innerWidth / 2:document.body.offsetWidth / 2;

	Popupobj.style.left = (l - w)+"px";
}

function PopupgObj() {
	return document.getElementById("PopupDiv");	
}

function Popupshw(b) {
	(b)? Popupobj.className = 'show':Popupobj.className = '';	
}