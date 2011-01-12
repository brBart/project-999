{* Smarty *}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Subir archivo</title>
{literal}
<style type="text/css">
body {
	font-size: 12px;
}

.close_window {
	float: right;
}

.error {
	color: red;
	border-style: none;
	background-image: none;
}
</style>
<script type="text/javascript">
	function confirmSuccess(){
		this.opener.reloadDetails();
	    this.close();
	}
	function closeWindow(){
		this.close();
	}
</script>
{/literal}
</head>
<body {if $uploaded eq 1}onload="confirmSuccess();"{/if}>
	<div id="wrapper">
		{if $uploaded eq 1}
			<a class="close_window" href="#" onclick="closeWindow();">Cerrar[X]</a>
			<p>&nbsp;</p>
			<h6>Detalles agregados satisfactoriamente.</h6>
		{else}
			<a class="close_window" href="#" onclick="closeWindow();">Cerrar[X]</a>
			<p>&nbsp;</p>
			<div id="console" class="console_display">
			{if $notify eq 1}
				<p class="{$type}">{$message}</p>
			{/if}
			</div>
			<form method="post" enctype="multipart/form-data" action="index.php?cmd=parse_file&key={$key}">
				<input name="MAX_FILE_SIZE" type="hidden" value="1000000" />
				<label for="count_file">Ruta:</label>
				<input id="count_file" name="count_file" type="file" />
				<input type="submit" value="Subir archivo" />
			</form>
		{/if}
	</div>
</body>
</html>