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
				<label>Reporte:</label><span>Estad&iacute;sticas de Ventas y Compras</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date}</span>
			</p>
			<p>
				<label>Ultimos:</label><span>{$months} meses</span>
			</p>
			<p>
				<label>Ordenado por:</label><span>{if $order eq 'product'}Nombre{else}Casa{/if}</span>
			</p>
		</fieldset>
		<p id="separator">&nbsp;</p>
		<table id="list_large">
	     	<caption>{$total_items} de {$total_items}</caption>
			<thead>
				<tr>
					<th>Barra</th>
					<th>Casa</th>
					<th>Nombre</th>
					<th>Presentaci&oacute;n</th>
					{section name=i loop=$months_names}
						<th class="data_title" colspan="3">{$months_names[i]}</th>
					{/section}
					<th  class="data_title" colspan="3">Promedio</th>
				</tr>
				<tr>
					<th colspan="4"></th>
					{section name=i loop=$months_names}
						<th class="data_col data_left">V</th>
						<th class="data_col data_separator">|</th>
						<th class="data_col data_right">C</th>
					{/section}
					<th class="data_col data_left">V</th>
					<th class="data_col data_separator">|</th>
					<th class="data_col data_right">C</th>
				</tr>
			</thead>
			<tbody>
			{section name=i loop=$list}
				<tr>
					<td>{$list[i].bar_code|escape|wordwrap:16:"<br />":true}</td>
					<td>{$list[i].manufacturer|escape|wordwrap:11:"<br />":true}</td>
					<td>{$list[i].product|escape|wordwrap:11:"<br />":true}</td>
					<td>{$list[i].packaging|escape|wordwrap:11:"<br />":true}</td>
					{section name=x loop="$months"}
						<td class="data_col data_left">{$list[i].sales[x].sales}</td>
						<td class="data_col data_separator">|</td>
						<td class="data_col data_right">{$list[i].purchases[x].purchases}</td>
					{/section}
					<td class="data_col data_left">{$list[i].sales_average|nf}</td>
					<td class="data_col data_separator">|</td>
					<td class="data_col data_right">{$list[i].purchases_average|nf}</td>
				</tr>
			{/section}
			</tbody>
		</table>
	</div>
</body>
</html>