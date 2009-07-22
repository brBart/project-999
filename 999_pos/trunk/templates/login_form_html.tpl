{* Smarty * }
{config_load file="site.conf"}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>{#site_title#} - {$module_title}</title>
{literal}
<style type="text/css">
#wrapper {
	width: 350px;
	margin: 0 auto;
}

#console {
	color: red;
}

fieldset {
	margin: 150px 0;
	padding: 10px;
}

label {
	float: left;
	width: 100px;
}

input [type="text"]{
	width: 100px;
}

input [type="password"]{
	width: 100px;
}
</style>
<script type="text/javascript">
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
			  	<div id="console">
			  	{if $notify eq 1}
			  		<p>{$message}</p>
			  	{/if}
			  	</div>
			  	<p>
					<input name="login" type="submit" value="Iniciar sesi&oacute;n" />
			  	</p>
		  	</fieldset>
	  	</form>
  	</div>
</body>
</html>