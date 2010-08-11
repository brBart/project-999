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
		<p id="title">
			{$company_name}<br />
			Nit: {$company_nit}<br />
			Resoluci&oacute;n No: {$resolution_number}<br />
			Fecha: {$resolution_date}<br />
			Del: {$correlative_initial_number} Al: {$correlative_final_number}
		</p>
		<p id="header">
			Factura Serie: {$serial_number} No: {$number}<br />
			Fecha: {$date_time}
		</p>
		<p id="customer">
			Nit: {$customer_nit}<br />
			Cliente: {$customer_name}
		</p>
		<table>
	       	<tbody>
       			{section name=i loop=$details}
				<tr>
					<td>{$details[i].quantity}</td>
					<td>{$details[i].product|htmlchars}</td>
					<td>{$details[i].packaging|htmlchars}</td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td>{$details[i].price|nf:2}</td>
					<td class="total_col">{$details[i].total|nf:2}</td>
				</tr>
				{/section}
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="2"></td>
	       			<td>Sub-Total:</td>
	       			<td class="total_col">{$sub_total|nf:2}</td>
	       		</tr>
	       		<tr>
	       			<td colspan="2"></td>
	       			<td>Descuento <span>({$discount_percentage}%)</span>: </td>
	       			<td class="total_col">{$discount|nf:2}</td>
	       		</tr>
	       		<tr>
	       			<td colspan="2"></td>
	       			<td>Total:</td>
	       			<td class="total_col">{$total|nf:2}</td>
	       		</tr>
	       	</tfoot>
		</table>
		<p id="cash_receipt">
			Efectivo: {$cash_amount|nf:2}<br />
			Tarjetas: {$vouchers_total|nf:2}<br />
			Cambio: {$change_amount|nf:2}
		</p>
		<p id="extras">
			Caja Id: {$cash_register_id} Cajero: {$username}
		</p>
	</div>
</body>
</html>