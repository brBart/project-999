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
				<label>Reporte:</label><span>Productos Sin Movimiento</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date}</span>
			</p>
			<p>
				<label>Dias:</label><span>Mas de {$days}</span>
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
					<th>Presentaci&oacute;n</th>
					<th>En Stock</th>
					<th>Ultima Venta</th>
					<th>Salieron</th>
				</tr>
			</thead>
			<tbody>
			{section name=i loop=$list}
				<tr>
					<td>{$list[i].bar_code|escape|wordwrap:16:"<br />":true}</td>
					<td>{$list[i].manufacturer|escape|wordwrap:11:"<br />":true}</td>
					<td>{$list[i].name|escape|wordwrap:11:"<br />":true}</td>
					<td>{$list[i].packaging|escape|wordwrap:11:"<br />":true}</td>
					<td>{$list[i].quantity}</td>
					<td>{$list[i].last_sale}</td>
					<td>{$list[i].sale_quantity}</td>
				</tr>
			{/section}
			</tbody>
		</table>
	</div>
</body>
</html>