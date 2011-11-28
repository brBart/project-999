{* Smarty *}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Imprimiendo...</title>
<link href="../styles/print.css" rel="stylesheet" type="text/css" />
</head>
<body onload="print();">
	<div id="wrapper">
		<div id="console" class="console_display">
		{if $notify eq 1}
			<p class="{$type}">{$message}</p>
		{/if}
		</div>
		{include file='header_data_log_html.tpl'}
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
	      	<thead>
				<tr>
					<th>Serie</th>
					<th>N&uacute;mero</th>
					<th>Fecha</th>
					<th>Monto</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
			{section name=i loop=$list}
				<tr>
					<td>{$list[i].serial_number}</td>
					<td>{$list[i].number}</td>
					<td>{$list[i].date}</td>
					<td>{$list[i].total|nf:2}</td>
					<td>{$list[i].state|escape}</td>
				</tr>
			{/section}
			</tbody>
		</table>
	</div>
</body>
</html>