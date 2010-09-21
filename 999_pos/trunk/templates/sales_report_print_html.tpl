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
			Corte de Caja {if $is_preliminary eq 1}**PRELIMINAR**{/if}<br />
			<br />
			Caja Id: {$cash_register_id}, {$shift}, {$date}
		</p>
		<table>
			<caption>Resumen:</caption>
			<tbody>
				<tr>
					<td>Vouchers:</td>
					<td class="total_col">{$total_vouchers|nf:2}</td>
				</tr>
				<tr>
					<td>Efectivo:</td>
					<td class="total_col">{$cash|nf:2}</td>
				</tr>
				<tr>
					<td>Depositos:</td>
					<td class="total_col">{$total_deposits|nf:2}</td>
				</tr>
				<tr>
					<td>Facturas Contado:</td>
					<td class="total_col">{$total|nf:2}</td>
				</tr>
			</tbody>
		</table>
		<p>Iva: {$vat_total}</p>
		<table>
			<caption>Facturas Contado: {$count_invoices}</caption>
			<thead>
				<tr>
					<th>No.</th>
					<th>Consumidor</th>
					<th>Efectivo</th>
					<th>Vouchers</th>
					<th>Descuento</th>
					<th>Total</th>
					<th></th>
				</tr>
			</thead>
	       	<tbody>
       			{section name=i loop=$invoices}
				<tr>
					<td>{$invoices[i].serial_number|cat:"-"|cat:$invoices[i].number}</td>
					<td>
						{if $invoices[i].name eq ""}
							CF
						{else}
							{$invoices[i].name|htmlchars}
						{/if}
					</td>
					<td class="total_col">{$invoices[i].cash|nf:2}</td>
					<td class="total_col">{$invoices[i].total_vouchers|nf:2}</td>
					<td class="total_col">{$invoices[i].discount|nf:2}</td>
					<td class="total_col">{$invoices[i].total|nf:2}</td>
					<td>{if $invoices[i].status eq 2}Anulado{/if}</td>
				</tr>
				{/section}
	       	</tbody>
	       	<tfoot>
	       		<tr>
					<td colspan="2"></td>
					<td class="total_col"d>{$total_cash|nf:2}</td>
					<td class="total_col">{$total_vouchers|nf:2}</td>
					<td class="total_col">{$total_discount|nf:2}</td>
					<td class="total_col">{$total|nf:2}</td>
					<td></td>
				</tr>
	       	</tfoot>
		</table>
		<br />
		<table>
			<caption>Depositos: {$count_deposits}</caption>
			<thead>
				<tr>
					<th>No.</th>
					<th>Cuenta No.</th>
					<th>Boleta No.</th>
					<th>Monto</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				{section name=i loop=$deposits}
				<tr>
					<td>{$deposits[i].id}</td>
					<td>{$deposits[i].bank_account_number|htmlchars}</td>
					<td>{$deposits[i].number|htmlchars}</td>
					<td class="total_col">{$deposits[i].total|nf:2}</td>
					<td>{if $deposits[i].status eq 2}Anulado{/if}</td>
				</tr>
				{/section}
			</tbody>
			<tfoot>
	       		<tr>
					<td colspan="3"></td>
					<td class="total_col">{$total_deposits|nf:2}</td>
					<td></td>
				</tr>
	       	</tfoot>
		</table>
	</div>
</body>
</html>