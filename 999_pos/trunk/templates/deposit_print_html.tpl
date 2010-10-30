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
		<fieldset>
			<p>
				<label>Caja Id:</label>
				<span>{$cash_register_id}</span>
			</p>
			<p>
				<label>Turno:</label>
				<span>{$shift}</span>
			</p>
		</fieldset>
		<fieldset id="main_data">
			<p>
		  		<label>Boleta No:</label>
		  		<span>{$slip_number|escape}</span>
		  	</p>
		  	<p>
		  		<label>Cuenta bancaria:</label>
		  		<span>{$bank_account|escape}</span>
		  	</p>
		  	<p>
		  		<label>Banco:</label>
		  		<span>{$bank|escape}</span>
		  	</p>
		</fieldset>
		{include file='status_bar_deposit_html.tpl'}
		{include file='header_data_html.tpl'}
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
	      	<thead>
	      		<tr>
	      			<th>Recibo No.</th>
	      			<th>Factura</th>
	         		<th>Total efectivo</th>
	         		<th>Depositado</th>
	      		</tr>
	       	</thead>
	       	<tbody>
       			{section name=i loop=$details}
				<tr>
					<td>{$details[i].receipt_id|escape}</td>
					<td>{$details[i].serial_number|cat:"-"|cat:$details[i].number}</td>
					<td>{$details[i].received|nf:2}</td>
					<td class="total_col">{$details[i].deposited|nf:2}</td>
				</tr>
				{/section}
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="2"></td>
	       			<td class="total_col">Total:</td>
	       			<td class="total_col">{$total|nf:2}</td>
	       		</tr>
	       	</tfoot>
		</table>
	</div>
</body>
</html>