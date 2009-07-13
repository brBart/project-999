{* Smarty * }
{config_load file="site.conf"}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>{#operations_title#}</title>
</head>
<body>
	<div>
		<h1>Sistema 999</h1>
	</div>
	<div>
		{include file=$main_menu}
	</div>
	{if success eq 0}
		<div><p>{$message}</p></div>
	{/if}
	<div>
		{include file=$second_menu}
	</div>
	<div>
		{include file=$content}
	</div>
</body>
</html>