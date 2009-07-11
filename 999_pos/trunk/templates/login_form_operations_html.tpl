{* Smarty * }
{config_load file="site.conf"}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>{#operations_title#}</title>
</head>
<body>
	<form method="post" action="index.php">
		<fieldset>
			<legend>Login</legend>
		  	<p>
				<label for="username">Usuario:</label>
				<input name="username" id="username" type="text" />
		  	</p>
		  	<p>
				<label for="password">Contrase&ntilde;a:</label>
				<input name="password" id="password" type="password" />
		  	</p>
		  	{if success eq 0}
		  	<p>{$message}</p>
		  	{/if}
		  	<p>
				<input type="submit" name="login" value="Iniciar sesi&oacute;n" />
		  	</p>
	  	</fieldset>
  	</form>
</body>
</html>