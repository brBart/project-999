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
				<label>Reporte:</label><span>Lotes Proximos a Vencer</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date}</span>
			</p>
			<p>
				<label>Proximos:</label><span>{$days} dias</span>
			</p>
		</fieldset>
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
	      	<thead>
				<tr>
					<th>Lote</th>
					<th>Barra</th>
					<th>Casa</th>
					<th>Nombre</th>
					<th>Presentaci&oacute;n</th>
					<th>Vence</th>
					<th>Cantidad</th>
					<th>Disponible</th>
				</tr>
			</thead>
			<tbody>
			{section name=i loop=$list}
				<tr>
					<td>{$list[i].lot_id}</td>
					<td>{$list[i].bar_code|escape|wordwrap:16:"<br />":true}</td>
					<td>{$list[i].manufacturer|escape|wordwrap:11:"<br />":true}</td>
					<td>{$list[i].name|escape|wordwrap:22:"<br />":true}</td>
					<td>{$list[i].expiration_date}</td>
					<td>{$list[i].quantity}</td>
					<td>{$list[i].available}</td>
				</tr>
			{/section}
			</tbody>
		</table>
	</div>
</body>
</html>