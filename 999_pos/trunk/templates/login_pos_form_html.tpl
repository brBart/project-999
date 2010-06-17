{* Smarty * }
{config_load file="site.conf"}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{#site_title#} - {$module_title}</title>
{literal}
<style type="text/css">
fieldset {
	margin: 150px 0;
	padding: 10px;
	font-family: Verdana, Arial;
	font-size: 10px;
	font-weight: bold;
}

legend {
	font-size: 14px;
}

label {
	float: left;
	width: 100px;
}

input[type="text"], input[type="password"]{
	width: 200px;
}

.hint {
	color: gray;
}

#wrapper {
	width: 410px;
	margin: 0 auto;
}

#console {
	color: red;
}
</style>
<script type="text/javascript">
	var isSessionActive = false;

	function init(){
		var oInput = document.getElementById('username');
		oInput.focus();
	}
</script>
{/literal}
</head>
<body onload="init();">
	<div id="wrapper">
		<form method="post" action="index.php">
			<fieldset>
				<legend>Login - {$module_title}</legend>
			  	<p>
					<label for="username">Usuario:</label>
					<input name="username" id="username" type="text" value="{$username}" maxlength="20" />
			  	</p>
			  	<p>
					<label for="password">Contrase&ntilde;a:</label>
					<input name="password" id="password" type="password" maxlength="20" />
			  	</p>
			  	<p>
					<label for="date">Fecha:</label>
					<input name="date" id="date" type="text" value="{$date}" maxlength="10" />
					<span class="hint">dd/mm/aaaa</span>
			  	</p>
			  	<div id="console">
			  	{if $notify eq 1}
			  		<p>{$message}</p>
			  	{/if}
			  	</div>
			  	<p>
					<input name="login" type="submit" value="Iniciar sesi&oacute;n" />
					<input name="close" type="button" value="Salir" onclick="mainWindow.close();" />
			  	</p>
		  	</fieldset>
	  	</form>
  	</div>
</body>
</html>