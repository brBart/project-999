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

#title {
	text-align: center;
}

.total_col {
	text-align: right;
}
</style>
{/literal}
</head>
<body>
	<div id="wrapper">
		{if $notify eq 1}
			<p>{$message}</p>
		{/if}
		<p id="title">
			{$company_name}<br />
			Reporte de Ventas {if $is_preliminary eq 1}**PRELIMINAR**{/if}<br />
			<br />
			Fecha: {$date}
		</p>
		<table>
			<caption>Cajas: {$count_cash_registers}</caption>
			<thead>
				<tr>
					<th>Caja Id</th>
					<th>Turno</th>
					<th>Horario</th>
					<th>Total</th>
				</tr>
			</thead>
	       	<tbody>
       			{section name=i loop=$cash_registers}
				<tr>
					<td>{$cash_registers[i].id}</td>
					<td>{$cash_registers[i].name|htmlchars}</td>
					<td>{$cash_registers[i].time_table|htmlchars}</td>
					<td class="total_col">{$cash_registers[i].total|nf:2}</td>
				</tr>
				{/section}
	       	</tbody>
	       	<tfoot>
	       		<tr>
					<td colspan="3"></td>
					<td class="total_col">{$total|nf:2}</td>
					<td></td>
				</tr>
	       	</tfoot>
		</table>
	</div>
</body>
</html>