{* Smarty *}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Imprimiendo...</title>
{literal}
<style type="text/css">
body {
	font-size: 10px;
	font-family: Arial, Verdana;
}

p {
	text-align: center;
}
</style>
{/literal}
</head>
<body>
	<div id="wrapper">
		<p>********************************</p>
		<p>********************************</p>
		<p id="title">FACTURA ANULADA</p>
		<p>Serie: {$serial_number} - No: {$number}</p>
		<p>{$date_time}</p>
		<p>********************************</p>
		<p>********************************</p>
		<br />
		<p>Motivo: {$reason}</p>
	</div>
</body>
</html>