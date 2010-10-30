{* Smarty *}
<div id="vouchers" class="items">
	<table class="read_only">
		<caption>Tarjetas: {$vouchers_count}</caption>
      	<thead>
      		<tr>
      			<th>Transaccion</th>
         		<th>Tarjeta No.</th>
         		<th>Tipo</th>
         		<th>Marca</th>
         		<th>Nombre</th>
         		<th>Fecha Vence</th>
         		<th>Monto</th>
      		</tr>
       	</thead>
       	<tbody>
       		{section name=i loop=$vouchers}
      			<tr>
	       		<td>{$vouchers[i].transaction_number|escape}</td>
	       		<td>{$vouchers[i].number}</td>
			    <td>{$vouchers[i].type|escape}</td>
			    <td>{$vouchers[i].brand|escape}</td>
			    <td>{$vouchers[i].name|escape}</td>
			    <td>{$vouchers[i].expiration_date}</td>
			    <td class="total_col">{$vouchers[i].amount|nf:2}</td>
			</tr>
			{/section}
       	</tbody>
       	<tfoot>
       		<tr>
       			<td colspan="5"></td>
       			<td class="total_col">Total:</td>
       			<td class="total_col">{$vouchers_total|nf:2}</td>
       		</tr>
       	</tfoot>
	</table>
</div>