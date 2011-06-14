{* Smarty * }
<ul>
	<li><a href="index.php" onclick="return oSession.setIsLink(true);">Inicio</a></li>
    <li><a href="index.php?cmd=show_maintenance_menu_admin" onclick="return oSession.setIsLink(true);">Mantenimiento</a></li>
    <li><a href="index.php?cmd=show_tools_menu_admin" onclick="return oSession.setIsLink(true);">Herramientas</a></li>
    <li><a href="{get_help_url}" target="_blank" onclick="return oSession.setIsLink(true);">Ayuda</a></li>
    <li><a href="index.php?cmd=logout" onclick="return oSession.setIsLink(true);">Logout</a></li>
</ul>