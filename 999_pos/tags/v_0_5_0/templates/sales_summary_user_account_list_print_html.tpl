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
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>Resumen de Ventas por Usuario</span>
			</p>
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
					<th>No.</th>
					<th>Usuario</th>
					<th>Nombre</th>
					<th>Vendio</th>
					<th>Descuentos Realizo</th>
					<th>Recolecto</th>
				</tr>
			</thead>
			<tbody>
			{section name=i loop=$list}
				<tr>
					<td>{$list[i].rank}</td>
					<td>{$list[i].username}</td>
					<td>{$list[i].name}</td>
					<td>{$list[i].subtotal|nf:2}</td>
					<td>{$list[i].discount_total|nf:2}</td>
					<td class="total_col">{$list[i].total|nf:2}</td>
				</tr>
			{/section}
			</tbody>
			<tfoot>
				<tr>
	       			<td colspan="4"></td>
	       			<td class="total_col">Total:</td>
	       			<td class="total_col">{$total|nf:2}</td>
	       		</tr>
	       	</tfoot>
		</table>
	</div>
</body>
</html>