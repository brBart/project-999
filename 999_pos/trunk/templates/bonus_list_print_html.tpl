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
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>Ofertas {if $bonus_used eq 1}Usadas{else}Creadas{/if}</span>
			</p>
			{if $bonus_used eq 0}
			<p>
				<label>Fecha:</label><span>{$date}</span>
			</p>
			{/if}
			<p>
				<label>Fecha Inicial:</label><span>{$start_date}</span>
			</p>
			<p>
				<label>Fecha Final:</label><span>{$end_date}</span>
			</p>
		</fieldset>
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
			<thead>
				<tr>
					<th>Barra</th>
					<th>Casa</th>
					<th>Nombre</th>
					<th>Cantidad</th>
					<th>Descuento(%)</th>
					<th>Creado</th>
					<th>Vence</th>
					<th>Usuario</th>
				</tr>
			</thead>
			<tbody>
			{section name=i loop=$list}
				<tr>
					<td>{$list[i].bar_code|escape|wordwrap:24:"<br />":true}</td>
					<td>{$list[i].manufacturer|escape|wordwrap:19:"<br />":true}</td>
					<td>{$list[i].name|escape|wordwrap:38:"<br />":true}</td>
					<td>{$list[i].quantity}</td>
					<td>{$list[i].percentage|nf:2}</td>
					<td>{$list[i].created_date}</td>
					<td>{$list[i].expiration_date}</td>
					<td>{$list[i].username}</td>
				</tr>
			{/section}
			</tbody>
		</table>
	</div>
</body>
</html>