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
		<fieldset id="main_data">
			<p>
		  		<label for="organization_id">Proveedor:</label>
		  		<span>{$supplier}</span>
		  	</p>
		  	<p>
		  		<label for="shipment_number">Env&iacute;o No:</label>
		  		<span>{$shipment_number}</span>
		  	</p>
		  	<p>
		  		<label for="shipment_total">Total env&iacute;o:</label>
		  		<span>{$shipment_total}</span>
		  	</p>
		</fieldset>
		{include file='status_bar_doc_html.tpl'}
		{include file='header_data_html.tpl' document_name='Recibo'}
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
	      	<thead>
	      		<tr>
	        		<th>Barra</th> 
	         		<th>Casa</th>
	         		<th>Nombre</th>
	         		<th>Presentacion</th>
	         		<th>UM</th>
	         		<th>Cantidad</th>
	         		<th>Precio</th>
	         		<th>Sub Total</th>
	         		<th>Vence</th>
	         	</tr>
	       	</thead>
	       	<tbody>
       			{section name=i loop=$details}
				<tr>
					<td>{$details[i].bar_code}</td>
					<td>{$details[i].manufacturer}</td>
					<td>{$details[i].product}</td>
					<td>{$details[i].packaging}</td>
					<td>{$details[i].um}</td>
					<td>{$details[i].quantity}</td>
					<td>{$details[i].price|nf:2}</td>
					<td class="total_col">{$details[i].total|nf:2}</td>
					<td>
					{if $details[i].expiration_date neq ''}{$details[i].expiration_date}{else}N/A{/if}
					</td>
				</tr>
				{/section}
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="6"></td>
	       			<td class="total_col">Total:</td>
	       			<td class="total_col">{$total|nf:2}</td>
	       		</tr>
	       	</tfoot>
		</table>
	</div>
</body>
</html>