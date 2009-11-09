{* Smarty *}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
	<div id="wrapper">
		<div id="console" class="console_display">
		{if $notify eq 1}
			<p class="{$type}">{$message}</p>
		{/if}
		</div>
		<form method="post" action="index.php?cmd=parse_file">
			<label for="count_file">Ruta:</label>
			<input id="count_file" name="count_file" type="file" />
			<input type="submit" value="Subir archivo" />
		</form>
	</div>
</body>
</html>