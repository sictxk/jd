
function SwitchSysBar()
{
	if(switchPoint.alt=="Close leftframe menu")
	{
		switchPoint.src = "images/nav_middle_show.gif";
		switchPoint.alt = "Open leftframe menu";
		document.all("frmTitle").style.display="none";
	}
	else
	{
		switchPoint.src = "images/nav_middle_hide.gif";
		switchPoint.alt = "Close leftframe menu";
		document.all("frmTitle").style.display="" ;
	}
}
