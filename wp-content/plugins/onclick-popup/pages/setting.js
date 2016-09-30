/**
 *     Onclick Popup
 *     Copyright (C) 2011 - 2016 www.gopiplus.com
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

function onclickpopup_submit()
{
	if(document.onclickpopup_form.onclickpopup_group.value=="")
	{
		alert("Please select popup group.")
		document.onclickpopup_form.onclickpopup_group.focus();
		return false;
	}
	else if(document.onclickpopup_form.onclickpopup_title.value=="")
	{
		alert("Please enter the popup title.")
		document.onclickpopup_form.onclickpopup_title.focus();
		document.onclickpopup_form.onclickpopup_title.select();
		return false;
	}
	else if(document.onclickpopup_form.onclickpopup_content.value=="")
	{
		alert("Please enter the popup content.")
		document.onclickpopup_form.onclickpopup_content.focus();
		document.onclickpopup_form.onclickpopup_content.select();
		return false;
	}
}

function onclickpopup_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_onclickpopup_display.action="admin.php?page=onclick-popup-content&ac=del&did="+id;
		document.frm_onclickpopup_display.submit();
	}
}	

function onclickpopup_redirect()
{
	window.location = "admin.php?page=onclick-popup-content";
}

function onclickpopup_help()
{
	window.open("http://www.gopiplus.com/work/2011/11/13/wordpress-plugin-onclick-popup/");
}